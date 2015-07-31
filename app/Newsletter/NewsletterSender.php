<?php

namespace Herecsrymy\Newsletter;

use Herecsrymy\Entities\Event;
use Kdyby\Doctrine\EntityManager;
use Nette\Application\LinkGenerator;
use Nette\Application\UI\ITemplate;
use Nette\Application\UI\ITemplateFactory;
use Nette\Mail\IMailer;
use Nette\Mail\Message;
use Herecsrymy\Entities\NewsletterSubscription;
use Herecsrymy\Entities\Post;
use Nette\Mail\SmtpException;
use PhpAmqpLib\Message\AMQPMessage;
use Tracy\Debugger;


class NewsletterSender
{

	const TYPE_POST = 'post';
	const TYPE_EVENT = 'event';

	const MESSAGE_TYPE_KEY = 'type';
	const MESSAGE_SUBSCRIPTION_KEY = 'subscription';
	const MESSAGE_ENTITY_KEY = 'entity';

	/** @var EntityManager */
	private $em;

	/** @var IMailer */
	private $mailer;

	/** @var LinkGenerator */
	private $linkGenerator;

	/** @var ITemplateFactory */
	private $templateFactory;


	public function __construct(EntityManager $em, IMailer $mailer, LinkGenerator $linkGenerator, ITemplateFactory $templateFactory)
	{
		$this->em = $em;
		$this->mailer = $mailer;
		$this->linkGenerator = $linkGenerator;
		$this->templateFactory = $templateFactory;
	}


	/**
	 * @param AMQPMessage $amqpMessage
	 */
	public function sendNewsletterFromAMQP(AMQPMessage $amqpMessage)
	{
		$data = unserialize($amqpMessage->body);
		$subscription = $this->em->find(NewsletterSubscription::class, $data[self::MESSAGE_SUBSCRIPTION_KEY]);

		if ($data[self::MESSAGE_TYPE_KEY] === self::TYPE_POST) {
			$post = $this->em->find(Post::class, $data[self::MESSAGE_ENTITY_KEY]);
			$this->sendPostNewsletter($subscription, $post);

		} else {
			$event = $this->em->find(Event::class, $data[self::MESSAGE_ENTITY_KEY]);
			$this->sendEventNewsletter($subscription, $event);
		}
	}


	public function sendPostNewsletter(NewsletterSubscription $subscription, Post $post)
	{
		$message = $this->createMessage($subscription, function (ITemplate $template) use ($post) {
			$template->post = $post;
			$template->setFile(__DIR__ . '/templates/newPost.latte');
		});

		try {
			$this->mailer->send($message);

		} catch (SmtpException $e) {
			Debugger::log($e, 'newsletter');
		}
	}


	public function sendEventNewsletter(NewsletterSubscription $subscription, Event $event)
	{
		$message = $this->createMessage($subscription, function (ITemplate $template) use ($event) {
			$template->event = $event;
			$template->setFile(__DIR__ . '/templates/newEvent.latte');
		});

		try {
			$this->mailer->send($message);

		} catch (SmtpException $e) {
			Debugger::log($e, 'newsletter');
		}
	}


	/**
	 * @param NewsletterSubscription $subscription
	 * @param callable $templateConfigurator
	 * @return Message
	 */
	private function createMessage(NewsletterSubscription $subscription, callable $templateConfigurator = NULL)
	{
		$unsubscribeLink = $this->linkGenerator->link('Front:Newsletter:unsubscribe', [
			'hash' => $subscription->getUnsubscribeHash(),
		]);

		$message = (new Message())
			->setFrom('system@jiripudil.cz', 'Jiří Pudil')
			->addTo($subscription->email)
			->setHeader('List-Unsubscribe', $unsubscribeLink);

		$template = $this->templateFactory->createTemplate();
		$template->subscription = $subscription;
		$template->unsubscribeLink = $unsubscribeLink;
		$template->_control = $this->linkGenerator;

		if ($templateConfigurator !== NULL) {
			call_user_func($templateConfigurator, $template);
		}

		$message->setHtmlBody($template);

		return $message;
	}

}

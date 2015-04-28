<?php

namespace Herecsrymy\Newsletter;

use Kdyby\Doctrine\EntityManager;
use Nette\Application\LinkGenerator;
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

	const MESSAGE_SUBSCRIPTION_KEY = 'subscription';
	const MESSAGE_POST_KEY = 'post';

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
		$post = $this->em->find(Post::class, $data[self::MESSAGE_POST_KEY]);

		$this->sendNewsletter($subscription, $post);
	}


	public function sendNewsletter(NewsletterSubscription $subscription, Post $post)
	{
		$unsubscribeLink = $this->linkGenerator->link('Front:Newsletter:unsubscribe', [
			'hash' => $subscription->getUnsubscribeHash(),
		]);

		$message = (new Message())
			->setFrom('me@jiripudil.cz', 'Jiří Pudil')
			->addTo($subscription->email)
			->setHeader('List-Unsubscribe', $unsubscribeLink);

		$template = $this->templateFactory->createTemplate();
		$template->subscription = $subscription;
		$template->post = $post;
		$template->unsubscribeLink = $unsubscribeLink;
		$template->_control = $this->linkGenerator;
		$template->setFile(__DIR__ . '/templates/newPost.latte');

		$message->setHtmlBody($template);

		try {
			$this->mailer->send($message);

		} catch (SmtpException $e) {
			Debugger::log($e, 'newsletter');
		}
	}

}

<?php

namespace Herecsrymy\Newsletter;

use Nette\Application\LinkGenerator;
use Nette\Application\UI\ITemplateFactory;
use Nette\Mail\IMailer;
use Nette\Mail\Message;
use Herecsrymy\Entities\NewsletterSubscription;
use Herecsrymy\Entities\Post;


class NewsletterSender
{

	/** @var IMailer */
	private $mailer;

	/** @var LinkGenerator */
	private $linkGenerator;

	/** @var ITemplateFactory */
	private $templateFactory;


	public function __construct(IMailer $mailer, LinkGenerator $linkGenerator, ITemplateFactory $templateFactory)
	{
		$this->mailer = $mailer;
		$this->linkGenerator = $linkGenerator;
		$this->templateFactory = $templateFactory;
	}


	/**
	 * @param NewsletterSubscription $subscription
	 * @param Post $post
	 */
	public function sendNewsletter(NewsletterSubscription $subscription, Post $post)
	{
		$message = (new Message())
			->setFrom('me@jiripudil.cz', 'Jiří Pudil')
			->addTo($subscription->email);

		$template = $this->templateFactory->createTemplate();
		$template->subscription = $subscription;
		$template->post = $post;
		$template->_control = $this->linkGenerator;
		$template->setFile(__DIR__ . '/templates/newPost.latte');

		$message->setHtmlBody($template);
		$this->mailer->send($message);
	}

}

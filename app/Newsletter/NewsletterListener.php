<?php

namespace Herecsrymy\Newsletter;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Herecsrymy\Entities\NewsletterSubscription;
use Herecsrymy\Entities\Post;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Events\Subscriber;


class NewsletterListener implements Subscriber
{

	/** @var EntityManager */
	private $em;

	/** @var NewsletterSender */
	private $sender;


	public function __construct(EntityManager $em, NewsletterSender $sender)
	{
		$this->em = $em;
		$this->sender = $sender;
	}


	public function getSubscribedEvents()
	{
		return [
			Events::postPersist,
		];
	}


	public function postPersist(LifecycleEventArgs $args)
	{
		$post = $args->getEntity();
		if ( ! $post instanceof Post) {
			return;
		}

		$subscriptions = $this->em->getRepository(NewsletterSubscription::class)->findBy(['active' => TRUE]);
		foreach ($subscriptions as $subscription) {
			// TODO put it into some queue and process asynchronously
			$this->sender->sendNewsletter($subscription, $post);
		}
	}

}

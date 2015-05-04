<?php

namespace Herecsrymy\Newsletter;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Kdyby\Doctrine\Events;
use Herecsrymy\Entities\NewsletterSubscription;
use Herecsrymy\Entities\Post;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Events\Subscriber;
use Kdyby\RabbitMq\Connection;
use PhpAmqpLib\Exception\AMQPExceptionInterface;
use Tracy\Debugger;


class NewsletterListener implements Subscriber
{

	/** @var EntityManager */
	private $em;

	/** @var Connection */
	private $rabbit;


	public function __construct(EntityManager $em, Connection $rabbit)
	{
		$this->em = $em;
		$this->rabbit = $rabbit;
	}


	public function getSubscribedEvents()
	{
		return [
			Events::postPersist,
			// TODO preUpdate unpublished -> published
		];
	}


	public function postPersist(LifecycleEventArgs $args)
	{
		$post = $args->getEntity();
		if ( ! $post instanceof Post || ! $post->isPublic()) {
			return;
		}

		$producer = $this->rabbit->getProducer('sendNewsletter');

		$subscriptions = $this->em->getRepository(NewsletterSubscription::class)->findBy(['active' => TRUE]);
		foreach ($subscriptions as $subscription) {
			$message = [
				NewsletterSender::MESSAGE_SUBSCRIPTION_KEY => $subscription->id,
				NewsletterSender::MESSAGE_POST_KEY => $post->id,
			];

			try {
				$producer->publish(serialize($message));

			} catch (AMQPExceptionInterface $e) {
				Debugger::log($e, 'newsletter');
			}
		}
	}

}

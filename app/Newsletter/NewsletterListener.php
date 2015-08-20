<?php

namespace Herecsrymy\Newsletter;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Kdyby\Doctrine\Events;
use Herecsrymy\Entities\NewsletterSubscription;
use Herecsrymy\Entities\Post;
use Kdyby\Events\Subscriber;
use Kdyby\RabbitMq\Connection;
use PhpAmqpLib\Exception\AMQPExceptionInterface;
use Tracy\Debugger;


class NewsletterListener implements Subscriber
{

	/** @var Connection */
	private $rabbit;

	/** @var Post */
	private $scheduledPost;


	public function __construct(Connection $rabbit)
	{
		$this->rabbit = $rabbit;
	}


	public function getSubscribedEvents()
	{
		return [
			Events::postPersist,
			Events::preUpdate,
			Events::postFlush,
		];
	}


	public function postPersist(LifecycleEventArgs $args)
	{
		$post = $args->getEntity();
		if ( ! $post instanceof Post || ! $post->isPublic()) {
			return;
		}

		$this->scheduledPost = $post;
	}


	public function preUpdate(PreUpdateEventArgs $args)
	{
		$post = $args->getEntity();
		if ( ! $post instanceof Post
			|| ! $post->isPublic()
			|| ! $args->hasChangedField('published')
			|| $args->getOldValue('published') === TRUE
			|| $args->getNewValue('published') === FALSE) {

			return;
		}

		$this->scheduledPost = $post;
	}


	public function postFlush(PostFlushEventArgs $args)
	{
		if ($this->scheduledPost === NULL) {
			return;
		}

		$producer = $this->rabbit->getProducer('sendNewsletter');

		/** @var NewsletterSubscription[] $subscriptions */
		$subscriptions = $args->getEntityManager()
			->getRepository(NewsletterSubscription::class)
			->findBy(['active' => TRUE]);

		foreach ($subscriptions as $subscription) {
			$message = [
				NewsletterSender::MESSAGE_SUBSCRIPTION_KEY => $subscription->getId(),
				NewsletterSender::MESSAGE_POST_KEY => $this->scheduledPost->getId(),
			];

			try {
				$producer->publish(serialize($message));

			} catch (AMQPExceptionInterface $e) {
				Debugger::log($e, 'newsletter');
			}
		}
	}

}

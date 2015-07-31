<?php

namespace Herecsrymy\Newsletter;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Herecsrymy\Entities\Event;
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

	/** @var Post|Event */
	private $scheduledEntity;


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
		$entity = $args->getEntity();
		if ( ! $entity instanceof Event && ( ! $entity instanceof Post || ! $entity->isPublic())) {
			return;
		}

		$this->scheduledEntity = $entity;
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

		$this->scheduledEntity = $post;
	}


	public function postFlush(PostFlushEventArgs $args)
	{
		if ($this->scheduledEntity === NULL) {
			return;
		}

		$producer = $this->rabbit->getProducer('sendNewsletter');

		/** @var NewsletterSubscription[] $subscriptions */
		$subscriptions = $args->getEntityManager()
			->getRepository(NewsletterSubscription::class)
			->findBy(['active' => TRUE]);

		foreach ($subscriptions as $subscription) {
			$message = $this->buildMessage($this->scheduledEntity, $subscription);

			try {
				$producer->publish(serialize($message));

			} catch (AMQPExceptionInterface $e) {
				Debugger::log($e, 'newsletter');
			}
		}
	}


	private function buildMessage($entity, $subscription)
	{
		$type = $entity instanceof Event ? NewsletterSender::TYPE_EVENT : NewsletterSender::TYPE_POST;

		return [
			NewsletterSender::MESSAGE_TYPE_KEY => $type,
			NewsletterSender::MESSAGE_SUBSCRIPTION_KEY => $subscription->id,
			NewsletterSender::MESSAGE_ENTITY_KEY => $entity->id,
		];
	}

}

<?php

namespace Herecsrymy\Newsletter;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Herecsrymy\Entities\Event;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Kdyby\Doctrine\Events;
use Herecsrymy\Entities\Post;
use Kdyby\Events\Subscriber;


class NewsletterListener implements Subscriber
{

	/** @var NewsletterQueue */
	private $queue;

	/** @var Post|Event */
	private $scheduledEntity;


	public function __construct(NewsletterQueue $queue)
	{
		$this->queue = $queue;
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
		if (( ! $entity instanceof Event || ! $entity->published)
			&& ( ! $entity instanceof Post || ! $entity->isPublic())) {

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

		if ($this->scheduledEntity instanceof Post) {
			$this->queue->enqueuePostNewsletter($this->scheduledEntity);

		} else {
			$this->queue->enqueueEventNewsletter($this->scheduledEntity);
		}
	}

}

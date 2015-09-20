<?php


namespace Herecsrymy\Newsletter;

use Herecsrymy\Entities\Event;
use Herecsrymy\Entities\NewsletterSubscription;
use Herecsrymy\Entities\Post;
use Herecsrymy\Entities\Queries\NewsletterSubscriptionQuery;
use Herecsrymy\Newsletter\Messages\CustomMessage;
use Herecsrymy\Newsletter\Messages\EventMessage;
use Herecsrymy\Newsletter\Messages\PostMessage;
use Kdyby\Doctrine\EntityManager;
use Kdyby\RabbitMq\Connection;
use Kdyby\RabbitMq\Producer;
use Nette\Object;
use PhpAmqpLib\Exception\AMQPExceptionInterface;


class NewsletterQueue extends Object
{

	/** @var Producer */
	private $producer;

	/** @var EntityManager */
	private $em;


	public function __construct(Connection $rabbit, EntityManager $em)
	{
		$this->producer = $rabbit->getProducer('sendNewsletter');
		$this->em = $em;
	}


	public function enqueuePostNewsletter(Post $post)
	{
		foreach ($this->getSubscriptions() as $subscription) {
			try {
				$this->producer->publish(serialize(new PostMessage($subscription, $post)));

			} catch (AMQPExceptionInterface $e) {
				Debugger::log($e, 'newsletter');
			}
		}
	}


	public function enqueueEventNewsletter(Event $event)
	{
		foreach ($this->getSubscriptions() as $subscription) {
			try {
				$this->producer->publish(serialize(new EventMessage($subscription, $event)));

			} catch (AMQPExceptionInterface $e) {
				Debugger::log($e, 'newsletter');
			}
		}
	}


	public function enqueueCustomNewsletter($subject, $text)
	{
		foreach ($this->getSubscriptions() as $subscription) {
			try {
				$this->producer->publish(serialize(new CustomMessage($subscription, $subject, $text)));

			} catch (AMQPExceptionInterface $e) {
				Debugger::log($e, 'newsletter');
			}
		}
	}


	/**
	 * @return NewsletterSubscription[]
	 */
	private function getSubscriptions()
	{
		return $this->em->getRepository(NewsletterSubscription::class)
			->fetch((new NewsletterSubscriptionQuery())->onlyActive());
	}

}

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
use Kdyby\Monolog\CustomChannel;
use Kdyby\Monolog\Logger;
use Kdyby\RabbitMq\Connection;
use Kdyby\RabbitMq\Producer;
use Nette\Object;
use PhpAmqpLib\Exception\AMQPExceptionInterface;
use Tracy\Debugger;


class NewsletterQueue extends Object
{

	/** @var Producer */
	private $producer;

	/** @var EntityManager */
	private $em;

	/** @var CustomChannel */
	private $logger;


	public function __construct(Connection $rabbit, EntityManager $em, Logger $logger)
	{
		$this->producer = $rabbit->getProducer('sendNewsletter');
		$this->em = $em;
		$this->logger = $logger->channel('newsletter');
	}


	public function enqueuePostNewsletter(Post $post)
	{
		foreach ($this->getSubscriptions() as $subscription) {
			try {
				$this->producer->publish(serialize($message = new PostMessage($subscription, $post)));
				$this->logger->addInfo('Enqueued post newsletter.', ['message' => $message]);

			} catch (AMQPExceptionInterface $e) {
				Debugger::log($e, 'newsletter');
			}
		}
	}


	public function enqueueEventNewsletter(Event $event)
	{
		foreach ($this->getSubscriptions() as $subscription) {
			try {
				$this->producer->publish(serialize($message = new EventMessage($subscription, $event)));
				$this->logger->addInfo('Enqueued event newsletter.', ['message' => $message]);

			} catch (AMQPExceptionInterface $e) {
				Debugger::log($e, 'newsletter');
			}
		}
	}


	public function enqueueCustomNewsletter($subject, $text)
	{
		foreach ($this->getSubscriptions() as $subscription) {
			try {
				$this->producer->publish(serialize($message = new CustomMessage($subscription, $subject, $text)));
				$this->logger->addInfo('Enqueued custom newsletter.', ['message' => $message]);

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

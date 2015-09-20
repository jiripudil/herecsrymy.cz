<?php

namespace Herecsrymy\Newsletter\Messages;

use Herecsrymy\Entities\Event;
use Herecsrymy\Entities\NewsletterSubscription;
use Herecsrymy\Newsletter\IMessage;
use Nette\Object;


class EventMessage extends Object implements IMessage
{

	/** @var int */
	private $subscription;

	/** @var int */
	private $event;


	public function __construct(NewsletterSubscription $subscription, Event $event)
	{
		$this->subscription = $subscription->getId();
		$this->event = $event->getId();
	}


	public function serialize()
	{
		return serialize([
			'subscription' => $this->subscription,
			'event' => $this->event,
		]);
	}


	public function unserialize($serialized)
	{
		$unserialized = unserialize($serialized);

		$this->subscription = $unserialized['subscription'];
		$this->event = $unserialized['event'];
	}


	/**
	 * @return int
	 */
	public function getSubscription()
	{
		return $this->subscription;
	}


	/**
	 * @return int
	 */
	public function getEvent()
	{
		return $this->event;
	}


	/**
	 * @return string
	 */
	public function __toString()
	{
		return sprintf('%s: event=%d, subscription=%d', __CLASS__, $this->event, $this->subscription);
	}

}

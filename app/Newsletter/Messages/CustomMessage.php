<?php

namespace Herecsrymy\Newsletter\Messages;

use Herecsrymy\Entities\NewsletterSubscription;
use Herecsrymy\Entities\Post;
use Herecsrymy\Newsletter\IMessage;
use Nette\Object;


class CustomMessage extends Object implements IMessage
{

	/** @var int */
	private $subscription;

	/** @var string */
	private $subject;

	/** @var string */
	private $text;


	public function __construct(NewsletterSubscription $subscription, $subject, $text)
	{
		$this->subscription = $subscription->getId();
		$this->subject = $subject;
		$this->text = $text;
	}


	public function serialize()
	{
		return serialize([
			'subscription' => $this->subscription,
			'subject' => $this->subject,
			'text' => $this->text,
		]);
	}


	public function unserialize($serialized)
	{
		$unserialized = unserialize($serialized);

		$this->subscription = $unserialized['subscription'];
		$this->subject = $unserialized['subject'];
		$this->text = $unserialized['text'];
	}


	/**
	 * @return int
	 */
	public function getSubscription()
	{
		return $this->subscription;
	}


	/**
	 * @return string
	 */
	public function getSubject()
	{
		return $this->subject;
	}


	/**
	 * @return string
	 */
	public function getText()
	{
		return $this->text;
	}


	/**
	 * @return string
	 */
	public function __toString()
	{
		return sprintf('%s: subject=%s, subscription=%d', __CLASS__, $this->subject, $this->subscription);
	}

}

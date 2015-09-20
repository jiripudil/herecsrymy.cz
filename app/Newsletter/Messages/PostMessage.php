<?php

namespace Herecsrymy\Newsletter\Messages;

use Herecsrymy\Entities\NewsletterSubscription;
use Herecsrymy\Entities\Post;
use Herecsrymy\Newsletter\IMessage;
use Nette\Object;


class PostMessage extends Object implements IMessage
{

	/** @var int */
	private $subscription;

	/** @var int */
	private $post;


	public function __construct(NewsletterSubscription $subscription, Post $post)
	{
		$this->subscription = $subscription->getId();
		$this->post = $post->getId();
	}


	public function serialize()
	{
		return serialize([
			'subscription' => $this->subscription,
			'post' => $this->post,
		]);
	}


	public function unserialize($serialized)
	{
		$unserialized = unserialize($serialized);

		$this->subscription = $unserialized['subscription'];
		$this->post = $unserialized['post'];
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
	public function getPost()
	{
		return $this->post;
	}


	/**
	 * @return string
	 */
	public function __toString()
	{
		return sprintf('%s: post=%d, subscription=%d', __CLASS__, $this->post, $this->subscription);
	}

}

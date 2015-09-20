<?php

namespace Herecsrymy\Newsletter;

use Serializable;


interface IMessage extends Serializable
{

	/**
	 * @return int
	 */
	public function getSubscription();


	/**
	 * @return string
	 */
	public function __toString();

}

<?php


namespace Herecsrymy\AdminModule\Components\SendNewsletter;


interface ISendNewsletterControlFactory
{
	/** @return SendNewsletterControl */
	function create();
}

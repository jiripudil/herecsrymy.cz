<?php

namespace Slovotepec\FrontModule\Components\Newsletter;


interface INewsletterControlFactory
{
	/** @return NewsletterControl */
	function create();
}

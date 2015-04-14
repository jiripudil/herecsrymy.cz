<?php

namespace Herecsrymy\FrontModule\Components\Newsletter;


interface INewsletterControlFactory
{
	/** @return NewsletterControl */
	function create();
}

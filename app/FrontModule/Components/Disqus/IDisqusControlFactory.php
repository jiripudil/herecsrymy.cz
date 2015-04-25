<?php

namespace Herecsrymy\FrontModule\Components\Disqus;


interface IDisqusControlFactory
{
	/** @return DisqusControl */
	function create($identifier = NULL, $title = NULL, $url = NULL);
}

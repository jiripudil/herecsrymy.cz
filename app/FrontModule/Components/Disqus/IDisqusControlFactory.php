<?php

namespace Herecsrymy\FrontModule\Components\Disqus;


interface IDisqusControlFactory
{

	public function create(int $identifier, string $title, string $url): DisqusControl;

}

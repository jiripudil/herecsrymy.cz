<?php

declare(strict_types = 1);

namespace Herecsrymy\FrontModule\Components\PostsFilter;


interface IPostsFilterControlFactory
{

	public function create(): PostsFilterControl;

}

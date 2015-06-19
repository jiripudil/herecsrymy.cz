<?php

namespace Herecsrymy\AdminModule\Components\FilterPosts;


interface IFilterPostsControlFactory
{
	/** @return FilterPostsControl */
	function create();
}

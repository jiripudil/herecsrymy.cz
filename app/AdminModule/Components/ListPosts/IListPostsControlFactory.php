<?php

namespace Herecsrymy\AdminModule\Components\ListPosts;


interface IListPostsControlFactory
{
	/** @return ListPostsControl */
	function create();
}

<?php

namespace Herecsrymy\FrontModule\Components\RecentPosts;


interface IRecentPostsControlFactory
{
	/**
	 * @param int $numberOfPosts
	 * @return RecentPostsControl
	 */
	function create($numberOfPosts);
}

<?php

namespace Herecsrymy\FrontModule\Components\RecentPosts;

use Herecsrymy\Application\UI\TBaseControl;
use Herecsrymy\Entities\Post;
use Herecsrymy\Entities\Queries\PostQuery;
use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI\Control;


class RecentPostsControl extends Control
{

	use TBaseControl;


	/** @var Post[] */
	private $posts;


	public function __construct($numberOfPosts, EntityManager $em)
	{
		$query = (new PostQuery())->onlyPublished();
		$this->posts = $em->getRepository(Post::class)->fetch($query)->applyPaging(0, $numberOfPosts);
	}


	public function render()
	{
		$this->template->posts = $this->posts;
		$this->template->render(__DIR__ . '/RecentPostsControl.latte');
	}

}

<?php

namespace Herecsrymy\AdminModule\Components\ListPosts;

use Herecsrymy\Application\UI\TBaseControl;
use Herecsrymy\Entities\Post;
use Herecsrymy\Entities\Queries\PostQuery;
use Herecsrymy\FrontModule\Components\Paging\IPagingControlFactory;
use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI\Control;


class ListPostsControl extends Control
{

	use TBaseControl;


	/** @var callable[] */
	public $onDelete = [];

	/** @var EntityManager */
	private $em;

	/** @var Post[] */
	private $posts;


	public function __construct(EntityManager $em)
	{
		$this->em = $em;

		$query = (new PostQuery())
			->joinCategories();
		$this->posts = $this->em->getRepository(Post::class)->fetch($query);
	}


	/** @secured */
	public function handleDelete($id)
	{
		$post = $this->em->find(Post::class, $id);
		$this->em->remove($post)->flush();
		$this->onDelete();
	}


	public function render()
	{
		$this->template->posts = $this->posts->applyPaginator($this['paging']->getPaginator(), 15);
		$this->template->render(__DIR__ . '/ListPostsControl.latte');
	}


	protected function createComponentPaging(IPagingControlFactory $factory)
	{
		return $factory->create();
	}

}

<?php

namespace Slovotepec\FrontModule\Presenters;

use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI\Presenter;
use Slovotepec\Application\UI\TBasePresenter;
use Slovotepec\Entities\Category;
use Slovotepec\Entities\Post;
use Slovotepec\Entities\Queries\PostsQuery;


class FeedPresenter extends Presenter
{

	use TBasePresenter;


	/** @var EntityManager */
	private $em;

	/** @var Post[] */
	private $posts;


	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}


	public function actionDefault(Category $category = NULL)
	{
		$query = (new PostsQuery())
			->onlyPublished();

		if ($category !== NULL) {
			$query->ofCategory($category);
		}

		$this->posts = $this->em->getRepository(Post::class)
			->fetch($query)
			->applyPaging(0, 10);

		$this->getHttpResponse()->setContentType('application/xml');
	}


	public function renderDefault(Category $category = NULL)
	{
		$this->template->category = $category;
		$this->template->posts = $this->posts;
	}

}

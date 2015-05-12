<?php

namespace Herecsrymy\FrontModule\Presenters;

use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI\Presenter;
use Herecsrymy\Application\UI\TBasePresenter;
use Herecsrymy\Entities\Category;
use Herecsrymy\Entities\Post;
use Herecsrymy\Entities\Queries\PostQuery;


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
		$query = (new PostQuery())
			->onlyPublished();

		if ($category !== NULL) {
			$query->inCategory($category);
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

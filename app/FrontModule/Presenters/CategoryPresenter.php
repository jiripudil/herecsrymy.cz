<?php

namespace Herecsrymy\FrontModule\Presenters;

use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI\Presenter;
use Herecsrymy\Application\UI\TBasePresenter;
use Herecsrymy\Entities\Category;
use Herecsrymy\Entities\Post;
use Herecsrymy\Entities\Queries\PostsQuery;
use Herecsrymy\FrontModule\Components\Head\HeadControl;
use Herecsrymy\FrontModule\Components\Header\IHeaderControlFactory;
use Herecsrymy\FrontModule\Components\Paging\IPagingControlFactory;


class CategoryPresenter extends Presenter
{

	use TBasePresenter;


	/** @var EntityManager */
	private $em;


	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}


	public function actionDefault(Category $category)
	{
		$query = (new PostsQuery())
			->onlyPublished()
			->ofCategory($category);

		/** @var Paginator $paginator */
		$paginator = $this['paging']->getPaginator();
		$posts = $this->em->getRepository(Post::class)->fetch($query);
		$posts->applyPaginator($paginator, 10);

		$this['paging']->addLinks($this['head']);
		$this['mainMenu']->setCurrentCategory($category);

		$this->template->category = $category;
		$this->template->posts = $posts;
	}


	public function renderDefault(Category $category)
	{
		/** @var HeadControl $head */
		$head = $this['head'];
		$head->addTitlePart($category->title);
		$head->addFeed(HeadControl::FEED_RSS, $this->link('Feed:', ['category' => $category]), $category->title . ' – Jiří Pudil');
	}


	protected function createComponentHeader(IHeaderControlFactory $factory)
	{
		return $factory->create('small');
	}


	protected function createComponentPaging(IPagingControlFactory $factory)
	{
		return $factory->create();
	}

}

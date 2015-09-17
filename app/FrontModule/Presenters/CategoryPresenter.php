<?php

namespace Herecsrymy\FrontModule\Presenters;

use Herecsrymy\FrontModule\Components\Disqus\IDisqusControlFactory;
use Herecsrymy\Application\UI\TBasePresenter;
use Herecsrymy\Entities\Category;
use Herecsrymy\Entities\Post;
use Herecsrymy\Entities\Queries\PostQuery;
use Herecsrymy\FrontModule\Components\Head\HeadControl;
use Herecsrymy\FrontModule\Components\Header\IHeaderControlFactory;
use Herecsrymy\FrontModule\Components\Newsletter\INewsletterControlFactory;
use Herecsrymy\FrontModule\Components\Paging\IPagingControlFactory;
use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI\Presenter;
use Nette\Utils\Paginator;


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
		$query = (new PostQuery())
			->onlyPublished()
			->inCategory($category);

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


	protected function createComponentDisqus(IDisqusControlFactory $factory)
	{
		return $factory->create();
	}


	protected function createComponentPaging(IPagingControlFactory $factory)
	{
		return $factory->create();
	}


	protected function createComponentNewsletter(INewsletterControlFactory $factory)
	{
		$control = $factory->create();
		$control->onSubscribe[] = function () {
			$this->redirect('this');
		};

		return $control;
	}

}

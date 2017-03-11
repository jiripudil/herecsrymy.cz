<?php

declare(strict_types = 1);

namespace Herecsrymy\FrontModule\Presenters;

use Herecsrymy\Application\UI\TBasePresenter;
use Herecsrymy\Entities\Category;
use Herecsrymy\Entities\Post;
use Herecsrymy\Entities\Queries\PostQuery;
use Herecsrymy\FrontModule\Components\Paging\IPagingControlFactory;
use Herecsrymy\FrontModule\Components\PostsFilter\IPostsFilterControlFactory;
use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI\Presenter;
use Nette\Utils\Paginator;


class PostsPresenter extends Presenter
{

	use TBasePresenter;


	/**
	 * @var EntityManager
	 */
	private $em;


	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}


	public function renderDefault()
	{
		$query = (new PostQuery())
			->onlyPublished();

		/** @var Category[] $categories */
		$categories = $this['filter']->getSelectedCategories();
		$query->inCategories($categories);

		/** @var Paginator $paginator */
		$paginator = $this['paging']->getPaginator();
		$posts = $this->em->getRepository(Post::class)->fetch($query);
		$posts->applyPaginator($paginator, 12);

		$this->template->posts = $posts;

		$this['paging']->redrawControl();
		$this['filter']->redrawControl();
	}


	protected function createComponentFilter(IPostsFilterControlFactory $factory)
	{
		$control = $factory->create();
		$control->onFilter[] = function () {
			// reset to first page after changing filters
			$this['paging']->reset();

			if ($this->isAjax()) {
				$this->payload->postGet = TRUE;
				$this->payload->url = $this->link('this');

			} else {
				$this->redirect('this');
			}
		};

		return $control;
	}


	protected function createComponentPaging(IPagingControlFactory $factory)
	{
		return $factory->create();
	}

}

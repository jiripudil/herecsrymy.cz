<?php

namespace Herecsrymy\AdminModule\Presenters;

use Herecsrymy\AdminModule\Components\EditPost\IEditPostControlFactory;
use Herecsrymy\AdminModule\Components\ListPosts\IListPostsControlFactory;
use Herecsrymy\Entities\Post;
use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI\Presenter;


class PostPresenter extends Presenter
{

	use TAdminPresenter;
	use TSecuredPresenter;


	/** @var EntityManager */
	private $em;

	/** @var Post */
	private $post;


	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}


	public function actionEdit($id = NULL)
	{
		$this->post = $id !== NULL ? $this->em->find(Post::class, $id) : new Post('Untitled');

		$this['editPost']->onSave[] = function () {
			$this['flashes']->flashMessage('Saved.', 'success');
			$this->redirect('default');
		};
	}


	protected function createComponentListPosts(IListPostsControlFactory $factory)
	{
		$control = $factory->create();
		$control->onDelete[] = function () {
			$this['flashes']->flashMessage('The post has been deleted.', 'success');
			$this->redirect('this');
		};

		return $control;
	}


	protected function createComponentEditPost(IEditPostControlFactory $factory)
	{
		return $factory->create($this->post);
	}

}

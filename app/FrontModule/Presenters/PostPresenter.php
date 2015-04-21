<?php

namespace Herecsrymy\FrontModule\Presenters;

use Nette\Application\UI\Presenter;
use Herecsrymy\Application\UI\TBasePresenter;
use Herecsrymy\Entities\Post;
use Herecsrymy\FrontModule\Components\Head\HeadControl;
use Herecsrymy\FrontModule\Components\Header\IHeaderControlFactory;


class PostPresenter extends Presenter
{

	use TBasePresenter;


	public function actionDefault(Post $post)
	{
		if ( ! $this->getUser()->isLoggedIn() && ! $post->isPublic()) {
			$this->error();
		}
	}


	public function renderDefault(Post $post)
	{
		/** @var HeadControl $head */
		$head = $this['head'];
		$head->addTitlePart($post->category->title);
		$head->addTitlePart($post->title);

		$this['mainMenu']->setCurrentCategory($post->category);

		$this->template->post = $post;
	}


	protected function createComponentHeader(IHeaderControlFactory $factory)
	{
		return $factory->create('small');
	}

}

<?php

namespace Slovotepec\FrontModule\Presenters;

use Nette\Application\UI\Presenter;
use Slovotepec\Application\UI\TBasePresenter;
use Slovotepec\Entities\Post;
use Slovotepec\FrontModule\Components\Head\HeadControl;
use Slovotepec\FrontModule\Components\Header\IHeaderControlFactory;


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

<?php

namespace Herecsrymy\FrontModule\Presenters;

use Herecsrymy\FrontModule\Components\Disqus\IDisqusControlFactory;
use Nette\Application\UI\Presenter;
use Herecsrymy\Application\UI\TBasePresenter;
use Herecsrymy\Entities\Post;
use Herecsrymy\FrontModule\Components\Head\HeadControl;
use Herecsrymy\FrontModule\Components\Header\IHeaderControlFactory;


class PostPresenter extends Presenter
{

	use TBasePresenter;


	/** @var Post */
	private $post;


	public function actionDefault(Post $post)
	{
		if ( ! $this->getUser()->isLoggedIn() && ! $post->isPublic()) {
			$this->error();
		}

		$this->post = $post;
	}


	public function renderDefault()
	{
		/** @var HeadControl $head */
		$head = $this['head'];
		$head->addTitlePart($this->post->category->title);
		$head->addTitlePart($this->post->title);

		$this['mainMenu']->setCurrentCategory($this->post->category);

		$this->template->post = $this->post;
	}


	protected function createComponentHeader(IHeaderControlFactory $factory)
	{
		return $factory->create('small');
	}


	protected function createComponentDisqus(IDisqusControlFactory $factory)
	{
		return $factory->create(
			$this->post->id,
			$this->post->title,
			$this->link('//this')
		);
	}

}

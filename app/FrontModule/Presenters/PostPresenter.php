<?php

namespace Herecsrymy\FrontModule\Presenters;

use Herecsrymy\FrontModule\Components\Attachments\IAttachmentsControlFactory;
use Herecsrymy\FrontModule\Components\Disqus\IDisqusControlFactory;
use Nette\Application\UI\Presenter;
use Herecsrymy\Application\UI\TBasePresenter;
use Herecsrymy\Entities\Post;


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
		$this->template->post = $this->post;
	}


	protected function createComponentDisqus(IDisqusControlFactory $factory)
	{
		return $factory->create(
			$this->post->getId(),
			$this->post->title,
			$this->link('//this')
		);
	}


	protected function createComponentAttachments(IAttachmentsControlFactory $factory)
	{
		return $factory->create($this->post);
	}

}

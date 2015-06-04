<?php

namespace Herecsrymy\AdminModule\Presenters;

use Herecsrymy\AdminModule\Components\EditAttachment\IEditAttachmentControlFactory;
use Herecsrymy\Entities\Attachment;
use Herecsrymy\Entities\Post;
use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI\Presenter;


class AttachmentPresenter extends Presenter
{

	use TAdminPresenter;
	use TSecuredPresenter;


	/** @var EntityManager */
	private $em;

	/** @var Post */
	private $post;

	/** @var Attachment */
	private $attachment;


	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}


	public function actionEdit($id = NULL, $post = NULL, $type = NULL)
	{
		if ($id !== NULL) {
			$this->attachment = $this->em->find(Attachment::class, $id);
			$this->post = $this->attachment->post;

		} elseif ($post !== NULL && $type !== NULL) {
			$this->post = $this->em->find(Post::class, $post);
			$this->attachment = new Attachment('Untitled', $type, $this->post);

		} else {
			$this->error();
		}

		$this['editAttachment']->onSave[] = function () {
			$this['flashes']->flashMessage('Saved.', 'success');
			$this->redirect('this');
		};
	}


	public function renderEdit()
	{
		$this->template->post = $this->post;
	}


	protected function createComponentEditAttachment(IEditAttachmentControlFactory $factory)
	{
		$control = $factory->create($this->attachment);

		$control->onFileUpload[] = function () {
			$this->flashMessage('Uploaded.', 'success');
			$this->redirect('this');
		};
		$control->onFileDelete[] = function () {
			$this->flashMessage('Deleted.', 'success');
			$this->redirect('this');
		};

		return $control;
	}

}

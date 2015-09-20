<?php


namespace Herecsrymy\AdminModule\Components\SendNewsletter;

use Herecsrymy\Application\UI\TBaseControl;
use Herecsrymy\Newsletter\NewsletterQueue;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;


/**
 * @method void onSend(string $subject)
 */
class SendNewsletterControl extends Control
{

	use TBaseControl;


	/** @var callable[] of function(string $subject) */
	public $onSend = [];

	/** @var NewsletterQueue */
	private $queue;


	public function __construct(NewsletterQueue $queue)
	{
		$this->queue = $queue;
	}


	public function render()
	{
		$this->template->render(__DIR__ . '/SendNewsletterControl.latte');
	}


	protected function createComponentForm()
	{
		$form = new Form();

		$form->addText('subject', 'Subject')
			->setRequired('You must enter newsletter subject.');
		$form->addTextArea('text', 'Text')
			->setRequired('You must enter newsletter text.');

		$form->addSubmit('send', 'Send');
		$form->onSuccess[] = function ($_, $values) {
			$this->queue->enqueueCustomNewsletter($subject = $values->subject, $values->text);
			$this->onSend($subject);
		};

		return $form;
	}

}

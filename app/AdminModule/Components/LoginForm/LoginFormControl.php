<?php

namespace Herecsrymy\AdminModule\Components\LoginForm;

use Herecsrymy\Application\UI\TBaseControl;
use Herecsrymy\Security\Authenticator;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use Nette\Security\IIdentity;
use Nette\Security\User;


/**
 * @method void onLogin(IIdentity $identity)
 */
class LoginFormControl extends Control
{

	use TBaseControl;


	/** @var callable[] of function(IIdentity $identity) */
	public $onLogin = [];

	/** @var Authenticator */
	private $authenticator;

	/** @var User */
	private $userContext;


	public function __construct(Authenticator $authenticator, User $userContext)
	{
		$this->authenticator = $authenticator;
		$this->userContext = $userContext;
	}


	protected function createComponentForm()
	{
		$form = new Form();

		$form->addText('email', 'E-mail')
			->setType('email')
			->setRequired('Please enter your e-mail.')
			->addRule($form::EMAIL, 'Please enter a valid e-mail address.');
		$form->addPassword('password', 'Password')
			->setRequired('Please enter your password.');

		$form->addSubmit('login', 'Login');
		$form->onSuccess[] = [$this, 'process'];
		return $form;
	}


	public function process(Form $form, $values)
	{
		try {
			$identity = $this->authenticator->authenticate($values->email, $values->password);
			$this->userContext->login($identity);
			$this->onLogin($identity);

		} catch (AuthenticationException $e) {
			$form->addError('Invalid credentials.');
		}
	}


	public function render()
	{
		$this->template->render(__DIR__ . '/LoginFormControl.latte');
	}

}

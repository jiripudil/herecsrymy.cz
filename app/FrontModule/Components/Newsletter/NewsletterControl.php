<?php

namespace Slovotepec\FrontModule\Components\Newsletter;

use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Slovotepec\Application\UI\TBaseControl;
use Slovotepec\Entities\NewsletterSubscription;


/**
 * @method void onSubscribe(NewsletterSubscription $subscription)
 */
class NewsletterControl extends Control
{

	use TBaseControl;


	/** @var callable[] of function(NewsletterSubscription $subscription) */
	public $onSubscribe = [];

	/** @var EntityManager */
	private $em;


	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}


	protected function createComponentSubscriptionForm()
	{
		$form = new Form();

		$form->addText('email')
			->setType('email')
			->setRequired('Zapomněli jste zadat svoji e-mailovou adresu.')
			->addRule($form::EMAIL, 'Zadejte prosím platnou e-mailovou adresu.');

		$form->addSubmit('subscribe', 'Odebírat novinky');
		$form->onSuccess[] = [$this, 'processSubscriptionForm'];

		return $form;
	}


	public function processSubscriptionForm(Form $form, $values)
	{
		/** @var NewsletterSubscription $subscription */
		$subscription = $this->em->getRepository(NewsletterSubscription::class)->findOneBy(['email' => $values->email]);

		if ($subscription === NULL) {
			$subscription = new NewsletterSubscription($values->email);
			$this->em->persist($subscription);

		} elseif ($subscription->active) {
			$form['email']->addError('Tento e-mail již novinky odebírá.');
			return;

		} else {
			$subscription->renewSubscription();
		}

		$this->em->flush();
		$this->flashMessage('Hotovo, teď už určitě o žádný nový příspěvek nepřijdete.', 'success');
		$this->onSubscribe($subscription);
	}


	public function render()
	{
		$this->template->render(__DIR__ . '/NewsletterControl.latte');
	}

}

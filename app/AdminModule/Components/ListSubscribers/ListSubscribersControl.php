<?php

namespace Herecsrymy\AdminModule\Components\ListSubscribers;

use Herecsrymy\Application\UI\TBaseControl;
use Herecsrymy\Entities\NewsletterSubscription;
use Herecsrymy\Entities\Queries\NewsletterSubscriptionQuery;
use Herecsrymy\FrontModule\Components\Paging\IPagingControlFactory;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Doctrine\ResultSet;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;


/**
 * @method void onUnsubscribe(NewsletterSubscription $subscription)
 */
class ListSubscribersControl extends Control
{

	use TBaseControl;


	/** @var callable[] of function(NewsletterSubscription $subscription) */
	public $onUnsubscribe = [];

	/** @var string @persistent */
	public $q;

	/** @var bool @persistent */
	public $onlyActive = FALSE;

	/** @var EntityManager */
	private $em;

	/** @var NewsletterSubscription[] */
	private $subscriptions;


	public function __construct(EntityManager $em)
	{
		$this->em = $em;
		$this->onAttached[] = [$this, 'loadSubscribers'];
	}


	/** @internal */
	public function loadSubscribers()
	{
		$repo = $this->em->getRepository(NewsletterSubscription::class);
		$query = new NewsletterSubscriptionQuery();

		if ($this->q !== NULL) {
			$query->byEmail($this->q);
		}

		if ($this->onlyActive) {
			$query->onlyActive();
		}

		$this->subscriptions = $repo->fetch($query);
	}


	/** @secured */
	public function handleUnsubscribe($id)
	{
		/** @var NewsletterSubscription $subscription */
		$subscription = $this->em->find(NewsletterSubscription::class, $id);
		$subscription->unsubscribe();
		$this->em->flush();

		$this->onUnsubscribe($subscription);
	}


	public function render()
	{
		$this->template->subscriptions = $this->subscriptions->applyPaginator($this['paging']->getPaginator(), 40);
		$this->template->render(__DIR__ . '/ListSubscribersControl.latte');
	}


	protected function createComponentPaging(IPagingControlFactory $factory)
	{
		return $factory->create();
	}


	protected function createComponentFilterForm()
	{
		$form = new Form();
		$form->addText('q')
			->setDefaultValue($this->q);
		$form->addCheckbox('onlyActive')
			->setDefaultValue($this->onlyActive);

		$form->addSubmit('filter', 'Filter');
		$form->onSuccess[] = function ($_, $values) {
			$this->redirect('this', ['q' => $values->q, 'onlyActive' => $values->onlyActive]);
		};

		return $form;
	}

}

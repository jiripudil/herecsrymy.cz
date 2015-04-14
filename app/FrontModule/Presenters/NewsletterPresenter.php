<?php

namespace Slovotepec\FrontModule\Presenters;

use Kdyby\Doctrine\EntityManager;
use Kdyby\Doctrine\EntityRepository;
use Nette\Application\UI\Presenter;
use Slovotepec\Application\UI\TBasePresenter;
use Slovotepec\Entities\NewsletterSubscription;
use Slovotepec\FrontModule\Components\Header\IHeaderControlFactory;


class NewsletterPresenter extends Presenter
{

	use TBasePresenter;


	/** @var EntityManager */
	private $em;

	/** @var EntityRepository */
	private $repository;

	/** @var NewsletterSubscription|NULL */
	private $subscription;


	public function __construct(EntityManager $em)
	{
		$this->em = $em;
		$this->repository = $em->getRepository(NewsletterSubscription::class);
	}


	public function actionUnsubscribe($hash)
	{
		if ($hash === NULL) {
			$this->error();
		}

		list($hash, $emailHash) = str_split($hash, 32);
		$this->subscription = $this->repository->findOneBy(['unsubscribeHash' => $hash]);

		if ($this->subscription !== NULL && md5($this->subscription->email) !== $emailHash) {
			$this->subscription = NULL;
		}

		if ($this->subscription !== NULL) {
			$this->subscription->unsubscribe();
			$this->em->flush();
		}
	}


	public function renderUnsubscribe()
	{
		$this->template->subscription = $this->subscription;
	}


	protected function createComponentHeader(IHeaderControlFactory $factory)
	{
		return $factory->create('small');
	}

}

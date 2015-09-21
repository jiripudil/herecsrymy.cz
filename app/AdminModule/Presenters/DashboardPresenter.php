<?php

namespace Herecsrymy\AdminModule\Presenters;

use Herecsrymy\Entities\Category;
use Herecsrymy\Entities\Event;
use Herecsrymy\Entities\NewsletterSubscription;
use Herecsrymy\Entities\Post;
use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI\Presenter;


class DashboardPresenter extends Presenter
{

	use TAdminPresenter;
	use TSecuredPresenter;


	/** @var EntityManager */
	private $em;


	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}


	public function renderDefault()
	{
		$this->template->summary = [
			'posts' => $this->em->getRepository(Post::class)->countBy(),
			'categories' => $this->em->getRepository(Category::class)->countBy(),
			'events' => $this->em->getRepository(Event::class)->countBy(['datetime >' => new \DateTime()]),
			'subscriptions' => $this->em->getRepository(NewsletterSubscription::class)->countBy(['active' => TRUE]),
		];
	}

}

<?php

namespace Slovotepec\FrontModule\Presenters;

use Doctrine;
use Slovotepec;
use Kdyby;
use Nette;


class ConcertsPresenter extends Nette\Application\UI\Presenter
{

	/** @var Kdyby\Doctrine\EntityManager */
	private $em;

	/** @var Slovotepec\Model\Concerts\Queries\ConcertsQuery */
	private $concertsQuery;


	public function __construct(Kdyby\Doctrine\EntityManager $em)
	{
		$this->em = $em;
	}


	public function actionDefault()
	{
		$this->concertsQuery = (new Slovotepec\Model\Concerts\Queries\ConcertsQuery())
			->inDateRange(new \DateTime(), new \DateTime('+1 year'));
	}


	public function renderDefault()
	{
		$dao = $this->em->getRepository(Slovotepec\Model\Concerts\Concert::class);
		$this->template->concerts = $dao->fetch($this->concertsQuery);
	}


	/**
	 * @param float $lon
	 * @param float $lat
	 */
	public function handleFindNearest($lon, $lat)
	{
		$this->concertsQuery->nearestTo(new Slovotepec\Doctrine\Geo\Point($lon, $lat));
		$this->redrawControl('concerts');
	}

}

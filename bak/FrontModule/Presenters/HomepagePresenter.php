<?php

namespace Slovotepec\FrontModule\Presenters;

use Doctrine;
use Slovotepec;
use Kdyby\Doctrine\EntityManager;
use Nette;


class HomepagePresenter extends Nette\Application\UI\Presenter
{

	/** @var EntityManager */
	private $em;


	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}


	public function renderDefault()
	{
		$concertsQuery = (new Slovotepec\Model\Concerts\Queries\ConcertsQuery())
			->upcoming();
		$concertsRepo = $this->em->getRepository(Slovotepec\Model\Concerts\Concert::class);
		$this->template->upcomingConcerts = $concertsRepo->fetch($concertsQuery);

		$newsRepo = $this->em->getRepository(Slovotepec\Model\News\Post::class);
		$this->template->latestNews = $newsRepo->findBy([
			'published' => TRUE,
		], [
			'datetime' => 'DESC',
		]);

		$albumsRepo = $this->em->getRepository(Slovotepec\Model\Songs\Album::class);
		$this->template->latestAlbum = $albumsRepo->findOneBy([
			'datetime <=' => new \DateTime,
		], [
			'datetime' => 'DESC',
		]);
	}

}

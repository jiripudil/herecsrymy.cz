<?php

namespace Herecsrymy\Sitemap;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Herecsrymy\Entities\Category;
use Herecsrymy\Entities\Post;
use Kdyby\Doctrine\Events;
use Kdyby\Events\Subscriber;


class SitemapListener implements Subscriber
{

	/** @var SitemapGenerator */
	private $generator;


	public function __construct(SitemapGenerator $generator)
	{
		$this->generator = $generator;
	}


	public function getSubscribedEvents()
	{
		return [
			Events::postPersist,
		];
	}


	public function postPersist(LifecycleEventArgs $args)
	{
		if ($args->getEntity() instanceof Post || $args->getEntity() instanceof Category) {
			$this->generator->generateSitemap();
		}
	}

}

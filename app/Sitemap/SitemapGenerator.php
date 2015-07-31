<?php

namespace Herecsrymy\Sitemap;

use Herecsrymy\Entities\Category;
use Herecsrymy\Entities\Post;
use Herecsrymy\Entities\Queries\PostQuery;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Doctrine\EntityRepository;
use Nette\Application\LinkGenerator;
use Nette\Utils\Html;


class SitemapGenerator
{

	/** @var string */
	private $fileName;

	/** @var EntityRepository */
	private $categoryRepo;

	/** @var EntityRepository */
	private $postRepo;

	/** @var LinkGenerator */
	private $linkGenerator;


	public function __construct($fileName, EntityManager $em, LinkGenerator $linkGenerator)
	{
		$this->fileName = $fileName;
		$this->linkGenerator = $linkGenerator;
		$this->categoryRepo = $em->getRepository(Category::class);
		$this->postRepo = $em->getRepository(Post::class);
	}


	public function generateSitemap()
	{
		$sitemap = fopen($this->fileName, 'wb');
		fwrite($sitemap, '<?xml version="1.0" encoding="utf-8"?>' . PHP_EOL . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL);

		$posts = $this->postRepo->fetch((new PostQuery())->onlyPublished());
		$categories = $this->categoryRepo->findBy(['published' => TRUE], ['sort' => 'ASC', 'title' => 'ASC']);

		$home = Html::el('url');
		$home->create('loc')->setText($this->linkGenerator->link('Front:Homepage:'));
		$home->create('changefreq')->setText('monthly');
		$home->create('priority')->setText('0.5');
		fwrite($sitemap, $home . PHP_EOL);

		$eventsTag = Html::el('url');
		$eventsTag->create('loc')->setText($this->linkGenerator->link('Front:Events:'));
		$eventsTag->create('changefreq')->setText('weekly');
		$eventsTag->create('priority')->setText('0.6');
		fwrite($sitemap, $eventsTag . PHP_EOL);

		foreach ($categories as $category) {
			$categoryTag = Html::el('url');
			$categoryTag->create('loc')->setText($this->linkGenerator->link('Front:Category:', ['category' => $category]));
			$categoryTag->create('changefreq')->setText('weekly');
			$categoryTag->create('priority')->setText('0.7');
			fwrite($sitemap, $categoryTag . PHP_EOL);
		}

		foreach ($posts as $post) {
			$postTag = Html::el('url');
			$postTag->create('loc')->setText($this->linkGenerator->link('Front:Post:', ['post' => $post]));
			$postTag->create('changefreq')->setText('monthly');
			$postTag->create('priority')->setText('1.0');
			fwrite($sitemap, $postTag . PHP_EOL);
		}

		fwrite($sitemap, '</urlset>' . PHP_EOL);
		fclose($sitemap);
	}

}

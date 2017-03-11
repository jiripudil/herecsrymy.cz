<?php

namespace Herecsrymy\Application\Routers;

use Herecsrymy\Entities\Category;
use Herecsrymy\Entities\Event;
use Herecsrymy\Entities\File;
use Herecsrymy\Entities\Post;
use Kdyby\Doctrine\EntityManager;
use Nette;
use Nette\Application\Routers as NRouters;
use NetteModule\MicroPresenter;
use Nextras\Routing\StaticRouter;
use Zax\Application\Routers\MetadataBuilder;


class RouterFactory extends Nette\Object
{

	/** @var EntityManager */
	private $em;


	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}


	/**
	 * @return Nette\Application\IRouter
	 */
	public function createRouter()
	{
		$router = new NRouters\RouteList();

		// admin
		$router[] = new NRouters\Route('admin[/<presenter>[/<action>[/<id>]]]', [
			'module' => 'Admin',
			'presenter' => 'Dashboard',
			'action' => 'default',
		]);

		// události
		$router[] = new NRouters\Route('udalosti[/<action>/<event>]', [
			'module' => 'Front',
			'presenter' => 'Events',
			'action' => 'default',
			'event' => [
				NRouters\Route::FILTER_IN => function ($event) {
					return $this->em->getRepository(Event::class)->find($event);
				},
				NRouters\Route::FILTER_OUT => function (Event $event) {
					return $event->getId();
				}
			]
		]);

		// RSS feeds
		$router[] = new NRouters\Route('rss[/<category>]', [
			'module' => 'Front',
			'presenter' => 'Feed',
			'action' => 'default',
			'category' => [
				NRouters\Route::FILTER_IN => function ($category) {
					return $this->em->getRepository(Category::class)->findOneBy(['slug' => $category]);
				},
				NRouters\Route::FILTER_OUT => function (Category $category) {
					return $category->slug;
				}
			]
		]);

		// attachments
		$router[] = new NRouters\Route('file/<file>', [
			'module' => 'Front',
			'presenter' => 'File',
			'action' => 'default',
			'file' => [
				NRouters\Route::FILTER_IN => function ($file) {
					return $this->em->getRepository(File::class)->find($file);
				},
				NRouters\Route::FILTER_OUT => function (File $file) {
					return $file->getId();
				}
			]
		]);

		$router[] = new NRouters\Route('prispevek/<post>', [
			'module' => 'Front',
			'presenter' => 'Post',
			'action' => 'default',
			'post' => [
				NRouters\Route::FILTER_IN => function ($post) {
					return $this->em->getRepository(Post::class)->findOneBy(['slug' => $post]);
				},
				NRouters\Route::FILTER_OUT => function (Post $post) {
					return $post->slug;
				}
			],
		]);

		$mapping = [
			'module' => 'Front',
			'presenter' => 'Posts',
			'action' => 'default',
		];
		$meta = (new MetadataBuilder($mapping))
			->addAlias('categories', 'filter-categories')
			->addArrayParam('categories')
			->build();
		$router[] = new NRouters\Route('tvorba[/<categories \D+>][/<paging-page \d+>]', $meta);

		$router[] = new StaticRouter([
			'Front:Page:about' => 'o-mne',
			'Front:Page:contact' => 'kontakt',
		]);

		$router[] = new NRouters\Route('<category>/<post>', function ($post, MicroPresenter $presenter) {
			return $presenter->redirectUrl('/prispevek/' . $post, Nette\Http\IResponse::S301_MOVED_PERMANENTLY);
		});

		$router[] = new NRouters\Route('<category>', function ($category, MicroPresenter $presenter) {
			return $presenter->redirectUrl('/tvorba/' . $category, Nette\Http\IResponse::S301_MOVED_PERMANENTLY);
		});

		$router[] = new NRouters\Route('<presenter>[/<action>[/<id>]]', 'Front:Homepage:default');
		$router[] = new NRouters\Route('index.php', 'Front:Homepage:default', NRouters\Route::ONE_WAY);

		return $router;
	}

}

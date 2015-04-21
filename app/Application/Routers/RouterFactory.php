<?php

namespace Herecsrymy\Application\Routers;

use Kdyby\Doctrine\EntityManager;
use Nette;
use Nette\Application\Routers as NRouters;
use Herecsrymy\Entities\Category;
use Herecsrymy\Entities\Post;


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
		NRouters\Route::$defaultFlags = NRouters\Route::SECURED;

		// admin
		$router[] = new NRouters\Route('admin[/<presenter>[/<action>[/<id>]]]', [
			'module' => 'Admin',
			'presenter' => 'Dashboard',
			'action' => 'default',
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

		// post detail
		$router[] = new NRouters\Route('<category>/<post>', [
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
			NULL => [
				NRouters\Route::FILTER_IN => function (array $params) {
					unset($params['category']);
					return $params;
				},
				NRouters\Route::FILTER_OUT => function (array $params) {
					$params['category'] = $params['post']->category->slug;
					return $params;
				}
			]
		]);

		// category listing
		$router[] = new NRouters\Route('<category>', [
			'module' => 'Front',
			'presenter' => 'Category',
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

		$router[] = new NRouters\Route('<presenter>[/<action>[/<id>]]', 'Front:Homepage:default');
		$router[] = new NRouters\Route('index.php', 'Front:Homepage:default', NRouters\Route::ONE_WAY);

		return $router;
	}

}

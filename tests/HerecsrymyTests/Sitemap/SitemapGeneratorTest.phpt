<?php

/**
 * @testCase
 */

namespace HerecsrymyTests\Sitemap;

use Herecsrymy\Entities\Category;
use Herecsrymy\Entities\Post;
use Herecsrymy\Entities\Queries\PostQuery;
use Herecsrymy\Sitemap\SitemapGenerator;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Doctrine\EntityRepository;
use Nette\Application\LinkGenerator;
use Tester\Assert;
use Tester\FileMock;
use Tester\TestCase;


require_once __DIR__ . '/../../bootstrap.php';


class SitemapGeneratorTest extends TestCase
{

	public function testGenerateSitemap()
	{
		// build categories
		$cat1 = new Category('Foo');
		$cat1->slug = 'foo';

		$cat2 = new Category('Bar');
		$cat2->slug = 'bar';

		$categories = [$cat1, $cat2];


		// build posts
		$post1 = new Post('Foo');
		$post1->slug = 'foo';
		$post1->category = $cat1;

		$post2 = new Post('Bar');
		$post2->slug = 'bar';
		$post2->category = $cat1;

		$post3 = new Post('Baz');
		$post3->slug = 'baz';
		$post3->category = $cat2;

		$posts = [$post1, $post2, $post3];


		// mocks
		$postRepo = \Mockery::mock(EntityRepository::class);
		$postRepo->shouldReceive('fetch')
			->with(\Mockery::type(PostQuery::class))
			->andReturn($posts);

		$categoryRepo = \Mockery::mock(EntityRepository::class);
		$categoryRepo->shouldReceive('findBy')
			->with(['published' => TRUE], ['sort' => 'ASC', 'title' => 'ASC'])
			->andReturn($categories);

		$em = \Mockery::mock(EntityManager::class);
		$em->shouldReceive('getRepository')->with(Post::class)->andReturn($postRepo);
		$em->shouldReceive('getRepository')->with(Category::class)->andReturn($categoryRepo);

		$linkGenerator = \Mockery::mock(LinkGenerator::class);
		$linkGenerator->shouldReceive('link')
			->times(6)
			->andReturnUsing(function ($destination, array $params = []) {
				$refUri = 'http://example.com';

				switch ($destination) {
					case 'Front:Homepage:':
						return $refUri;

					case 'Front:Category:':
						return $refUri . '/' . $params['category']->slug;

					case 'Front:Post:':
						return $refUri . '/' . $params['post']->category->slug . '/' . $params['post']->slug;
				}
			});

		$file = FileMock::create('');

		$generator = new SitemapGenerator($file, $em, $linkGenerator);
		$generator->generateSitemap();

		Assert::same(file_get_contents(__DIR__ . '/expected.xml'), file_get_contents($file));
	}


	protected function tearDown()
	{
		\Mockery::close();
	}

}


(new SitemapGeneratorTest())->run();

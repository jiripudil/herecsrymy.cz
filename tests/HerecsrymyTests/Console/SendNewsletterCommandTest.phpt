<?php

/**
 * @testCase
 */

namespace HerecsrymyTests\Console;

use Herecsrymy\Console\SendNewsletterCommand;
use Herecsrymy\Entities\NewsletterSubscription;
use Herecsrymy\Entities\Post;
use Herecsrymy\Newsletter\NewsletterSender;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Doctrine\EntityRepository;
use Symfony\Component\Console;
use Tester\Assert;
use Tester\TestCase;


require_once __DIR__ . '/../../bootstrap.php';


class SendNewsletterCommandTest extends TestCase
{

	public function testWithoutArguments()
	{
		$em = \Mockery::mock(EntityManager::class);
		$em->shouldReceive('getRepository')
			->with(Post::class)
			->andReturn($postRepository = \Mockery::mock(EntityRepository::class));

		$postRepository->shouldReceive('findOneBy')
			->andReturn($post = new Post('Foo'));

		$em->shouldReceive('getRepository')
			->with(NewsletterSubscription::class)
			->andReturn($subscriptionRepository = \Mockery::mock(EntityRepository::class));

		$subscriptionRepository->shouldReceive('findBy')
			->with(['active' => TRUE])
			->andReturn([
				new NewsletterSubscription('john.doe@example.com'),
				new NewsletterSubscription('jane.smith@example.com'),
			]);

		$sender = \Mockery::mock(NewsletterSender::class);
		$sender->shouldReceive('sendPostNewsletter')
			->with(\Mockery::type(NewsletterSubscription::class), $post)
			->twice();

		$application = new Console\Application();
		$application->add(new SendNewsletterCommand($em, $sender));

		$command = $application->find('herecsrymy:send-newsletter');
		$tester = new Console\Tester\CommandTester($command);
		$tester->execute(['command' => $command->getName()]);

		Assert::contains("Total newsletters sent: 2 / 2", $tester->getDisplay());
	}


	public function testWithGivenPost()
	{
		$em = \Mockery::mock(EntityManager::class);
		$em->shouldReceive('find')
			->with(Post::class, 42)
			->andReturn($post = new Post('Foo'));

		$em->shouldReceive('getRepository')
			->with(NewsletterSubscription::class)
			->andReturn($subscriptionRepository = \Mockery::mock(EntityRepository::class));

		$subscriptionRepository->shouldReceive('findBy')
			->with(['active' => TRUE])
			->andReturn([
				new NewsletterSubscription('john.doe@example.com'),
				new NewsletterSubscription('jane.smith@example.com'),
			]);

		$sender = \Mockery::mock(NewsletterSender::class);
		$sender->shouldReceive('sendPostNewsletter')
			->with(\Mockery::type(NewsletterSubscription::class), $post)
			->twice();

		$application = new Console\Application();
		$application->add(new SendNewsletterCommand($em, $sender));

		$command = $application->find('herecsrymy:send-newsletter');
		$tester = new Console\Tester\CommandTester($command);
		$tester->execute([
			'command' => $command->getName(),
			'--post' => 42,
		]);

		Assert::contains("Total newsletters sent: 2 / 2", $tester->getDisplay());
	}


	public function testWithGivenSubscriptions()
	{
		$em = \Mockery::mock(EntityManager::class);
		$em->shouldReceive('getRepository')
			->with(Post::class)
			->andReturn($postRepository = \Mockery::mock(EntityRepository::class));

		$postRepository->shouldReceive('findOneBy')
			->andReturn($post = new Post('Foo'));

		$em->shouldReceive('getRepository')
			->with(NewsletterSubscription::class)
			->andReturn($subscriptionRepository = \Mockery::mock(EntityRepository::class));

		$subscriptionRepository->shouldReceive('findBy')
			->with(['email' => ['john.doe@example.com', 'jane.smith@example.com'], 'active' => TRUE])
			->andReturn([
				new NewsletterSubscription('john.doe@example.com'),
				new NewsletterSubscription('jane.smith@example.com'),
			]);

		$sender = \Mockery::mock(NewsletterSender::class);
		$sender->shouldReceive('sendPostNewsletter')
			->with(\Mockery::type(NewsletterSubscription::class), $post)
			->twice();

		$application = new Console\Application();
		$application->add(new SendNewsletterCommand($em, $sender));

		$command = $application->find('herecsrymy:send-newsletter');
		$tester = new Console\Tester\CommandTester($command);
		$tester->execute([
			'command' => $command->getName(),
			'--email' => ['john.doe@example.com', 'jane.smith@example.com'],
		]);

		Assert::contains("Total newsletters sent: 2 / 2", $tester->getDisplay());
	}


	protected function tearDown()
	{
		\Mockery::close();
	}

}


(new SendNewsletterCommandTest())->run();

<?php

/**
 * @testCase
 */

namespace HerecsrymyTests\Newsletter;

use Herecsrymy\Entities\Category;
use Herecsrymy\Entities\Event;
use Herecsrymy\Entities\Location;
use Herecsrymy\Entities\NewsletterSubscription;
use Herecsrymy\Entities\Post;
use Herecsrymy\Newsletter\NewsletterSender;
use HerecsrymyTests\CreateContainer;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Monolog\CustomChannel;
use Kdyby\Monolog\Logger;
use Nette\Application\LinkGenerator;
use Nette\Application\UI\ITemplateFactory;
use Nette\Mail\IMailer;
use Nette\Mail\Message;
use Tester\Assert;
use Tester\TestCase;


require_once __DIR__ . '/../../bootstrap.php';


class NewsletterSenderTest extends TestCase
{

	use CreateContainer;


	public function testSendPostNewsletter()
	{
		$subscription = new NewsletterSubscription('john.doe@example.com');
		$subscription->unsubscribeHash = 'acbd18db4cc2f85cedef654fccc4a4d8';

		$category = new Category('foo');
		$category->slug = 'foo';

		$post = new Post('Foo');
		$post->slug = 'foo';
		$post->description = 'foo bar baz';
		$post->category = $category;

		$mailer = \Mockery::mock(IMailer::class);
		$mailer->shouldReceive('send')
			->andReturnUsing(function (Message $message) {
				Assert::same(['john.doe@example.com' => NULL], $message->getHeader('To'));
				Assert::same(['system@jiripudil.cz' => 'Jiří Pudil'], $message->getFrom());
				Assert::same('Nový příspěvek Foo na herecsrymy.cz', $message->getSubject());
				Assert::same('http://example.com/newsletter/unsubscribe?hash=acbd18db4cc2f85cedef654fccc4a4d88eb1b522f60d11fa897de1dc6351b7e8', $message->getHeader('List-Unsubscribe'));
				Assert::same(file_get_contents(__DIR__ . '/newPost.html'), $message->getHtmlBody());
			});

		$linkGenerator = \Mockery::mock(LinkGenerator::class);
		$linkGenerator->shouldReceive('link')
			->andReturnUsing(function ($destination, array $params = []) {
				$refUri = 'http://example.com';

				if ($destination === 'Front:Newsletter:unsubscribe') {
					return $refUri . '/newsletter/unsubscribe?hash=' . $params['hash'];

				} elseif ($destination === 'Front:Post:') {
					return $refUri . '/' . $params['post']->category->slug . '/' . $params['post']->slug;
				}
			});

		$em = \Mockery::mock(EntityManager::class);

		$templateFactory = $this->createContainer()->getByType(ITemplateFactory::class);

		$channel = \Mockery::mock(CustomChannel::class);
		$channel->shouldReceive('addInfo');

		$logger = \Mockery::mock(Logger::class);
		$logger->shouldReceive('channel')->with('newsletter')->andReturn($channel);

		$sender = new NewsletterSender($em, $mailer, $linkGenerator, $templateFactory, $logger);
		$sender->sendPostNewsletter($subscription, $post);
	}


	public function testSendEventNewsletter()
	{
		$subscription = new NewsletterSubscription('john.doe@example.com');
		$subscription->unsubscribeHash = 'acbd18db4cc2f85cedef654fccc4a4d8';

		$location = new Location();
		$location->name = 'Horní dolní';

		$event = new Event('Foo', new \DateTime('2015-08-08 20:00:00'));
		$event->note = 'největší party roku';
		$event->location = $location;

		$mailer = \Mockery::mock(IMailer::class);
		$mailer->shouldReceive('send')
			->andReturnUsing(function (Message $message) {
				Assert::same(['john.doe@example.com' => NULL], $message->getHeader('To'));
				Assert::same(['system@jiripudil.cz' => 'Jiří Pudil'], $message->getFrom());
				Assert::same('Nová událost Foo na herecsrymy.cz', $message->getSubject());
				Assert::same('http://example.com/newsletter/unsubscribe?hash=acbd18db4cc2f85cedef654fccc4a4d88eb1b522f60d11fa897de1dc6351b7e8', $message->getHeader('List-Unsubscribe'));
				Assert::same(file_get_contents(__DIR__ . '/newEvent.html'), $message->getHtmlBody());
			});

		$linkGenerator = \Mockery::mock(LinkGenerator::class);
		$linkGenerator->shouldReceive('link')
			->andReturnUsing(function ($destination, array $params = []) {
				$refUri = 'http://example.com';

				if ($destination === 'Front:Newsletter:unsubscribe') {
					return $refUri . '/newsletter/unsubscribe?hash=' . $params['hash'];

				} elseif ($destination === 'Front:Events:') {
					return $refUri . '/udalosti';
				}
			});

		$em = \Mockery::mock(EntityManager::class);

		$templateFactory = $this->createContainer()->getByType(ITemplateFactory::class);

		$channel = \Mockery::mock(CustomChannel::class);
		$channel->shouldReceive('addInfo');

		$logger = \Mockery::mock(Logger::class);
		$logger->shouldReceive('channel')->with('newsletter')->andReturn($channel);

		$sender = new NewsletterSender($em, $mailer, $linkGenerator, $templateFactory, $logger);
		$sender->sendEventNewsletter($subscription, $event);
	}


	public function testSendCustomNewsletter()
	{
		$subscription = new NewsletterSubscription('john.doe@example.com');
		$subscription->unsubscribeHash = 'acbd18db4cc2f85cedef654fccc4a4d8';

		$subject = 'Test custom newsletteru';
		$text = <<<TEXY
Ahoj, toto je test custom newsletteru.

Obsahuje několik odstavců textu a vzápětí následuje seznam:

- s první položkou,
- druhou položkou
- a třetí položkou.

Díky za pozornost a ahoj příště!

Jiří Pudil,
 brněnský herec s rýmy
TEXY;


		$mailer = \Mockery::mock(IMailer::class);
		$mailer->shouldReceive('send')
			->andReturnUsing(function (Message $message) use ($subject, $text) {
				Assert::same(['john.doe@example.com' => NULL], $message->getHeader('To'));
				Assert::same(['system@jiripudil.cz' => 'Jiří Pudil'], $message->getFrom());
				Assert::same($subject . ' – herecsrymy.cz', $message->getSubject());
				Assert::same('http://example.com/newsletter/unsubscribe?hash=acbd18db4cc2f85cedef654fccc4a4d88eb1b522f60d11fa897de1dc6351b7e8', $message->getHeader('List-Unsubscribe'));
				Assert::same(file_get_contents(__DIR__ . '/custom.html'), $message->getHtmlBody());
			});

		$linkGenerator = \Mockery::mock(LinkGenerator::class);
		$linkGenerator->shouldReceive('link')
			->andReturnUsing(function ($destination, array $params = []) {
				$refUri = 'http://example.com';

				if ($destination === 'Front:Newsletter:unsubscribe') {
					return $refUri . '/newsletter/unsubscribe?hash=' . $params['hash'];
				}
			});

		$em = \Mockery::mock(EntityManager::class);

		$templateFactory = $this->createContainer()->getByType(ITemplateFactory::class);

		$channel = \Mockery::mock(CustomChannel::class);
		$channel->shouldReceive('addInfo');

		$logger = \Mockery::mock(Logger::class);
		$logger->shouldReceive('channel')->with('newsletter')->andReturn($channel);

		$sender = new NewsletterSender($em, $mailer, $linkGenerator, $templateFactory, $logger);
		$sender->sendCustomNewsletter($subscription, $subject, $text);
	}


	protected function tearDown()
	{
		\Mockery::close();
	}

}


(new NewsletterSenderTest())->run();

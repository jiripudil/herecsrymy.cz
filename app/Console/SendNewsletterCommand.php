<?php

namespace Herecsrymy\Console;

use Herecsrymy\Entities\NewsletterSubscription;
use Herecsrymy\Entities\Post;
use Herecsrymy\Newsletter\NewsletterSender;
use Kdyby\Doctrine\EntityManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class SendNewsletterCommand extends Command
{

	/** @var EntityManager */
	private $em;

	/** @var NewsletterSender */
	private $sender;


	public function __construct(EntityManager $em, NewsletterSender $sender)
	{
		parent::__construct();
		$this->em = $em;
		$this->sender = $sender;
	}


	protected function configure()
	{
		$this->setName('herecsrymy:send-newsletter')
			->addOption('post', 'p', InputOption::VALUE_OPTIONAL, 'ID of the new post. If not provided, the most recent published post will be assumed.')
			->addOption('email', 'e', InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'Target e-mail address. If not provided, the whole list of active subscriptions will be used.');
	}


	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$postId = $input->getOption('post');
		if ($postId !== NULL) {
			$post = $this->em->find(Post::class, $postId);
		} else {
			$post = $this->em->getRepository(Post::class)->findOneBy([
				'published' => TRUE,
				'publishedOn <=' => new \DateTime(),
			], [
				'publishedOn' => 'DESC',
			]);
		}

		$emails = (array)$input->getOption('email');
		if (empty($emails)) {
			$subscriptions = $this->em->getRepository(NewsletterSubscription::class)->findBy(['active' => TRUE]);
		} else {
			$subscriptions = $this->em->getRepository(NewsletterSubscription::class)->findBy(['email' => $emails, 'active' => TRUE]);
		}

		$counter = 0;
		foreach ($subscriptions as $subscription) {
			$this->sender->sendPostNewsletter($subscription, $post);
			$counter++;
		}

		$output->writeln("<info>Total newsletters sent: {$counter} / " . count($subscriptions) . "</info>");
	}

}

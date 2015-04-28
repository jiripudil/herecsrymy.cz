<?php

namespace Herecsrymy\Console;

use Herecsrymy\Entities\User;
use Kdyby\Doctrine\EntityManager;
use Nette\Security\Passwords;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;


class CreateUserCommand extends Command
{

	/** @var EntityManager */
	private $em;


	public function __construct(EntityManager $em)
	{
		parent::__construct();
		$this->em = $em;
	}


	protected function configure()
	{
		$this->setName('herecsrymy:create-user')
			->addArgument('email', InputArgument::REQUIRED, 'E-mail address');
	}


	protected function execute(InputInterface $input, OutputInterface $output)
	{
		// 1) exit with error if e-mail is already taken
		$email = $input->getArgument('email');
		$user = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);
		if ($user !== NULL) {
			$output->writeln('<error>A user with given e-mail address already exists.</error>');
			exit(1);
		}

		// 2) prompt for password
		$question = (new Question("Enter the desired password of the user: "))
			->setHidden(TRUE)
			->setHiddenFallback(FALSE)
			->setValidator(function ($value) {
				if (trim($value) === '') {
					throw new \InvalidArgumentException("The password cannot be empty");
				}
				return $value;
			});
		$helper = $this->getHelper('question');
		$password = $helper->ask($input, $output, $question);

		// 3) save
		$user = new User($email);
		$user->password = Passwords::hash($password);
		$this->em->persist($user)->flush();

		// 4) done
		$output->writeln('<info>User successfully created.</info>');
	}

}

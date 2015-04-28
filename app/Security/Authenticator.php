<?php

namespace Herecsrymy\Security;


use Herecsrymy\Entities\User;
use Kdyby\Doctrine\EntityDao;
use Kdyby\Doctrine\EntityManager;
use Nette\Security\AuthenticationException;
use Nette\Security\IIdentity;
use Nette\Security\Passwords;

class Authenticator
{

	/** @var EntityDao */
	private $repository;


	public function __construct(EntityManager $em)
	{
		$this->repository = $em->getRepository(User::class);
	}


	/**
	 * @param string $email
	 * @param string $password
	 * @return User|IIdentity
	 * @throws AuthenticationException
	 */
	public function authenticate($email, $password)
	{
		/** @var User $user */
		$user = $this->repository->findOneBy(['email' => $email]);

		if ($user === NULL ||  ! Passwords::verify($password, $user->password)) {
			throw new AuthenticationException();
		}

		return $user;
	}

}

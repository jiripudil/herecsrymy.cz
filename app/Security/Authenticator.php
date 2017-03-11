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
	 * @return User|IIdentity
	 * @throws AuthenticationException
	 */
	public function authenticate(string $email, string $password): IIdentity
	{
		/** @var User $user */
		$user = $this->repository->findOneBy(['email' => $email]);

		if ($user === NULL || ! $user->verifyPassword($password)) {
			throw new AuthenticationException();
		}

		return $user;
	}

}

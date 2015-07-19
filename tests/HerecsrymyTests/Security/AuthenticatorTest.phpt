<?php

/**
 * @testCase
 */

namespace HerecsrymyTests\Security;

use Herecsrymy\Entities\User;
use Herecsrymy\Security\Authenticator;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Doctrine\EntityRepository;
use Nette\Security\AuthenticationException;
use Nette\Security\Passwords;
use Tester;
use Tester\Assert;


require_once __DIR__ . '/../../bootstrap.php';


class AuthenticatorTest extends Tester\TestCase
{

	public function testSuccess()
	{
		$user = new User('john.doe@example.com');
		$user->password = Passwords::hash('password');

		$repository = \Mockery::mock(EntityRepository::class);
		$repository->shouldReceive('findOneBy')
			->with(['email' => 'john.doe@example.com'])
			->andReturn($user);

		$em = \Mockery::mock(EntityManager::class);
		$em->shouldReceive('getRepository')
			->with(User::class)
			->andReturn($repository);

		$authenticator = new Authenticator($em);

		$identity = $authenticator->authenticate('john.doe@example.com', 'password');
		Assert::same($user, $identity);
	}


	public function testInvalidPassword()
	{
		$user = new User('john.doe@example.com');
		$user->password = Passwords::hash('password');

		$repository = \Mockery::mock(EntityRepository::class);
		$repository->shouldReceive('findOneBy')
			->with(['email' => 'john.doe@example.com'])
			->andReturn($user);

		$em = \Mockery::mock(EntityManager::class);
		$em->shouldReceive('getRepository')
			->with(User::class)
			->andReturn($repository);

		$authenticator = new Authenticator($em);

		Assert::throws(function () use ($authenticator) {
			$authenticator->authenticate('john.doe@example.com', 'invalidPassword');
		}, AuthenticationException::class);
	}


	public function testNoMatchingEmail()
	{
		$repository = \Mockery::mock(EntityRepository::class);
		$repository->shouldReceive('findOneBy')
			->with(['email' => 'john.doe@example.com'])
			->andReturnNull();

		$em = \Mockery::mock(EntityManager::class);
		$em->shouldReceive('getRepository')
			->with(User::class)
			->andReturn($repository);

		$authenticator = new Authenticator($em);

		Assert::throws(function () use ($authenticator) {
			$authenticator->authenticate('john.doe@example.com', 'password');
		}, AuthenticationException::class);
	}


	protected function tearDown()
	{
		\Mockery::close();
	}

}


(new AuthenticatorTest())->run();

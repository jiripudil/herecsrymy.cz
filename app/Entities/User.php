<?php

namespace Herecsrymy\Entities;

use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Kdyby\Doctrine\Entities\BaseEntity;
use Nette\Security\IIdentity;
use Nette\Security\Passwords;


/**
 * @ORM\Entity()
 * @ORM\Table(name="user_account")
 */
class User extends BaseEntity implements IIdentity
{

	use Identifier;

	/**
	 * @ORM\Column(type="string", unique=TRUE)
	 * @var string
	 */
	protected $email;

	/**
	 * @ORM\Column(type="string")
	 * @var string
	 */
	protected $password;


	public function __construct(string $email)
	{
		$this->email = $email;
	}


	public function verifyPassword(string $password): bool
	{
		return Passwords::verify($password, $this->password);
	}


	public function getRoles()
	{
		return [];
	}

}

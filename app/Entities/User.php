<?php

namespace Herecsrymy\Entities;

use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Kdyby\Doctrine\Entities\BaseEntity;
use Nette\Security\IIdentity;


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


	/**
	 * @param string $email
	 */
	public function __construct($email)
	{
		$this->email = $email;
	}


	public function getRoles()
	{
		return [];
	}

}

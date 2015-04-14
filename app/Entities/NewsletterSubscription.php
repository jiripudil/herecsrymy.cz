<?php

namespace Slovotepec\Entities;

use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Kdyby\Doctrine\Entities\BaseEntity;
use Nette\Utils\Random;


/**
 * @ORM\Entity()
 * @ORM\Table(indexes={
 *   @ORM\Index(columns={"email"}),
 *   @ORM\Index(columns={"active"}),
 *   @ORM\Index(columns={"unsubscribe_hash"})
 * })
 */
class NewsletterSubscription extends BaseEntity
{

	use Identifier;


	/**
	 * @ORM\Column(type="string")
	 * @var string
	 */
	protected $email;

	/**
	 * @ORM\Column(type="boolean")
	 * @var bool
	 */
	protected $active;

	/**
	 * @ORM\Column(type="datetime")
	 * @var \DateTime
	 */
	protected $subscribedOn;

	/**
	 * @ORM\Column(type="datetime", nullable=TRUE)
	 * @var \DateTime|NULL
	 */
	protected $unsubscribedOn;

	/**
	 * @ORM\Column(type="string", nullable=TRUE)
	 * @var string
	 */
	protected $unsubscribeHash;


	/**
	 * @param string $email
	 */
	public function __construct($email)
	{
		$this->email = $email;
		$this->active = TRUE;
		$this->subscribedOn = new \DateTime();
		$this->unsubscribeHash = Random::generate(32);
	}


	public function renewSubscription()
	{
		$this->active = TRUE;
		$this->subscribedOn = new \DateTime();
		$this->unsubscribedOn = NULL;
		$this->unsubscribeHash = Random::generate(32);
	}


	public function unsubscribe()
	{
		$this->active = FALSE;
		$this->unsubscribedOn = new \DateTime();
		$this->unsubscribeHash = NULL;
	}


	public function getUnsubscribeHash()
	{
		return $this->unsubscribeHash . md5($this->email);
	}

}

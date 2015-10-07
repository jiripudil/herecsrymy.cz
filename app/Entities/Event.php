<?php

namespace Herecsrymy\Entities;

use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Kdyby\Doctrine\Entities\BaseEntity;


/**
 * @ORM\Entity()
 * @ORM\Table(indexes={
 *   @ORM\Index(columns={"datetime", "published"}),
 *   @ORM\Index(columns={"datetime"}),
 *   @ORM\Index(columns={"published"})
 * })
 */
class Event extends BaseEntity
{

	use Identifier;


	/**
	 * @ORM\Column(type="string")
	 * @var string
	 */
	protected $name;

	/**
	 * @ORM\Column(type="string")
	 * @var string
	 */
	protected $note;

	/**
	 * @ORM\Column(type="datetime")
	 * @var \DateTime
	 */
	protected $datetime;

	/**
	 * @ORM\ManyToOne(targetEntity="Location", cascade={"persist"})
	 * @var Location
	 */
	protected $location;

	/**
	 * @ORM\Column(type="smallint")
	 * @var int
	 */
	protected $ticketsPrice;

	/**
	 * @ORM\Column(type="string")
	 * @var string
	 */
	protected $ticketsLink;

	/**
	 * @ORM\Column(type="string")
	 * @var string
	 */
	protected $facebookUrl;

	/**
	 * @ORM\Column(type="boolean", options={"default":"1"})
	 * @var bool
	 */
	protected $published;


	/**
	 * @param string $name
	 * @param \DateTime $datetime
	 */
	public function __construct($name, \DateTime $datetime)
	{
		$this->name = $name;
		$this->datetime = $datetime;
	}


	public function __clone()
	{
		$this->datetime = clone $this->datetime;
		$this->published = FALSE;
	}

}

<?php

namespace JiriHraje\Model\Concerts;

use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Kdyby\Doctrine\Entities\BaseEntity;
use Kdyby\Doctrine\Geo;


/**
 * @ORM\Entity()
 * @ORM\Table(indexes={
 *   @ORM\Index(name="i_datetime", columns="datetime")
 * })
 *
 * @property-read int $int
 * @property string $name
 * @property string $note
 * @property \DateTime $datetime
 * @property string $location
 * @property Geo\Element|NULL $locationPoint
 * @property int $ticketsPrice
 * @property string $ticketsLink
 * @property string $facebookUrl
 */
class Concert extends BaseEntity
{

	use Identifier;


	/**
	 * @ORM\Column(type="string", length=140)
	 * @var string
	 */
	protected $name;

	/**
	 * @ORM\Column(type="string", length=255)
	 * @var string
	 */
	protected $note;

	/**
	 * @ORM\Column(type="datetime")
	 * @var \DateTime
	 */
	protected $datetime;

	/**
	 * @ORM\Column(type="string", length=255)
	 * @var string
	 */
	protected $location;

	/**
	 * @ORM\Column(type="point", nullable=TRUE)
	 * @var Geo\Element|NULL
	 */
	protected $locationPoint;

	/**
	 * @ORM\Column(type="smallint")
	 * @var int
	 */
	protected $ticketsPrice;

	/**
	 * @ORM\Column(type="string", length=255)
	 * @var string
	 */
	protected $ticketsLink;

	/**
	 * @ORM\Column(type="string", length=255)
	 * @var string
	 */
	protected $facebookUrl;


	/**
	 * @param string $name
	 * @param \DateTime $datetime
	 */
	public function __construct($name, \DateTime $datetime)
	{
		$this->name = $name;
		$this->datetime = $datetime;
	}

}

<?php

namespace Herecsrymy\Entities;

use Doctrine\ORM\Mapping as ORM;
use Herecsrymy\Doctrine\Geo\Point;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Kdyby\Doctrine\Entities\BaseEntity;
use VojtechDobes\NetteForms\GpsPoint;


/**
 * @ORM\Entity()
 * @ORM\Table(indexes={
 *   @ORM\Index(columns={"datetime"})
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
	 * @ORM\Column(type="string")
	 * @var string
	 */
	protected $location;

	/**
	 * @ORM\Column(type="point", nullable=TRUE)
	 * @var Point|NULL
	 */
	protected $locationPoint;

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
	 * @param string $name
	 * @param \DateTime $datetime
	 */
	public function __construct($name, \DateTime $datetime)
	{
		$this->name = $name;
		$this->datetime = $datetime;
	}


	public function setLocationPoint($point)
	{
		if ($point instanceof GpsPoint) {
			$point = new Point($point->getLng(), $point->getLat());
		}

		$this->locationPoint = $point;
	}

}

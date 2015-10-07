<?php

namespace Herecsrymy\Entities;

use Doctrine\ORM\Mapping as ORM;
use Herecsrymy\Doctrine\Geo\Point;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Kdyby\Doctrine\Entities\BaseEntity;
use VojtechDobes\NetteForms\GpsPoint;


/**
 * @ORM\Entity()
 */
class Location extends BaseEntity
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
	protected $address;

	/**
	 * @ORM\Column(type="point", nullable=TRUE)
	 * @var Point|NULL
	 */
	protected $point;


	public function setPoint($point)
	{
		if ($point instanceof GpsPoint) {
			$point = new Point($point->getLng(), $point->getLat());
		}

		$this->point = $point;
	}




}

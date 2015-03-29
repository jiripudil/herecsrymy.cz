<?php

namespace JiriHraje\Model\Songs;

use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Kdyby\Doctrine\Entities\BaseEntity;


/**
 * @ORM\Entity()
 *
 * @property-read int $id
 * @property string $title
 * @property Album|NULL $album
 * @property int|NULL $albumPosition
 * @property \DateTime $published
 * @property string $soundcloudUrl
 * @property int $duration
 * @property string $note
 * @property string $lyrics
 */
class Song extends BaseEntity
{

	use Identifier;


	/**
	 * @ORM\Column(type="string", length=140)
	 * @var string
	 */
	protected $title;

	/**
	 * @ORM\ManyToOne(targetEntity="Album", inversedBy="songs")
	 * @ORM\JoinColumn(nullable=TRUE)
	 * @var Album|NULL
	 */
	protected $album;

	/**
	 * @ORM\Column(type="smallint", nullable=TRUE)
	 * @var int|NULL
	 */
	protected $albumPosition;

	/**
	 * @ORM\Column(type="date")
	 * @var \DateTime
	 */
	protected $published;

	/**
	 * @ORM\Column(type="string", length=255)
	 * @var string
	 */
	protected $soundcloudUrl;

	/**
	 * @ORM\Column(type="smallint")
	 * @var int
	 */
	protected $duration;

	/**
	 * Dedications, attributions, co-authors, etc.
	 *
	 * @ORM\Column(type="string", length=255, nullable=TRUE)
	 * @var string
	 */
	protected $note;

	/**
	 * @ORM\Column(type="text")
	 * @var string
	 */
	protected $lyrics;


	public function __construct($title)
	{
		$this->title = $title;
	}

}

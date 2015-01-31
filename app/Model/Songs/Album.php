<?php

namespace JiriHraje\Model\Songs;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JiriHraje\Model\Media\Image;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Kdyby\Doctrine\Entities\BaseEntity;


/**
 * @ORM\Entity()
 * @ORM\Table(indexes={
 *   @ORM\Index(columns={"datetime"})
 * })
 *
 * @property-read int $id
 * @property string $title
 * @property \DateTime $datetime
 * @property Image $cover
 * @property Song[]|ArrayCollection $songs
 */
class Album extends BaseEntity
{

	use Identifier;


	/**
	 * @ORM\Column(type="string", length=140)
	 * @var string
	 */
	protected $title;

	/**
	 * @ORM\Column(type="datetime")
	 * @var \DateTime
	 */
	protected $datetime;

	/**
	 * @ORM\ManyToOne(targetEntity="JiriHraje\Model\Media\Image")
	 * @ORM\JoinColumn(nullable=FALSE)
	 * @var Image
	 */
	protected $cover;

	/**
	 * @ORM\OneToMany(targetEntity="Song", mappedBy="album")
	 * @ORM\OrderBy({"albumPosition" = "ASC"})
	 * @var Song[]|ArrayCollection
	 */
	protected $songs;


	/**
	 * @param string $title
	 */
	public function __construct($title)
	{
		$this->title = $title;
		$this->songs = new ArrayCollection();
	}

}

<?php

namespace JiriHraje\Model\News;

use Doctrine\ORM\Mapping as ORM;
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
 * @property string $text
 * @property bool $published
 */
class Post extends BaseEntity
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
	 * @ORM\Column(type="text")
	 * @var string
	 */
	protected $text;

	/**
	 * @ORM\Column(type="boolean")
	 * @var bool
	 */
	protected $published;


	/**
	 * @param string $title
	 */
	public function __construct($title)
	{
		$this->title = $title;
		$this->datetime = new \DateTime();
		$this->published = TRUE;
	}

}

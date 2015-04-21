<?php

namespace Herecsrymy\Entities;

use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Kdyby\Doctrine\Entities\BaseEntity;


/**
 * @ORM\Entity()
 * @ORM\Table(indexes={
 *   @ORM\Index(columns={"sort"})
 * })
 */
class Category extends BaseEntity
{

	use Identifier;


	/**
	 * @ORM\Column(type="string", length=64)
	 * @var string
	 */
	protected $title;

	/**
	 * @ORM\Column(type="string", length=64)
	 * @var string
	 */
	protected $slug;

	/**
	 * @ORM\Column(type="string", nullable=TRUE)
	 * @var string
	 */
	protected $description;

	/**
	 * @ORM\Column(type="boolean")
	 * @var bool
	 */
	protected $published;

	/**
	 * @ORM\Column(type="smallint")
	 * @var int
	 */
	protected $sort;


	/**
	 * @param string $title
	 */
	public function __construct($title)
	{
		$this->title = $title;
	}

}

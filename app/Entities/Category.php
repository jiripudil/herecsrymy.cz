<?php

namespace Herecsrymy\Entities;

use Doctrine\Common\Collections\ArrayCollection;
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
	 * @ORM\OneToMany(targetEntity="Post", mappedBy="category")
	 * @var ArrayCollection|Post[]
	 */
	protected $posts;


	public function __construct(string $title)
	{
		$this->title = $title;
		$this->posts = new ArrayCollection();
	}

}

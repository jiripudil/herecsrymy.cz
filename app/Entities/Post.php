<?php

namespace Herecsrymy\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Kdyby\Doctrine\Entities\BaseEntity;


/**
 * @ORM\Entity()
 */
class Post extends BaseEntity
{

	use Identifier;


	/**
	 * @ORM\Column(type="string")
	 * @var string
	 */
	protected $title;

	/**
	 * @ORM\Column(type="string")
	 * @var string
	 */
	protected $slug;

	/**
	 * @ORM\Column(type="string")
	 * @var string
	 */
	protected $description;

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
	 * @ORM\Column(type="datetime")
	 * @var \DateTime
	 */
	protected $publishedOn;

	/**
	 * @ORM\ManyToOne(targetEntity="Category", inversedBy="posts")
	 * @ORM\JoinColumn(nullable=FALSE)
	 * @var Category
	 */
	protected $category;

	/**
	 * @ORM\OneToMany(targetEntity="Attachment", mappedBy="post")
	 * @var Attachment[]|ArrayCollection
	 */
	protected $attachments;


	/**
	 * @param string $title
	 */
	public function __construct($title)
	{
		$this->title = $title;
		$this->published = FALSE;
		$this->attachments = new ArrayCollection();
	}


	/**
	 * @return bool
	 */
	public function isPublic()
	{
		return $this->published && $this->publishedOn <= new \DateTime();
	}

}

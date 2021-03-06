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
	 * @ORM\Column(type="boolean", options={"default":"0"})
	 * @var bool
	 */
	protected $containsChords;

	/**
	 * @ORM\ManyToOne(targetEntity="Category", inversedBy="posts")
	 * @ORM\JoinColumn(nullable=FALSE)
	 * @var Category
	 */
	protected $category;

	/**
	 * @ORM\OneToMany(targetEntity="Attachment", mappedBy="post", indexBy="id")
	 * @var Attachment[]|ArrayCollection
	 */
	protected $attachments;


	public function __construct(string $title)
	{
		$this->title = $title;
		$this->published = FALSE;
		$this->attachments = new ArrayCollection();
	}


	public function isPublic(): bool
	{
		return $this->published && $this->publishedOn <= new \DateTime();
	}


	public function getPlayableAttachment(): ?Attachment
	{
		return $this->attachments->filter(function (Attachment $attachment) {
			return $attachment->inPlayer;
		})->first() ?: NULL;
	}

}

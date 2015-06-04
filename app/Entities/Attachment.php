<?php

namespace Herecsrymy\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Kdyby\Doctrine\Entities\BaseEntity;


/**
 * @ORM\Entity()
 */
class Attachment extends BaseEntity
{

	use Identifier;


	const TYPE_DOCUMENT = 1;
	const TYPE_AUDIO = 2;
	const TYPE_VIDEO = 3;
	const TYPE_PHOTOS = 4;


	/**
	 * @ORM\Column(type="string")
	 * @var string
	 */
	protected $name;

	/**
	 * @ORM\Column(type="smallint")
	 * @var int
	 */
	protected $type;

	/**
	 * @ORM\OneToMany(targetEntity="File", mappedBy="attachment")
	 * @var File[]|ArrayCollection
	 */
	protected $files;

	/**
	 * @ORM\ManyToOne(targetEntity="Post", inversedBy="attachments")
	 * @ORM\JoinColumn(nullable=FALSE)
	 * @var Post
	 */
	protected $post;


	/**
	 * @param string $name
	 * @param int $type
	 * @param Post $post
	 */
	public function __construct($name, $type, Post $post)
	{
		$this->name = $name;
		$this->type = $type;
		$this->post = $post;
		$this->files = new ArrayCollection();
	}


	public function getDirectoryName()
	{
		return substr(md5($this->id), 0, 4);
	}

}

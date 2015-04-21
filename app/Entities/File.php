<?php

namespace Herecsrymy\Entities;

use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Kdyby\Doctrine\Entities\BaseEntity;


/**
 * @ORM\Entity()
 */
class File extends BaseEntity
{

	use Identifier;


	/**
	 * @ORM\Column(type="string")
	 * @var string
	 */
	protected $fileName;

	/**
	 * @ORM\Column(type="string", length=32)
	 * @var string
	 */
	protected $fileType;

	/**
	 * @ORM\ManyToOne(targetEntity="Attachment", inversedBy="files")
	 * @ORM\JoinColumn(nullable=FALSE)
	 * @var Attachment
	 */
	protected $attachment;


	public function __construct($fileName, Attachment $attachment)
	{
		$this->fileName = $fileName;
		$this->fileType = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $fileName);
		$this->attachment = $attachment;
	}

}

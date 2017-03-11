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
	 * @ORM\Column(type="integer")
	 * @var int
	 */
	protected $fileSize;

	/**
	 * @ORM\ManyToOne(targetEntity="Attachment", inversedBy="files")
	 * @ORM\JoinColumn(nullable=FALSE)
	 * @var Attachment
	 */
	protected $attachment;

	/**
	 * @ORM\Column(type="integer")
	 * @var int
	 */
	protected $downloadCount;


	/**
	 * @param string $fileName
	 * @param string $fileType
	 * @param int $fileSize
	 * @param Attachment $attachment
	 */
	public function __construct(string $fileName, string $fileType, int $fileSize, Attachment $attachment)
	{
		$this->fileName = $fileName;
		$this->fileType = $fileType;
		$this->attachment = $attachment;
		$this->fileSize = $fileSize;
		$this->downloadCount = 0;
	}


	public function getExtension(): string
	{
		return pathinfo($this->fileName, PATHINFO_EXTENSION);
	}


	public function addDownload(): void
	{
		$this->downloadCount++;
	}

}

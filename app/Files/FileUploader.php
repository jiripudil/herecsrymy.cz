<?php

namespace Herecsrymy\Files;

use Herecsrymy\Entities\Attachment;
use Herecsrymy\Entities\File;
use Nette\Http\FileUpload;
use Nette\Object;


class FileUploader extends Object
{

	/**
	 * @var string
	 */
	private $wwwDir;

	/**
	 * @var string
	 */
	private $dirName;

	/**
	 * @var \getID3
	 */
	private $id3;


	public function __construct(string $wwwDir, string $dirName, \getID3 $id3)
	{
		$this->wwwDir = $wwwDir;
		$this->dirName = $dirName;
		$this->id3 = $id3;
	}


	/**
	 * @return string
	 */
	public function getWwwDir()
	{
		return $this->wwwDir;
	}


	/**
	 * @return string
	 */
	public function getDirName()
	{
		return $this->dirName;
	}


	/**
	 * @return string
	 */
	public function getRootPath()
	{
		return $this->getWwwDir() . DIRECTORY_SEPARATOR . $this->getDirName();
	}


	/**
	 * @param FileUpload $fileUpload
	 * @param Attachment $attachment
	 * @return File
	 */
	public function upload(FileUpload $fileUpload, Attachment $attachment)
	{
		$dir = $this->getRootPath() . DIRECTORY_SEPARATOR . $attachment->getDirectoryName();

		$name = $fileUpload->getSanitizedName();
		$path = $dir . DIRECTORY_SEPARATOR . $name;

		$fileUpload->move($path);

		$type = $fileUpload->getContentType();
		$size = $fileUpload->getSize();

		$meta = $this->id3->analyze($path);
		if (isset($meta['playtime_seconds']) && ! $attachment->playtime) {
			$attachment->playtime = $meta['playtime_seconds'];
		}

		return new File($name, $type, $size, $attachment);
	}

}

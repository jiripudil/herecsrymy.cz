<?php

namespace Herecsrymy\Files;

use Herecsrymy\Entities\Attachment;
use Herecsrymy\Entities\File;
use Nette\Http\FileUpload;
use Nette\Object;


class FileUploader extends Object
{

	/** @var string */
	private $wwwDir;

	/** @var string */
	private $dirName;


	/**
	 * @param string $wwwDir
	 * @param string $dirName
	 */
	public function __construct($wwwDir, $dirName)
	{
		$this->wwwDir = $wwwDir;
		$this->dirName = $dirName;
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

		$type = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $path);
		$size = filesize($path);

		return new File($name, $type, $size, $attachment);
	}

}

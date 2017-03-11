<?php

declare(strict_types = 1);

namespace Herecsrymy\Files;


use Herecsrymy\Entities\Photo;
use Nette\Http\FileUpload;
use Nette\Utils\Image;


class PhotoUploader
{

	/**
	 * @var string
	 */
	private $wwwDir;

	/**
	 * @var string
	 */
	private $dirName;


	public function __construct(string $wwwDir, string $dirName)
	{
		$this->wwwDir = $wwwDir;
		$this->dirName = $dirName;
	}


	public function getWwwDir(): string
	{
		return $this->wwwDir;
	}


	public function getDirName(): string
	{
		return $this->dirName;
	}


	public function getRootPath(): string
	{
		return $this->getWwwDir() . DIRECTORY_SEPARATOR . $this->getDirName();
	}


	public function getOriginalDirectory(): string
	{
		return 'orig';
	}


	public function getThumbsDirectory(): string
	{
		return 'thumbs';
	}


	public function upload(FileUpload $fileUpload, string $description): Photo
	{
		$name = $fileUpload->getSanitizedName();
		$origPath = $this->getRootPath() . DIRECTORY_SEPARATOR . $this->getOriginalDirectory() . DIRECTORY_SEPARATOR . $name;
		$thumbPath = $this->getRootPath() . DIRECTORY_SEPARATOR . $this->getThumbsDirectory() . DIRECTORY_SEPARATOR . $name;

		$fileUpload->move($origPath);
		$image = Image::fromFile($origPath);
		$image->resize(NULL, 140);
		$image->save($thumbPath);

		return new Photo($name, $description);
	}

}

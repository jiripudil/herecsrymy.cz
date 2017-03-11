<?php

declare(strict_types = 1);

namespace Herecsrymy\Entities;

use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Kdyby\Doctrine\Entities\BaseEntity;


/**
 * @ORM\Entity()
 */
class Photo extends BaseEntity
{

	use Identifier;


	/**
	 * @ORM\Column(type="string")
	 * @var string
	 */
	protected $fileName;

	/**
	 * @ORM\Column(type="string")
	 * @var string
	 */
	protected $description;


	public function __construct(string $fileName, string $description)
	{
		$this->fileName = $fileName;
		$this->description = $description;
	}

}

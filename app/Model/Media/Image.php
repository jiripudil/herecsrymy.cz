<?php

namespace JiriHraje\Model\Media;

use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Kdyby\Doctrine\Entities\BaseEntity;


/**
 * @ORM\Entity()
 */
class Image extends BaseEntity
{

	use Identifier;

}

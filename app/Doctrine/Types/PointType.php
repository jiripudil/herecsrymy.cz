<?php

namespace Slovotepec\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Slovotepec\Doctrine\Geo;


class PointType extends Type
{

	const NAME = 'point';


	public function getName()
	{
		return self::NAME;
	}


	public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
	{
		return 'point';
	}


	public function convertToDatabaseValue($value, AbstractPlatform $platform)
	{
		if ($value instanceof Geo\Point) {
			return (string) $value;
		}

		return NULL;
	}


	public function convertToDatabaseValueSQL($sqlExpr, AbstractPlatform $platform)
	{
		return $sqlExpr;
	}


	public function convertToPHPValue($value, AbstractPlatform $platform)
	{
		if ($value === NULL || empty($value)) {
			return NULL;
		}

		return Geo\Point::fromString($value);
	}


	public function convertToPHPValueSQL($sqlExpr, $platform)
	{
		return $sqlExpr;
	}

}

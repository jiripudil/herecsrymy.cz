<?php

/**
 * @testCase
 */

namespace HerecsrymyTests\Doctrine;

use Doctrine\DBAL\Platforms\PostgreSqlPlatform;
use Herecsrymy\Doctrine\Geo\Point;
use Herecsrymy\Doctrine\Types\PointType;
use Nette\PhpGenerator as Code;
use Tester;
use Tester\Assert;


require_once __DIR__ . '/../../bootstrap.php';


class PointTypeTest extends Tester\TestCase
{

	/** @var PointType */
	private $point;


	protected function setUp()
	{
		$this->point = Code\Helpers::createObject(PointType::class, []);
	}


	public function testPhpToSql()
	{
		$result = $this->point->convertToDatabaseValueSQL('?', new PostgreSqlPlatform());
		Assert::same('?', $result);
	}


	public function testSqlToPhp()
	{
		$result = $this->point->convertToPHPValueSQL('?', new PostgreSqlPlatform());
		Assert::same('?', $result);
	}


	public function testConvertToPhp()
	{
		$point = $this->point->convertToPHPValue('(10,-12.5)', new PostgreSqlPlatform());
		Assert::true($point instanceof Point);
		Assert::same(10.0, $point->getLongitude());
		Assert::same(-12.5, $point->getLatitude());
	}


	public function testConvertToSql()
	{
		$point = new Point(10, -12.5);

		$sql = $this->point->convertToDatabaseValue($point, new PostgreSqlPlatform());
		Assert::same('(10,-12.5)', $sql);
	}

}


(new PointTypeTest())->run();

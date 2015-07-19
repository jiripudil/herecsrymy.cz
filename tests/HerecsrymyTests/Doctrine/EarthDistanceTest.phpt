<?php

/**
 * @testCase
 */

namespace HerecsrymyTests\Doctrine;

use Doctrine;
use Herecsrymy;
use Herecsrymy\Doctrine\Geo;
use HerecsrymyTests\CreateContainer;
use Kdyby;
use Nette\DI\Container;
use Tester;
use Tester\Assert;


require_once __DIR__ . '/../../bootstrap.php';


class EarthDistanceTest extends Tester\TestCase
{

	use CreateContainer;

	/** @var Kdyby\Doctrine\EntityManager */
	private $em;


	protected function setUp()
	{
		$this->em = $this->createContainer()->getByType(Kdyby\Doctrine\EntityManager::class);
	}


	public function testAsProperty()
	{
		if ( ! $this->em->getConnection()->getDatabasePlatform() instanceof Doctrine\DBAL\Platforms\PostgreSqlPlatform) {
			Tester\Environment::skip('Implemented for PostgreSQL only.');
		}

		$to = new Geo\Point(-3, 4);

		$qb = $this->em->createQueryBuilder()
			->select('e.id')->from(Herecsrymy\Entities\Event::class, 'e')
			->where('DISTANCE(e.location_point, :to) = :distance')
			->setParameters([
				'to' => $to,
				'distance' => 42,
			]);

		self::assertQuery(
			'SELECT e.id FROM Herecsrymy\Entities\Event e WHERE DISTANCE(e.location_point, :to) = :distance',
			[
				'to' => $to,
				'distance' => 42,
			],
			$qb->getQuery()
		);
	}


	public function testAsParameters()
	{
		if ( ! $this->em->getConnection()->getDatabasePlatform() instanceof Doctrine\DBAL\Platforms\PostgreSqlPlatform) {
			Tester\Environment::skip('Implemented for PostgreSQL only.');
		}

		$from = new Geo\Point(1, 2);
		$to = new Geo\Point(-3, 4);

		$qb = $this->em->createQueryBuilder()
			->select('e.id')->from(Herecsrymy\Entities\Event::class, 'e')
			->where('DISTANCE(:from, :to) = :distance')
			->setParameters([
				'from' => $from,
				'to' => $to,
				'distance' => 42,
			]);

		self::assertQuery(
			'SELECT e.id FROM Herecsrymy\Entities\Event e WHERE DISTANCE(:from, :to) = :distance',
			[
				'from' => $from,
				'to' => $to,
				'distance' => 42,
			],
			$qb->getQuery()
		);
	}


	protected static function assertQuery($expectedDql, $expectedParams, Doctrine\ORM\Query $query)
	{
		Assert::same($expectedDql, $query->getDQL());

		$actualParams = [];
		foreach ($query->getParameters() as $key => $value) {
			if ($value instanceof Doctrine\ORM\Query\Parameter) {
				$actualParams[$value->getName()] = $value->getValue();
			} else {
				$actualParams[$key] = $value;
			}
		}
		Assert::same($expectedParams, $actualParams);
	}

}


(new EarthDistanceTest())->run();

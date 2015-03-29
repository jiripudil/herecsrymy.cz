<?php

namespace JiriHraje\Model\Concerts\Queries;

use Doctrine;
use Slovotepec\Doctrine\Geo;
use Slovotepec\Doctrine\Types\PointType;
use Kdyby;
use Kdyby\Doctrine\QueryObject;


class ConcertsQuery extends QueryObject
{

	/** @var callable[] */
	private $filters = [];


	/**
	 * @param Geo\Point $point
	 * @return ConcertsQuery
	 */
	public function nearestTo(Geo\Point $point)
	{
		$this->filters[] = function (Doctrine\ORM\QueryBuilder $qb) use ($point) {
			$qb->addSelect('DISTANCE(:point, c.locationPoint) AS HIDDEN distance')
				->setParameter('point', $point, 'point')
				->addOrderBy('distance', 'ASC');
		};

		return $this;
	}


	/**
	 * @param \DateTime $from
	 * @param \DateTime $to
	 * @return ConcertsQuery
	 */
	public function inDateRange(\DateTime $from, \DateTime $to)
	{
		$this->filters[] = function (Doctrine\ORM\QueryBuilder $qb) use ($from, $to) {
			$qb->andWhere('c.datetime >= :from')
				->andWhere('c.datetime <= :to')
				->setParameter('from', $from)
				->setParameter('to', $to);
		};

		return $this;
	}


	/**
	 * @return ConcertsQuery
	 */
	public function upcoming()
	{
		return $this->inDateRange(new \DateTime(), new \DateTime('+1 year'));
	}


	/**
	 * @param Kdyby\Persistence\Queryable $repository
	 * @return Doctrine\ORM\Query|Doctrine\ORM\QueryBuilder
	 */
	protected function doCreateQuery(Kdyby\Persistence\Queryable $repository)
	{
		$qb = $repository->createQueryBuilder('c')
			->select('c');

		foreach ($this->filters as $filter) {
			$filter($qb);
		}

		$qb->addOrderBy('c.datetime');

		return $qb;
	}

}

<?php

namespace Herecsrymy\Entities\Queries;

use Kdyby;
use Kdyby\Doctrine\QueryObject;
use Herecsrymy\Doctrine\Geo;


class EventQuery extends QueryObject
{

	/** @var callable[] */
	protected $filters = [];


	/**
	 * @return EventQuery
	 */
	public function onlyPublished()
	{
		$this->filters[] = function (Kdyby\Doctrine\QueryBuilder $builder) {
			$builder->andWhere('e.published = TRUE');
		};

		return $this;
	}


	/**
	 * @param Geo\Point $point
	 * @return EventQuery
	 */
	public function nearestTo(Geo\Point $point)
	{
		$this->filters[] = function (Kdyby\Doctrine\QueryBuilder $qb) use ($point) {
			$qb->addSelect('DISTANCE(:point, l.point) AS HIDDEN distance')
				->setParameter('point', $point, 'point')
				->addOrderBy('distance', 'ASC');
		};

		return $this;
	}


	/**
	 * @param \DateTime $from
	 * @param \DateTime $to
	 * @return EventQuery
	 */
	public function inDateRange(\DateTime $from, \DateTime $to)
	{
		$this->filters[] = function (Kdyby\Doctrine\QueryBuilder $qb) use ($from, $to) {
			$qb->andWhere('e.datetime >= :from')
				->andWhere('e.datetime <= :to')
				->setParameter('from', $from)
				->setParameter('to', $to);
		};

		return $this;
	}


	/**
	 * @return EventQuery
	 */
	public function upcoming()
	{
		return $this->inDateRange(new \DateTime(), new \DateTime('+1 year'));
	}


	/**
	 * @return EventQuery
	 */
	public function newestFirst()
	{
		$this->filters[] = function (Kdyby\Doctrine\QueryBuilder $builder) {
			$builder->orderBy('e.datetime', 'DESC');
		};

		return $this;
	}


	/**
	 * @return EventQuery
	 */
	public function only(int $limit)
	{
		$this->filters[] = function (Kdyby\Doctrine\QueryBuilder $builder) use ($limit) {
			$builder->setMaxResults($limit);
		};

		return $this;
	}


	/**
	 * @param \Kdyby\Persistence\Queryable $repository
	 * @return \Doctrine\ORM\Query|\Doctrine\ORM\QueryBuilder
	 */
	protected function doCreateQuery(Kdyby\Persistence\Queryable $repository)
	{
		$qb = $repository->createQueryBuilder('e')
			->select('e, l')
			->innerJoin('e.location', 'l');

		foreach ($this->filters as $filter) {
			$filter($qb);
		}

		$qb->addOrderBy('e.datetime');

		return $qb;
	}

}

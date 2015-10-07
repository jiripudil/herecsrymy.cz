<?php

namespace Herecsrymy\Entities\Queries;

use Kdyby;
use Kdyby\Doctrine\QueryObject;


class LocationQuery extends QueryObject
{

	/** @var callable[] */
	protected $filters = [];


	/**
	 * @param \Kdyby\Persistence\Queryable $repository
	 * @return \Doctrine\ORM\Query|\Doctrine\ORM\QueryBuilder
	 */
	protected function doCreateQuery(Kdyby\Persistence\Queryable $repository)
	{
		$qb = $repository->createQueryBuilder('l')
			->select('l');

		foreach ($this->filters as $filter) {
			$filter($qb);
		}

		$qb->addOrderBy('l.name');

		return $qb;
	}

}

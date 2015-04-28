<?php

namespace Herecsrymy\Entities\Queries;

use Kdyby;
use Kdyby\Doctrine\QueryObject;


class CategoryQuery extends QueryObject
{

	/** @var callable[] */
	private $filters = [];


	/**
	 * @return CategoryQuery
	 */
	public function countPosts()
	{
		$this->filters[] = function (Kdyby\Doctrine\QueryBuilder $builder) {
			$builder->leftJoin('c.posts', 'p')
				->addSelect('COUNT(p) AS postsCount')
				->groupBy('c.id');
		};

		return $this;
	}


	/**
	 * @param Kdyby\Persistence\Queryable $repository
	 * @return \Doctrine\ORM\Query|Kdyby\Doctrine\QueryBuilder
	 */
	protected function doCreateQuery(Kdyby\Persistence\Queryable $repository)
	{
		$queryBuilder = $repository->createQueryBuilder('c')
			->select('c');

		foreach ($this->filters as $filter) {
			$filter($queryBuilder);
		}

		$queryBuilder->addOrderBy('c.sort', 'ASC');
		return $queryBuilder;
	}

}

<?php

namespace Herecsrymy\Entities\Queries;

use Kdyby;
use Kdyby\Doctrine\QueryObject;


class CategoryQuery extends QueryObject
{

	/** @var callable[] */
	private $filters = [];


	public function indexById(): CategoryQuery
	{
		$this->filters[] = function (Kdyby\Doctrine\QueryBuilder $builder) {
			$builder->indexBy('c', 'c.id');
		};

		return $this;
	}


	public function onlyPublished(): CategoryQuery
	{
		$this->filters[] = function (Kdyby\Doctrine\QueryBuilder $builder) {
			$builder->andWhere('c.published = :published')
				->setParameter('published', TRUE);
		};

		return $this;
	}


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

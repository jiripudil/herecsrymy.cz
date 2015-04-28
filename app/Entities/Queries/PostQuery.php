<?php

namespace Herecsrymy\Entities\Queries;

use Kdyby;
use Kdyby\Doctrine\QueryObject;
use Herecsrymy\Entities\Category;


class PostQuery extends QueryObject
{

	/** @var callable[] */
	protected $filters = [];


	/**
	 * @return PostQuery
	 */
	public function onlyPublished()
	{
		$this->filters[] = function (Kdyby\Doctrine\QueryBuilder $builder) {
			$builder->andWhere('p.published = :published', TRUE)
				->andWhere('p.publishedOn <= :now', new \DateTime());
		};

		return $this;
	}


	/**
	 * @param Category $category
	 * @return PostQuery
	 */
	public function ofCategory(Category $category)
	{
		$this->filters[] = function (Kdyby\Doctrine\QueryBuilder $builder) use ($category) {
			$builder->andWhere('p.category = :category', $category);
		};

		return $this;
	}


	/**
	 * @return PostQuery
	 */
	public function joinCategories()
	{
		$this->filters[] = function (Kdyby\Doctrine\QueryBuilder $builder) {
			$builder->innerJoin('p.category', 'c')
				->addSelect('c');
		};

		return $this;
	}


	/**
	 * @param Kdyby\Persistence\Queryable $dao
	 * @return \Doctrine\ORM\Query|Kdyby\Doctrine\QueryBuilder
	 */
	protected function doCreateQuery(Kdyby\Persistence\Queryable $dao)
	{
		$queryBuilder = $dao->createQueryBuilder('p')
			->select('p');

		foreach ($this->filters as $filter) {
			$filter($queryBuilder);
		}

		$queryBuilder->addOrderBy('p.publishedOn', 'DESC');
		return $queryBuilder;
	}

}

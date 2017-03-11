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
			$builder->andWhere('p.published = TRUE')
				->andWhere('p.publishedOn <= :now')
				->setParameter('now', new \DateTime());
		};

		return $this;
	}


	/**
	 * @param Category $category
	 * @return PostQuery
	 */
	public function inCategory(Category $category)
	{
		$this->filters[] = function (Kdyby\Doctrine\QueryBuilder $builder) use ($category) {
			$builder->andWhere('p.category = :category')
				->setParameter('category', $category);
		};

		return $this;
	}


	/**
	 * @param Category[] $categories
	 * @return PostQuery
	 */
	public function inCategories(array $categories)
	{
		$this->filters[] = function (Kdyby\Doctrine\QueryBuilder $builder) use ($categories) {
			$builder->andWhere('p.category IN (:categories)')
				->setParameter('categories', $categories);
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
	 * @return PostQuery
	 */
	public function joinAttachments()
	{
		$this->filters[] = function (Kdyby\Doctrine\QueryBuilder $builder) {
			$builder->leftJoin('p.attachments', 'a')
				->addSelect('a');
		};

		return $this;
	}


	/**
	 * @param PostFilter $filter
	 * @return PostQuery
	 */
	public function filtered(PostFilter $filter)
	{
		$this->filters[] = function (Kdyby\Doctrine\QueryBuilder $builder) use ($filter) {
			if ($filter->getCategory() !== NULL) {
				$builder->andWhere('p.category = :category')
					->setParameter('category', $filter->getCategory());
			}

			if ($filter->getPublished() !== NULL) {
				$builder->andWhere('p.published = :published')
					->setParameter('published', $filter->getPublished());
			}
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

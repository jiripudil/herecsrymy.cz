<?php

namespace Herecsrymy\Entities\Queries;

use Kdyby;
use Kdyby\Doctrine\QueryObject;


class NewsletterSubscriptionQuery extends QueryObject
{

	/** @var callable[] */
	protected $filters = [];


	/**
	 * @return NewsletterSubscriptionQuery
	 */
	public function onlyActive()
	{
		$this->filters[] = function (Kdyby\Doctrine\QueryBuilder $builder) {
			$builder->andWhere('s.active = TRUE');
		};

		return $this;
	}


	/**
	 * @param string $email
	 * @return NewsletterSubscriptionQuery
	 */
	public function byEmail($email)
	{
		$this->filters[] = function (Kdyby\Doctrine\QueryBuilder $builder) use ($email) {
			$builder->andWhere('s.email = :email')
				->setParameter('email', $email);
		};

		return $this;
	}


	/**
	 * @param Kdyby\Persistence\Queryable $dao
	 * @return \Doctrine\ORM\Query|Kdyby\Doctrine\QueryBuilder
	 */
	protected function doCreateQuery(Kdyby\Persistence\Queryable $dao)
	{
		$queryBuilder = $dao->createQueryBuilder('s')
			->select('s');

		foreach ($this->filters as $filter) {
			$filter($queryBuilder);
		}

		return $queryBuilder;
	}

}

<?php

declare(strict_types = 1);

namespace Herecsrymy\Entities\Queries;

use Kdyby;
use Kdyby\Doctrine\QueryObject;
use Kdyby\Persistence\Queryable;


class PlayableAttachmentsQuery extends QueryObject
{

	/**
	 * @param \Kdyby\Persistence\Queryable $repository
	 * @return \Doctrine\ORM\Query|\Doctrine\ORM\QueryBuilder
	 */
	protected function doCreateQuery(Kdyby\Persistence\Queryable $repository)
	{
		return $repository->createQueryBuilder('a', 'a.id')
			->select('a')
			->innerJoin('a.post', 'p')
			->where('a.inPlayer = :inPlayer')->setParameter('inPlayer', TRUE)
			->andWhere('a.displayed = :displayed')->setParameter('displayed', TRUE)
			->andWhere('p.published = :published')->setParameter('published', TRUE)
			->orderBy('p.publishedOn', 'DESC');
	}


	public function postFetch(Queryable $repository, \Iterator $iterator)
	{
		$ids = array_keys(iterator_to_array($iterator));
		$repository->createQueryBuilder('a')
			->select('PARTIAL a.{id}, f')
			->innerJoin('a.files', 'f')
			->where('a.id IN (:ids)')->setParameter('ids', $ids)
			->getQuery()
			->getResult();
	}

}

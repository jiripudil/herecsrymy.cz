<?php

namespace Herecsrymy\Entities\Queries;

use Herecsrymy\Entities\Category;


class PostFilter
{

	/** @var Category|NULL */
	private $category;

	/** @var bool|NULL */
	private $published;


	/**
	 * @return Category|NULL
	 */
	public function getCategory()
	{
		return $this->category;
	}


	/**
	 * @param Category|NULL $category
	 */
	public function setCategory(Category $category = NULL)
	{
		$this->category = $category;
	}


	/**
	 * @return bool|NULL
	 */
	public function getPublished()
	{
		return $this->published;
	}


	/**
	 * @param bool|NULL $published
	 */
	public function setPublished($published = NULL)
	{
		$this->published = $published;
	}

}

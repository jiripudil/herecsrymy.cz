<?php

namespace Herecsrymy\AdminModule\Components\EditCategory;


use Herecsrymy\Entities\Category;

interface IEditCategoryControlFactory
{
	/**
	 * @param Category $category
	 * @return EditCategoryControl
	 */
	function create(Category $category);
}

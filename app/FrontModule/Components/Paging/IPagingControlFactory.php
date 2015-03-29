<?php

namespace Slovotepec\FrontModule\Components\Paging;


interface IPagingControlFactory
{
	/** @return PagingControl */
	function create();
}

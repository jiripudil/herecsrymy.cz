<?php

namespace Herecsrymy\FrontModule\Components\Paging;


interface IPagingControlFactory
{
	/** @return PagingControl */
	function create();
}

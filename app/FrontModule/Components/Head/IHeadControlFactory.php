<?php

namespace Herecsrymy\FrontModule\Components\Head;


interface IHeadControlFactory
{
	/** @return HeadControl */
	function create();
}

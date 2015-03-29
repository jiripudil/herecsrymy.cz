<?php

namespace Slovotepec\FrontModule\Components\Head;


interface IHeadControlFactory
{
	/** @return HeadControl */
	function create();
}

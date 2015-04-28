<?php

namespace Herecsrymy\Forms;


interface IEntityFormFactory
{
	/** @return EntityForm */
	function create();
}

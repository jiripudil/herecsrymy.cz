<?php

namespace Herecsrymy\AdminModule\Components\LoginForm;


interface ILoginFormControlFactory
{
	/** @return LoginFormControl */
	function create();
}

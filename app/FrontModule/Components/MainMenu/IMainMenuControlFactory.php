<?php

namespace Slovotepec\FrontModule\Components\MainMenu;


interface IMainMenuControlFactory
{
	/** @return MainMenuControl */
	function create();
}

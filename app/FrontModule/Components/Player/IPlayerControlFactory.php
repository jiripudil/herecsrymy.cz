<?php

declare(strict_types = 1);

namespace Herecsrymy\FrontModule\Components\Player;


interface IPlayerControlFactory
{

	public function create(): PlayerControl;

}

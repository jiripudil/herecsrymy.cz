<?php

namespace JiriHraje\Application\UI;

use Kdyby\Autowired\AutowireComponentFactories;
use Kdyby\Autowired\AutowireProperties;
use Nextras\Application\UI\SecuredLinksPresenterTrait;


trait TBasePresenter
{
	use AutowireProperties;
	use AutowireComponentFactories;
	use SecuredLinksPresenterTrait;
}

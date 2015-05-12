<?php

namespace Herecsrymy\Latte;

use Herecsrymy\Entities\Event;
use Nette\Caching\Cache;
use Nette\Caching\IStorage;
use Nette\Object;
use Herecsrymy\Texy\TexyFactory;


class Filters extends Object
{

	/** @var \Texy */
	private $texy;

	/** @var Cache */
	private $cache;


	public function __construct(TexyFactory $texyFactory, IStorage $cacheStorage)
	{
		$this->texy = $texyFactory->create();
		$this->cache = new Cache($cacheStorage, __CLASS__);
	}


	public function loader()
	{
		$args = func_get_args();
		$method = array_shift($args);

		if (method_exists($this, $method)) {
			return call_user_func_array([$this, $method], $args);
		}

		return NULL;
	}


	public function czechDate(\DateTime $date)
	{
		return str_replace([
			'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
		], [
			'ledna', 'února', 'března', 'dubna', 'května', 'června', 'července', 'srpna', 'září', 'října', 'listopadu', 'prosince'
		], $date->format('j. M Y'));
	}


	public function ago(\DateTime $date)
	{
		$delta = (new \DateTime)->format('U') - $date->format('U');

		$delta = round($delta / 60);
		if ($delta == 0) return 'před okamžikem';
		if ($delta == 1) return 'před minutou';
		if ($delta < 45) return "před $delta minutami";
		if ($delta < 90) return 'před hodinou';
		if ($delta < 1440) return 'před ' . round($delta / 60) . ' hodinami';
		if ($delta < 2880) return 'včera';
		if ($delta < 43200) return 'před ' . round($delta / 1440) . ' dny';
		if ($delta < 86400) return 'před měsícem';
		if ($delta < 525960) return 'před ' . round($delta / 43200) . ' měsíci';
		if ($delta < 1051920) return 'před rokem';
		return 'před ' . round($delta / 525960) . ' lety';
	}


	public function texy($input)
	{
		return $this->cache->load($input, function () use ($input) {
			return $this->texy->process($input);
		});
	}


	public function map(Event $event)
	{
		$point = $event->locationPoint;

		return 'https://www.google.com/maps/place/'
			. urlencode($event->location)
			. '/@'
			. $point->getLatitude()
			. ','
			. $point->getLongitude()
			. ',16z';
	}


	public function price($price)
	{
		return $price . ' Kč';
	}

}

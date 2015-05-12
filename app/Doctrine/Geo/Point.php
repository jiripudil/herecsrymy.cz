<?php

namespace Herecsrymy\Doctrine\Geo;


class Point implements \ArrayAccess
{

	/** @var float */
	private $longitude;

	/** @var float */
	private $latitude;


	/**
	 * @param float $longitude
	 * @param float $latitude
	 */
	public function __construct($longitude, $latitude)
	{
		$this->longitude = (float) $longitude;
		$this->latitude = (float) $latitude;
	}


	/**
	 * @return float
	 */
	public function getLongitude()
	{
		return $this->longitude;
	}


	/**
	 * @return float
	 */
	public function getLatitude()
	{
		return $this->latitude;
	}


	/**
	 * @return string
	 */
	public function __toString()
	{
		return '(' . $this->longitude . ',' . $this->latitude . ')';
	}


	/**
	 * @param string $value
	 * @return Point
	 */
	public static function fromString($value)
	{
		list($longitude, $latitude) = explode(',', trim($value, '()'));
		return new static($longitude, $latitude);
	}


	public function offsetExists($offset)
	{
		return in_array($offset, ['lat', 'lng', 'address']);
	}


	public function offsetGet($offset)
	{
		switch ($offset) {
			case 'lat':
				return $this->getLatitude();

			case 'lng':
				return $this->getLongitude();

			case 'address':
			default:
				return NULL;
		}
	}


	public function offsetSet($offset, $value)
	{
		switch ($offset) {
			case 'lat':
				$this->latitude = $value;
				break;

			case 'lng':
				$this->longitude = $value;
				break;

			default:
				return;
		}
	}


	public function offsetUnset($offset) {
		// intentionally empty
	}

}

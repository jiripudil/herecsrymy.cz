<?php

namespace JiriHraje\Doctrine\Geo;


class Point
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

}

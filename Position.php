<?php
namespace GDO\Maps;
final class Position
{
	private $lat, $lng;
	
	public function __construct($lat=null, $lng=null)
	{
		$this->lat = $lat;
		$this->lng = $lng;
	}
	
	public function isEmpty() { return $this->lat === null || $this->lng === null; }
	public function getLat() { return $this->lat; }
	public function getLng() { return $this->lng; }
	public function displayLat() { return number_format($this->lat, 6) . ($this->lat >= 0 ? '°N' : '°S'); }
	public function displayLng() { return number_format($this->lng, 6) . ($this->lng >= 0 ? '°E' : '°W'); }
	public function displayRaw() { return $this->isEmpty() ? '' : sprintf('%.06f,%.06f', $this->lat, $this->lng); }
	public function hasValidLat() { return self::isValidLat($this->lat); }
	public function hasValidLng() { return self::isValidLng($this->lng); }
	
	##############
	### Static ###
	##############
	public static function isValidLat($lat) { return is_numeric($lat) && $lat >= -90 && $lat <= 90; }
	public static function isValidLng($lng) { return is_numeric($lng) && $lng >= -180 && $lng <= 180; }
	
	/**
	 * http://assemblysys.com/geographical-distance-calculation-in-php/
	 *
	 * @param float $point1_lat
	 * @param float $point1_long
	 * @param float $point2_lat
	 * @param float $point2_long
	 * @param string $unit
	 * @param number $decimals
	 * @return float
	 */
	public static function distanceCalculation($point1_lat, $point1_long, $point2_lat, $point2_long, $unit = 'km', $decimals = 2) {
		// Calculate the distance in degrees
		$degrees = rad2deg(acos((sin(deg2rad($point1_lat))*sin(deg2rad($point2_lat))) + (cos(deg2rad($point1_lat))*cos(deg2rad($point2_lat))*cos(deg2rad($point1_long-$point2_long)))));
		
		// Convert the distance in degrees to the chosen unit (kilometres, miles or nautical miles)
		switch($unit) {
			case 'km':
				$distance = $degrees * 111.13384; // 1 degree = 111.13384 km, based on the average diameter of the Earth (12,735 km)
				break;
			case 'mi':
				$distance = $degrees * 69.05482; // 1 degree = 69.05482 miles, based on the average diameter of the Earth (7,913.1 miles)
				break;
			case 'nmi':
				$distance =  $degrees * 59.97662; // 1 degree = 59.97662 nautic miles, based on the average diameter of the Earth (6,876.3 nautical miles)
		}
		return round($distance, $decimals);
	}
	
	
	/**
	 * Build an sql select query for calculating distance in km.
	 *
	 * http://stackoverflow.com/questions/1006654/fastest-way-to-find-distance-between-two-lat-long-points
	 *
	 * @param float $lat latitude to compare to.
	 * @param float $lng longitude to comapre to.
	 * @param string $latColumn column name for latitude.
	 * @param string $lngColumn column name for longitude.
	 * @return string partial query string
	 */
	public static function getDistanceQuery($lat, $lng, $latColumn='lat', $lngColumn='lng')
	{
		return
		"(6371 * acos(cos(radians({$lat})) * cos(radians({$latColumn})) * cos(radians({$lngColumn}) ".
		"- radians({$lng})) + sin(radians({$lat})) * sin(radians({$latColumn}))))";
	}
	
}

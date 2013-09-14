<?php
/***************************************
  This file is part of Silly.
 
  Silly is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  Silly is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with Silly.  If not, see <http://www.gnu.org/licenses/>.
  
  @copyright     Copyright 20012-2013, Silly PHP Framework
  @link          silly.gianstocks.com
  @package       silly
  @since         Silly(tm) v 0.9
  @license       http://www.gnu.org/licenses/
 ***************************************/
class unitsCore {
	
	static $measurements = array ("Area", "Density", "Electrict current", "Energy", "Force", "Length", "Mass", "Power", "Pressure", "Speed", "Volume" );
	function __construct() {
	}
	public static function metricsLength() {
		return array ("mm" => __ ( "millimeter" ), "cm" => "centimeter", "m" => "meter", "km" => "kilometer" );
	}
	public static function metricsMass() {
		return array ("mg" => "milligram", "g" => "gram", "kg" => "kilogram", "t" => "metric ton" );
	}
	public static function metricsArea() {
		return array ("m²" => "square meter", "ha" => "hectare", "km²" => "square kilometer" );
	}
	public static function metricsVolume() {
		return array ("mL" => "milliliter", "cm³" => "cubic centimeter", "L" => "liter", "m³" => "cubic meter" );
	}
	public static function metricsSpeed() {
		return array ("m/s" => "meter per second", "km/h" => "kilometer per hour" );
	}
	public static function metricsDensity() {
		return array ("kg/m³" => "kilogram per cubic meter" );
	}
	public static function metricsForce() {
		return array ("N" => "newton" );
	}
	public static function metricsPressure() {
		return array ("kPa" => "kilopascal" );
	}
	public static function metricsPower() {
		return array ("W" => "watt", "kW" => "kilowatt" );
	}
	public static function metricsEnergy() {
		return array ("kJ" => "kilojoule", "MJ" => "megajoule", "kW·h" => "kilowatt hour" );
	}
	public static function metricsElectricCurrent() {
		return array ("A" => "ampere" );
	}
	public static function metrics() {
		return array_merge ( self::metricsArea (), self::metricsDensity (), self::metricsElectricCurrent (), self::metricsEnergy (), self::metricsForce (), self::metricsLength (), self::metricsMass (), self::metricsPower (), self::metricsPressure (), self::metricsSpeed (), self::metricsVolume () );
	}
}

?>
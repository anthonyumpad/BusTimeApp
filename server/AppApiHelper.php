<?php
        /**
        PHP AppApiHelper class

        This is a simple class that contains function used in busTimeAppApi
        */
	class AppApiHelper
	{
		/*function to calculate square location
		@param lat, base latitude
		@param lng, base longitude
		@param limit, square location limit
		@param distance, distance of seach
		@param unit, unit of measurement, 'km' or 'mi'
		*/
		function bar_get_nearby( $lat, $lng, $limit = 50, $distance = 5, $unit = 'km' ) 
		{
			// radius of earth; @note: the earth is not perfectly spherical, but this is considered the 'mean radius'
			if( $unit == 'km' ) { $radius = 6371.009; }
			elseif ( $unit == 'mi' ) { $radius = 3958.761; }

			// latitude boundaries
			$maxLat = ( float ) $lat + rad2deg( $distance / $radius );
			$minLat = ( float ) $lat - rad2deg( $distance / $radius );

			// longitude boundaries (longitude gets smaller when latitude increases)
			$maxLng = ( float ) $lng + rad2deg( $distance / $radius) / cos( deg2rad( ( float ) $lat ) );
			$minLng = ( float ) $lng - rad2deg( $distance / $radius) / cos( deg2rad( ( float ) $lat ) );

			$max_min_values = array(
			'max_latitude' => $maxLat,
			'min_latitude' => $minLat,
			'max_longitude' => $maxLng,
			'min_longitude' => $minLng
			);
			return $max_min_values;
		}
		/*
		 funtion that calculates the great-circle distance between two points, with
		 the Vincenty formula.
		 @param latitudeFrom, Latitude of start point in [deg decimal]
		 @param longitudeFrom, Longitude of start point in [deg decimal]
		 @param latitudeTo, Latitude of target point in [deg decimal]
		 @param longitudeTo, Longitude of target point in [deg decimal]
		 @param $earthRadius, Mean earth radius in [m]
		 @return Distance between points in [m] (same as earthRadius)
		 */
		function vincentyGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
		{
			// convert from degrees to radians
			$latFrom = deg2rad($latitudeFrom);
			$lonFrom = deg2rad($longitudeFrom);
			$latTo = deg2rad($latitudeTo);
			$lonTo = deg2rad($longitudeTo);

			$lonDelta = $lonTo - $lonFrom;
			$a = pow(cos($latTo) * sin($lonDelta), 2) +
			pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
			$b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

			$angle = atan2(sqrt($a), $b);
			return $angle * $earthRadius;
		}
		/**
		 This will help contruct $where query for MongoDB using $js function
		 @param coords = array(
			'max_latitude' => $maxLat,
			'min_latitude' => $minLat,
			'max_longitude' => $maxLng,
			'min_longitude' => $minLng
			}
		 $return = string
		**/
		function constructCoordQuery($coords)
		{
			$where = '';
			if(isset($coords['max_latitude']) && isset($coords['max_longitude']) && isset($coords['min_latitude']) && isset($coords['min_longitude']))
			{
				$where = "function() { return this.lat > ".$coords['min_latitude']." && this.lat < ".$coords['max_latitude']." && this.long > ".$coords['min_longitude']." && this.long < ".$coords['max_longitude']."};";
			}
			return $where;
		}
		/*
		 This function will add 'distFromLocation' from a given array output from BusStop()
		 @param busStops array() output from BuStop() class
		 $return array()
		*/
		function calculateDistFromLocation($busStops)
		{
			foreach($busStops as $key => $obj)
			{

				$busStops[$key]['distFromLocation'] = $this->vincentyGreatCircleDistance($_GET['lat'],$_GET['lng'], $obj['lat'],$obj['long']);
			}
			return $busStops;
		}


	}//end class
?>

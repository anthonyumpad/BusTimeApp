<?php
/**
Credits to cheeaun
Bus stop test data adapted from http://cheeaun.github.io/busrouter-sg/
https://github.com/cheeaun/busrouter-sg
functions below will populate bustimeapp database
I created 3 different collections
bus_services
	{ 
		"_id" : "402", 
	    "stops" : { "03239" : 5, "03211" :15, "03222" : 7}  
		@where "03239" is a bus_stops _id || 5 is the time or the bus_services "402" arriving in min
	}
bus_stops
	{ 
		"_id" : 1009,
		"name" : "Bt Merah Int",
		"long" : 103.817225,
		"lat" : 1.282102 
	}
bus_stop_services
	{ 
		"_id" : "10099", 
		"services" : { 
						"14" : 2, 
						"147" : 11, 
						"196" : 1, 
						"197" : 3
					 } 
		@where _id is a bus_stops _id "14":2 = "bus_services._id":bus service arrival time in mins
	}
**/
require_once('../MongoDB.php');

	function busStopsParse()
	{
		$mdb = new MongoDBConnector;
		$mdb->connect();

		$string = file_get_contents("/var/www/html/busData/data/bus-stops.json");
		$json_a=json_decode($string,true);

		foreach ($json_a as $key => $value)
		{
			//echo  $key . "\n";
			$coords = explode(",",$value['coords']);
			$long = $coords[0];
			$lat = $coords[1];
			if(is_numeric($coords[0]))
			{
				$long = round($coords[0],6);
			}
			if(is_numeric($coords[1]))
			{
				$lat = round($coords[1],6);
			}

			//echo "long :$long\n";
			//echo "lat  :$lat\n";
			//echo "name :".$value['name']."\n";
			$tdata = array("_id" => $key,"name" => $value['name'],"long" => $long,"lat" => $lat);
			print_r($tdata);
			$mdb->addCollection("bus_stops",$tdata);
		}
	}

	function busServicesParse()
	{
		$mdb = new MongoDBConnector;
		$mdb->connect();

		$files = scandir("/var/www/html/busData/data/bus-services/");
		$i = 0;
		foreach($files as $file)
		{
			$fexplode = explode(".",$file);
			$_id = trim($fexplode[0]);
			//echo $_id."\n";
	                $string = file_get_contents("/home/ec2-user/data/bus-services/".$file);
	                $json_a=json_decode($string,true);

			$stopsArr = array();
			if(count($json_a) > 0)
			{
				foreach ($json_a as $key => $value)
				{
					foreach($value['stops'] as $stopId)
					{
						$stopsArr[$stopId] = rand(1,15);
					}
				}
			}
			$tData = array("_id" => $_id,"stops" => $stopsArr);
			print_r($tData);
			echo "\n";
			$mdb->addCollection("bus_services",$tData);
			echo ++$i."\n";
		}
	}

        function busStopServicesParse()
        {
                $mdb = new MongoDBConnector;
                $mdb->connect();

                $string = file_get_contents("/var/www/html/busData/data/bus-stops-services.json");
                $json_a=json_decode($string,true);

		$i = 0;
                foreach ($json_a as $key => $value)
                {
			$_id = (string)$key;
			//echo "_id : $_id\n";
			$services = array();
			if(count($value) > 0)
			{
				foreach($value as $key => $service)
				{
					$services[$service] = rand(1,15);
				}
			}
			$tData = array("_id" =>$_id,"services" => $services);
			print_r($tData);
			echo "\n";
			 $mdb->addCollection("bus_stop_services",$tData);
			echo "\n".++$i."\n";
                }
        }
	busStopServicesParse();

?>

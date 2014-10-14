<?php
require_once('MongoDB.php');
require_once('AppApiHelper.php');
require_once('BusService.php');
require_once('BusStop.php');
require_once('BusStopServices.php');
$method = $_SERVER['REQUEST_METHOD'];
$request = explode("/", substr(@$_SERVER['PATH_INFO'], 1));

switch ($method) {
  case 'PUT':
    break;
  case 'POST':
    break;
  case 'GET':
	if(isset($request[0]))
	{
		$apiHelper = new AppApiHelper();
		$mdb  = new MongoDBConnector();
		$mdb->connect();
		//bus services
		$findOne = false;	
		$data = null;
		$locationBusStops = null;

		//check if geolocation is given
		if(isset($_GET['lat']) && isset($_GET['lng']) && !isset($_GET['_id']))
		{
			$busStop= new BusStop($mdb);
			//get min max lat and long
			//we will just find within a box of 500 meter distance
			$nearby = $apiHelper->bar_get_nearby($_GET['lat'],$_GET['lng'],0.5,0.5,'km');
			$where  = $apiHelper->constructCoordQuery($nearby);
			$criteria = array('$where' => $where);
			$locationBusStops = $busStop->query($criteria,$findOne);
			$locationBusStops = $apiHelper->calculateDistFromLocation($locationBusStops);
		}

		// Query for Bus Services
		if(strcasecmp(trim($request[0]),'services') == 0)
		{
			$busService = new BusService($mdb);	
		        /*bus_services colection sample data	
			    [_id] => RWS8
			    [stops] => Array
				(
				    [14519] => 15
				    [14141] => 15
				    [14121] => 15
				    [14139] => 15
				)
			   @stops = array of bus_stops _id = minutes of arrival time in _id stop
			*/
			$criteria = array();
			if(isset($_GET['_id']))
			{
				$criteria = array("_id" => $_GET['_id']);
				$findOne = true;
			}

			if(!is_null($locationBusStops))
                        {
		 		//construct criteria to query for Services with the Bus Stops near the area
				$tmpCriteria = array();
				foreach(array_keys($locationBusStops) as $busStop_id)
				{
					array_push($tmpCriteria,array("stops.".$busStop_id => array('$exists' => 'true')));
				}			
				$criteria = array('$or' => $tmpCriteria);
                        }
			$data = $busService->query($criteria,$findOne);
		}	

                //Query for Bus Stops
                if(strcasecmp(trim($request[0]),'stops') == 0)
                {
                        $busStop= new BusStop($mdb);
                        /*bus_stops colection sample data
 
			    [_id] => E0807
			    [name] => Non Stop
			    [long] => 0
			    [lat] => 0
                        */
                        $criteria = array();
                        if(isset($_GET['_id']))
                        {
                                $criteria = array("_id" => $_GET['_id']);
				$findOne = true;
                        }

			if(!is_null($locationBusStops))
			{
				$data = $locationBusStops;
			}
			else
			{
				$data = $busStop->query($criteria,$findOne);
			}

                }

                //Query for Bus Stops and Services
                if(strcasecmp(trim($request[0]),'stopservices') == 0)
                {
			$data = null;
			$busSServices = null;
                        $busSServices = new BusStopServices($mdb);
                        /*bus_stop_services colection sample data
			4625
			Array
			(
			    [_id] => E0402
			    [services] => Array
				(
				    [NR6] => 12
				)
			)
                       */
                        $filter = array();
                        if(isset($_GET['_id']))
                        {
                                $filter = array('_id' => trim($_GET['_id']));
				$findOne = true;
                        }
                        $data = $busSServices->query($filter,$findOne);

                }
		$mdb->disconnect();
		
		echo json_encode($data);
	}
    break;
  case 'HEAD':
    break;
  case 'DELETE':
    break;
  case 'OPTIONS':
    break;
  default:
    break;
}

?>

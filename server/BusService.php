<?php
        /**
        PHP BusService class

        This is a simple class to connect to mongodb and perform basic query operation
	for collection bus_services
        @copyright Anthony Umpad
        @author    Anthony Umpad
        Oct.8,2014
        */
	class BusService
	{
		var $mdb = null;
		function __construct($mdb) 
		{
			$this->mdb = $mdb;
		}

                /**
                * query
                * @param criteria array(), search criteria for MongoDB
                * @param finDOne boolean,flag to search for 1 data
                * @return array
                */
		function query($criteria,$findOne = false)
		{
			$result = '';
			try
			{
				$tdata = null;
		        	$tdata = $this->mdb->getCollection("bus_services",$criteria,$findOne);
		
                                if(is_array($tdata))
                                {
					//need to query bStops to get Bus Stop name
					//query one
					$bStopsIt= $this->mdb->getCollection("bus_stops",array(),false);
					$bStops = iterator_to_array($bStopsIt);
					$tdata = $this->mdb->getCollection("bus_services",$criteria,true);
					
					$busStopData = array();
					if(isset($tdata['stops']))
					{
						foreach($tdata['stops'] as $stopId => $arrTime)
						{
							$busStopName = $bStops[$stopId]['name'];
							$tdata['stops'][$stopId] = array("name" => $busStopName,"arrTime" => $arrTime);	
						}
					}
                                        $result = $tdata;
                                }
                                else
                                {
					//query all
                                        $result = iterator_to_array($tdata);
                                }

			}
			catch(Exception $e)
			{
				error_log($e->getMessage());
				return $result;
			}
			return $result;	
		}

	
		function __destruct() 
		{
		}	
	}//end class
?>

<?php
        /**
        PHP BusStopServices class

        This is a simple class to connect to mongodb and perform basic operations like add,update,delete.
	for collection bus_stop_services
        @copyright Anthony Umpad
        @author    Anthony Umpad
        */
	class BusStopServices
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
		function query($criteria,$findOne=false)
		{
			$result = '';
			try
			{
				$tdata = null;
		        	$tdata = $this->mdb->getCollection("bus_stop_services",$criteria,$findOne);
			
				if(is_array($tdata))
				{
                                        //get bus_stop data
				
					if($criteria["_id"][0] != '0')
					{
						$criteria["_id"] = (int)$criteria["_id"];
					}
                                        $bStop= $this->mdb->getCollection("bus_stops",$criteria,true);
				        $tdata['name'] = $bStop['name'];
					//transform the array for easier transition to front-end
					if(isset($tdata['services']))
					{
						foreach($tdata['services'] as $serviceId => $arrTime)
						{
							$tdata['services'][$serviceId] = array("service" => $serviceId,"arrTime"=>$arrTime);
						}
					}
                                        $result = $tdata;
				}
				else
				{
					$result = iterator_to_array($tdata);
				}
			}
			catch(Exception $e)
			{
				error_log($e->getMessage());
			}
			return $result;	
		}

	
		function __destruct() 
		{
		}	
	}//end class
?>

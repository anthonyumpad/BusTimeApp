<?php
        /**
        PHP BusStop class

        This is a simple class to connect to mongodb and perform query
	for collection bus_stops
        @copyright Anthony Umpad
        @author    Anthony Umpad
        */
	class BusStop
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
		        	$tdata = $this->mdb->getCollection("bus_stops",$criteria,$findOne);
			
				if(is_array($tdata))
				{
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
				return $result;
			}
			return $result;	
		}

	
		function __destruct() 
		{
		}	
	}//end class
?>

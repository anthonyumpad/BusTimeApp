<?php
	/**
	PHP MongoDB class

	This is a simple class to connect to mongodb and perform basic operations like add,update,delete.
	@copyright Anthony Umpad
	@author    Anthony Umpad
	Oct.8,2014
	*/
	class MongoDBConnector
	{
		var $dbhost = 'localhost';
		var $dbname = 'bustimeapp';
		var $connection = null;
		var $db     = null;

		/**
		* Connect to localhost MongoDB
		* false - fail
		* true  -  sucess
		*/
		public function connect()
		{
			try
			{
			   // connect to mongodb
			   $m = new Mongo("mongodb://$this->dbhost");
			   #echo "INFO:Connection to database successful";
			   // select a database
			   $db = $this->dbname;
			   $this->db = $m->$db;
			   $this->connection = $m;
			   #echo "\nINFO:Database $db selected";
			}
			catch(Exception $e)
			{
				error_log($e->getMessage());
				#echo "\nERR:Caught exception: ",  $e->getMessage();
				return false;
			}
			return true;
		}

                /**
                * Create MongoDB Collection
                * false - fail
                * true  -  sucess
                */
		public function createCollection($colName)
		{
			try
			{
				if(!is_null($this->db))
				{
					$collection = $this->db->$colName;
					#echo "\nINFO:Collection $colName successfully created";	
				}
				else
				{
					return false;
				}
			}
                        catch(Exception $e)
                        {
				#echo "\nERR:Caught exception: ",  $e->getMessage();
                                return false;
                        }
			return true;			
		}
		/**
                * Insert date to collection
                * false - fail
                * true  -  sucess
                */
                public function addCollection($colName,$data)
                {
                        try
                        {
                                if(!is_null($this->db))
                                {
                                        $collection = $this->db->selectCollection($colName);
					$collection->insert($data);
					#echo "\nINFO:Data inserted to collection $colName";
                                }
                                else
                                {
                                        return false;
                                }
                        }
                        catch(Exception $e)
                        {
				#echo "\nERR:Caught exception: ",  $e->getMessage();
                                return false;
                        }
                        return true;
                }

                /**
                * get data from collection
                * false - fail/fatal error
                * $rdata[array] 
                */
                public function getCollection($colName,$criteria,$findOne)
                {
			$rdata = null;
			$collection = null;
                        try
                        {
                                if(!is_null($this->db))
                                {
                                        $collection = $this->db->selectCollection($colName);
					#echo "\nINFO:Collection $colName selected" ;
					if(count($criteria) > 0)
					{
						if($findOne)
						{
                                			$rdata =   $collection->findOne($criteria);
						}
						else
						{
                                			$rdata =   $collection->find($criteria);
						}
					}
					else
					{
						$rdata = $collection->find();
					}
					
                                }
                        }
                        catch(Exception $e)
                        {
				error_log("\nERR:Caught exception: ". $e->getMessage());
				//echo "\nERR:Caught exception: ",  $e->getMessage();
                                return false;
                        }
                        return $rdata;
                }

                /**
                * remove data from  collection
                * false - fail/fatal error
                * true
                */
                public function removeFromCollection($colName,$criteria)
                {
                        $rdata = true;
                        try
                        {
                                if(!is_null($this->db))
                                {
                                        $collection = $this->db->selectCollection($colName);
                                        #echo "\nINFO:Collection $colName selected" ;
                                        $rdata = $collection->remove($criteria);
                                        #echo "\nINFO:Collection data removed." ;

                                }
                                else
                                {
                                        return false;
                                }
                        }
                        catch(Exception $e)
                        {
                                #echo "\nERR:Caught exception: ",  $e->getMessage();
                                return false;
                        }
                        return $rdata;
                }

                /**
                * disconnect from MongoDB
                * false - fail/fatal error
                * true
                */
		public function disconnect()
		{
			try
			{
				$this->connection->close();
                                #echo "\nINFO:Connection to db closed" ;
			}
                        catch(Exception $e)
                        {
                                #echo "\nERR:Caught exception: ",  $e->getMessage();
                                return false;
                        }
			return true;
		}

	}//end class
?>

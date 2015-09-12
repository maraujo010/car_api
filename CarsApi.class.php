<?php

require_once 'Restful.class.php';

define('DB_SERVER', "localhost");
define('DB_DATABASE', "cars");
define('DB_USER', "test");
define('DB_PASSWORD', "test");

function car($description, $latitude, $longitude) {
	$carAssArray = ['description'=> $description, 'latitude' => (float)$latitude, 'longitude' => (float)$longitude];
	return $carAssArray;
}	

class CarsApi extends Restful
{	
	
	private $db = null;
	
	private function connectDB() {
		$db = new PDO("pgsql:host=".DB_SERVER.";dbname=".DB_DATABASE, DB_USER, DB_PASSWORD);

		if (!$db) {
			return "Connection to database failed.";
		}

		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		$this->db=$db;
	}
	
    public function __construct($request, $origin) {
        parent::__construct($request);

    }


	/* endpoint cars */
    protected function cars() {		
		
		parse_str($_SERVER['QUERY_STRING'], $queryArray);

		if (!array_key_exists("location", $queryArray) && $this->verb==''){
			
			return "Invalid parameters";
		}	
									
		else if ($this->verb=='all') {
									
			$this->connectDB();						
			
			try {
				$result = $this->db->prepare("select description, latitude, longitude from cars");											  
				$result->execute();				
				$resultArray  = $result->fetchAll(PDO::FETCH_FUNC, "car");									
				
			}
			catch(PDOException $e) {					
				return "Database error";
			}
			
			if ($resultArray==null || count($resultArray)==0) {
				return "No results";
			}
			
			$jsonArray["cars"] = $resultArray;	
			$this->db = null;
			return $jsonArray["cars"];
		
		}		
		
        if ($this->method == 'GET') {									
			
			$location = $queryArray['location'];
			
			if (preg_match('/^(\-?\d+(\.\d+)?),\s*(\-?\d+(\.\d+)?)$/', $location)!=1) {
				return "Invalid location";
			}
			
			$point = explode(",",$location);			
			$this->connectDB();
						
			$dist = 0.01;
			$resultArray = null;
			
			while ($dist<0.5) {
				
				try {
					$result = $this->db->prepare("select description, latitude, longitude from 
												  (select description, latitude, longitude, ST_Distance(geography(geom), ST_GeographyFromText('POINT(".$point[1]." ".$point[0].")')) as distance 
												  from cars where ST_DWithin(geom,  st_setsrid(st_makepoint(".$point[1].", ".$point[0]."),4326),".$dist.")
												  order by distance) as points LIMIT 10");	
					$result->execute();				
					
				}
				catch(PDOException $e) {					
					return "Database error";
				}
				
				$resultArray  = $result->fetchAll(PDO::FETCH_FUNC, "car");			
				if ($result->rowCount()==10) {											
					break;
				}				
				
				$dist = $dist+0.005;						
			}
			
			if ($resultArray==null || count($resultArray)==0) {
				return "No results";
			}
						
			$jsonArray["cars"] = $resultArray;						
			
			$this->db = null;
			return $jsonArray;
			
			            
        } else {
            return "This endpoint only accepts GET requests";
        }
        
    }
 }

?>


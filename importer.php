<?php

define('DB_SERVER', "localhost");
define('DB_DATABASE', "cars");
define('DB_USER', "test");
define('DB_PASSWORD', "test");


$db = new PDO("pgsql:host=".DB_SERVER.";dbname=".DB_DATABASE, DB_USER, DB_PASSWORD);

if (!$db) {
	print("Connection Failed.");
	die;
}

$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$string = file_get_contents("data.json");
$json_array = json_decode($string, true);


for ($i=0; $i<count($json_array["locations"]); $i++) {
	
	$desc = $json_array["locations"][$i]["description"];
	$lat = $json_array["locations"][$i]["latitude"];
	$lon = $json_array["locations"][$i]["longitude"];
			
	
	try {
		$stmt = $db->prepare("INSERT INTO cars (description, latitude, longitude) VALUES ('". pg_escape_string($desc)."', ".$lat.", ".$lon.");");		
		
		if($stmt->execute()) {
			print ('inserted car: '.$desc."\xA");  
		}
	
	} 
	catch(PDOException $e) {
		trigger_error('DB Error: ' . $e->getMessage(), E_USER_ERROR);
		die;
	}

}

try {
	$stmt = $db->prepare("UPDATE cars SET geom = ST_SetSRID(ST_MakePoint(longitude,latitude),4326);");		
	
	if($stmt->execute()) {
						
		print ("......... geom column updated! "."\xA");  
		
		$stmt = $db->prepare("CREATE INDEX idx_cars_geom ON cars USING GIST(geom);");		
		
		if($stmt->execute()) {
			print ("......... spacial index created! "."\xA");  
		}
		
	}

} 
catch(PDOException $e) {
	trigger_error('DB Error: ' . $e->getMessage(), E_USER_ERROR);
	die;
}



$db = null;

?>

<?php

$connection = mysql_connect("localhost","root","password");
mysql_select_db("map", $connection);

$action = @$_GET['mode'];


switch($action) {
	
	case "add":
		$lat = $_GET["lat"];
		$lng = $_GET["lng"];

		$sql = "INSERT INTO MAP (lat, lng) values ('$lat', '$lng')";
		if (!mysql_query($sql,$connection)) {
		  die('Error: ' . mysql_error());
		}
		
	break;


	default:
		$data = array();
		$result = mysql_query("SELECT * FROM map");
		
		while ($row 	= mysql_fetch_array($result)) 
		{
			$data_elements = array("id" => $row['id'], "longitude" => $row['lng'], "latitude" => $row['lat'] );
			array_push($data, $data_elements);

		}
		
		echo json_encode($data);
		
	break;
}

?>
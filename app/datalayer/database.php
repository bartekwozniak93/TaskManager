<?php

$configs = include('../config.php');

function getDB() {
	$dbConnection = pg_connect('host=localhost port=5432 dbname=postgres user=postgres password=cs2102') 
	or die('Could not connect: ' . pg_last_error());
	$setPath = pg_query($dbConnection, "SET search_path TO project");
	if (!$setPath) {
		die("Failed to set schema: " . pg_last_error());
	}
	return $dbConnection;
}

?>

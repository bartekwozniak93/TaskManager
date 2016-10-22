<?php
include_once('../datalayer/database.php');
$configs = include('../config.php');


function getCategoriesDB(){
	$dbconn = getDB();
	$query = "SELECT * FROM task_category;";
	$result = pg_query($dbconn, $query) 
	or die('Select failed: ' . pg_last_error());
	$categories = array(); 
	while($row = pg_fetch_array($result, null, PGSQL_ASSOC)){
		$element = array("name" => $row["name"]);
		array_push($categories, $element);
	}
	pg_close($dbconn);
	return $categories;	
}

function postCategoryDB($taskcat) {
	$dbconn = getDB();
	$query = "INSERT INTO task_category (name)
	VALUES ('$taskcat->name')";
	$result = pg_query($dbconn, $query) 
	or die('Post failed: ' . pg_last_error());
	pg_close($dbconn);
	return $result;
}

function getCategoryDB($categoryName){
	$dbconn = getDB();
	$query = "SELECT * FROM task_category where name = '$categoryName';";
	$result = pg_query($dbconn, $query) 
	or die('Select failed: ' . pg_last_error());
	$row = pg_fetch_array($result, null, PGSQL_ASSOC);
	$category = array("name" => $row["name"]);
	pg_close($dbconn);
	return $category;	
}

function editCategoryDB($taskcat, $categoryName){
	$dbconn = getDB();
	$query = "UPDATE task_category SET name='$taskcat->name' where name = '$categoryName';";
	$query_result = pg_query($dbconn, $query);
	$status = pg_result_status($query_result);
	if ($status == PGSQL_COMMAND_OK)
	   $result= "Category successfully changed.";
	else
	    $result= "Change category failed!";
	pg_close($dbconn);
	return $result;	
}

function deleteCategoryDB($categoryName){
	$dbconn = getDB();
	$query = "DELETE FROM task_category WHERE name='$categoryName'";
	$query_result = pg_query($dbconn, $query);
	$status = pg_result_status($query_result);
	if ($status == PGSQL_COMMAND_OK)
	   $result= "Category successfully deleted.";
	else
	    $result= "Delete category failed!";
	pg_close($dbconn);
	return $result;
}	

?>
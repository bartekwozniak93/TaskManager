<?php

$configs = include('../config.php');

function getDB() {
	$dbConnection = pg_connect('host=localhost port=5432 dbname=postgres user=postgres password=Martynka123!')
	or die('Could not connect: ' . pg_last_error());
	$setPath = pg_query($dbConnection, "SET search_path TO project");
	if (!$setPath) {
		die("Failed to set schema: " . pg_last_error());
	}
	return $dbConnection;
}

function getUsersDB() {
	$dbconn = getDB();
	$query = "SELECT * FROM account;";
	$result = pg_query($dbconn, $query) 
	or die('Insert failed: ' . pg_last_error());

	pg_close($dbconn);
	return $result;
}

function postUserDB($user) {
	$dbconn = getDB();
	$query = "INSERT INTO account (password, first_name, last_name, email, contact_num)
	VALUES ('$user->password', '$user->firstName', '$user->lastName', '$user->email', '$user->contactNum')";
	$result = pg_query($dbconn, $query) 
	or die('Insert failed: ' . pg_last_error());
	pg_close($dbconn);
	return $result;
}

function loginUserDB($user) {
	$dbconn = getDB();
	$query = "SELECT * FROM account WHERE email='$user->email' AND password='$user->password';";
	$result = pg_query($dbconn, $query) 
	or die('Login failed: ' . pg_last_error());
	pg_close($dbconn);
	return pg_num_rows($result)==1;
}

function getUserTasksDB($login_user) {
	$dbconn = getDB();
	$query = "SELECT * FROM task WHERE created_by='$login_user';";
	$result2 = pg_query($dbconn, $query) 
	or die('Select failed: ' . pg_last_error());
	$result = "";
        while($line = pg_fetch_array($result2, null, PGSQL_ASSOC)){
           foreach ($line as $col_value) {
             $result.= $col_value." ";
            }
        }


	pg_close($dbconn);
	return $result;
}

function postTaskDB($task) {
	$dbconn = getDB();
	$query = "INSERT INTO task (task_name, task_description	, task_type, amount, created_by)
	VALUES ('$task->task_name', '$task->task_description', '$task->task_type', '$task->amount', '$task->login_user')";
	$result = pg_query($dbconn, $query) 
	or die('Post failed: ' . pg_last_error());
	pg_close($dbconn);
	return $result;
}

function getTaskCategoriesDB() {
	$dbconn = getDB();
	$query = "SELECT * FROM taskcategory;";
	$result = pg_query($dbconn, $query) 
	or die('Select failed: ' . pg_last_error());
	pg_close($dbconn);
	return $result;
}

function postTaskCategoryDB($taskcat) {
	$dbconn = getDB();
	$query = "INSERT INTO taskcategory (category)
	VALUES ('$taskcat->taskCategory')";
	$result = pg_query($dbconn, $query) 
	or die('Post failed: ' . pg_last_error());
	pg_close($dbconn);
	return $result;
}


?>

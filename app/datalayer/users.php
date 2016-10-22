<?php
include_once('../datalayer/database.php');
$configs = include('../config.php');

function getUsersDB() {
	$dbconn = getDB();
	$query = "SELECT * FROM account;";
	$result = pg_query($dbconn, $query) 
	or die('Insert failed: ' . pg_last_error());
	$users = array(); 
	while($row = pg_fetch_array($result, null, PGSQL_ASSOC)){
		$user = array("email" => $row["email"], "first_name" => $row["first_name"], "last_name" => $row["last_name"], "contact_num" => $row["contact_num"]);
		array_push($users, $user);
	}
	pg_close($dbconn);
	return $users;
}

function getUserDB($userEmail) {
	$dbconn = getDB();
	$query = "SELECT * FROM account WHERE email='$userEmail';";
	$result = pg_query($dbconn, $query) 
	or die('Insert failed: ' . pg_last_error());
	$row = pg_fetch_array($result, null, PGSQL_ASSOC);
	$user = array("email" => $row["email"], "first_name" => $row["first_name"], "last_name" => $row["last_name"], "contact_num" => $row["contact_num"]);
	pg_close($dbconn);
	return $user;
}

function postUserDB($user) {
	$dbconn = getDB();
	$query = "INSERT INTO account (email, password, first_name, last_name, contact_num)
	VALUES ('$user->email', '$user->password', '$user->firstName', '$user->lastName', '$user->contactNum')";
	$query_result = pg_query($dbconn, $query);
	$status = pg_result_status($query_result);
	if ($status == PGSQL_COMMAND_OK){
		$result = "True";
	}
	elseif($status == PGSQL_TUPLES_OK){
		$result= "Email already taken.";	
	}	
	else{
		$result= "Registration failed!";
	}
	pg_close($dbconn);
	return $result;
}


function editUserDB($user) {
	$dbconn = getDB();
	$query = "UPDATE account SET email='$user->email', first_name='$user->firstName', last_name='$user->lastName', contact_num='$user->contactNum' WHERE email='$user->email';";
	$query_result = pg_query($dbconn, $query);
	pg_close($dbconn);
	return $query_result;
}

function loginUserDB($user) {
	$dbconn = getDB();
	$query = "SELECT * FROM account WHERE email='$user->email' AND password='$user->password';";
	$result = pg_query($dbconn, $query);
	pg_close($dbconn);
	return pg_num_rows($result)==1;
}
?>
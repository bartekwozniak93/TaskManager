<?php
include_once('../datalayer/database.php');
$configs = include('../config.php');

function getUserTasksDB($login_user) {
	$dbconn = getDB();
	$query = "SELECT * FROM task WHERE created_by='$login_user';";
	$result = pg_query($dbconn, $query) 
	or die('Select failed: ' . pg_last_error());
	$tasks = array(); 
	while($row = pg_fetch_array($result, null, PGSQL_ASSOC)){
		$element = array("id" => $row["id"], "name" => $row["name"], "description"=> $row["description"], "amount"=> $row["amount"], "category"=> $row["category"], "status"=> $row["status"]);
		array_push($tasks, $element);
	}
	pg_close($dbconn);
	return $tasks;
}

function postTaskDB($task) {
	$dbconn = getDB();
	$query = "INSERT INTO task (name, description, amount, category, status, created_by)
	VALUES ('$task->name', '$task->description', $task->amount, '$task->category', '$task->status','$task->login_user')";
	$result = pg_query($dbconn, $query) 
	or die('Post failed: ' . pg_last_error());
	pg_close($dbconn);
	return $result;
}

function editTaskDB($task){
	$dbconn = getDB();
	$query = "UPDATE task SET name='$task->name', description='$task->description', amount=$task->amount, category='$task->category' where id = $task->id";
	$result = pg_query($dbconn, $query) 
	or die('Select failed: ' . pg_last_error());
	pg_close($dbconn);
	return $task;
}

function getTaskDB($taskId) {
	$dbconn = getDB();
	$query = "SELECT * FROM task WHERE id='$taskId';";
	$result = pg_query($dbconn, $query) 
	or die('Select failed: ' . pg_last_error());
	$row = pg_fetch_array($result, null, PGSQL_ASSOC);
	$task = array("id" => $row["id"], "name" => $row["name"], "description"=> $row["description"], "amount"=> $row["amount"], "category"=> $row["category"], "created_by"=> $row["created_by"],"status"=> $row["status"]);
	pg_close($dbconn);
	return $task;
}

function findTasksToDoDB($userEmail) {
	$dbconn = getDB();
	$query = "SELECT * FROM task WHERE created_by<>'$userEmail' AND status='New';";
	$result = pg_query($dbconn, $query) 
	or die('Select failed: ' . pg_last_error());
	$tasks = array(); 
	while($row = pg_fetch_array($result, null, PGSQL_ASSOC)){
		$element = array("id" => $row["id"], "name" => $row["name"], "description"=> $row["description"], "amount"=> $row["amount"], "category"=> $row["category"],  "created_by"=> $row["created_by"], "status"=> $row["status"]);
		array_push($tasks, $element);
	}
	pg_close($dbconn);
	return $tasks;
}

function deleteTaskDB($taskId){
	$dbconn = getDB();
	$query = "DELETE FROM task WHERE id='$taskId'";
	$result = pg_query($dbconn, $query) 
	or die('Post failed: ' . pg_last_error());
	pg_close($dbconn);
	return $result;
}

function acceptTaskDB($transaction){
	$dbconn = getDB();
	$query = "INSERT INTO transaction (date, accepted_by, task_id, task_owner, is_accepted)
	VALUES ('now()', '$transaction->accepted_by',$transaction->task_id, '$transaction->task_owner', 'false')";
	$result = pg_query($dbconn, $query) 
	or die('Post failed: ' . pg_last_error());
	pg_close($dbconn);
	return $result;
}

function finalizeTaskDB($transaction){
	$dbconn = getDB();
	$query = "UPDATE transaction SET is_accepted='true' WHERE accepted_by = '$transaction->accepted_by' AND task_id= $transaction->task_id AND task_owner='$transaction->task_owner'";
	$result = pg_query($dbconn, $query) 
	or die('Post failed: ' . pg_last_error());
	pg_close($dbconn);
	return $result;
}



function resignFromTaskDB($transaction){
	$dbconn = getDB();
	$query = "DELETE FROM transaction WHERE accepted_by = '$transaction->accepted_by' AND task_id= $transaction->task_id AND task_owner='$transaction->task_owner'";
	$result = pg_query($dbconn, $query) 
	or die('Post failed: ' . pg_last_error());
	pg_close($dbconn);
	return $result;
}

function isAssignedToTaskDB($transaction){
	$dbconn = getDB();
	$query = "SELECT * FROM transaction WHERE accepted_by = '$transaction->accepted_by' AND task_id= $transaction->task_id AND task_owner='$transaction->task_owner'";
	$result = pg_query($dbconn, $query) 
	or die('Post failed: ' . pg_last_error());
	pg_close($dbconn);
	return pg_num_rows($result)==1;
}

function getAssignedUsersToTaskDB($transaction){
	$dbconn = getDB();
	$query = "SELECT account.email AS email, account.first_name AS first_name, account.last_name AS last_name, account.contact_num AS contact_num FROM transaction, account WHERE transaction.task_owner = '$transaction->task_owner' AND transaction.task_id='$transaction->task_id' AND transaction.accepted_by=account.email";
	$result = pg_query($dbconn, $query) 
	or die('Post failed: ' . pg_last_error());
	$users = array(); 
	while($row = pg_fetch_array($result, null, PGSQL_ASSOC)){
		$user = array("email" => $row["email"], "first_name" => $row["first_name"], "last_name" => $row["last_name"], "contact_num" => $row["contact_num"]);
		array_push($users, $user);
	}
	pg_close($dbconn);
	return $users;
}


?>
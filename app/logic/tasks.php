<?php

include_once('../datalayer/database.php');

function getUserTasks() {
	$login_user = $_SESSION['login_user'];
	return getUserTasksDB($login_user);
}

function postTask($task) {
	$login_user = $_SESSION['login_user'];
	$task->login_user = $login_user; 
	return postTaskDB($task);
}

function getNew(){
	$value =  array(
		'title' => "titleee"
	);
	return $value;
}

?>
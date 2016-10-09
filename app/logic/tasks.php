<?php

$datalayer = include_once('../datalayer/database.php');

function getUserTasks() {
	$login_user = $_SESSION['login_user'];
	return $datalayer.getUserTasksDB($login_user);
}

function postTask($task) {
	$login_user = $_SESSION['login_user'];
	$task->login_user = $login_user; 
	return $datalayer.postTaskDB($task);
}

?>
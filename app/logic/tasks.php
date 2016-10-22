<?php

include_once('../datalayer/tasks.php');
include_once('../datalayer/taskcategory.php');

function getUserTasks() {
	$login_user = $_SESSION['login_user'];
	return getUserTasksDB($login_user);
}

function postTask($task) {
	$login_user = $_SESSION['login_user'];
	$task->login_user = $login_user; 
	$task->status = "New";
	return postTaskDB($task);
}

function deleteTask($taskId){
	return deleteTaskDB($taskId);
}

function editTask($task){
	return editTaskDB($task);
}

function getTask($taskId){
	return getTaskDB($taskId);
}

function findTasksToDo($userEmail){
	return findTasksToDoDB($userEmail);
}

function findTasksToDoJSON($userEmail){
	$tasks= findTasksToDoDB($userEmail);
	$categoriesDB = getCategories();
	$categories= array();
	foreach ($categoriesDB as $category) {
		$element["name"] = $category["name"];
		$element["description"] = "description";
		$categoryElements= array();
		foreach ($tasks as $task) {
			if($task["category"]==$category["name"]){
				$elementTask["name"]= $task["name"];
				$elementTask["address"]= "http://localhost:8080/task/app/public/task/".$task["id"];
				array_push($categoryElements, $elementTask);
			}
		}
		$element["children"] =$categoryElements;
		array_push($categories, $element);
	}
	$arr = array('name' => 'bubble', 'children' => $categories);
	$current =json_encode($arr);
	$file = 'people.json';
	file_put_contents($file, $current);
}




function acceptTask($taskId, $task_owner, $userEmail){
	$transaction->accepted_by = $userEmail; 
	$transaction->task_id = $taskId; 
	$transaction->task_owner = $task_owner; 
	return acceptTaskDB($transaction);
}

function resignFromTask($taskId, $task_owner, $userEmail){
	$transaction->accepted_by = $userEmail; 
	$transaction->task_id = $taskId; 
	$transaction->task_owner = $task_owner; 
	return resignFromTaskDB($transaction);
}

function isAssignedToTask($taskId, $task_owner, $userEmail){
	$transaction->task_id = $taskId; 
	$transaction->task_owner = $task_owner; 
	$transaction->accepted_by = $userEmail;
	return isAssignedToTaskDB($transaction);
}

function getAssignedUsersToTask($taskId, $userEmail){
	$transaction->task_id = $taskId; 
	$transaction->task_owner = $userEmail;
	return getAssignedUsersToTaskDB($transaction);
}

function finalizeTask($taskId, $task_owner, $userEmail){
	$transaction->task_id = $taskId; 
	$transaction->task_owner = $task_owner; 
	$transaction->accepted_by = $userEmail;
	return finalizeTaskDB($transaction);
}

?>


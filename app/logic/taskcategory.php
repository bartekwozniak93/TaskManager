<?php

$datalayer = include_once('../datalayer/database.php');

function getTaskCategories() {
	return $datalayer.getTaskCategoriesDB();
}

function postTaskCategory($taskcat) {
	return $datalayer.postTaskCategoryDB($taskcat);
}

?>
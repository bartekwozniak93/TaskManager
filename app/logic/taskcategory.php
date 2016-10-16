<?php

include_once('../datalayer/database.php');

function getTaskCategories() {
	return getTaskCategoriesDB();
}

function postTaskCategory($taskcat) {
	return postTaskCategoryDB($taskcat);
}

function getCategories(){
	return getCategoriesDB();
}
?>
<?php

include_once('../datalayer/database.php');

function getTaskCategories() {
	return getTaskCategoriesDB();
}

function postTaskCategory($taskcat) {
	return postTaskCategoryDB($taskcat);
}


//ADMIN PORTION
function getCategories(){
	return getCategoriesDB();
}

function deleteCategories($taskcat){
	return deleteCategoriesDB($taskcat);
}
?>
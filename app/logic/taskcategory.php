<?php

include_once('../datalayer/taskcategory.php');

function postCategory($taskcat) {
	return postCategoryDB($taskcat);
}

function getCategories(){
	return getCategoriesDB();
}

function deleteCategory($categoryName){
	return deleteCategoryDB($categoryName);
}

function getCategory($categoryName) {
	return getCategoryDB($categoryName);
}

function editCategory($taskcat, $categoryName){
	return editCategoryDB($taskcat, $categoryName);
}
?>
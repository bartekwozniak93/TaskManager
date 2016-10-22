<?php

include_once('../datalayer/users.php');

function loginUser($user) {
	$result = loginUserDB($user);
	if($result==False)
		$_SESSION['login_user']='';
	else
		$_SESSION['login_user']=$user->email;
	return $result;
}

function getUsers() {
	return getUsersDB();
}

function getUser($userEmail) {
	return getUserDB($userEmail);
}

function postUser($user) {
	$pass=$user->password;
	$confirmPass=$user->confirmPass;
	if(strcmp($pass,$confirmPass)!=0)
		return 'Passwords are not the same!';
	return postUserDB($user);
}

function editUser($userToEdit) {
	return editUserDB($userToEdit);
}
?>
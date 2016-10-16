<?php

include_once('../datalayer/database.php');

function loginUser($user) {
	$result = loginUserDB($user);
	if($result==False)
		return "Login or password are not correct!";
	$_SESSION['login_user']=$user->email;
	return $result;
}

function getUsers() {
	return getUsersDB();
}

function postUser($user) {
	$pass=$user->password;
	$confirmPass=$user->confirmPass;
	if(strcmp($pass,$confirmPass)!=0)
		return 'Passwords are not the same!';
	return postUserDB($user);
}

?>
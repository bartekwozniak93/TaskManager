<?php
// Routes
include('../logic/users.php');
include('../logic/tasks.php');
include('../logic/taskcategory.php');

// Define named route
$app->get('/register', function ($request, $response, $args) {
	$login_user = $_SESSION['login_user'];
	return $this->view->render($response, 'register.html', ['login_user' => $login_user]);
})->setName('register');

$app->post('/register', function ($request, $response, $args) {
	$login_user = $_SESSION['login_user'];
	$form_data = $request->getParsedBody();
	$user = (object) array(
		'firstName' => $form_data['firstName'],
		'lastName' => $form_data['lastName'],
		'email' => $form_data['email'],
		'password' => $form_data['password'],
		'confirmPass' => $form_data['confirmPass'],
		'contactNum' => $form_data['contactNum']  
		);
	$result = postUser($user);

	return $this->view->render($response, 'register.html', [
		'result' => $result, 'login_user' => $login_user
		]);
})->setName('register');

$app->get('/login', function ($request, $response, $args) {
	$login_user = $_SESSION['login_user'];
	return $this->view->render($response, 'login.html', ['login_user' => $login_user]);
})->setName('login');

$app->post('/login', function ($request, $response, $args) {
	$login_user = $_SESSION['login_user'];
	$form_data = $request->getParsedBody();
	$user = (object) array(
		'email' => $form_data['email'],
		'password' => $form_data['password']
		);
	$result = loginUser($user);
	if($result == True){
		return $this->view->render($response, 'main.html', ['login_user' => $login_user]);
	}
	return $this->view->render($response, 'login.html', [
		'result' => $result, 'login_user' => $login_user
		])

	;
})->setName('login');


$app->get('/logout', function ($request, $response, $args) {
	$_SESSION['login_user'] = "";
	return $this->view->render($response, 'login.html', ['login_user' => $login_user]);
})->setName('logout');

$app->get('/main', function ($request, $response, $args) {
	$login_user = $_SESSION['login_user'];
	$result= getUserTasks();
	return $this->view->render($response, 'main.html', ['result' => $result,'login_user' => $login_user]);
})->setName('main');

$app->post('/category', function ($request, $response, $args) {
	$login_user = $_SESSION['login_user'];
	$form_data = $request->getParsedBody();
	$taskcat = (object) array(
		'taskCategory' => $form_data['category']
		);
	$result = postTaskCategory($taskcat);
	return $this->view->render($response, 'taskcategory.html', [
		'result' => $result, 'login_user' => $login_user
		])

	;
})->setName('category');


$app->get('/category', function ($request, $response, $args) {
	$login_user = $_SESSION['login_user'];
	return $this->view->render($response, 'taskcategory.html', ['login_user' => $login_user]);
})->setName('category');

function gete(){
	return $arrayName = array('title' => "titlefadfas" );
}


$app->get('/new', function ($request, $response, $args) {
	$login_user = $_SESSION['login_user'];
	$myarray=getCategories();
	return $this->view->render($response, 'new.html', ['login_user' => $login_user, 'myarray' => $myarray]);
})->setName('new');



$app->post('/task', function ($request, $response, $args) {
	$login_user = $_SESSION['login_user'];
	$form_data = $request->getParsedBody();
	$task = (object) array(
		'task_name' => $form_data['task_name'],
		'task_description' => $form_data['task_description'],
		'task_type' => $form_data['task_type'],
		'amount' => $form_data['amount']
		);
	$result = postTask($task);
	return $this->view->render($response, 'task.html', [
		'result' => $result, 'login_user' => $login_user
		])

	;
})->setName('task');


$app->get('/task', function ($request, $response, $args) {
	$login_user = $_SESSION['login_user'];
	return $this->view->render($response, 'task.html', ['login_user' => $login_user]);
})->setName('task');
?>



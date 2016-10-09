<?php
// Routes

$users = include('../logic/users.php');
$tasks = include('../logic/tasks.php');
$taskcategory = include('../logic/taskcategory.php');

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
	$result = $users.postUser($user);

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
	$result = $users.loginUser($user);
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
	$result= $tasks.getUserTasks();
	return $this->view->render($response, 'main.html', ['result' => $result,'login_user' => $login_user]);
})->setName('main');

$app->post('/category', function ($request, $response, $args) {
	$login_user = $_SESSION['login_user'];
	$form_data = $request->getParsedBody();
	$taskcat = (object) array(
		'taskCategory' => $form_data['category']
		);
	$result = $taskcategory.postTaskCategory($taskcat);
	return $this->view->render($response, 'taskcategory.html', [
		'result' => $result, 'login_user' => $login_user
		])

	;
})->setName('category');


$app->get('/category', function ($request, $response, $args) {
	$login_user = $_SESSION['login_user'];
	return $this->view->render($response, 'taskcategory.html', ['login_user' => $login_user]);
})->setName('category');


$app->get('/new', function ($request, $response, $args) {
	$login_user = $_SESSION['login_user'];
	$value = "MY VALUE";
	return $this->view->render($response, 'new.html', ['login_user' => $login_user, 'value' => $value]);
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
	$result = $tasks.postTask($task);
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



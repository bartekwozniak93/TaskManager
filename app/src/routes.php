<?php
// Routes
include_once('../logic/users.php');
include_once('../logic/tasks.php');
include_once('../logic/taskcategory.php');

/*
USER
*/
//REGISTER
$app->get('/register', function ($request, $response, $args) {
	if($_SESSION['login_user']!=''){
		$tasks = getUserTasks();
		return $this->view->render($response, 'task//mytasks.html', ['result' => $result,'login_user' => $_SESSION['login_user'], 'tasks'=> $tasks]);
	}
	return $this->view->render($response, 'user//register.html', ['login_user' => $_SESSION['login_user']]);
})->setName('register');

$app->post('/register', function ($request, $response, $args) {
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
	if($result == "True"){
		$result="";
		$_SESSION['login_user']=$user->email;
		$tasks = getUserTasks();
		return $this->view->render($response, 'task//mytasks.html', ['result' => $result,'login_user' => $_SESSION['login_user'], 'tasks'=> $tasks]);
	}
	else{
		return $this->view->render($response, 'user//register.html', ['result' => $result,'login_user' => $_SESSION['login_user']]);
	}
})->setName('register');

//LOGIN
$app->get('/login', function ($request, $response, $args) {
	if($_SESSION['login_user']!=''){
		$tasks = getUserTasks();
		return $this->view->render($response, 'task//mytasks.html', ['result' => $result,'login_user' => $_SESSION['login_user'], 'tasks'=> $tasks]);
	}
	return $this->view->render($response, 'user//login.html', ['login_user' => $_SESSION['login_user']]);
})->setName('login');

$app->post('/login', function ($request, $response, $args) {
	$form_data = $request->getParsedBody();
	$user = (object) array(
		'email' => $form_data['email'],
		'password' => $form_data['password']
		);
	$result = loginUser($user);
	if($result == True){
		$tasks = getUserTasks();
		$result="";
		return $this->view->render($response, 'task//mytasks.html', ['result' => $result,'login_user' => $_SESSION['login_user'], 'tasks'=> $tasks]);
	}
	else{
		$result= "Login or password incorrect!";
		return $this->view->render($response, 'user//login.html', ['result' => $result, 'login_user' => $_SESSION['login_user']]);
	}
})->setName('login');

//LOGOUT
$app->get('/logout', function ($request, $response, $args) {
	$_SESSION['login_user']='';
	return $this->view->render($response, 'user//login.html', ['login_user' => '']);
})->setName('logout');

//All USERS
$app->get('/allusers', function ($request, $response, $args) {
	if($_SESSION['login_user']==''){
		return $this->view->render($response, 'user//login.html', ['login_user' => $_SESSION['login_user']]);
	}
	$result= "";
	$users = getUsers();
	return $this->view->render($response, 'user//allusers.html', ['result' => $result,'login_user' => $_SESSION['login_user'], 'users'=> $users]);
})->setName('allusers');

//EDIT USER
$app->get('/user/edit/{email}', function ($request, $response, $args) {
	if($_SESSION['login_user']==''){
		return $this->view->render($response, 'user//login.html', ['login_user' => $_SESSION['login_user']]);
	}
	$route = $request->getAttribute('route');
	$userEmail=$route->getArgument('email');
	$user = getUser($userEmail);
	$result="";
	return $this->view->render($response, 'user//edituser.html', ['user' => $user, 'result'=>$result,'login_user' => $_SESSION['login_user']]);
})->setName('edituser');

$app->post('/user/edit/{email}', function ($request, $response, $args) {
	$form_data = $request->getParsedBody();
	$userToEdit = (object) array(
		'firstName' => $form_data['firstName'],
		'lastName' => $form_data['lastName'],
		'email' => $form_data['email'],
		'contactNum' => $form_data['contactNum']
		);
	$user = editUser($userToEdit);
	$result = "User successfully edited.";
	$users = getUsers();
	return $this->view->render($response, 'user//allusers.html', ['result' => $result,'login_user' => $_SESSION['login_user'], 'users'=> $users]);
})->setName('edituser');




/*
TASKS
*/

//MY TASKS
$app->get('/mytasks', function ($request, $response, $args) {
	if($_SESSION['login_user']==''){
		return $this->view->render($response, 'user//login.html', ['login_user' => $_SESSION['login_user']]);
	}
	$result= "";
	$tasks = getUserTasks();
	return $this->view->render($response, 'task//mytasks.html', ['result' => $result,'login_user' => $_SESSION['login_user'], 'tasks'=> $tasks]);
})->setName('mytasks');

//NEW TASK
$app->get('/newtask', function ($request, $response, $args) {
	if($_SESSION['login_user']==''){
		return $this->view->render($response, 'user//login.html', ['login_user' => $_SESSION['login_user']]);
	}
	$categories = getCategories();
	return $this->view->render($response, 'task//newtask.html', ['login_user' => $_SESSION['login_user'], 'categories' => $categories,]);
})->setName('newtask');

$app->post('/newtask', function ($request, $response, $args) {
	$form_data = $request->getParsedBody();
	$task = (object) array(
		'name' => $form_data['name'],
		'description' => $form_data['description'],
		'amount' => $form_data['amount'],
		'category' => $form_data['category']
		);
	postTask($task);
	$result = "Task successfully added.";
	$tasks = getUserTasks();
	return $this->view->render($response, 'task//mytasks.html', ['result' => $result,'login_user' => $_SESSION['login_user'], 'tasks'=> $tasks]);
})->setName('newtask');

//DELETE TASK
$app->get('/task/delete/{id}', function ($request, $response, $args) {
	if($_SESSION['login_user']==''){
		return $this->view->render($response, 'task//login.html', ['login_user' => $_SESSION['login_user']]);
	}
	$route = $request->getAttribute('route');
	$taskId=$route->getArgument('id');
	$task = getTask($taskId);
	$result="";
	return $this->view->render($response, 'task//deletetask.html', ['task' => $task, 'login_user' => $_SESSION['login_user'],'result'=>$result]);
})->setName('deletetask');

$app->post('/task/delete/{id}', function ($request, $response, $args) {
	$route = $request->getAttribute('route');
	$taskId=$route->getArgument('id');
	$task =deleteTask($taskId);
	$result = "Task successfully deleted.";
	$tasks = getUserTasks();
	return $this->view->render($response, 'task//mytasks.html', ['result' => $result,'login_user' => $_SESSION['login_user'], 'tasks'=> $tasks]);
})->setName('deletetask');

//EDIT TASK
$app->get('/task/edit/{id}', function ($request, $response, $args) {
	if($_SESSION['login_user']==''){
		return $this->view->render($response, 'user//login.html', ['login_user' => $_SESSION['login_user']]);
	}
	$route = $request->getAttribute('route');
	$taskId=$route->getArgument('id');
	$task = getTask($taskId);
	$result="";
	$categories = getCategories();
	return $this->view->render($response, 'task//edittask.html', ['task' => $task, 'result'=>$result,'login_user' => $_SESSION['login_user'], 'categories'=>$categories]);
})->setName('edittask');

$app->post('/task/edit/{id}', function ($request, $response, $args) {
	$form_data = $request->getParsedBody();
	$taskToEdit = (object) array(
		'id' => $form_data['id'],	
		'name' => $form_data['name'],
		'description'=> $form_data['description'],
		'category'=> $form_data['category'],
		'amount'=> $form_data['amount']
		);
	$task = editTask($taskToEdit);
	$result = "Task successfully edited.";
	$tasks = getUserTasks();
	return $this->view->render($response, 'task//mytasks.html', ['login_user' => $_SESSION['login_user'],'result' => $result, 'tasks'=> $tasks]);
})->setName('edittask');

//CHOOSE USER TO DO YOUR TASK
$app->get('/task/chooseuser/{id}', function ($request, $response, $args) {
	if($_SESSION['login_user']==''){
		return $this->view->render($response, 'user//login.html', ['login_user' => $_SESSION['login_user']]);
	}
	$route = $request->getAttribute('route');
	$taskId=$route->getArgument('id');
	$task = getTask($taskId);
	$users= getAssignedUsersToTask($taskId, $_SESSION['login_user']);
	$result="";
	return $this->view->render($response, 'task//chooseuser.html', ['task' => $task, 'result'=>$result,'login_user' => $_SESSION['login_user'],'users'=>$users]);
})->setName('chooseuser');

$app->post('/task/chooseuser/{id}', function ($request, $response, $args) {
	$form_data = $request->getParsedBody();
	$route = $request->getAttribute('route');
	$taskId=$route->getArgument('id');
	finalizeTask($taskId, $_SESSION['login_user'], $form_data["selected_user"]);
	$result = "Task successfully finalized.";
	$tasks = getUserTasks();
	return $this->view->render($response, 'task//mytasks.html', ['login_user' => $_SESSION['login_user'],'result' => $result, 'tasks'=> $tasks]);
})->setName('chooseuser');

//FIND TASK TO DO
$app->get('/findtask', function ($request, $response, $args) {
	if($_SESSION['login_user']==''){
		return $this->view->render($response, 'user//login.html', ['login_user' => $_SESSION['login_user']]);
	}
	$result= "";
	$userEmail = $_SESSION['login_user']; 
	$tasks = findTasksToDo($userEmail);
	return $this->view->render($response, 'task//findtask.html', ['result' => $result,'login_user' => $_SESSION['login_user'], 'tasks'=> $tasks]);
})->setName('findtask');

$app->get('/findtask_interactive', function ($request, $response, $args) {
	$userEmail = $_SESSION['login_user']; 
	$tasks = findTasksToDoJSON($userEmail);
	if($_SESSION['login_user']==''){
		return $this->view->render($response, 'user//login.html', ['login_user' => $_SESSION['login_user']]);
	}
	$result= "";
	$userEmail = $_SESSION['login_user']; 
	$tasks = findTasksToDo($userEmail);
	return $this->view->render($response, 'task//findtask_interactive.html', ['result' => $result,'login_user' => $_SESSION['login_user'], 'tasks'=> $tasks]);
})->setName('findtask_interactive');

//VIEW TASK TO DO
$app->get('/task/{id}', function ($request, $response, $args) {
	if($_SESSION['login_user']==''){
		return $this->view->render($response, 'user//login.html', ['login_user' => $_SESSION['login_user']]);
	}
	$route = $request->getAttribute('route');
	$taskId=$route->getArgument('id');
	$userEmail=$_SESSION['login_user'];
	$task = getTask($taskId);
	$result="";
	$isAssigned=isAssignedToTask($taskId, $task['created_by'], $userEmail);
	return $this->view->render($response, 'task//viewtask.html', ['task' => $task, 'result'=>$result,'login_user' => $_SESSION['login_user'],'isAssigned'=>$isAssigned]);
})->setName('viewtask');

//ACCEPT TASK TO DO
$app->post('/accepttask/{id}', function ($request, $response, $args) {
	$route = $request->getAttribute('route');
	$taskId=$route->getArgument('id');
	$userEmail = $_SESSION['login_user']; 
	$task_owner=$request->getParsedBody()['created_by'];
	acceptTask($taskId, $task_owner, $userEmail);
	$task = getTask($taskId);
	$result="";
	$isAssigned=isAssignedToTask($taskId, $task['created_by'], $userEmail);
	return $this->view->render($response, 'task//viewtask.html', ['task' => $task, 'result'=>$result,'login_user' => $_SESSION['login_user'],'isAssigned'=>$isAssigned]);
})->setName('accepttask');

//RESIGN FROM DOING TASK
$app->post('/resignfromtask/{id}', function ($request, $response, $args) {
	$route = $request->getAttribute('route');
	$taskId=$route->getArgument('id');
	$userEmail = $_SESSION['login_user']; 
	$task_owner=$request->getParsedBody()['created_by'];
	resignFromTask($taskId, $task_owner, $userEmail);
	$task = getTask($taskId);
	$result="";
	$isAssigned=isAssignedToTask($taskId, $task['created_by'], $userEmail);
	return $this->view->render($response, 'task//viewtask.html', ['task' => $task, 'result'=>$result,'login_user' => $_SESSION['login_user'],'isAssigned'=>$isAssigned]);
})->setName('resignfromtask');


/*
CATEGORY
*/

//NEW CATEGORY
$app->get('/newcategory', function ($request, $response, $args) {
	if($_SESSION['login_user']==''){
		return $this->view->render($response, 'login.html', ['login_user' => $_SESSION['login_user']]);
	}
	return $this->view->render($response, 'category//newcategory.html', ['login_user' => $_SESSION['login_user']]);
})->setName('newcategory');

$app->post('/newcategory', function ($request, $response, $args) {
	$form_data = $request->getParsedBody();
	$taskcat = (object) array(
		'name' => $form_data['name']
		);
	postCategory($taskcat);
	$result = "Category successfully added.";
	$categories = getCategories();
	return $this->view->render($response, 'category//categories.html', ['login_user' => $_SESSION['login_user'],'categories' => $categories]);
})->setName('newcategory');


//CATEGORIES
$app->get('/categories', function ($request, $response, $args) {
	if($_SESSION['login_user']==''){
		return $this->view->render($response, 'user//login.html', ['login_user' => $_SESSION['login_user']]);
	}
	$categories = getCategories();
	return $this->view->render($response, 'category//categories.html', ['login_user' => $_SESSION['login_user'],'categories' => $categories]);
})->setName('categories');

//EDIT CATEGORY
$app->get('/categories/edit/{name}', function ($request, $response, $args) {
	if($_SESSION['login_user']==''){
		return $this->view->render($response, 'user//login.html', ['login_user' => $_SESSION['login_user']]);
	}
	$route = $request->getAttribute('route');
	$categoryName=$route->getArgument('name');
	$category = getCategory($categoryName);
	$result="";
	return $this->view->render($response, 'category//editcategory.html', ['login_user' => $_SESSION['login_user'],'category' => $category, 'result'=>$result]);
})->setName('editcategory');


$app->post('/categories/edit/{name}', function ($request, $response, $args) {
	$form_data = $request->getParsedBody();
	$route = $request->getAttribute('route');
	$categoryName=$route->getArgument('name');
	$taskcat = (object) array(
		'name' => $form_data['name']
		);
	$result =editCategory($taskcat, $categoryName);
	$categories = getCategories();
	return $this->view->render($response, 'category//categories.html', ['login_user' => $_SESSION['login_user'],'categories' => $categories, 'result'=>$result]);
})->setName('editcategory');


//DELETE CATEGORY
$app->get('/categories/delete/{name}', function ($request, $response, $args) {
	if($_SESSION['login_user']==''){
		return $this->view->render($response, 'user//login.html', ['login_user' => $_SESSION['login_user']]);
	}
	$route = $request->getAttribute('route');
	$categoryName=$route->getArgument('name');
	$category = getCategory($categoryName);
	$result="";
	return $this->view->render($response, 'category//deletecategory.html', ['login_user' => $_SESSION['login_user'],'category' => $category, 'result'=>$result]);
})->setName('deletecategory');


$app->post('/categories/delete/{name}', function ($request, $response, $args) {
	$form_data = $request->getParsedBody();
	$route = $request->getAttribute('route');
	$categoryName=$route->getArgument('name');
	$result =deleteCategory($categoryName);
	$categories = getCategories();
	return $this->view->render($response, 'category//categories.html', ['login_user' => $_SESSION['login_user'],'categories' => $categories, 'result'=>$result]);
})->setName('deletecategory');




?>



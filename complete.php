<?php 
require_once 'core/init.php';
$user = new User();
$data = $user->data();
if($user->hasPermission('banned')){
	 exit("you are banned");
}
if(!$user->isLoggedIn()){
	Redirect::to('index.php');
	exit("you are not logged in");
}

if(!$goalId = Input::get('post')){
	Session::flash('userMessage', 'noting to update!');
	Redirect::to('index.php');
	exit("Nothing to update!");
 }elseif(Token::check(Input::get('token'))) { 
	$goals = DB::getInstance()->get('goals', array('id', '=', $goalId));
	$results = $goals->results();
	foreach ($results as $goal) {
		if($data->id !== $goal->userID){
			Session::flash('userMessage', 'You cannot access that goal!');
			Redirect::to('index.php');
			exit("Sorry you have no permission to update that file");
		}
	}

	if(DB::getInstance()->query("UPDATE goals SET completed = 1 WHERE id = ?", array($goalId))) {
	}else{
		error_log("Could not update goal with the id = $goalId",0);
		Session::flash('userMessage', 'Sorry we had a problem!');
		Redirect::to('index.php');
		exit("Sorry we had a problem!");
	}
}else{
 	Session::flash('userMessage', 'Sorry token invaild');
 	Redirect::to('index.php');
 	exit("Sorry token invaild!");
}
Session::flash('userMessage', $data->username.' we where able to update that goal!');
Redirect::to('index.php');
exit("we where able to update that goal!");

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

if($goalId = Input::get('post')){
	$option = "one";
 }elseif($goalIds = Input::get('chk_group')){
 	$option = "many";
 }else{
 	Session::flash('userMessage', 'noting to delete!');
	// Redirect::to('index.php');
	exit("Nothing to delete!");
 }
if(Token::check(Input::get('token'))) { 

	if($option == "many"){
		for ($i=0; $i<count($goalIds); $i++) {
			$goals = DB::getInstance()->get('goals', array('id', '=', $goalIds[$i]));
			$results = $goals->results();
			foreach ($results as $goal) {
				if($data->id !== $goal->userID){
					Session::flash('userMessage', 'You cannot access that goal!');
					Redirect::to('index.php');
					exit("Sorry you have no permission to delete that file");
				}
			}
			if(DB::getInstance()->delete('goals', array('id', '=', $goalIds[$i]))){	
			}else{
				error_log("Could not delete goal with the id = $goalId",0);
				Session::flash('userMessage', 'Sorry we had a problem!');
				Redirect::to('index.php');
				exit("Sorry we had a problem!");
			}
	    }
	}elseif($option == "one"){
		echo "it is one ";
		$goals = DB::getInstance()->get('goals', array('id', '=', $goalId));
		$results = $goals->results();
		foreach ($results as $goal) {
			if($data->id !== $goal->userID){
				Session::flash('userMessage', 'You cannot access that goal!');
				Redirect::to('index.php#goals');
				exit("Sorry you have no permission to delete that file");
			}
		}

		if(DB::getInstance()->delete('goals', array('id', '=', $goalId))) {
		}else{
			error_log("Could not delete goal with the id = $goalId",0);
			Session::flash('userMessage', 'Sorry we had a problem!');
			Redirect::to('index.php');
			exit("Sorry we had a problem!");
		}
	}
}else{
 	Session::flash('userMessage', 'Sorry token invaild');
 	Redirect::to('index.php');
 	exit("Sorry token invaild!");
}
Session::flash('userMessage', $data->username.' we where able to delete that goal!');
Redirect::to('index.php');
exit("we where able to delete that goal!");

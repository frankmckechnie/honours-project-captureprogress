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

if(!$answer = Input::get('answer')){
	Session::flash('userMessage', 'noting to delete!');
	Redirect::to('index.php');
	exit("Nothing to delete!");
 }elseif(Token::check(Input::get('token'))) { 

	$images = DB::getInstance()->get('images', array('userID', '=', $data->id));
	$results = $images->results();

	$dir = "images/photos/";	

	foreach ($results as $image ) {
		if(is_file($dir.$image->filePath)){
			if(unlink($dir.$image->filePath)){
				unlink($dir."small-".$image->filePath);
			}else{
				error_log($image->filePath.'image not deleted',0);
			}
		}
		error_log($image->filePath.'image not deleted',0);
	}

	if($data->picture == "default-profile-pic.png"){
	}else if(is_file("images/profile/".$data->picture)){
		unlink("images/profile/".$data->picture);
		unlink("images/profile/small-".$data->picture);
	}
	if(DB::getInstance()->delete('users', array('id', '=', $data->id))) {
		$user->logout();
	}else{
		Session::flash('userMessage', 'Sorry something went wrong');
 		Redirect::to('index.php');
 		exit("Sorry something went wrong");
	}
}else{
 	Session::flash('userMessage', 'Sorry token invaild');
 	Redirect::to('index.php');
 	exit("Sorry token invaild!");
}
Session::flash('home', ' Your account has been deleted');
Redirect::to('index.php');
exit("Your account has been deleted");
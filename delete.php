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

if(!$postId = Input::get('post')){
	Session::flash('userMessage', 'noting to delete!');
	Redirect::to('index.php');
	exit("Nothing to delete!");
 }elseif(Token::check(Input::get('token'))) { 
	$posts = DB::getInstance()->get('posts', array('id', '=', $postId));
	$results = $posts->results();

	$images = DB::getInstance()->get('images', array('postID', '=', $postId));
	$imgResults = $images->results();

	$dir = "images/photos/";	
	foreach ($results as $post) {
		if($data->id !== $post->userID){
			Session::flash('userMessage', 'You cannot access that post!');
			Redirect::to('index.php');
			exit("Sorry you have no permission to delete that file");
		}
	}

	if(DB::getInstance()->delete('posts', array('id', '=', $postId))) {
		foreach ($imgResults as $image ) {
			if(is_file($dir.$image->filePath)){
				if(unlink($dir.$image->filePath)){
					unlink($dir."small-".$image->filePath);
				}else{
					error_log($image->filePath.'image not deleted',0);
				}
			}
			error_log($image->filePath.'image not deleted',0);
		}
	}else{
		error_log("Could not delete post with the id = $postId",0);
		Session::flash('userMessage', 'Sorry we had a problem!');
		Redirect::to('index.php');
		exit("Sorry we had a problem!");
	}
}else{
 	Session::flash('userMessage', 'Sorry token invaild');
 	Redirect::to('index.php');
 	exit("Sorry token invaild!");
}
Session::flash('userMessage', $data->username.' we where able to delete that post!');
Redirect::to('index.php');
exit("we where able to delete that post!");
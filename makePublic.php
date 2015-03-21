<?php
require_once 'core/init.php';
$user = new User();
$data = $user->data();

if(!$user->isLoggedIn()){
	Redirect::to('index.php');
	exit("you are not logged in");
}

if(!$postId = Input::get('post')){
	Session::flash('userMessage', 'noting to change!');
	Redirect::to('index.php');
	exit("Nothing to change!");
 }elseif (Token::check(Input::get('token'))) { 
	$posts = DB::getInstance()->get('posts', array('id', '=', $postId));
	$results = $posts->results();

	foreach ($results as $post) {
		if($data->id !== $post->userID){
			Session::flash('userMessage', 'You cannot change that post!');
			Redirect::to('index.php');
			exit("Sorry you have no permission to delete that file");
		}
	}

	if(!DB::getInstance()->update('posts',$postId,array('public' => 1))){
		Session::flash('userMessage', 'Sorry we could not change that');
		Redirect::to('index.php');
		exit("Sorry we could not change that post!");
	}
}else{
 	Session::flash('userMessage', 'Sorry token invaild');
 	Redirect::to('index.php');
 	exit("Sorry token invaild!");
}
Session::flash('userMessage', $data->username.' we where able to change that post!');
Redirect::to('index.php');
exit("we where able to change that post!");
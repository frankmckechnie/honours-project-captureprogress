<?php 
require_once 'core/init.php';

$user = new User();
$data = $user->data();

if(!$user->isLoggedIn()){
	Redirect::to('index.php');
	exit("you are not logged in");
}

$voted = false;

if(!$postId = Input::get('post')){
	exit("Nothing to vote!");
}else{

	$like = DB::getInstance()->query("SELECT * FROM likes WHERE postID = ? AND userID = ?", array($postId, $data->id)); 
	
	if($like->count() != 0){ // returns 5 
		$voted = true;
	}

	if($voted == true){ // they want to - vote
		if(DB::getInstance()->query("DELETE FROM likes WHERE postID = ? AND userID = ?", array($postId, $data->id))){
			DB::getInstance()->query("UPDATE posts SET like_count = like_count - 1 WHERE id = ?", array($postId));
			exit("DOWN: voted");
		};

	}

	if($voted == false){ // they haven't voted 
		if(DB::getInstance()->query("UPDATE posts SET like_count = like_count + 1 WHERE id = ?", array($postId))){
			try {
				$user->create('like','likes',array(
					'postID' => $postId,
					'userID' => $data->id
				));
			} catch(Exception $e){
				//delete vote
				die($e->getMessage());
			}
			echo "YES: count Updated";
		}else{
			echo "Sorry no upvote";
		}
	}

}
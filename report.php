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

$reported = false;

if(!$imageID = Input::get('imageID')){
	exit("Nothing to report!");
}else{
	$report = DB::getInstance()->query("SELECT * FROM reports WHERE imageID = ? AND userID = ?", array($imageID, $data->id)); 
	
	if($report->count() != 0){ // returns 5 
		$reported = true;
	}

	if($reported == true){ // they want to - vote
		exit("NO: you can only report once");
	}

	if($reported == false){ // they haven't voted 
		if(DB::getInstance()->query("UPDATE images SET report_count = report_count + 1 WHERE id = ?", array($imageID))){
			try {
				$user->create('report','reports',array(
					'imageID' => $imageID,
					'userID' => $data->id
				));
			} catch(Exception $e){
				//delete vote
				die($e->getMessage());
			}
			$images = DB::getInstance()->get('images', array('id', '=', $imageID))->results();
			echo $images[0]->report_count;
			if($images[0]->report_count > 5){
				$message = '
				Image '.$imageID.' has been reported more than 5 times the user who is related to this image has the id of '.$images[0]->userID.' 
				
				best check the post out http://webfrankly.com/captureprogress/viewPost.php?post='.$images[0]->postID.'
				';
				mail('rkcfm@hotmail.co.uk', 'reported image:CaptureProgress', $message);
			}
			echo "YES: report Submitted";
		}else{
			echo "Sorry no upvote";
		}
	}

}
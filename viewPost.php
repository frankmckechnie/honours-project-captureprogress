<?php 
require_once 'core/init.php';
$user = new User();
$data = $user->data();
if(!$user->isLoggedIn()){
	Redirect::to('index.php');
	exit("Sorry we had a problem!");
}	
include 'includes/header.php';
include 'includes/nav.php';
if(!$postId = Input::get('post')){
	Session::flash('postProb', 'There was no images!');
	Redirect::to('post.php');
	exit("Sorry we had a problem!");
}else{
	$images = DB::getInstance()->get('images', array('postID', '=', $postId));
	$imgResults = $images->results();

	if(!$images->count()){
		Session::flash('userMessage', 'There was no images!');
		Redirect::to('index.php');
		echo "their is no images!";
		exit("Sorry we had a problem!");
	}

	$posts = DB::getInstance()->get('posts', array('id', '=', $postId));
	$results = $posts->results();
	foreach ($results as $post) {
		if($post->public == 0){
			if($data->id !== $post->userID){
				Session::flash('home', 'You cannot access that post!');
				Redirect::to('posts.php');
				exit("You cannot access that post!");
			}
		}
?>
	<h1><?php echo escape($post->title); ?></h1>
	<h2>Post By <a href="profile.php?user=<?php echo escape($post->username); ?>"><?php echo escape($post->username); ?></a> (<?php echo escape($post->gender); ?>)</h2>
	<P><?php echo escape($post->timeStamp); ?></P>
	<p>Description: <?php echo escape($post->description); ?></p>
<?php

}
		foreach ($imgResults as $image ) {
?>
		<strong><?php echo $image->addedTitle; ?></strong><br>
		<img  src="images/photos/<?php echo $image->filePath; ?>"><br>
<?php
	}

}
?>
</body>
</html>
<?php

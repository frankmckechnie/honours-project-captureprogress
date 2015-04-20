<?php 
require_once 'core/init.php';
$user = new User();
$data = $user->data();
if($user->hasPermission('banned')){
	 exit("you are banned");
}
if(!$user->isLoggedIn()){
	Redirect::to('index.php');
		exit('
		<div id="container">
		<div class="form-main">
		<div class="red loginBar" > You need to login in or register</div>
		<div class="form-div">
		<a href="register.php" class="trans button grey"> Register</a>
		<a href="login.php" class="trans mLeft button green greenB" type="submit">Log In</a>
		</div>
		</div>
		</div>');
}	
include 'includes/header.php';
echo "<div class='wrap'>";
include 'includes/nav.php';
echo "<div style='display:none' class='warning red'></div>";
if(!$postId = Input::get('post')){
	Session::flash('postProb', 'There was no images!');
	Redirect::to('posts.php');
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
?>
	
<div class="content">
	<div class="contentCenter">
<?php
	foreach ($results as $post) {
		if($post->public == 0){
			if($data->id !== $post->userID){
				Session::flash('home', 'You cannot access that post!');
				Redirect::to('posts.php');
				exit("You cannot access that post!");
			}
		}
		$like = DB::getInstance()->query("SELECT * FROM likes WHERE postID = ? AND userID = ?", array($post->id, $user->data()->id))->results(); 

		if($like[0]->userID != $user->data()->id){
			$vote = "nun";
			$image = "background-image: url('css/icons/up.png'); background-position: top;";
		}else{
			$vote = "down";
			$image = "background-image: url('css/icons/down.png'); background-position: bottom;";
		}

?>	
		<div class="post-main" data-id="<?php echo escape($postId); ?>">
			<div class="left-bar-colum"><div class="post-bar green"><h1><?php echo escape($post->title); ?></h1></div></div>
			<div class="unVote vote">
				<div class="darkBlue upVote textGreen" style="<?php echo $image; ?>" data-vote="<?php echo $vote; ?>" data-action="upVote.php" data-like="<?php echo escape($post->like_count);  ?>" data-post="<?php echo escape($post->id); ?>">
					<span class="textGreen"><?php echo escape($post->like_count);  ?></span> 
				</div>  
			</div>
			<div class="post-sub">
				<p class="formLable"><?php echo escape($post->description); ?></p>
				<div class="space"></div>
						<div class="overide timeHolder">
					<img style="margin-right:5px;" src="<?php echo escape($post->gender); ?>">By <a class="none blue" href="profile.php?user=<?php echo escape($post->username); ?>"><?php echo escape($post->username); ?></a>
					<span class="textGreen">Submitted On</span>
					<span style="font-size: 10pt; color:#58585d;"><?php echo escape($post->timeStamp); ?></span>
				</div>
			</div>
		</div>
		
<?php

}
		foreach ($imgResults as $image ) {
?>
		<div class="post-image-holder">
			<img  src="images/photos/<?php echo $image->filePath; ?>"><br>
			<div class="overflow bar darkBlue">
				<h1><?php echo $image->addedTitle; ?></h1>
				<div class="report trans barButton red " data-id="<?php echo $image->id; ?>">Report</div>
			</div>
		</div>
<?php
	}

}
?>
	</div>
</div>

 
<div class="comment-contain">
<div class="bar lightBlue">
	<div class="comment-center"><h1>Have something to say?</h1></div>
</div>
	<div class="comment-center">
		<div class="form-div">
				<textarea class="summary feedback-input"  id="newComment" name="comment" id="summary" cols="30" rows="5"></textarea>
			    <input type="submit" id="commentRequest" class="longButton green greenB" value="Post Comment" />
		</div>
				<div id="addComment">

<?php
		$comments = DB::getInstance()->query("SELECT * FROM comments WHERE postID = ? LIMIT 5 ", array(Input::get('post')))->results(); 
			
		foreach ($comments	as $comment) {

?>
		<div class="comment-content">
			<div class="commentImage">
				<img onError="this.src = 'images/profile/small-default-profile-pic.png'" src="images/profile/small-<?php echo escape($comment->userId); ?>-<?php echo escape($comment->username); ?>.jpg">
			</div>
			<div class="comment-right">
				<div class="CommentHolder">
					<?php echo escape($comment->comment); ?>
				</div>	

				<div class="overide timeHolder">
					<img style="margin-right:5px;" src="<?php echo escape($comment->gender); ?>">By <a class="none blue" href="profile.php?user=<?php echo escape($comment->username); ?>"><?php echo escape($comment->username); ?></a>
					<span class="textGreen">Submitted On</span>
					<span style="font-size: 10pt; color:#58585d;"><?php echo escape($comment->timeStamp); ?></span>
				</div>
			</div>
		</div>
<?php
	}
?>
	</div>
	<div class="space"></div>
	<div id="loadMore" data-row="5" class="longButton green greenB">Load More</div>
	</div>
</div>


</div>
</body>
</html>
<?php

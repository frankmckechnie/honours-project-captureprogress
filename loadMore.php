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

if(!$offset = Input::get('count')){
	exit("ERROR Sorry sothing went wrong!");
}else if(!$postID = Input::get('post')){
	exit("ERROR Sorry sothing went wrong!");
}
$comments = DB::getInstance()->query("SELECT * FROM comments WHERE postID = ? LIMIT ?,5", array($postID,$offset) ); 

if($comments->count() < 5){
	echo "<div style='display:none'>LASTROW</div>";
}
$comments = $comments->results();



foreach ($comments as $comment) {
?>
	<div class="comment-content">
		<div class="commentImage">
			<img onError="this.src = 'images/profile/small-default-profile-pic.png'" src="images/profile/small-<?php echo escape($comment->userId); ?>-<?php echo escape($comment->username); ?>.jpg">
		</div>
		<div class="comment-right">
			<div class="CommentHolder">
				<?php echo escape($comment->comment); ?>
			</div>	

			<div class="timeHolder">
				<img style="margin-right:5px;" src="<?php echo escape($comment->gender); ?>">By <a class="none blue" href="profile.php?user=<?php echo escape($comment->username); ?>"><?php echo escape($comment->username); ?></a>
				<span class="textGreen">Submitted On</span>
				<span style="font-size: 10pt; color:#58585d;"><?php echo escape($comment->timeStamp); ?></span>
			</div>
		</div>
	</div>
<?php
}




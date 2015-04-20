<?php
require_once 'core/init.php';
$user = new User();

if($user->hasPermission('banned')){
	 exit("you are banned");
}
if(!$user->isLoggedIn()){
	Redirect::to('index.php');
	exit("you are not logged in");
}

if(!$order = Input::get('order')){
	exit("Nothing to vote!");
}else{

	if($order == "id"){
		$posts = DB::getInstance()->query("SELECT * FROM posts WHERE public = ? ORDER BY timeStamp ASC LIMIT 20", array(1)); 
	}else if($order == "timeStamp"){
		$posts = DB::getInstance()->query("SELECT * FROM posts WHERE public = ? ORDER BY timeStamp DESC LIMIT 20", array(1)); 
	}else {
		$posts = DB::getInstance()->query("SELECT * FROM posts WHERE public = ? ORDER BY like_count DESC LIMIT 20", array(1)); 
	}

	if (!$posts->count()){
		echo '<h3 class="textGreen">You have no posts!</h3>';
	}else{
		foreach ($posts->results() as $post) {
			$like = DB::getInstance()->query("SELECT * FROM likes WHERE postID = ? AND userID = ?", array($post->id, $user->data()->id))->results(); 

			if($like[0]->userID != $user->data()->id){
				$vote = "nun";
				$image = "background-image: url('css/icons/up.png'); background-position: top;";
			}else{
				$vote = "down";
				$image = "background-image: url('css/icons/down.png'); background-position: bottom;";
			}
			$images = DB::getInstance()->get('images', array('postID', '=', $post->id))->results();		

	?>
	<a class="none" href="viewPost.php?post=<?php echo escape($post->id); ?>">				
		<div class="list extraWidth">
			<div class="listImage">
				<img  src="images/photos/small-<?php echo escape($images[0]->filePath); ?>"/>
			</div>

			<div class="titleHolder">
				<?php echo escape($post->title); ?>
			</div>		
			<div class="extraLine line"></div>
			<div class="timeHolder">
				<img style="margin-right:5px;" src="<?php echo escape($post->gender); ?>">By <a class="none blue" href="profile.php?user=<?php echo escape($post->username); ?>"><?php echo escape($post->username); ?></a>
				<span class="textGreen">Submitted On</span>
				<span style="font-size: 10pt; color:#58585d;"><?php echo escape($post->timeStamp); ?></span>

			</div>
		</div>	
	</a>
		<div class="vote">
		<div class="upVote textGreen" style="<?php echo $image; ?>" data-vote="<?php echo $vote; ?>" data-action="upVote.php" data-like="<?php echo escape($post->like_count);  ?>" data-post="<?php echo escape($post->id); ?>">
			<span class="textGreen"><?php echo escape($post->like_count);  ?></span> 
		</div>  
	</div>

	<?php
		}
	}	
}




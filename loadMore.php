<?php
require_once 'core/init.php';
$user = new User();
$data = $user->data();
if(!$user->isLoggedIn()){
	Redirect::to('index.php');
	exit("you are not logged in");
}

if(!$order = Input::get('order')){
	exit("Nothing to vote!");
}else{

if($order == "id"){
	$posts = DB::getInstance()->query("SELECT * FROM posts WHERE public = ? ORDER BY timeStamp ASC LIMIT 10", array(1)); 
}else if($order == "timeStamp"){
	$posts = DB::getInstance()->query("SELECT * FROM posts WHERE public = ? ORDER BY timeStamp DESC LIMIT 10", array(1)); 
}else {
	$posts = DB::getInstance()->query("SELECT * FROM posts WHERE public = 1 ORDER BY like_count DESC LIMIT 10", array()); 
}

$results = $posts->results();



if (!$posts->count()){ // row count 
	echo 'no posts';
	exit();
}else{
	foreach ($results as $post) {
		$like = DB::getInstance()->query("SELECT * FROM likes WHERE postID = ? AND userID = ?", array($post->id, $data->id))->results(); 
		if (!$like[0]->userID == $data->id){
			$vote = "nun";
			$image = "background-image: url('images/up.png')";
		}else{
			$vote = "down";
			$image = "background-image: url('images/down.png')";
		}
		$id = $post->id;
		$likeCount = $post->like_count;
?>
		<li>
			<a href="viewPost.php?post=<?php echo escape($post->id); ?>"><?php echo escape($post->title); ?></a>
			<?php echo escape($post->timeStamp); ?>
			<br> By <a href="profile.php?user=<?php echo escape($post->username); ?>"><?php echo escape($post->username); ?></a>
			Gender = <?php echo escape($post->gender);  ?></br>
			<div class="upVote" style="<?php echo $image; ?>" data-vote="<?php echo $vote; ?>" data-action="upVote.php" data-like="<?php echo escape($post->like_count);  ?>" data-post="<?php echo escape($post->id); ?>"> votes <?php echo escape($post->like_count);  ?> <img class="up"></div>  
		</li>
<?php
	}

}
}



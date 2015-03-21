<?php 
require_once 'core/init.php';
include 'includes/header.php';
$user = new User();
$data = $user->data();
if(!$user->isLoggedIn()){
	Redirect::to('index.php');
	exit("you are not logged in");
}

include 'includes/nav.php';
?>

<div id="orderby">Filter
		<a class="change" data-order="like_count" href="#">Most Popular</a>
		<a class="change" data-order="id" href="#">Oldest</a>
		<a class="change" data-order="timeStamp" href="#">Most Recent</a>
</div>

<?php

$posts = DB::getInstance()->query("SELECT * FROM posts WHERE public = ? ORDER BY like_count DESC LIMIT 10", array(1)); 
$results = $posts->results();

if (!$posts->count()){ // row count 
	echo 'no posts';
}else{
	echo "<h1> The Post Page</h1>";
	echo "<ul id='posts'>";
	foreach ($results as $post) {
		$like = DB::getInstance()->query("SELECT * FROM likes WHERE postID = ? AND userID = ?", array($post->id, $data->id))->results(); 
		if (!$like[0]->userID == $data->id){
			$vote = "nun";
			$image = "background-image: url('images/up.png')";
		}else{
			$vote = "down";
			$image = "background-image: url('images/down.png')";
		}
		$images = DB::getInstance()->get('images', array('postID', '=', $post->id))->results();
?>

		<li>
			<img  src="images/photos/small-<?php echo $images[0]->filePath; ?>">
			<a href="viewPost.php?post=<?php echo escape($post->id); ?>"><?php echo escape($post->title); ?></a>
			<?php echo escape($post->timeStamp); ?>
			<br> By <a href="profile.php?user=<?php echo escape($post->username); ?>"><?php echo escape($post->username); ?></a>
			Gender = <?php echo escape($post->gender);  ?></br>
			<div class="upVote" style="<?php echo $image; ?>" data-vote="<?php echo $vote; ?>" data-action="upVote.php" data-like="<?php echo escape($post->like_count);  ?>" data-post="<?php echo escape($post->id); ?>"> votes <?php echo escape($post->like_count);  ?> <img class="up"></div>  
		</li>
<?php
	}
	echo "</ul>";
	echo "<div id='result'></div>";
}

if (Session::exists('postProb')){
	echo '<p>' . Session::flash('postProb') . '</p>';
}

?>

</body>
</html>
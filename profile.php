<?php 
require_once 'core/init.php';
$user = new User();
$data = $user->data();
if($user->hasPermission('banned')){
	 exit("you are banned");
}
include 'includes/header.php';
if(!$user->isLoggedIn()){	
	exit('<p>you need to <a href="login.php" >login</a> or <a href="register.php">register</a> </p>');
}
include 'includes/nav.php';
if(!$username = Input::get('user')){
	Redirect::to('index.php');
}else{
	$user = new User($username);
	if(!$user->exists()){
		Redirect::to(404);
	}else{
		$data = $user->data();
	}
}
$posts = DB::getInstance()->query("SELECT * FROM posts WHERE public = 1 AND userID = ? ", array($data->id)); 

?>

<div class="wrap">
	<div class="content">
		<div class="contentCenter">
			<div class="bar green">
				<h1><?php echo escape($data->username);  ?> Profile</h1>
			</div>
			<div class="profile-contain">
				<img class="profile-Image" src="images/profile/<?php echo escape($data->picture);  ?>" >
				<div class="profile-box-right">
					
					<h2><img src="<?php echo escape($data->gender);  ?>">  <?php echo escape($data->name);  ?></h2>
					<div class="space"></div>
					<p><?php echo escape($data->summary);  ?></p>
				</div>
			</div>
	<div class="space"></div>
	<div class="bar darkBlue">
				<h1><?php echo escape($data->username);  ?> Posts</h1>
	</div>
<?php
if (!$posts->count()){
	echo '<h3 class="textGreen">You have no posts!</h3>';
}else{

foreach ($posts->results() as $post) {
		$like = DB::getInstance()->query("SELECT * FROM likes WHERE postID = ? AND userID = ?", array($post->id, $data->id))->results(); 

		if($like[0]->userID != $data->id){
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
?>
</div>
</div>
</div>



</body>
</html>
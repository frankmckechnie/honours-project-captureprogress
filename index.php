<?php
require_once 'core/init.php';
$user = new User();
include 'includes/header.php';
if (Session::exists('home')){
	echo '<p>' . Session::flash('home') . '</p>';
}
if($user->isLoggedIn()){

$posts = DB::getInstance()->get('posts', array('userID', '=',$user->data()->id ));

include 'includes/nav.php';
?>
 
 
	<img  src="images/profile/small-<?php echo escape($user->data()->id).'-'. escape($user->data()->username); ?>.jpg">
	<p>hello <a href="profile.php?user=<?php echo escape($user->data()->username); ?>"><?php echo escape($user->data()->username); ?></a>!</p>
	<h2>Users Options</h2>
	<ul>
		<li><a href="logout.php">Log Out</a></li>
		<li><a href="update.php">Update Details</a></li>
		<li><a href="changepassword.php">Change Password</a></li>
		<li><a href="addPost.php">Add new post</a></li>
	</ul>

	<h2>Navigation</h2>

	<ul>
		<li><a href="posts.php">Public Posts</a></li>	
	</ul>

	<h2>My Posts</h2>
<?php 

	if (!$posts->count()){
		echo 'no posts';
	}else{
		echo "<ul>";
		foreach ($posts->results() as $post) {
			$images = DB::getInstance()->get('images', array('postID', '=', $post->id))->results();			
?>
			<li>		
			    <img  src="images/photos/small-<?php echo escape($images[0]->filePath); ?>">
				<a href="viewPost.php?post=<?php echo escape($post->id); ?>"><?php echo escape($post->title); ?></a>
				<?php echo escape($post->timeStamp); ?>
				<br> By <a href="profile.php?user=<?php echo escape($post->username); ?>"><?php echo escape($post->username); ?></a>
				Gender = <?php echo escape($post->gender);  ?><br>
				<a class="tokenRefresh" data-action="delete.php" data-post="<?php echo escape($post->id); ?>" href="#">Delete</a>
<?php
				if($post->public == 0){
?>				
					<a class="tokenRefresh" data-action="makePublic.php" data-post="<?php echo escape($post->id); ?>" href="#">Make Public!</a>
<?php				
				}
?>

			</li>
<?php
		}
		echo "</ul>";
		echo "<div id='result'></div>";
	}
	if (Session::exists('userMessage')){
		echo '<p>' . Session::flash('userMessage') . '</p>';
	}
	echo "<h3>Your Goals</h3>";
	if($user->hasPermission('admin')){
		echo "you have admin";
	}

}else{
	echo '<p>you need to <a href="login.php" >login</a> or <a href="register.php">register</a> </p>';
}
?>

</body>
</html>
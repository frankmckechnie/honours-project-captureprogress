<h1> The Post Page</h1>
 
<?php 
require_once 'core/init.php';

$posts = DB::getInstance()->get('posts', array('public', '=', 1));
$results = $posts->results();
// print_r($results);

if (!$posts->count()){
	echo 'no posts';
}else{
	echo "<ul>";
	foreach ($results as $post) {

?>
		<li>
			<a href="viewPost.php?post=<?php echo escape($post->id); ?>"><?php echo escape($post->title); ?></a>
			<?php echo escape($post->timeStamp); ?>
			<br> By <a href="profile.php?user=<?php echo escape($post->username); ?>"><?php echo escape($post->username); ?></a>
			Gender = <?php echo escape($post->gender);  ?>
		</li>

<?php

	}
	echo "</ul>";
}



?>
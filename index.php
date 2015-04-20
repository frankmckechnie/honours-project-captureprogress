<?php
require_once 'core/init.php';
$title = "CaptureProgress User Home Page";
$user = new User();
if($user->hasPermission('banned')){
	 exit("you are banned");
}
include 'includes/header.php';
if (Session::exists('home')){
	echo '<div class="warning red">' . Session::flash('home') . '</div>';
}
if (Session::exists('verify')){
	echo '<div class="warning green">' . Session::flash('verify') . '</div>';
}
if(!$user->isLoggedIn()){
	exit('
		<div id="container">
		<div class="form-main">
		<div class="red loginBar" > You need to login in or register</div>
		<div class="form-div">
				<p class="coverStory">The purpose of Capture Progress is to provide a way for particular users that preform fitness related tasks to be able to share any progress online.</p>

		<a href="register.php" class="trans button grey"> Register</a>
		<a href="login.php" class="trans mLeft button green greenB" type="submit">Log In</a>
		</div>
		</div>
		</div>');
}

$posts = DB::getInstance()->get('posts', array('userID', '=',$user->data()->id ));
echo "<div class='wrap'>";
include 'includes/nav.php';
echo "<div style='display:none' class='warning red'></div>";
if (Session::exists('userMessage')){
	echo '<div class="warning green">' . Session::flash('userMessage') . '</div>';
}
?>
<div class="content">
<div class="overlay"></div>
	<div class="contentCenter">
		<div class="bar green">
			<h1>My Posts</h1>
	  		<a href="addPost.php"><div class='barButton darkGreen trans'>Create</div></a>
		</div>

<?php 

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
	<div class="list">
		<div class="listImage">
			<img  src="images/photos/small-<?php echo escape($images[0]->filePath); ?>"/>
		</div>

		<div class="titleHolder">
			<?php echo escape($post->title); ?>
		</div>		
		<div class="line"></div>
		<div class="timeHolder">
			<img style="margin-right:5px;" src="<?php echo escape($post->gender); ?>">By <a class="none blue" href="profile.php?user=<?php echo escape($post->username); ?>"><?php echo escape($post->username); ?></a>
			<span class="textGreen">Submitted On</span>
			<span style="font-size: 10pt; color:#58585d;"><?php echo escape($post->timeStamp); ?></span>
<?php
			if($post->public == 0){
?>				
			<span class="tokenRefresh blue private" data-option="1" data-action="makePublic.php" data-post="<?php echo escape($post->id); ?>" href="#">Make Public?</span>
<?php				
			}else{
?>				
			<span class="tokenRefresh blue private" data-option="0" data-action="makePublic.php" data-post="<?php echo escape($post->id); ?>" href="#">Make Private?</span>
<?php
			}
?>		
		</div>
	</div>	
</a>

<div class="vote">
	<div class="upVote textGreen" style="<?php echo $image; ?>" data-vote="<?php echo $vote; ?>" data-action="upVote.php" data-like="<?php echo escape($post->like_count);  ?>" data-post="<?php echo escape($post->id); ?>">
		<span class="textGreen"><?php echo escape($post->like_count);  ?></span> 
	</div>  
</div>
<div class="trans tokenRefresh mainButton red " style="" data-action="delete.php" data-post="<?php echo escape($post->id); ?>" href="#">Delete</div>


<div class="menuSet">
	<div class=" tokenRefresh subBut red" style="" data-action="delete.php" data-post="<?php echo escape($post->id); ?>" href="#">Delete</div>

<?php
			if($post->public == 0){
?>				
			<span class="tokenRefresh subBut  grey" data-option="1" data-action="makePublic.php" data-post="<?php echo escape($post->id); ?>" href="#">Make Public?</span>
<?php				
			}else{
?>				
			<span class="tokenRefresh subBut grey" data-option="0" data-action="makePublic.php" data-post="<?php echo escape($post->id); ?>" href="#">Make Private?</span>
<?php
			}
?>		
</div>
<div class="mSettings" >
</div>





<?php
	}
}
		
?>		
	<div class="space"></div>
	<div class="bar yellow bottom">
		<h1>My Goals</h1>
  		<div id='showGoal' class='barButton darkYellow trans'>Create</div>
	</div>

<?php
echo "<div id='goalForm'><input id='goalVal' name='goalVal' type='text'/> <div id='addGoal' class='addButton green'> Add</div> </div>";
$completed = array();
$goals = DB::getInstance()->query("SELECT * FROM goals WHERE userID = ? ORDER BY date DESC ", array($user->data()->id));
if (!$goals->count()){
	echo '<div class="listHolder"><h3 class="dis textGreen">You have no goals!</h3></div>';
}else{
	echo "<div class='listHolder'>";
	$goals = $goals->results();
	foreach ($goals	 as $goal) {

		if($goal->completed == 0){

?>
			<div class="list goalList">
			<div class="overide titleHolder">
				<a href="#" class="none"><?php echo escape($goal->title); ?></a>
			</div>		
			<div class="line goalLine"></div>
			    <div class="overide timeHolder">
				    <img style="margin-right:5px;" src="<?php echo escape($user->data()->gender); ?>">By <a class="none blue" href="profile.php?user=<?php echo escape($user->data()->username); ?>"><?php echo escape($user->data()->username); ?></a>
				    <span style="font-size: 10pt; color:#58585d;"><?php echo escape($goal->date); ?></span>
			    </div>
			</div>
			<div class="tokenRefresh x" data-action="deleteGoal.php" data-post="<?php echo escape($goal->id); ?>" ></div>
			<div class="tokenRefresh tick" data-action="complete.php" data-post="<?php echo escape($goal->id); ?>" ></div>
<?php
		}else{
			array_push($completed, array("title" => $goal->title,"goalID"=>$goal->id));
		}
?>	
<?php
	}
		echo "</div>";
}
echo "";

?>		
<div class="space"></div>
<div class="compbar white">
<h4>Completed Goals</h4>
</div>
<div class='completedGoals '>
<form class="checkGoal" id="ajaxRequest" data-name="deleteGoal.php" method="post" >

<?php      
	foreach ($completed	 as $complete) {
		
		echo '<div class="checkBox"><input type="checkbox" name="chk_group[]"  value="'. escape($complete["goalID"]).'"/></div>'. '<div class="compRight">'.escape($complete["title"]).' <span class="textGreen">Submitted On</span>  <span style="font-size: 10pt; color:#58585d;">'. escape($goal->date).'</span></div></br>';
	}

?>

<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
<div class="lastBox"><input type="checkbox" name="select-all" id="select-all" /></div> 
<div class="marginTop">Select all</div>
<input class="submitDelete red trans" type="submit" value="Delete!" />
</form>
</div>
<div id="message"></div>

<!-- end of content center -->
</div>

<!-- end of content -->
</div>

<!-- end of wrap -->
</div>
</body>
</html>
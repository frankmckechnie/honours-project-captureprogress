<?php

if($user->data()->picture == "default-profile-pic.png"){
	$picture = "images/profile/small-default-profile-pic.png";
}else{
	$picture = "images/profile/small-".escape($user->data()->id).'-'.escape($user->data()->username).".jpg";
}

?>

<div class="header">
	<div class="headerCenter">

		<a href="index.php"><div class="logo"></div></a>
		<div class="nav">
			<ul class="">
				<li class=""><img class="userImg" src="<?php echo $picture; ?>"><a href="profile.php?user=<?php echo escape($user->data()->username); ?>"><?php echo escape($user->data()->username); ?></a></li>
				<li  data-dropdown="#dropdown-2"><a class="posts" href="posts.php">Posts</a></li>
				<li ><a class="home" href="index.php">Personal</a></li>
				<li  data-dropdown="#dropdown-1"><a class="settings" href="#">Settings</a></li>
			</ul>
		</div>
		<div id="dropdown-1" class="dropdown dropdown-tip has-icons">
			<ul class="dropdown-menu">
				<li class="update"><a href="update.php">Update Details</a></li>
				<li class="change"><a href="changepassword.php">Change Password</a></li>
				<li class="logout"><a href="logout.php">Logout</a></li>
			</ul>
		</div>
		<div id="dropdown-2" class="dropdown dropdown-tip has-icons">
			<ul class="dropdown-menu">
				<li class="post"><a href="posts.php">Post Feed</a></li>
				<li class="plus"><a href="addPost.php">New Post</a></li>
			</ul>
		</div>

		<div id="activate" class='uiButton' style=""> 
      	</div>

	</div>
</div>
 <div id="mnav">
	 
        <ul>
            <li class=""><img class="userImg" src="<?php echo $picture; ?>"><a href="profile.php?user=<?php echo escape($user->data()->username); ?>"><?php echo escape($user->data()->username); ?></a></li>
			<li><a class="posts" href="posts.php">Posts</a></li>
			<li><a class="home" href="index.php">Personal</a></li>
			<li><a class="settings" href="#">Settings</a>

			<ul>
				<li class="lightBlue update" ><a href="update.php">Update Details</a></li>
				<li class="lightBlue"><a href="changepassword.php">Change Password</a></li>
				<li class="lightBlue"><a href="logout.php">Logout</a></li>
			</ul>

			</li>
     	 </ul>
      </div>
<div class="extraSpace"></div>
<a href="#" onclick="$('#header').animatescroll();" class="uparrow" title='Back to top...'></a>

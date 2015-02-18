<?php
require_once 'core/init.php';

if (Session::exists('home')){
	echo '<p>' . Session::flash('home') . '</p>';
}

$user = new User();
if($user->isLoggedIn()){
?>
	<p>hello <a href="profile.php?user=<?php echo escape($user->data()->username); ?>"><?php echo escape($user->data()->username); ?></a>!</p>

	<ul>
		<li><a href="logout.php">Log Out</a></li>
		<li><a href="update.php">Update Details</a></li>
		<li><a href="changepassword.php">Change Password</a></li>
	</ul>

<?php

	if($user->hasPermission('admin')){
		echo "you have admin";
	}
	

}else{
	echo '<p>you need to <a href="login.php" >login</a> or <a href="register.php">register</a> </p>';
}
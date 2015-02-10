<?php
require_once 'core/init.php';

if (Session::exists('home')){
	echo '<p>' . Session::flash('home') . '</p>';
}

$user = new User();
if($user->isLoggedIn()){
?>
	<p>hello <a href="#"><?php echo escape($user->data()->username); ?></a>!</p>

	<ul>
		<li><a href="logout.php">Log Out</a></li>
	</ul>

<?php
}else{
	echo '<p>you need to <a href="login.php" >login</a> or <a href="register.php">register</a> </p>';

}
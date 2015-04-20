<?php
require_once 'core/init.php';
$title = "CaptureProgress email val";
include 'includes/header.php';

if(Input::exists('get')){
	if(!$username = Input::get('username')){
		Session::flash('home', 'sorry noting there');
		Redirect::to('index.php');
		exit("sorry noting there");
	}elseif(!$unq = Input::get('unq') ){
		Session::flash('home', 'sorry noting there');
		Redirect::to('index.php');
		exit("sorry noting there");
	}
	$user = DB::getInstance()->get('users', array('username', '=', $username));
	
	if(!$user->count() >= 1 ){
		// Session::flash('home', 'sorry noting there');
		// Redirect::to('index.php');
		exit("$username sorry ncount found nothing");
	}

	$cUser = $user->results();
	if($cUser[0]->verify == 1){
		exit('
		<div id="container">
		<div class="form-main">
		<div class="red loginBar" > You are already verified</div>
		<div class="form-div">
		<a href="register.php" class="trans button grey"> Register</a>
		<a href="login.php" class="trans mLeft button green greenB" type="submit">Log In</a>
		</div>
		</div>
		</div>
		</body>
		</html>
		');
	}
	if($cUser[0]->verify == $unq){
		if(DB::getInstance()->query("UPDATE users SET verify = 1 WHERE username = ?", array($username))){
			Session::flash('verify', 'Your email is verified and you can now login');
			Redirect::to('index.php');
			exit("Your email is verified and you can now login");
		}
	}else{
		echo "sorry you have an invaild key";
		exit('
		<div id="container">
		<div class="form-main">
		<div class="red loginBar" > You need to login in or register</div>
		<div class="form-div">
		<a href="register.php" class="trans button grey"> Register</a>
		<a href="login.php" class="trans mLeft button green greenB" type="submit">Log In</a>
		</div>
		</div>
		</div>
		</body>
		</html>
		');
	}

}else{
echo "sorry there was no input";
echo '<p>you need to <a href="login.php" >login</a> or <a href="register.php">register</a> </p>';
}
echo "sorry something went wrong";
?>

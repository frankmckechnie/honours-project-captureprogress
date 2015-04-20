<?php
require_once 'core/init.php';
$user = new User();
if($user->hasPermission('banned')){
	 exit("you are banned");
}
if(!$user->isLoggedIn()){
	Redirect::to('index.php');
		exit('
		<div id="container">
		<div class="form-main">
		<div class="red loginBar" > You need to login in or register</div>
		<div class="form-div">
		<a href="register.php" class="trans button grey"> Register</a>
		<a href="login.php" class="trans mLeft button green greenB" type="submit">Log In</a>
		</div>
		</div>
		</div>');
}

include 'includes/header.php';
include 'includes/nav.php';
echo "<div style='display:none' class='warning red'></div>";
if(Input::exists()){
	if(Token::check(Input::get('token'))){

		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'password_current' => array(
				'required' => true,
				'min' => 6
			),
			'password_new' => array(
				'required' =>true,
				'min' => 6
			),
			'password_new_again' => array(
				'required' => true,
				'min' => 6,
				'matches' => 'password_new'
			)
		));

		if($validation->passed()){
			
			if(Hash::make(Input::get('password_current'), $user->data()->salt) !== $user->data()->password){
				echo 'your current password is wrong';
			}else{
				$salt = Hash::salt(32);
				$user->update(array(
					'password' => hash::make(Input::get('password_new'), $salt),
					'salt' => $salt
				));

				Session::flash('home', ' your password has been changed!');
				Redirect::to('index.php');
			}

		}else{
			foreach($validation->errors() as $error){
				echo "<div class='warning red'>$error</div>";
			}

		}
	}
}


?>
<div class="wrap">
	<div class="content">
		<div class="contentCenter">
			<div class="bar green">
				<h1>Change Password</h1>
			</div>

			<form action="" method="post">
				<div class="form-div">
					<label class="formLable" for="password_current">Current password</label>
					<input class="password feedback-input" type="password" name="password_current" id="password_current" required>

					<label class="formLable" for="password_new">New password</label>
					<input class="password feedback-input" type="password" name="password_new" id="password_new" required>

					<label class="formLable" for="password_new_again">New password again</label>
					<input class="password feedback-input" type="password" name="password_new_again" id="password_new_again" required>

					<input   type="hidden" name="token" value="<?php echo Token::generate(); ?>">
					<input  class="button green greenB" type="submit" value="Change">
				</div>
			</form>
		</div>
	</div>
</div>

</body>
</html>
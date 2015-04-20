<?php
require_once 'core/init.php';
$title = "Update information";
$user = new User();
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
if($user->hasPermission('banned')){
	 exit("you are banned");
}

include 'includes/header.php';
include 'includes/nav.php';
echo "<div style='display:none' class='warning red'></div>";
if(Input::exists()){
	if(Token::check(Input::get('token'))){
		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'name' => array(
				'required' => true,
				'min' => 6,
				'max' => 50
				),
			'email' => array(
				'required' => true,
				'min' => 5,
				'max' => 30
				),
			'summary' => array(
				'required' => false,
				'max' => 100
			 	)
		));
		if($validation->passed()){
			try {
				$user->update(array(
					'name' => Input::get('name'), // if  empty 
					'email' => Input::get('email'),
					'summary' => Input::get('summary')
				));
				Session::flash('home', 'your details have been updated.');
				Redirect::to('index.php');
			} catch (Exception $e) {
				die($e->getMessage());
			}
		}else{
			foreach ($validation->errors() as $error) {
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
					<h1>Update Details </h1>
				</div>
				<form action="" method="post" enctype="multipart/form-data">
					<div class="form-div">
						<label  class="formLable" for="name">Name</label>
						<input class="feedback-input name" type="text" name="name" value="<?php echo escape($user->data()->name); ?>" >
						<br>
						<label class="formLable"  for="email">Email</label>
						<input  class="feedback-input email" type="email" name="email" value="<?php echo escape($user->data()->email); ?>" >
						<br>
						<label class="formLable"  for="summary">Summary</label>
						<textarea  class="feedback-input summary"  name="summary" cols="30" rows="5"><?php echo escape($user->data()->summary); ?></textarea>
						<br>	
						<input type="submit"  class="button grey" name="submit" value="Update">
						<div id="token">
								<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
						</div>
					</div>
				</form>



				<div class="space"></div>
				<div class="form-div-two">
					<form id="ajaxRequest" data-name="imageUploader.php"  method="post" enctype="multipart/form-data">
					  <label class="formLable" >Update your profile picture: </label>
					  <input type="hidden" name="dir" value="profile">
					  <input name="userfile[]" type="file" /><br />
					  <input type="submit" class="longButton green greenB" value="Upload Image" />
					</form>
				</div>
				<div class="space"></div>
				<div class="form-div-two">
					<form id="deleteAccount" method="post" enctype="multipart/form-data">
					  <p class="formLable"> Any user once registered to this website can delete their account.</p>
					  <div class="space"></div>
					  <label class="formLable" for="sex">Are you sure you want to delete this account</label>
					  <input class="answer" type="radio" name="answer" value="yes">yes
					  <input class="answer" type="radio" name="answer" value="no" checked>no
					  <input type="submit" class="longButton red" value="Delete Acount" />
					</form>
				</div>
				
		</div>
	</div>
</div>



</body>
</html>
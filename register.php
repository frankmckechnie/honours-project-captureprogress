<?php
require_once 'core/init.php';
$user = new User();
if($user->isLoggedIn()){
	Session::flash('userMessage', 'you are allready logged in!');
	Redirect::to('index.php');
	exit("you have already been logged in!");
}

if(Input::exists()){
	if(Token::check(Input::get('token'))){
		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'username' => array(
				'required' => true,
				'min' => 2,
				'max' => 20,
				'unique' => 'users'
			),
			'password' => array(
				'required' => true,
				'min' => 6
			),
			'password_again' => array(
				'required' => true,
				'matches' => 'password'

			),
			'name' => array(
				'required' => true,
				'min' => 2,
				'max' => 50
			),
			'email' => array(
				'required' => true,
				'min' => 7,
				'max' => 30,
				'email'=> true,
				'unique' => 'users'
			)
		));

		if($validation->passed()){
			$user = new User();
			$time = date('Y-m-d H:i:s');
			$unq = md5(uniqid($time.Input::get('username')));
			$salt = Hash::salt(32);

			try {
				$user->create('acount','users',array(
					'username' => Input::get('username'),
					'password' => Hash::make(Input::get('password'), $salt),
					'salt' => $salt,
					'email' => Input::get('email'),
					'name' => Input::get('name'),
					'joined' => $time,
					'group' => 1,
					'gender' => Input::get('sex'),	
					'summary' => 'Hey I am Name, this is my summary talking about why I am here and what I hope to accomplish.',	
					'picture' => 'default-profile-pic.png',		
					'verify' =>	 $unq
				));
				$to        = Input::get('email');
				$subject   = 'Email verify for CaptureProgress';
				$username = Input::get('username');
				$message = '
				Thanks for signing up!
		        Your account has been created, you can login with the following credentials after you have activated your account by pressing the url below.
				------------------------
				Username: '.Input::get('username').'
				Password: '.Input::get('password').'
				------------------------

				Please click this link to activate your account:
				http://captureprogress.com/emailVal.php?username='.$username.'&unq='.$unq.'
	
				';
				$headers = 'From:noreply@captureProgress.com' . "\r\n"; // Set from headers
				mail($to, $subject, $message, $headers); // Send our email

				Session::flash('home', 'you have been registered, now you have to check your emails before you can login!');
				Redirect::to('index.php');

			} catch(Exception $e){
				die($e->getMessage());
			}

		}else{
			foreach ($validation->errors() as $error) {
				echo '<div class="warning red">' . $error, '<br>' . '</div>';
			}
			
		}
	}
}

?>

<!DOCTYPE html>
<head>
<!-- search engine  optimization  for websites-->
<title><?php echo $title;?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<!-- end of search engine optimization for websites-->

<!-- js files for websites-->
<script type="text/javascript" src="js/modernizr.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="js/ajax.js"></script>
<script type="text/javascript" src="js/jquery.dropdown.js"></script>
<!--  end of js files for websites-->

<!-- the css styles -->
<link type="text/css" rel="stylesheet" href="css/jquery.dropdown.css" />
<link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,900,300italic,400italic,700italic,900italic' rel='stylesheet' type='text/css'>
<link href="css/reset.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="css/whiteTheme.css">
<link rel="stylesheet" type="text/css" href="css/form.css">
<link rel="stylesheet" type="text/css" href="css/responsive.css">
<!-- end of styles-->

<style>
body{   
  background-color: #1b9e85;
  background-position:  center; 
}
</style>
</head>
<body>
<div id="regContain">
	<div class="form-main">
		<div class="loginBar" > Register Form</div>
		<div class="form-div">
			<form action="" method="post">

				<label class="formLable" for="username">Create a username</label>
				<input class="feedback-input name" type="text" name="username" id="username" value="<?php echo escape(Input::get('username')); ?>" autocomplete="off">



				<label class="formLable" for="email">Enter your email</label>
				<input class="feedback-input email" type="email" value="<?php echo escape(Input::get('email')); ?>" name="email" >



				<label class="formLable" for="password">Choose a password</label>
				<input class="feedback-input password" type="password" name="password" >


				<label class="formLable" for="password_again">Enter your password again</label>
				<input class="feedback-input password" type="password" name="password_again">


				<label class="formLable" for="name">Enter your name</label>
				<input class="feedback-input name" type="text" value="<?php echo escape(Input::get('name')); ?>" name="name" >
			


				<label class="formLable" for="sex">Male or Female</label>
				<input type="radio" name="sex" value="css/icons/male.png" checked>Male
				<input type="radio" name="sex" value="css/icons/female.png" >Female
				<input type="hidden" name="token" value="<?php echo Token::generate(); ?>" >
				<div class="space"></div>
				<input class="trans button green greenB" type="submit" value="Register">
				<a href="login.php" class="trans mLeft button grey"> Log In</a>

			</form>
		</div>
	</div>
</div>

</body>
</html>
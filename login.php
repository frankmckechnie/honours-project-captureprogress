<?php
require_once 'core/init.php';
$title = "CaptureProgress User login Page";
$user = new User();
if(!$user->isLoggedIn()){

	if(Input::exists()){
		if(Token::check(Input::get('token'))){

			$validate = new Validate();
			$validation = $validate->check($_POST, array(
				'username' => array('required' => true),
				'password' => array('required' => true)
			));

			if($validation->passed()){
				$user = new User();

				$remember = (Input::get('remember') === 'on') ? true : false;
				$login = $user->login(Input::get('username'), Input::get('password'), $remember);
	           
	            if($login) {
	            	Redirect::to('index.php');
	            } else {
	                echo '<div class="warning red">Incorrect username or password or email is not verified</div>';
	            }

			}else{
				foreach ($validation->errors() as $error) {
					echo '<div class="warning red">' . $error, '<br>' . '</div>';
				}
			}
		}
	}
}else{
	Session::flash('userMessage', 'you are allready logged in!');
	Redirect::to('index.php');
	exit("you have already been logged in!");
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
  background: url(css/assets/login.png) no-repeat;
  background-position: top; 
}
</style>
</head>
<body>
<div id="container">
	<div class="form-main">
		<div class="loginBar" > User Login</div>
		<div class="form-div">
			<form action="" method="post">
				
					<input class=" name feedback-input" placeholder="UserName" type="text" name="username" id="username" autocomplete="off">
					<input class=" password feedback-input" placeholder="Password" type="password" name="password" id="password" autocomplete="off">
					<label class="remember">
						<input type="checkbox" name="remember" id="remember" > Remember me
					</label>
				<div class="space"></div>
				<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
				<a href="register.php" class="trans mLeft button grey"> Register</a>
				<input class="trans button green greenB" type="submit" value="Log In">
			</form>
		</div>
	</div>
</div>


</body>
</html>
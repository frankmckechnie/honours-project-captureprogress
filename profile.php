<?php 
require_once 'core/init.php';
$user = new User();
$data = $user->data();
if($user->isLoggedIn()){	
	if(!$username = Input::get('user')){
		Redirect::to('index.php');
	}else{
		$user = new User($username);
		if(!$user->exists()){
			Redirect::to(404);
		}else{
			$data = $user->data();
		}
		?>

		<h3>
		<img src="images/profile/<?php echo escape($data->picture);  ?>" >
		<?php echo escape($data->username);  ?>
		</h3>
		<h4>Gender = <?php echo escape($data->gender);  ?></h4>
		<p>FullName:<?php echo escape($data->name);  ?></p>
		<p>My Summary:<?php echo escape($data->summary);  ?></p>

		
	<?php
	}
}else{
	echo '<p>you need to <a href="login.php" >login</a> or <a href="register.php">register</a> </p>';
}

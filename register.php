<?php
require_once 'core/init.php';

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
			)
		));

		if($validation->passed()){
			$user = new User();

			$salt = Hash::salt(32);

			try {
				$user->create('acount','users',array(
					'username' => Input::get('username'),
					'password' => Hash::make(Input::get('password'), $salt),
					'salt' => $salt,
					'email' => Input::get('email'),
					'name' => Input::get('name'),
					'joined' => date('Y-m-d H:i:s'),
					'group' => 1,
					'gender' => Input::get('sex'),	
					'summary' => 'Hey I am Name, this is my summary talking about why I am here and what I hope to acomplish.',	
					'picture' => 'images/profile/default-profile-pic.png',				
				));

				Session::flash('home', 'you have been registered and now can log in!');
				Redirect::to('index.php');

			} catch(Exception $e){
				die($e->getMessage());
			}

		}else{
			foreach ($validation->errors() as $error) {
				echo "$error<br>";
			}
			
		}
	}
}

?>

<form action="" method="post">
	<div class="field">
		<label for="username">username</label>
		<input type="text" name="username" id="username" value="<?php echo escape(Input::get('username')); ?>" autocomplete="off">
	</div>

	<div class="field">
		<label for="email">Enter your a email</label>
		<input type="email" value="<?php echo escape(Input::get('email')); ?>" name="email" id="email">
	</div>

	<div class="field">
		<label for="password">choose a password</label>
		<input type="password" name="password" id="password">
	</div>

	<div class="field">
		<label for="password_again">Enter your password again</label>
		<input type="password" name="password_again" id="password_again">
	</div>	

	<div class="field">
		<label for="name">Enter your name</label>
		<input type="text" value="<?php echo escape(Input::get('name')); ?>" name="name" id="name">
	</div>		

	<div class="field">
		<label for="sex">Male or Female</label>
		<input type="sex" value="<?php echo escape(Input::get('sex')); ?>" name="sex" id="sex">
	</div>	

	<input type="hidden" name="token" value="<?php echo Token::generate(); ?>" >
	<input type="submit" value="register">
</form>


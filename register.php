<?php
require_once 'core/init.php';




if(Input::exists()){
	if(Token::check(Input::get('token'))){
		$validate = new Validate();
		$salt = Hash::salt(32);
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
		}else{
			foreach ($validation->errors() as $error) {
				echo "$error<br>";
				try {
					$User::create(array(
						'username' => Input::get('username'),
						'password' => Hash::make(Input::get('password'), $salt),
						'salt' => $salt,
						'name' => Input::get('name'),
						'joined' => date('Y-m-d H:i:s'),
						'group' => 1

					));

				} catch(Exception $e){
					die($e->getMessage());
				}
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
	<input type="hidden" name="token" value="<?php echo Token::generate(); ?>" >
	<input type="submit" value="register">
</form>


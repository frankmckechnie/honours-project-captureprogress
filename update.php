<?php
require_once 'core/init.php';
$title = "Update information";
include 'includes/header.php';
include 'includes/nav.php';

$user = new User();
if(!$user->isLoggedIn()){
	Redirect::to('index.php');
}


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
			 	)//,
			// 'gender' => array(
			// 	'required' => true,
			// 	'max' => 1
			// 	)
		));

		if($validation->passed()){
			try {
				$user->update(array(
					'name' => Input::get('name'), // if  empty 
					'email' => Input::get('email'),
					'summary' => Input::get('summary'),
					'gender' => Input::get('sex')
				));

				Session::flash('home', 'your details have been updated.');
				//Redirect::to('index.php');
			} catch (Exception $e) {
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

<form action="" method="post" enctype="multipart/form-data">
	<div class="field">
		<label for="name">Name</label>
		<input type="text" name="name" value="<?php echo escape($user->data()->name); ?>" >
		<br>
		<label for="email">Email</label>
		<input type="text" name="email" value="<?php echo escape($user->data()->email); ?>" >
		<br>
		<label for="summary">summary</label>
		<textarea name="summary" id="summary" cols="30" rows="5"><?php echo escape($user->data()->summary); ?></textarea>
		<br>	
		<label for="sex">
			<input type="radio" name="sex" value="m" <?php echo ($user->data()->gender=='m')?'checked':'' ?> >Male<br>
			<input type="radio" name="sex" value="f" <?php echo ($user->data()->gender=='f')?'checked':'' ?> >Female
		</label>
		<br>
		<input type="submit" name="submit" value="Update">
		<div id="token">
		<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
		</div>
	</div>
</form>

</br>

<p>Update your profile image</p>

<form id="imageUpload" data-name="imageUploader.php"  method="post" enctype="multipart/form-data">
  Upload images:
  <input type="hidden" name="dir" value="profile">
  <input name="userfile[]" type="file" /><br />
  <input type="submit" value="Send files" />
  <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
</form>
<div id="message"></div>



</body>
</html>
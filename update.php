<?php
require_once 'core/init.php';

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
				'required' => true,
				'max' => 30
				),
			'gender' => array(
				'required' => true,
				'max' => 1
				)
		));

		$target_dir = "images/profile/";
		$ext = basename( $_FILES["fileToUpload"]["name"]);
		$ext = explode("." ,$ext);
		$target_file = $target_dir . $user->data()->id . "-" . $user->data()->username . "." . end($ext);
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
		$uploadOk = 1;
		
		// Check if image file is a actual image or fake image
		if(isset($_POST["fileToUpload"])) {
		    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
		    if($check !== false) {
		        echo "File is an image - " . $check["mime"] . ".";
		        $uploadOk = 1;
		    } else {
		        echo "File is not an image.";
		        $uploadOk = 0;
		    }
		}
		// Check if file already exists
		// if (file_exists($target_file)) {
		//     echo "Sorry, file already exists.";
		//     $uploadOk = 0;
		// }
		// Check file size
		if ($_FILES["fileToUpload"]["size"] > 500000) {
		    echo "Sorry, your file is too large.";
		    $uploadOk = 0;
		}
		// Allow certain file formats
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif" ) {
		    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
		    $uploadOk = 0;
		}
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
		    echo "Sorry, your file was not uploaded.";
		// if everything is ok, try to upload file
		} else {
		    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
		        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
		    } else {
		        echo "Sorry, there was an error uploading your file.";
		    }
		}

		if($validation->passed()){
			try {
				$user->update(array(
					'name' => Input::get('name'),
					'email' => Input::get('email'),
					'summary' => Input::get('summary'),
					'gender' => Input::get('gender'),
					'picture' => $target_file

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
		<label for="gender">gender</label>
		<input type="text" name="gender" value="<?php echo escape($user->data()->gender); ?>" >
		<br>	
		<label for="remember">
			<input type="checkbox" name="male" id="male" > male
			<input type="checkbox" name="female" id="female" > female
		</label>
		<br>
		Select image to upload:
   		<input type="file" name="fileToUpload" id="fileToUpload">
		<input type="submit" name="submit" value="Update">
		<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
	</div>
</form>
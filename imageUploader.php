<?php
require_once 'core/init.php';
$user = new User();
if($user->hasPermission('banned')){
	 exit("you are banned");
}

if(!$user->isLoggedIn()){
	Redirect::to('index.php');
}
$user = new User();
$target_dir = "images/". Input::get('dir') ."/";
$uploadOk = 1;

if ($_FILES['userfile']) {
	$imgVal = ImageVal('userfile');
	if(!array_key_exists(0, $imgVal)){
		$file_ary = reArrayFiles($_FILES['userfile']);
		foreach ($file_ary as $file) {
			if($file['tmp_name'] != null){
				$ext = explode("." ,$file['name']);
				$target_file = $target_dir . $user->data()->id . "-" . $user->data()->username   . "." . end($ext); // i want users to have no unqi
				$filePath = $user->data()->id . "-" . $user->data()->username   . "." . end($ext); 
				$filename = $target_dir. "small-". $user->data()->id . "-" . $user->data()->username   . "." . end($ext);
				$image = new SimpleImage($file['tmp_name']);
				$image->resize(220,220);
				if ($image->save($target_file)) {
			    	$image->resize(60,60);
			    	$image->save($filename);
				    try {
						$user->update(array(
							'picture' => $filePath
						));

						Session::flash('home', 'your details have been updated.');
						//Redirect::to('index.php');
					} catch (Exception $e) {
						die($e->getMessage());
					}
			        echo "The file ". escape($file['name']). " has been uploaded.";
				}else{
					echo "Sorry, there was an error uploading your file.";
				}
			}else{
			    echo "Sorry, there was an error uploading your file.";
			}
		}
	}else{
		foreach ($imgVal as $imgError) {
			echo "Error:$imgError<br>";
		}
	}
}
// Check if file already exists
// if (file_exists($target_file)) {
//     echo "Sorry, file already exists.";
//     $uploadOk = 0;
// }
	    
	    
	   
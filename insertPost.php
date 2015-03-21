<?php
require_once 'core/init.php';
$user = new User();
if(!$user->isLoggedIn()){
	Redirect::to('index.php');
	exit("you are not logged in");
}
if(Input::exists()){
	$imgVal = ImageVal('userfile');
// if(Token::check(Input::get('token'))){
		$validate = new Validate();
		$validation = $validate->check($_POST, array(	
			'pTitle' => array(
				'required' => true,
				'min' => 5,
				'max' => 40
			),	
			'titleOne' => array(
				'required' => true,
				'min' => 5,
				'max' => 60
			)
		));
		if($validation->passed()){
			if(!array_key_exists(0, $imgVal)){
				$public = (Input::get('public') === 'on') ? 1 : 0;
				try {
					$time = date('Y-m-d H:i:s');
					$str = $time.$user->data()->id;
					$uni = sha1($str);

					$user->create('post','posts',array(
						'userID' => $user->data()->id,
						'title' => Input::get('pTitle'),
						'timeStamp' => $time,
						'public' => $public,
						'username' => $user->data()->username,
						'gender' => $user->data()->gender,
						'identifier' => $uni,
						'description' => Input::get('desc')

					));

					$posts = DB::getInstance()->get('posts', array('identifier', '=', $uni));
					$results = $posts->results();

					if (!$posts->count()){ // row count 
						echo 'no posts';
					}else{
						$target_dir = "images/photos/";
						foreach ($results as $post) {
							$postID = $post->id;
							$countN = 0;
							$uploadOk = 1;
							if ($_FILES['userfile']) {
							    $file_ary = reArrayFiles($_FILES['userfile']);
							    foreach ($file_ary as $file) {
							    	if($file['tmp_name'] != null){
								    	$ext = explode("." ,$file['name']);
								    	$uniq = uniqid();
										$target_file = $target_dir . $post->id . "-" . $uniq  . "." . end($ext); 
								    	$filename = $target_dir. "small-".$post->id . "-" . $uniq  . "." . end($ext);
								    	$filePath = $post->id . "-" . $uniq  . "." . end($ext); 
									    $image = new SimpleImage($file['tmp_name']);
									    $image->resizeToWidth(620);
									    if ($image->save($target_file)) {
									    	$image->resize(60,60);
									    	$image->save($filename);
								    		if($countN == 0){
								    			$addedTitle = Input::get('titleOne'); 
								    			$countN = $countN + 1;
								    		} elseif ($countN == 1) {
								    			$addedTitle = Input::get('titleTwo');
								    			$countN = $countN + 1;
								    		} elseif ($countN == 2) {
								    			$addedTitle = Input::get('titleThree');
								    			$countN = $countN + 1;
								    		}

					    					$user->create('image','images',array(
												'postID' => $post->id,
												'userID' => $post->userID,
												'addedTitle' => $addedTitle,
												'filePath' => $filePath,
												'timeStamp' => date('Y-m-d H:i:s')
											));
									    } else {
									        echo "Sorry, there was an error uploading your file.";
									    }
									}
							    }
							}
						}
					}
				} catch(Exception $e){
					die($e->getMessage());
				}
				 Redirect::to('viewPost.php?post='.$postID);
			}else{
				foreach ($imgVal as $imgError) {
					echo "Error:$imgError<br>";
				}
			}	
		}else{
			foreach ($validation->errors() as $error) {
				echo "Error:$error<br>";

			}
		}
// }
}


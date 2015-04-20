<?php
require_once 'core/init.php';
$user = new User();
if($user->hasPermission('banned')){
	 exit("ERROR:you are banned");
}
if(!$user->isLoggedIn()){
	Redirect::to('index.php');
	exit("ERROR:you are not logged in");
}
if(Input::exists()){
	if(Token::check(Input::get('token'))){
		$validate = new Validate();
		$validation = $validate->check($_POST, array(	
			'comment' => array(
				'required' => true,
				'min' => 5,
				'max' => 40
			),	
		));
		if(!$post = Input::get('post')){
			exit("sorry there was a post error");
		}
		if($validation->passed()){
			if($comment = Input::get('comment')){
				$date = date('Y-m-d H:i:s');
				try{
					$user->create('comment','comments',array(
						'userId' => $user->data()->id,
						'postID' => $post,
						'username' => $user->data()->username,
						'gender' => $user->data()->gender,
						'comment' => $comment,
						'timeStamp' => $date
					));
				} catch(Exception $e){
					die("ERROR:".$e->getMessage());
				}
?>
		<div class="comment-content">
			<div class="commentImage">
				<img onError="this.src = 'images/profile/small-default-profile-pic.png'" src="images/profile/small-<?php echo escape($user->data()->id); ?>-<?php echo escape($user->data()->username); ?>.jpg">
			</div>
			<div class="comment-right">
				<div class="CommentHolder">
					<?php echo escape($comment); ?>
				</div>	

				<div class="timeHolder">
					<img style="margin-right:5px;" src="<?php echo escape($user->data()->gender); ?>">By <a class="none blue" href="profile.php?user=<?php echo escape($user->data()->username); ?>"><?php echo escape($user->data()->username); ?></a>
					<span class="textGreen">Submitted On</span>
					<span style="font-size: 10pt; color:#58585d;"><?php echo escape($date); ?></span>
				</div>
			</div>
		</div>	
<?php
			}else{
				exit("ERROR:sorry no goal");
			}
		}else{
			foreach ($validation->errors() as $error) {
				echo "ERROR:$error<br>";

			}
		}
	}
}else{
	echo Input::get('comment');
	echo "ERROR:sorry there was a problem";
}




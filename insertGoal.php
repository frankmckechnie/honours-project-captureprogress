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
			'goalTitle' => array(
				'required' => true,
				'min' => 5,
				'max' => 40
			),	
		));
		if($validation->passed()){
			if($goal = Input::get('goalTitle')){
				$date = date('Y-m-d H:i:s');
				try{
					$user->create('goal','goals',array(
						'title' => $goal,
						'date' => date('Y-m-d H:i:s'),
						'completed' => 0,
						'userID' => $user->data()->id
					));
				} catch(Exception $e){
					die("ERROR:".$e->getMessage());
				}
				$goals = DB::getInstance()->query("SELECT * FROM goals WHERE userID = ? AND date = ?", array($user->data()->id, $date))->results();


?>
				<div class="list goalList">
				<div class="titleHolder">
					<a href="#" class="none"><?php echo escape($goals[0]->title); ?></a>
				</div>		
				<div class="line goalLine"></div>
				        <div class="timeHolder">
					    <img style="margin-right:5px;" src="<?php echo escape($user->data()->gender); ?>">By <a class="none blue" href="profile.php?user=<?php echo escape($user->data()->username); ?>"><?php echo escape($user->data()->username); ?></a>
					    <span style="font-size: 10pt; color:#58585d;"><?php echo escape($goals[0]->date); ?></span>
					
				   </div>
				</div>
				<div class="tokenRefresh x" data-action="deleteGoal.php" data-post="<?php echo escape($goals[0]->id); ?>" ></div>
				<div class="tokenRefresh tick" data-action="complete.php" data-post="<?php echo escape($goals[0]->id); ?>" ></div>
<?php
				// return this 
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
	echo "ERROR:sorry there was a problem";
}




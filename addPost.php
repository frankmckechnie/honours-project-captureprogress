<?php
require_once 'core/init.php';
$title = "Creating new Post";
$user = new User();
if($user->hasPermission('banned')){
	 exit("you are banned");
}
if(!$user->isLoggedIn()){
	Redirect::to('index.php');
		exit('
		<div id="container">
		<div class="form-main">
		<p class="coverStory">The purpose of Capture Progress is to provide a way for particular users that preform fitness related tasks to be able to share any progress online.</p>
		<div class="red loginBar" > You need to login in or register</div>
		<div class="form-div">
		<a href="register.php" class="trans button grey"> Register</a>
		<a href="login.php" class="trans mLeft button green greenB" type="submit">Log In</a>
		</div>
		</div>
		</div>');
}
include 'includes/header.php';
echo "<div class='wrap'>";
include 'includes/nav.php';
echo "<div style='display:none' class='warning red'></div>";
?>


	<div class="content">
		<div class="contentCenter">
			<div class="bar green">
				<h1>New Post Form</h1>
			</div>

			

			<form id="ajaxRequest" data-name="insertPost.php" method="post" enctype="multipart/form-data">
				<div class="form-div">
					<label class="formLable" for="pTitle">Post Title</label>
					<input class="feedback-input name" type="text" placeholder="Lost 5 Pounds in two weeks" name="pTitle" id="pTitle" value="" required>
			
					<label class="formLable" for="desc">Post Description</label>
					<textarea class="feedback-input summary" name="desc" placeholder="Lost 5 Pounds in two weeks, this is how it was done" id="desc" cols="30" rows="5"></textarea>
			
					<label class="formLable" for="public">
						<input type="checkbox" name="public" id="public" > Click on to make your post public ? 
					</label>
				</div>
				<div class="space"></div>
				<div class="red loginBar "> You must add at least one image to a post!</div>
				<div class="form-div-two">
					
				    <div class="space"></div>
					<label class="formLable" for="titleOne">Image title one</label>
					<input class="feedback-input image" type="text" name="titleOne" id="titleOne" required> 
					<input name="userfile[]" type="file" required/><br />
					<div class="space"></div>


					<label class="formLable" for="titleTwo"><br>The last two Images are Optional! <br><br>Image title two</label>
					<input class="feedback-input image" type="text" name="titleTwo" id="titleTwo" > 
					<input name="userfile[]" type="file"/>
					<div class="space"></div>
			
					<label class="formLable" for="titleThree">Image title three</label>
					<input class="feedback-input image" type="text" name="titleThree" id="titleThree" >
					<input name="userfile[]" type="file" /><br />

					<input class="button green greenB" type="submit" value="Create Post" />
					<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
				</div>
			</form>


		</div>
	</div>
</div>
</body>
</html>
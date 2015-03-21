<?php
require_once 'core/init.php';
$title = "Creating new Post";
$user = new User();
if(!$user->isLoggedIn()){
	Redirect::to('index.php');
}
include 'includes/header.php';
include 'includes/nav.php';
?>
<h1>New post!</h1>

<form id="ajaxRequest" data-name="insertPost.php" method="post" enctype="multipart/form-data">
	<div class="field">
		<label for="pTitle">Post Title</label>
		<input type="text" name="pTitle" id="pTitle" value="" >
	</div>
	<div class="field">
		<label for="desc">Post Description</label>
		<textarea name="desc" id="desc" cols="30" rows="5"></textarea>
	</div>
	<div class="field">
		<label for="public">
			<input type="checkbox" name="public" id="public" > Click on to make your post public ? 
		</label>
	</div>
	</br>

	YOu must add at least one image to a post:
	</br>
	<div class="field">
		<label for="titleOne">
		Image Title 
			<input type="text" name="titleOne" id="titleOne" > 
		</label>
	</div>
	<input name="userfile[]" type="file" /><br />
	</br>

	Images 2 and 3 are optional
	<div class="field">
		<label for="titleTwo">
		Image Title 
			<input type="text" name="titleTwo" id="titleTwo" > 
		</label>
	</div>
	<input name="userfile[]" type="file" /><br />
	</br>
	<div class="field">
		<label for="titleThree">
		Image Title 
			<input type="text" name="titleThree" id="titleThree" >
		</label>
	</div>
	<input name="userfile[]" type="file" /><br />

	</br>
	<input type="submit" value="Create Post" />
	<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">

</form>

<div id="message"></div>
</body>
</html>
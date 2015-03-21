	echo "<ul class='load_content' id='posts'>";
	foreach ($results as $post) {
		$like = DB::getInstance()->query("SELECT * FROM likes WHERE postID = ? AND userID = ?", array($post->id, $data->id))->results(); 
		if (!$like[0]->userID == $data->id){
			$vote = "nun";
			$image = "background-image: url('images/up.png')";
		}else{
			$vote = "down";
			$image = "background-image: url('images/down.png')";
		}
		$id = $post->id;
		$likeCount = $post->like_count;
?>
		<li>
			<a href="viewPost.php?post=<?php echo escape($post->id); ?>"><?php echo escape($post->title); ?></a>
			<?php echo escape($post->timeStamp); ?>
			<br> By <a href="profile.php?user=<?php echo escape($post->username); ?>"><?php echo escape($post->username); ?></a>
			Gender = <?php echo escape($post->gender);  ?></br>
			<div class="upVote" style="<?php echo $image; ?>" data-vote="<?php echo $vote; ?>" data-action="upVote.php" data-like="<?php echo escape($post->like_count);  ?>" data-post="<?php echo escape($post->id); ?>"> votes <?php echo escape($post->like_count);  ?> <img class="up"></div>  
		</li>
<?php
	}

	echo "<div class='button_container'><div class='button dark' data-like='$likeCount' id='loadMore' data-post='$id'>Load More</div></div>";
	echo "</ul>";
	echo "<div id='result'></div>";
}
?>
<script>

	$(document).on("click",".button_container div",function() {
		var postid = $(this).data('post');
		var likeCount = $(this).data('like');
		$.ajax({
			url: "loadMore.php", // Url to which the request is send
			type: "Post",             // Type of request to be send, called as method
			data: { post: postid, likeCount: likeCount }, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
			success: function(data) {  // A function to be called if request succeeds
				if(data.indexOf("END") > -1){
					console.log(data);
					$("#loadValue").html("No More Content");
				}
				$(".button_container").remove();
				$(".load_content").append(data);
				bindThis();
			},
			error: function(data){
				$("#result").html(data);
			}
		});	
	});
</script>
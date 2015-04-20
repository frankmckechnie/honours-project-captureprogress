$(document).ready(function (e) {

	$(window).load(function() {
		$('#loading').hide();
	});

	 mnav = $("#mnav");

	 image = $("#activate");
	 
	 image.click(function(){
	   mnav.slideToggle(500);
	 });
		 
	$('.uiButton').click(function() {
	    $(this).toggleClass('uiButtonActive');
	});

	$( window ).resize(function() {
		var winW = $( window ).width();
		if ( winW > 751 ){
		mnav.hide();
		$('.uiButton').removeClass('uiButtonActive');
		}else{
		}
	});
	$(window).scroll(function(){  
		if ($(this).scrollTop() > 200) {
		 $('.uparrow').fadeIn();
		}else {
		 $('.uparrow').fadeOut();
		 } 
	});


	setTimeout(function(){$(".warning").fadeOut();}, 4000);

	$(".tokenRefresh").on("click",function() {
		$('#loading').show();
		var postid = $(this).data('post');
		var action = $(this).data('action');
		var option = $(this).data('option');
		$.getJSON("tokenSecurity.php", function(result) {
		    var currentToken = result[0].myVariable;
		    postData(action,postid,currentToken,option);
		});

	});

	$(".warning").on("click",function() {
		$(".warning").fadeOut();
	});

	$("#showGoal").on("click",function() {
		$("#goalForm").fadeIn();
		$(".overlay").fadeIn();
	});

	$(".overlay").on("click",function() {
		$("#goalForm").fadeOut();
		$(".overlay").fadeOut();
	});

	
	function optionGoal() {
		$('#loading').show();
		var thisItem = $(this);
		var postid = thisItem.data('post');
		var action = thisItem.data('action');
		var option = thisItem.data('option');
		$.getJSON("tokenSecurity.php", function(result) {
		    var currentToken = result[0].myVariable;
		    postData(action,postid,currentToken,option);
		});

	};


	function postData(action,postid,currentToken,option){
		$.ajax({
			url: action, // Url to which the request is send
			type: "Post",
			async:false,             // Type of request to be send, called as method
			data: { post: postid, token: currentToken, option: option }, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
			success: function(data) {  // A function to be called if request succeeds
				$('#loading').hide();
				$(".warning").html(data);
				warningFade();
			},
			error: function(data){
				$('#loading').hide();
				$(".warning").html(data);
			}
		});
	};

	$("#orderby").on("click",".change",function() {
		$('#loading').show();
		var order = $(this).data('order');
		$.ajax({
			url: "orderBy.php", // Url to which the request is send
			type: "POST",             // Type of request to be send, called as method
			data: {order: order}, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
			success: function(data) {  // A function to be called if request succeeds
				$('#loading').hide();
				$("#posts").html(data);
				bindThis();
			},
			error: function(data){
				$("#posts").html(data);
			}
		});

	});

	var upVote = function(){
		var thisItem = $(this);
		var postid = thisItem.data('post');
		var action = thisItem.data('action');
		var like = thisItem.data('like');
		var vote = thisItem.attr("data-vote")
		$( ".upVote" ).unbind( "click", upVote );
		$.getJSON("tokenSecurity.php", function(result) {
		    var currentToken = result[0].myVariable;
			console.log("new likes"+like);
			$.ajax({
				url: action, // Url to which the request is send
				type: "Post",             // Type of request to be send, called as method
				data: { post: postid, token: currentToken, vote: vote }, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
				success: function(data) {  // A function to be called if request succeed
					console.log("this is the data: " + data);
					if(data.indexOf("YES") > -1){
						if(vote == "up"){
							console.log("it = up" + vote);
							var uplike = like;
							thisItem.attr('data-vote', 'down');	
						}else{
							console.log("it = whoknows" + vote);
							var uplike = like + 1;
						}
						thisItem.html("<span class='greentext'>"+ uplike +"</span>" );
						thisItem.css({"background-image": "url('css/icons/down.png')", "background-position": "bottom"});
					}else if(data.indexOf("NO") > -1){
						$("#result").html(data);
					}else if(data.indexOf("DOWN") > -1){
						if(vote == "down"){
							var downLike = like - 1;
							thisItem.attr('data-vote', 'up');	
						}else{
							var downLike = like;
						}
						thisItem.css({"background-image": "url('css/icons/up.png')", "background-position": "top"});
						thisItem.html("<span class='greentext'>"+downLike+"</span>" );
					}
				},
				complete: function(data){
					bindThis();
				}

			});
		
		});
	};

	function bindThis(){
		$( ".upVote" ).bind( "click", upVote );
	};
	bindThis();

	$("#ajaxRequest").on('submit',(function(e) {
		$('#loading').show();
		e.preventDefault();
		$.ajax({
			url: $(this).data('name'), // Url to which the request is send
			type: "POST",             // Type of request to be send, called as method
			data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
			contentType: false,       // The content type used when sending data to the server.
			cache: false,             // To unable request pages to be cached
			processData: false,
			     // To send DOMDocument or non processed data file it is set to false
			success: function(data) {  // A function to be called if request succeed
				$('#loading').hide();
				console.log(data);	
				$(".warning").html(data);
				$(".warning").fadeIn();
				warningFade();
				window.scrollTo(0, 0);
			},
			error: function(data){
				$(".warning").html(data);
			}
		});

	}));
	if ( $('div.comment-content').length < 5 ) {
		var n = $('div.comment-content').length;
		$("#loadMore").attr('data-row', n);	
		$("#loadMore").hide();
	}else{
		var n = 5;
		$("#loadMore").attr('data-row', n);	
	}

	$("#deleteAccount").on('click',(function(e) {
		if($("input[name=answer]:checked").val() == "no"){
			alert("you answered no!");
		}else{
			$.getJSON("tokenSecurity.php", function(result) {
			var answer = $("input[name=answer]:checked").val();
		    var currentToken = result[0].myVariable;
				$.ajax({
					url: "deleteAccount.php", // Url to which the request is send
					type: "Post",             // Type of request to be send, called as method
					data: { answer: answer, token: currentToken}, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
					success: function(data) {  // A function to be called if request succeeds
						console.log("hey"+ data);
					},
					error: function(data){
						$(".warning").html(data);
					}
				});
			});
		}

	})); 

	$("#loadMore").on('click',(function(e) {
		var loadButton = $(this);
		loadButton.attr("disabled", "disabled");
		loadButton.text(" ");
		loadButton.addClass('commentLoad');
		var count = $("#loadMore").attr('data-row');	
		var postID = $('.post-main').data('id');
		$.ajax({
			url: "loadMore.php", // Url to which the request is send
			type: "Post",             // Type of request to be send, called as method
			data: { count: count, post: postID}, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
			success: function(data) {  // A function to be called if request succeeds
				if(data.indexOf("LASTROW") > -1){
					loadButton.removeClass('commentLoad');
					loadButton.text("No More");
					$("#addComment").append(data);
					$("#loadMore").fadeOut( "slow");
					
				}else{
					var n = count * 2;
					loadButton.attr('data-row', n);	
					$("#addComment").append(data);
					loadButton.removeClass('commentLoad');
					loadButton.text("Load More");
					loadButton.prop('disabled', false);
				}
				
			},
			error: function(data){
				$(".warning").html(data);
			}
		});

	})); 

	$("#commentRequest").on('click',(function(e) {
		var commentButton = $(this);
		commentButton.attr("disabled", "disabled");
		commentButton.val("");
		commentButton.addClass('commentLoad');
		var postID = $('.post-main').data('id');
		var commentTitle = $('#newComment').val();
		e.preventDefault();
		$.getJSON("tokenSecurity.php", function(result) {
		    var currentToken = result[0].myVariable;
		    addComment(commentTitle,currentToken,postID,commentButton);
		});

	})); 

	function addComment(commentTitle,currentToken,postID,commentButton){
		$.ajax({
			url: "insertComment.php", // Url to which the request is send
			type: "Post",             // Type of request to be send, called as method
			data: { comment: commentTitle, token: currentToken, post: postID}, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
			success: function(data) {  // A function to be called if request succeeds
				if(data.indexOf("ERROR") > -1){
					$(".warning").fadeIn();
					$(".warning").html(data);
					warningFade();
					window.scrollTo(0, 0);
				}else{
					$("#addComment").prepend(data);
				}
				commentButton.removeClass('commentLoad');
				commentButton.val("Post Comment");
				commentButton.prop('disabled', false);
			},
			error: function(data){
				$(".warning").html(data);
			}
		});
	};

	$('#select-all').click(function(event) {   
	    if(this.checked) {
	        // Iterate each checkbox
	        $(':checkbox').each(function() {
	            this.checked = true;                        
	        });
	    }else{
	        $(':checkbox').each(function() {
	            this.checked = false;                        
	        });
	    }
	});

	$("#addGoal").on("click",function() {
		$("#goalForm").fadeOut();
		$(".overlay").fadeOut();
		var goalTitle = $("#goalVal").val();
		$.getJSON("tokenSecurity.php", function(result) {
		    var currentToken = result[0].myVariable;
		    addGoal(goalTitle,currentToken);
		});

	});

	function addGoal(goalTitle,currentToken){
		$.ajax({
			url: "insertGoal.php", // Url to which the request is send
			type: "Post",             // Type of request to be send, called as method
			data: { goalTitle: goalTitle, token: currentToken}, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
			success: function(data) {  // A function to be called if request succeeds
				console.log(data);
				if(data.indexOf("ERROR") > -1){
					$(".warning").removeClass("green");
					$(".warning").addClass("red");
					$(".warning").html(data);
					$(".warning").fadeIn();
					warningFade();
				}else{
			    	$(".listHolder").prepend(data);
		    		var option = $( ".tokenRefresh" ).bind( "click", optionGoal );
		    		$('.dis').hide();
		    		$('#goalVal').val("");
				}
			},
			error: function(data){
				$(".warning").html(data);
			}
		});
	};


	$(".report").on("click",function() {
		var thisRe = $(this);
		var imageID = thisRe.data('id');
		$.getJSON("tokenSecurity.php", function(result) {
		    var currentToken = result[0].myVariable;
		    report(thisRe,imageID,currentToken);
		});
	});

	function report(thisRe,imageID,currentToken){
		$.ajax({
			url: "report.php", // Url to which the request is send
			type: "Post",             // Type of request to be send, called as method
			data: { imageID: imageID, token:currentToken }, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
			success: function(data) {  // A function to be called if request succeed
				if(data.indexOf("YES") > -1){
					$(".warning").fadeIn();
					$(".warning").html(data);
					thisRe.css({"opacity": "0.5"});
					warningFade();
					window.scrollTo(0, 0);
				}else if(data.indexOf("NO") > -1){
					$(".warning").fadeIn();
					$(".warning").html(data);
					var a = thisRe.css({"opacity": "0.5"});
					warningFade();
					window.scrollTo(0, 0);
				}else{
					$(".warning").fadeIn();
					$(".warning").html(data);
					warningFade();
					window.scrollTo(0, 0);
				}
			}
		});
	};

	$( ".mSettings" ).click(function() {
	  $( '.menuSet').slideToggle( "slow" );
	});

});
 

function warningFade(){
	setTimeout(function(){$(".warning").fadeOut();}, 4000);	
}

(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-61728145-1', 'auto');
ga('send', 'pageview');


<!DOCTYPE html>
<head>
<!-- search engine  optimization  for websites-->
<title><?php echo $title;?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<!-- end of search engine optimization for websites-->

<!-- js files for websites-->
<script type="text/javascript" src="js/modernizr.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="js/ajax.js"></script>
<script type="text/javascript" src="js/jquery.dropdown.js"></script>
<!--  end of js files for websites-->

<!-- the css styles -->
<link type="text/css" rel="stylesheet" href="css/jquery.dropdown.css" />
<link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,900,300italic,400italic,700italic,900italic' rel='stylesheet' type='text/css'>
<link href="css/reset.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="css/whiteTheme.css">
<!-- end of styles-->
</head>
<body>

	<div class="wrap">
		<div class="header">
			<div class="headerCenter">

				<div class="logo"></div>
				<div class="nav">
					<ul class="">
						<li class="user"><a href="#">User</a></li>
						<li class="posts"><a href="#">Posts</a></li>
						<li class="home"><a href="#">Home</a></li>
						<li class="settings" data-dropdown="#dropdown-5"><a href="#">Settings</a></li>
					</ul>
				</div>
				<div id="dropdown-5" class="dropdown dropdown-tip has-icons">
					<ul class="dropdown-menu">
						<li class="update"><a href="#">Update Details</a></li>
						<li class="change"><a href="#">Change Password</a></li>
						<li class="logout"><a href="#">Logout</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>

</body>
</html>

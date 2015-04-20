<!DOCTYPE html>
<head>
<!-- search engine  optimization  for websites-->
<title><?php echo $title;?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<!-- end of search engine optimization for websites-->

<!-- js files for websites-->
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

<a href="#" data-dropdown="#dropdown-5">dropdown</a>

	<div id="dropdown-5" class="dropdown dropdown-tip has-icons">
		<ul class="dropdown-menu">
			<li class="undo"><a href="#">Undo</a></li>
			<li class="redo"><a href="#">Redo</a></li>
		</ul>
	</div>

</body>
</html>

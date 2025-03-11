<?php
	session_start();
?>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>ISBN Lookup</title>
<link type="text/css" rel="stylesheet" href="table.css" />
<script type="text/javascript" language="javascript" src="javascript.js"></script>
<script type="text/javascript" language="javascript" src="table.js"></script>
<script type="text/javascript" src="spin.js"></script>
</head>

<STYLE TYPE="text/css">
  H2 { font-size: large; color: red }
</STYLE>

<body onload="fillPage('',0)">

<div id="error_area">
	<?php
		if (isset($_SESSION['mysql_result']))
		{
			if ($_SESSION['mysql_result'] == True)
			{
				echo "<h2>Book successfully added/updated.</h2>".PHP_EOL;
			}
			else
			{
				echo "<h2>Book not added/updated!</h2>".PHP_EOL;
				echo "<ul>".PHP_EOL;
				echo "<li/>".$_SESSION['mysql_error'];
				echo "</ul>";
			}
			$_SESSION['mysql_result'] = null;
			$_SESSION['mysql_error'] = null;
		}
	?>
</div>

<script>
	setTimeout(function()
	{
		document.getElementById('error_area').style.display = 'none';
	}, 10000);	
</script>

<form id="input_controls">
<input type="radio" name="action" id="browse" value="browse" checked="true" onclick="fillPage('',0);" />
<label for="browse">Browse/Update</label>
<input type="radio" name="action" id="add" value="add" onclick="fillPage('',0);" />
<label for="add">Add New Book</label>
<input type="radio" name="search" id="add" value="search" onclick="fillPage('',0);" />
<label for="search">Add New Book</label>
<span id="waitbox" class="idle"></span>
<script>
var opts = {
  lines: 7, // The number of lines to draw
  length: 5, // The length of each line
  width: 4, // The line thickness
  radius: 10, // The radius of the inner circle
  rotate: 0, // The rotation offset
  color: '#FF0000', // #rgb or #rrggbb
  speed: 1.1, // Rounds per second
  trail: 38, // Afterglow percentage
  shadow: false, // Whether to render a shadow
  hwaccel: false, // Whether to use hardware acceleration
  className: 'spinner', // The CSS class to assign to the spinner
  zIndex: 2e9, // The z-index (defaults to 2000000000)
  top: 0, // Top position relative to parent in px
  left: 410 // Left position relative to parent in px
};
var target = document.getElementById('header');
var spinner = new Spinner(opts).spin(target);
spinner.stop();
</script>
</form>
<div id="results_area"></div>
</body>
</html>

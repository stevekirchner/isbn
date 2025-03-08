<?php
   session_start();
	$db = mysqli_connect("localhost", "root", "", "books") or die(mysqli_error($db));


	$sql_start = "INSERT INTO books (";
	$sql_values = ") VALUES (";

	$first_flag = True;  // Flag to keep track of the first item in the values

	// iterate through each item in the post and build the sql insert statement
	foreach($_POST as $key=>$value)
	{
	    // Trim off leading or trailing whitespace
	    $value = trim($value);
		
		if(($value != "") && ($key != "tableName"))
		{
			if(!$first_flag)
			{
				$sql_start .= ", ";
				$sql_values .= ", ";
			}						
			
			$sql_start .= $key;
			$string = $value;
			$sql_values .= sprintf("'%s'", mysqli_real_escape_string($db, $string));
			$first_flag = False;

			// save off the data to repopulate the form in case the
			// insert is unsuccessful.
			$_SESSION[$key]=$value;
		}
	}
	
	$sql = $sql_start . $sql_values . ")";
	
	try
	{
	   $_SESSION['mysql_result'] = mysqli_query($db, $sql);
	}
	catch (Exception $e)
	{
		# Store the error message
		$_SESSION['mysql_result'] = False;
		$_SESSION['mysql_error'] = mysqli_error($db);
	}

	mysqli_close($db);

	// redirect back to the comments page
	header("Location: " . $_GET['redirect']);
?>
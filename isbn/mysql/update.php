<?php
	session_start();

	$db = mysqli_connect("localhost", "root", "", "books") or die(mysqli_error($db));

	$sql_start = "UPDATE books SET ";
	$sql_values = "";

	$first_flag = True;  // Flag to keep track of the first item in the values

	// iterate through each item in the post and build the sql insert statement
	foreach($_POST as $key=>$value)
	{
	    // Trim off leading or trailing whitespace
	    $value = trim($value);
	    		
		if(($key != "tableName") && ($key != "id"))
		{			
			if(!$first_flag)
			{
				$sql_values .= ", ";
			}
			$sql_values .= sprintf("%s='%s'", $key, mysqli_real_escape_string($db, $value));
			$first_flag = False;

			// save off the data to repopulate the form in case the
			// insert is unsuccessful.
			$_SESSION[$key]=$value;
		}
	}
		
	$id = $_POST['id'];
	$sql = $sql_start . $sql_values . " WHERE id=" . $id;

    try
	{
	   $_SESSION['mysql_result'] = mysqli_query($db, $sql);
	}
	catch (Exception $e)
	{
		# Store the error message
		$_SESSION['mysql_error'] = mysqli_error($db);
	}	

	mysqli_close($db);

	// redirect back to the comments page
	header("Location: " . $_GET['redirect']);
?>
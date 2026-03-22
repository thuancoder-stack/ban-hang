<?php 
	$servername = "localhost";
	$username = "root";
	$password = "";
	$database = "ban-hang";
	
	
	$con = new mysqli($servername, $username, $password, $database);

	// Check connection
	if ($con->connect_error) {
	    die("Connection failed: " . $con->connect_error);
	} else {
		//echo "Database is activing . ";
		//echo "<br>";
		//echo "<a href='index.html'>Back to list</a>";
	}
	
?>

<!DOCTYPE html>
<html>
<head>
	<title>REGISTER</title>
</head>
<body>
	<div id="main">
		<h1>REGISTER DB_USER</h1>
		<form method="POST">
			Username <input type="text" name="username" class="text" autocomplete="off" required>
			Password <input type="password" name="password" class="text" required>
			<input type="Submit" name="submit" id="sub">
		</form>
	</div>
</body>
</html>

<?php

	if(isset($_POST['submit'])){
		$un=$_POST['username'];
		$pw=$_POST['password'];

		//try this
		$servername = "sql1.njit.edu";
		$username = "npm26";
		$password = "DBPassword1!";
		$dbname = "npm26";

		try {
    		$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
   			// set the PDO error mode to exception
    		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    		$sql = "INSERT INTO users (username, password)
    		VALUES ('$un',sha1('$pw'))";
    		// use exec() because no results are returned
    		$conn->exec($sql);
    		echo "New record created successfully";
    	}
		catch(PDOException $e)
    	{
    		echo $sql . "<br>" . $e->getMessage();
    	}

	$conn = null;

	}

?>

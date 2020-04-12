<!DOCTYPE html>
<html>
<head>
	<title>LOGIN</title>
</head>
<body>
	<div id="main">
		<h1>Simple Login</h1>
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
		$pw=sha1($_POST['password']);

		//try this
		$servername = "sql1.njit.edu";
		$username = "npm26";
		$password = "DBPassword1!";
		$dbname = "npm26";

		try {
    		$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
   			// set the PDO error mode to exception
    		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    		//query
    		$q = $conn->query("SELECT password FROM users WHERE username='$un'");
    		$passhash = $q->fetchColumn();
    	}

		catch(PDOException $e){
    		echo $sql . "<br>" . $e->getMessage();
    	}

    if($pw==$passhash){
    	echo "\n\n\n\n\n";
    	echo "SUCESSFULL login";
    	echo "\n\n\n\n\n";
    }

    if($pw!=$passhash){
       	echo "\n\n\n\n\n";
    	echo "UNSUCESSFULL login";
    	echo "\n\n\n\n\n";
    }

	$conn = null;

	}

?>

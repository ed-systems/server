<?php
	
	$input_data = json_decode(file_get_contents('php://input'), true);
	
		$tok=$input_data['token'];
		$n=$input_data['name'];
		$d=$input_data['description'];
		$t=$input_data['task'];
		$i1=$input_data['input1'];
		$o1=$input_data['output1'];
		$i2=$input_data['input2'];
		$o2=$input_data['output2'];
		$id=$input_data['ID'];


		//try this
		$servername = "sql1.njit.edu";
		$username = "npm26";
		$password = "DBPassword1!";
		$dbname = "npm26";

		try {
    		$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
   			// set the PDO error mode to exception
    		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    		if($id==''){
    			$sql = "INSERT INTO questions (name, description, task, input1, output1, input2, output2)
    			VALUES ('$n', '$d', '$t', '$i1', '$o1', '$i2', '$o2')";
    			// use exec() because no results are returned
    			$conn->exec($sql);
    			$message = "New question record created successfully";
    			json_encode($message);
    			echo $message;
    		}
    		else{
    			//update
    			//$conn->exec("UPDATE auth_table SET token='$tok' WHERE id = '$uid'");
    			//$conn->exec("UPDATE questions SET (name, description, task, input1, output1, input2, output2) VALUES ('$n', '$d', '$t', '$i1', '$o1', '$i2', '$o2') WHERE id = '$id'");
    			////////////$conn->exec("UPDATE questions SET name='$n',description='$d',task='$t',input1='$i1',output1='$o1',input2='$i2',output2='o2' WHERE id = '$id'");
    			$conn->exec("UPDATE questions SET name='$n' WHERE id='$id'");   			
    			$conn->exec("UPDATE questions SET description='$d' WHERE id='$id'");
    			$conn->exec("UPDATE questions SET task = '$t' WHERE id = '$id'");
    			//str_replace("'", "\'", $t);
    			$conn->exec("UPDATE questions SET task='$t' WHERE id='$id'");
    			$conn->exec("UPDATE questions SET input1='$i1' WHERE id='$id'");
    			$conn->exec("UPDATE questions SET output1='$o1' WHERE id='$id'");
    			$conn->exec("UPDATE questions SET input2='$i2' WHERE id='$id'");
    			$conn->exec("UPDATE questions SET output2='$o2' WHERE id='$id'");


    			$message = "Existing question record update successfully";
    			json_encode($message);
    			echo $message;
    		}
    	}
		catch(PDOException $e)
    	{
    		echo $sql . "<br>" . $e->getMessage();
    

	$conn = null;

	}

?>

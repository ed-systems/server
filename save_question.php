<?php
	
	$input_data = json_decode(file_get_contents('php://input'), true);
	
		$tok=$input_data['token'];
		$n=$input_data['name'];
		$d=$input_data['description'];
		$t=$input_data['task'];
		$cid=$input_data['constraintID'];
		$did=$input_data['difficultyID'];
		$tid=$input_data['topicID'];
		$i1=$input_data['input1'];
		$o1=$input_data['output1'];
		$i2=$input_data['input2'];
		$o2=$input_data['output2'];
		$i3=$input_data['input3'];
		$o3=$input_data['output3'];
		$i4=$input_data['input4'];
		$o4=$input_data['output4'];
		$i5=$input_data['input5'];
		$o5=$input_data['output5'];
		$i6=$input_data['input6'];
		$o6=$input_data['output6'];

		$fn=$input_data['function_name'];
		
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
				$sql = $conn->prepare("INSERT INTO questions (`constraintID`, `difficultyID`, `topicID`, `name`, `description`, `task`, `input1`, `output1`, `input2`, `output2`, `input3`, `output3`, `input4`, `output4`, `input5`, `output5`, `input6`, `output6`, `functionName`)
				VALUES (:cid, :did, :tid, :n, :d, :t, :i1, :o1, :i2, :o2, :i3, :o3, :i4, :o4, :i5, :o5, :i6, :o6, :fn)");


				$sql->bindParam(':cid', $cid);
				$sql->bindParam(':did', $did);
				$sql->bindParam(':tid', $tid);
				$sql->bindParam(':n', $n);				
				$sql->bindParam(':d', $d);
				$sql->bindParam(':t', $t);
				$sql->bindParam(':i1', $i1);
				$sql->bindParam(':o1', $o1);
				$sql->bindParam(':i2', $i2);
				$sql->bindParam(':o2', $o2);
				$sql->bindParam(':i3', $i3);
				$sql->bindParam(':o3', $o3);				
				$sql->bindParam(':i4', $i4);
				$sql->bindParam(':o4', $o4);
				$sql->bindParam(':i5', $i5);
				$sql->bindParam(':o5', $o5);
				$sql->bindParam(':i6', $i6);
				$sql->bindParam(':o6', $o6);
				$sql->bindParam(':fn', $fn);

				
				$sql->execute();
				



				$message = "New question record created successfully";
				json_encode($message);
				echo $message;




    		}
    		else{
    			//update
    			//$conn->exec("UPDATE auth_table SET token='$tok' WHERE id = '$uid'");
    			//$conn->exec("UPDATE questions SET (name, description, task, input1, output1, input2, output2) VALUES ('$n', '$d', '$t', '$i1', '$o1', '$i2', '$o2') WHERE id = '$id'");
    			////////////$conn->exec("UPDATE questions SET name='$n',description='$d',task='$t',input1='$i1',output1='$o1',input2='$i2',output2='o2' WHERE id = '$id'");


				//$sql = $conn->prepare("UPDATE `questions` SET (`constraintID`=:cid, `difficultyID`=:did, `topicID`=:tid, `name`=:n, `description`=:d, `task`=:t, `input1`=:i1, `input2`=:i2, `input3`=:i3, `input4`=:i4, `input5`=:i5, `input6`=:i6, `output1`=:o1, `output2`=:o2, `output3`=:o3, `output4`=:o4, `output5`=:o5, `output6`=:o6, `fn`=:fn) WHERE id=$id");


				//$sql = $conn->prepare("UPDATE questions (`constraintID`, `difficultyID`, `topicID`, `name`, `description`, `task`, `input1`, `output1`, `input2`, `output2`, `input3`, `output3`, `input4`, `output4`, `input5`, `output5`, `input6`, `output6`, `functionName`)
				//VALUES (:cid, :did, :tid, :n, :d, :t, :i1, :o1, :i2, :o2, :i3, :o3, :i4, :o4, :i5, :o5, :i6, :o6, :fn) WHERE `id`=$id");

				//$sql->bindParam(':id',$id);

				$sql="UPDATE `questions` SET `name` = :n WHERE id = :id'";
				$stmt = $conn->prepare($sql);
				$stmt->bindParam(':n'=$n);
				$stmt->bindParam(':id'=$id);
				$stmt->execute();



				//$conn->prepare("UPDATE questions SET name='$n' WHERE id='$id'")->execute();   			
    			$conn->prepare("UPDATE questions SET description='$d' WHERE id='$id'")->execute(); 
    			$conn->prepare("UPDATE questions SET task = '$t' WHERE id = '$id'")->execute(); 
    			//str_replace("'", "\'", $t);
    			//$conn->exec("UPDATE questions SET task = '$t' WHERE id = '$id'");
    			$conn->prepare("UPDATE questions SET input1='$i1' WHERE id='$id'")->execute(); 
    			$conn->prepare("UPDATE questions SET output1='$o1' WHERE id='$id'")->execute(); 
    			$conn->prepare("UPDATE questions SET input2='$i2' WHERE id='$id'")->execute(); 
    			$conn->prepare("UPDATE questions SET output2='$o2' WHERE id='$id'")->execute(); 
    			$conn->prepare("UPDATE questions SET input3='$i3' WHERE id='$id'")->execute();
				$conn->prepare("UPDATE questions SET output3='$o3' WHERE id='$id'")->execute(); 
				$conn->prepare("UPDATE questions SET input4='$i4' WHERE id='$id'")->execute(); 
				$conn->prepare("UPDATE questions SET output4='$o4' WHERE id='$id'")->execute(); 
				$conn->prepare("UPDATE questions SET input5='$i5' WHERE id='$id'")->execute(); 
				$conn->prepare("UPDATE questions SET output5='$o5' WHERE id='$id'")->execute(); 
				$conn->prepare("UPDATE questions SET input6='$i6' WHERE id='$id'")->execute(); 
    			$conn->prepare("UPDATE questions SET output6='$o6' WHERE id='$id'")->execute(); 

				$conn->prepare("UPDATE questions SET constraintID='$cid' WHERE id='$id'")->execute(); 
				$conn->prepare("UPDATE questions SET difficultyID='$did' WHERE id='$id'")->execute(); 
    			$conn->prepare("UPDATE questions SET topicID='$tid' WHERE id='$id'")->execute(); 
				
				$conn->prepare("UPDATE questions SET functionName='$fn' WHERE id='$id'")->execute(); 


/*
				$sql = $conn->prepare("UPDATE questions SET (`constraintID`, `difficultyID`, `topicID`, `name`, `description`, `task`, `input1`, `output1`, `input2`, `output2`, `input3`, `output3`, `input4`, `output4`, `input5`, `output5`, `input6`, `output6`, `functionName`)
				VALUES (:cid, :did, :tid, :n, :d, :t, :i1, :o1, :i2, :o2, :i3, :o3, :i4, :o4, :i5, :o5, :i6, :o6, :fn) WHERE `id`=$id");


				$sql->bindParam(':cid', $cid);
				$sql->bindParam(':did', $did);
				$sql->bindParam(':tid', $tid);
				$sql->bindParam(':n', $n);				
				$sql->bindParam(':d', $d);
				$sql->bindParam(':t', $t);
				$sql->bindParam(':i1', $i1);
				$sql->bindParam(':o1', $o1);
				$sql->bindParam(':i2', $i2);
				$sql->bindParam(':o2', $o2);
				$sql->bindParam(':i3', $i3);
				$sql->bindParam(':o3', $o3);				
				$sql->bindParam(':i4', $i4);
				$sql->bindParam(':o4', $o4);
				$sql->bindParam(':i5', $i5);
				$sql->bindParam(':o5', $o5);
				$sql->bindParam(':i6', $i6);
				$sql->bindParam(':o6', $o6);
				$sql->bindParam(':fn', $fn);

				echo "before";
				$sql->execute();
				echo "after";
*/



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

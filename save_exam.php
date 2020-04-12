<?php
	
	$input_data = json_decode(file_get_contents('php://input'), true);
		
		$eid=$input_data['id'];
		$uTok=$input_data['token'];
		$n=$input_data['name'];

		$d=$input_data['description'];
		$questions=$input_data['questions'];
        //echo $questions;
        //print_r($input_data);
       // $qid = $questions['questionID'];
        //$pts = $questions['points'];

       // echo $qid;
       // echo $points

		//try this
		$servername = "sql1.njit.edu";
		$username = "npm26";
		$password = "DBPassword1!";
		$dbname = "npm26";

        function generateRandomString($length = 10) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
        }

		try {
    		$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
   			// set the PDO error mode to exception
    		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    		//$eid='';//remove
            //$n="nameofexam";//remove
            //$d="descriptionofexam";//remove
            $q = $conn->query("SELECT id FROM auth_table WHERE token = '$uTok'");
            $uid = $q->fetchColumn();
            $eTok=generateRandomString();
    		if($eid==''){

/*
                function generateRandomString($length = 10) {
                    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    $charactersLength = strlen($characters);
                    $randomString = '';
                    for ($i = 0; $i < $length; $i++) {
                      $randomString .= $characters[rand(0, $charactersLength - 1)];
                     }
                    return $randomString;
                }
*/
                //$eTok=generateRandomString();

                //$q = $conn->query("SELECT id FROM auth_table WHERE token = '$uTok'");
                 //   $uid = $q->fetchColumn();
                    //$uid=999;//remove

                $stmt = $conn->prepare("INSERT INTO exams (creatorID, name, description, examToken) VALUES (:uid, :n, :d, :eTok)");
                $stmt->bindParam(':uid', $uid);
                $stmt->bindParam(':n', $n);
                $stmt->bindParam(':d', $d);
                $stmt->bindParam(':eTok', $eTok);
                $stmt->execute();

    			//$sql = "INSERT INTO exams (creatorID, name, description, examToken)
    			//VALUES ('$uid', '$n', '$d', '$eTok')";
    			// use exec() because no results are returned
    			//$conn->exec($sql);

                $q = $conn->query("SELECT id FROM exams WHERE examToken='$eTok'");
                $eid = $q->fetchColumn();

                //echo "$eid";

                //fetch eid
                //SELECT id FROM questions = qid
                //fetch pts
                //for each question in question do this
                foreach($questions as $question){
                    
                    $qid = $question['questionID'];
                    $pts = $question['points'];
                    $sql = "INSERT INTO exam_questions (examID, questionID, points)
                    VALUES ('$eid', '$qid', '$pts')";
                    $conn->query($sql);
                }
                $message = "New exam record created successfully";
                $m=json_encode($message);
                echo $m;
    		}
            else{
                $conn->exec("UPDATE exams SET name='$n' WHERE id='$eid'");               
                $conn->exec("UPDATE exams SET description='$d' WHERE id='$eid'");
                //$eTok=generateRandomString();
                $conn->exec("UPDATE exams SET examToken='$eTok' WHERE id='$eid'");

                //remove all questionId's, points, and examIds WHERE examID is $eid
                $sql="DELETE FROM exam_questions WHERE examID='$eid'";
                $conn->query($sql);

                foreach($questions as $question){
                    
                    $qid = $question['questionID'];
                    $pts = $question['points'];
                    $sql = "INSERT INTO exam_questions (examID, questionID, points)
                    VALUES ('$eid', '$qid', '$pts')";
                    $conn->query($sql);
                }

                $message = "Exam record updates succesfully";
                $m=json_encode($message);
                echo $m;
            }
    	}
		catch(PDOException $e)
    	{
    		echo $sql . "<br>" . $e->getMessage();
    

	$conn = null;

	}

?>

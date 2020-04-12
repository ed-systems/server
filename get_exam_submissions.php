<?php
  $servername = "sql1.njit.edu";
  $username = "npm26";
  $password = "DBPassword1!";
  $dbname = "npm26";

    /* hardcode POST argument */
    // HERE

        //recieve POST
        $input_data = json_decode(file_get_contents('php://input'), true);

        //pull token
        $tok = $input_data["token"];
        $eid = $input_data["examID"];
        //$eid = 30;
        //echo $eid;
        //echo "<br>";


    //try connect to database
  try {
    
      $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      
      $sql = "SELECT DISTINCT id FROM submissions WHERE examID = '$eid'";

      $subIds=array();

      foreach($conn->query($sql) as $row){
        array_push($subIds,$row['id']);
      }

      //print_r($subIds);
      //echo "<br>";

      $submissions=array();
//echo "here1";
      foreach($subIds as $id){
//      echo "here2";
       
        $subQuery = "SELECT * FROM submited_questions_with_info WHERE subID='$id'";


            $question_rows = $conn->query($subQuery);

            $questionsArr = array("name" => "", "description" => "", "task"=> "", "input1" => "", "output1" => "", "input2" => "", "output2" => "", "solution" => "", "result1" => "", "result2" => "", "autoGrade" => "", "grade" => "", "comments" => "", "ID" => "");
            $arrQarr = array();
            foreach ($question_rows as $q) {
              
              $questionsArr["name"]=$q['name'];
              $questionsArr["description"]=$q['description'];
              $questionsArr["task"]=$q['task'];
              $questionsArr["input1"]=$q['input1'];
              $questionsArr["output1"]=$q['output1'];
              $questionsArr["input2"]=$q['input2'];
              $questionsArr["output2"]=$q['output2'];
              $questionsArr["solution"]=$q['solution'];
              $questionsArr["result1"]=$q['result1'];
              $questionsArr["result2"]=$q['result2'];
              $questionsArr["autoGrade"]=$q['autograde'];
              $questionsArr["grade"]=$q['grade'];
              $questionsArr["comments"]=$q['comments'];
              $questionsArr["ID"]=$q['id'];
              array_push($arrQarr, $questionsArr);
            }

//echo "here4";


        $q = $conn->query("SELECT studentID FROM submissions WHERE id= '$id'");
          $uid = $q->fetchColumn();

        $q = $conn->query("SELECT full_name FROM users WHERE id = '$uid'");
          $sn = $q->fetchColumn();

        $q = $conn->query("SELECT status FROM submissions WHERE id = '$id'");
          $stat = $q->fetchColumn();      

        $q = $conn->query("SELECT autograde FROM submissions WHERE id = '$id'");
          $ag = $q->fetchColumn();
        
        $q = $conn->query("SELECT grade FROM submissions WHERE id = '$id'");
          $g = $q->fetchColumn();
        
        $q = $conn->query("SELECT comments FROM submissions WHERE id = '$id'");
          $c = $q->fetchColumn();


        $submission_obj = array("studentName" => $sn, "examID" => $eid, "studentID" => $uid, "status" => $stat, "autoGrade" => $ag, "grade" => $g, "comments" => $c, "ID" => $id, "questions" => $arrQarr);

        array_push($submissions, $submission_obj);

        // echo "HERE";

        //print_r($submission_obj);








        }




    
  }

  //err handling
  Catch(PDOException $e){
    echo $sql . "<br>" . $e->getMessage();
  }
   
  //terminate connection
  $conn = null;
  

  /* prints array for formating test */
  //print_r($dataArr);
    
    //encodes dataArr in json formatting
    $output_data = json_encode($submissions);

    //json header
    header('Content-Type: application/json');
    

    echo $output_data;
    //curl responds with results
    //print_r($jsonArr);
?>
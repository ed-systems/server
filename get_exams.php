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


    //try connect to database
  try {
    
      $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      //get uid from auth table where token match
      $q = $conn->query("SELECT id FROM auth_table WHERE token = '$tok'");
      $uid = $q->fetchColumn();
      //$uid = 9;

        //query view
        $sql = "SELECT DISTINCT examID FROM `exam_questions_with_userid` WHERE creatorID = '$uid'";

        //create array variable for examIds
        $examIds=array();

        foreach ($conn->query($sql) as $row) {
          # code...
          array_push($examIds,$row['examID']);
        }
        //print_r($examIds);
        //echo "<br>";
            //creates array variable for exams
            $exams=array();

            foreach($examIds as $id){
              
              $examQuery = "SELECT * FROM exam_questions_with_userid WHERE examID='$id'";

                  //questions part

                    $question_rows = $conn->query($examQuery);
                    //create array variable for questions
                    $questionsArr = array("questionID"=>"","points"=>"");
                    $arrQarr = array();
                    foreach($question_rows as $q){
                      #code...
                      //array_push($questionsArr, "questionID" => $q['questionID'], "points" => $q['points']);
                      $questionsArr["questionID"]=$q['questionID'];
                      $questionsArr["points"]=$q['points'];
                      array_push($arrQarr, $questionsArr);
                    }
                    
                    
              json_encode($arrQarrr);      


              $q = $conn->query("SELECT examName FROM exam_questions_with_userid WHERE examID = '$id'");
              $n = $q->fetchColumn();

              $q = $conn->query("SELECT examDescription FROM exam_questions_with_userid WHERE examID = '$id'");
              $d = $q->fetchColumn();

              //exam info obj
              $exam_obj = array("name" => "$n", "description"=> "$d", "questions" => $arrQarr, "id" => "$id");
              array_push($exams, $exam_obj);
              //$jsonArr = array();
             // array_push($jsonArr, $exams);      
            }
      //json_encode($jsonArr);  
      //print_r($jsonArr);

    
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
    $output_data = json_encode($exams);

    //json header
    header('Content-Type: application/json');
    

    echo $output_data;
    //curl responds with results
    //print_r($jsonArr);
?>
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
        $sql = "SELECT DISTINCT id FROM exams WHERE creatorID = '$uid'";

        //create array variable for examIds
        $examIds=array();

        foreach ($conn->query($sql) as $row) {
          # code...
          array_push($examIds, $row['id']);
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
                    $questionsArr = array("questionID"=>"","points"=>"", "function_name_points" => "", "constraint_points" => "", "colon_points" => "", "output1_points" => "", "output2_points" => "", "output3_points" => "", "output4_points" => "", "output5_points" => "", "output6_points" => "" );
                    $arrQarr = array();
                    foreach($question_rows as $qr){
                      #code...
                      //array_push($questionsArr, "questionID" => $q['questionID'], "points" => $q['points']);
                      $questionsArr["questionID"]=$qr['questionID'];
                      $questionsArr["points"]=$qr['points'];


                      $qid=$qr['questionID'];
                      
                   ///   $q = $conn->query("SELECT functionNamePoints FROM exam_questions WHERE questionID='$qid' AND examID=$id");
                    ///  $fnp = $q->fetchColumn();
                     /// $questionsArr["function_name_points"]=$fnp;


                      //functionName points
                      $q = $conn->query("SELECT functionNamePoints FROM exam_questions WHERE questionID='$qid' AND examID=$id");
                      $fnp = $q->fetchColumn();
                      $questionsArr["function_name_points"]=$fnp;

                      //constraint points
                      $q = $conn->query("SELECT constraintStringPoints FROM exam_questions WHERE questionID='$qid' AND examID=$id");
                      $csp = $q->fetchColumn();
                      $questionsArr["constraint_points"]=$csp;

                      //colon points
                      $q = $conn->query("SELECT colonPoints FROM exam_questions WHERE questionID='$qid' AND examID=$id");
                      $clnp = $q->fetchColumn();
                      $questionsArr["colon_points"]=$clnp;




                      $q = $conn->query("SELECT output1_points FROM exam_questions WHERE questionID='$qid' AND examID=$id");
                      $op1 = $q->fetchColumn();
                      $questionsArr["output1_points"]=$op1;
                      $q = $conn->query("SELECT output2_points FROM exam_questions WHERE questionID='$qid' AND examID=$id");
                      $op2 = $q->fetchColumn();
                      $questionsArr["output2_points"]=$op2;
                      $q = $conn->query("SELECT output3_points FROM exam_questions WHERE questionID='$qid' AND examID=$id");
                      $op3 = $q->fetchColumn();
                      $questionsArr["output3_points"]=$op3;
                      $q = $conn->query("SELECT output4_points FROM exam_questions WHERE questionID='$qid' AND examID=$id");
                      $op4 = $q->fetchColumn();
                      $questionsArr["output4_points"]=$op4;
                      $q = $conn->query("SELECT output5_points FROM exam_questions WHERE questionID='$qid' AND examID=$id");
                      $op5 = $q->fetchColumn();
                      $questionsArr["output5_points"]=$op5;
                      $q = $conn->query("SELECT output6_points FROM exam_questions WHERE questionID='$qid' AND examID=$id");
                      $op6 = $q->fetchColumn();
                      $questionsArr["output6_points"]=$op6;




                      array_push($arrQarr, $questionsArr);
                    }
                    
                    
              json_encode($arrQarrr);      




#make edits with code below
/*
              //$q = $conn->query("SELECT examName FROM exam_questions_with_userid WHERE examID = '$id'");
              $q = $conn->query("SELECT `name` FROM exams WHERE id='$id'");
              $n = $q->fetchColumn();

             // $q = $conn->query("SELECT examDescription FROM exam_questions_with_userid WHERE examID = '$id'");
              $q = $conn->query("SELECT 'description' FROM exams WHERE id='$id'");
              $d = $q->fetchColumn();
*/
#make edits with code above

//code edit is below!!!
//$q = $conn->query("SELECT 'description' FROM exams WHERE id='$id'");



              $q = $conn->query("SELECT examName FROM exam_questions_with_userid WHERE `examID` = '$id'");


             // $q = $conn->query("SELECT examName FROM exam_questions_with_userid WHERE examID = '$id'");
              $n = $q->fetchColumn();

              $q = $conn->query("SELECT examDescription FROM exam_questions_with_userid WHERE `examName` = '$n'");
              $d = $q->fetchColumn();
              //echo $d;
              //exam info obj
              $exam_obj = array("name" => $n, "description"=> $d, "questions" => $arrQarr, "id" => $id);
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
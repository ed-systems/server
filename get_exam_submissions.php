<?php
  $servername = "sql1.njit.edu";
  $username = "npm26";
  $password = "DBPassword1!";
  $dbname = "npm26";

 

        //recieve POST
        $input_data = json_decode(file_get_contents('php://input'), true);

        //pull token
        $tok = $input_data["token"];
        $eid = $input_data["examID"];



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



      $submissions=array();

      foreach($subIds as $id){

       
        $subQuery = "SELECT * FROM grand_view WHERE subID='$id'";
            $question_rows = $conn->query($subQuery);

            $questionsArr = array("name" => "", "description" => "", "task"=> "", "solution" => "", "function_name" => "", "function_name_points"=>"", "constraint"=>"", "constraint_points"=>"", "colon_points"=>"", "input1" => "", "input2" => "", "input3" => "", "input4" => "", "input5" => "", "input6" => "", "output1" => "", "output2" => "", "output3" => "", "output4" => "", "output5" => "", "output6" => "", "result1" => "", "result2" => "", "result3" => "", "result4" => "", "result5" => "", "result6" => "", "function_name_result" => "", "colon_result" => "", "constraint_result" => "", "output1_points" => "", "output2_points" => "", "output3_points" => "", "output4_points" => "", "output5_points" => "", "output6_points" => "", "result1_points" => "", "result2_points" => "", "result3_points" => "", "result4_points" => "", "result5_points" => "", "result6_points" => "", "function_name_result" => true, "function_name_result_points"=>"", "colon_result" => true, "colon_result_points"=>"", "constraint_result"=>true, "constraint_result_points"=>"", "autoGrade" => "", "grade" => "", "comments" => "", "ID" => "");
            $arrQarr = array();
            foreach ($question_rows as $q) {

              echo "Points here \n";
              echo $q['output6_points'];
              echo "\nPoints here \n";
              
              $questionsArr["output1_points"]=$q['output1_points'];
              $questionsArr["output2_points"]=$q['output2_points'];
              $questionsArr["output3_points"]=$q['output3_points'];
              $questionsArr["output4_points"]=$q['output4_points'];
              $questionsArr["output5_points"]=$q['output5_points'];
              $questionsArr["output6_points"]=$q['output6_points'];
              
              echo "OTHER Points here \n";
              echo $questionsArr['output6_points'];
              echo "\nOTHER Points here \n";

              $questionsArr["name"]=$q['name'];
              $questionsArr["description"]=$q['description'];
              $questionsArr["task"]=$q['task'];
              $questionsArr["input1"]=$q['input1'];
              $questionsArr["input2"]=$q['input2'];
              $questionsArr["input3"]=$q['input3'];
              $questionsArr["input4"]=$q['input4'];
              $questionsArr["input5"]=$q['input5'];
              $questionsArr["input6"]=$q['input6'];
              $questionsArr["output1"]=$q['output1'];
              $questionsArr["output2"]=$q['output2'];
              $questionsArr["output3"]=$q['output3'];
              $questionsArr["output4"]=$q['output4'];
              $questionsArr["output5"]=$q['output5'];
              $questionsArr["output6"]=$q['output6'];
              $questionsArr["solution"]=$q['solution'];
              $questionsArr["result1"]=$q['result1'];
              $questionsArr["result2"]=$q['result2'];
              $questionsArr["result3"]=$q['result3'];
              $questionsArr["result4"]=$q['result4'];
              $questionsArr["result5"]=$q['result5'];
              $questionsArr["result6"]=$q['result6'];
              $questionsArr["result1_points"]=$q['result1_points'];
              $questionsArr["result2_points"]=$q['result2_points'];
              $questionsArr["result3_points"]=$q['result3_points'];
              $questionsArr["result4_points"]=$q['result4_points'];
              $questionsArr["result5_points"]=$q['result5_points'];
              $questionsArr["result6_points"]=$q['result6_points'];
              $questionsArr["autoGrade"]=$q['autograde'];
              $questionsArr["grade"]=$q['grade'];
              $questionsArr["comments"]=$q['comments'];
              $questionsArr["ID"]=$q['id'];

              $questionsArr["function_name"]=$q['functionName'];
              $questionsArr["function_name_points"]=$q['functionNamePoints'];
              $questionsArr["function_name_result"]=$q['functionName_result'];
              $questionsArr["function_name_result_points"]=$q['function_name_result_points'];

              $questionsArr["colon_points"]=$q['colonPoints'];
              $questionsArr["colon_result"]=$q['colon_result'];
              $questionsArr["colon_result_points"]=$q['colon_result_points'];
              
             
              $cid=$q['constraintID'];
              $f = $conn->query("SELECT constraint_string FROM constraints WHERE id=$cid");
              $cn = $f->fetchColumn();
              $questionsArr["constraint"]=$cn;
            
              $questionsArr["constraint_points"]=$q['constraintStringPoints'];
              $questionsArr["constraint_result"]=$q['constraint_result'];
              $questionsArr["constraint_result_points"]=$q['constraint_result_points'];
            

              array_push($arrQarr, $questionsArr);    
            }






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











        }




    
  }

  //err handling
  Catch(PDOException $e){
    echo $sql . "<br>" . $e->getMessage();
  }
   
  //terminate connection
  $conn = null;
  

    
    //encodes dataArr in json formatting
    $output_data = json_encode($submissions);

    //json header
    header('Content-Type: application/json');
    

    echo $output_data;

?>
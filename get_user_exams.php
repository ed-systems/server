<?php
/////issues
##grab all student exams not just one
##submissions id is null
////
// pass json_decode'd data into these 
$json = json_decode(file_get_contents('php://input'), true);

$servername = "sql1.njit.edu";
$username = "npm26";
$password = "DBPassword1!";
$dbname = "npm26";

function gue($json, $conn){  
  //user info
  $usersinfoquery = 'SELECT * FROM `users` WHERE `token`=:token';
  $usersinfo = $conn->prepare($usersinfoquery);
  $usersinfo->bindValue(':token', $json['token']);
  $usersinfo->execute();
  $uinfo = $usersinfo->fetchAll(\PDO::FETCH_ASSOC);
  
  foreach ($uinfo as $info){
    $fname=$info['full_name'];
    $uid=$info['id'];

    //examsubmissioninfo
    $examssubinfoquery = 'SELECT * FROM `submissions` WHERE `studentID`=:s';
    $examssubinfo = $conn->prepare($examssubinfoquery);
    $examssubinfo->bindValue(':s', $uid);
    $examssubinfo->execute();
    $einfo = $examssubinfo->fetchAll(\PDO::FETCH_ASSOC);
    
    $user_exams=array();

    foreach($einfo as $info){

      $eid=$info['examID'];
      $stat=$info['status'];
      $ag=$info['autograde'];
      $g=$info['grade'];
      $c=$info['comments'];
      $subid=$info['id'];      

      $subQuery = "SELECT * FROM grand_view WHERE subID='$id'";
      $question_rows = $conn->query($subQuery);

      $questionsArr = array("name" => "", "description" => "", "task"=> "", "solution" => "", "function_name" => "", "function_name_points"=>"", "constraint"=>"", "constraint_points"=>"", "colon_points"=>"", "input1" => "", "input2" => "", "input3" => "", "input4" => "", "input5" => "", "input6" => "", "output1" => "", "output2" => "", "output3" => "", "output4" => "", "output5" => "", "output6" => "", "result1" => "", "result2" => "", "result3" => "", "result4" => "", "result5" => "", "result6" => "", "function_name_result" => "", "colon_result" => "", "constraint_result" => "", "output1_points" => "", "output2_points" => "", "output3_points" => "", "output4_points" => "", "output5_points" => "", "output6_points" => "", "result1_points" => "", "result2_points" => "", "result3_points" => "", "result4_points" => "", "result5_points" => "", "result6_points" => "", "function_name_result" => true, "function_name_result_points"=>"", "colon_result" => true, "colon_result_points"=>"", "constraint_result"=>true, "constraint_result_points"=>"", "autoGrade" => "", "grade" => "", "comments" => "", "ID" => "");
      $arrQarr = array();
      foreach ($question_rows as $q) {
        
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
      
        $questionsArr["constraint_points"]=$q['constraint_result_points'];
        $questionsArr["constraint_result"]=$q['constraint_result'];
        $questionsArr["constraint_result_points"]=$q['constraint_result_points'];

        $questionsArr["output1_result"]=$q['output1_result'];
        $questionsArr["output2_result"]=$q['output2_result'];
        $questionsArr["output3_result"]=$q['output3_result'];
        $questionsArr["output4_result"]=$q['output4_result'];
        $questionsArr["output5_result"]=$q['output5result'];
        $questionsArr["output6_result"]=$q['output6result'];
        
        array_push($arrQarr, $questionsArr);

        
 
      }



       //echo "here1";
      $q = $conn->query("SELECT `name` FROM `exams` WHERE `id`=$eid");
        $en = $q->fetchColumn();
       // echo json_encode($en);
        //echo "here2";
      $q = $conn->query("SELECT `description` FROM `exams` WHERE `id`=$eid");
        $ed = $q->fetchColumn();
        //echo json_encode($ed);
        //echo "here3";

/*

        $subminfoquery = 'SELECT * FROM `exams` WHERE `id`=:i';
        $subminfo = $conn->prepare($subminfoquery);
        $subminfo->bindValue(':i', $eid);
        $subminfo->execute();
        $minfo = $subminfo->fetchAll(\PDO::FETCH_ASSOC);

       foreach($minfo as $info){

       //echo json_encode($info['name']);
       $en=$info['name'];
       echo json_encode($en);

       //echo json_encode($info['description']);
       $ed = $info['description'];
       echo json_encode($ed);

      //$user_exams_obj = array("studentName" => $fname, "examID" => $eid, "name" = $en, "description" = $ed, "studentID" => $uid, "status" => $stat, "autoGrade" => $ag, "grade" => $g, "comments" => $c, "ID" => $subid, "questions" => $arrQarr);


      }
*/






      $user_exams_obj = array("studentName" => $fname, "examID" => $eid, "examName" => $en, "examDescription" => $ed, "studentID" => $uid, "status" => $stat, "autoGrade" => $ag, "grade" => $g, "comments" => $c, "ID" => $subid, "questions" => $arrQarr);
      //$user_exams_obj = array("studentName" => $fname, "examID" => $eid, "name" = $en, "description" = $ed, "studentID" => $uid, "status" => $stat, "autoGrade" => $ag, "grade" => $g, "comments" => $c, "ID" => $subid, "questions" => $arrQarr);

      array_push($user_exams, $user_exams_obj);
    }
    // echo $minfo['name'];
     //echo json_encode($en);
  }
 
  //encodes dataArr in json formatting
  $output_data = json_encode($user_exams);

  //json header
  header('Content-Type: application/json');
    

  echo $output_data;  

}

try {    
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  gue($json, $conn);  
}

//err handling
Catch(PDOException $e){
  echo $sql . "<br>" . $e->getMessage();
}

$conn = null;

?>



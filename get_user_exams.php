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

      echo "\nOUTPUT HERE\n";
      echo $info['id'];

      $subQinfoquery = 'SELECT * FROM `grand_view` WHERE `subID`=:s';
      echo $subQinfoquery;

      $subQinfo = $conn->prepare($subQinfoquery);
      $subQinfo->bindValue(':s', $subid);
      //echo $subQinfoquery . "\nOUTPUT HERE1\n";
      $subQinfo->execute();
      $qinfo = $subQinfo->fetchAll(\PDO::FETCH_ASSOC);


      $questionsArr = array("output1_points" => "", "output2_points" => "", "output3_points" => "", "output4_points" => "", "output5_points" => "", "output6_points" => "", "name" => "", "description" => "", "task"=> "", "input1" => "", "input2" => "", "input3" => "", "input4" => "", "input5" => "", "input6" => "", "output1" => "", "output2" => "", "output3" => "", "output4" => "", "output5" => "", "output6" => "", "solution" => "", "result1" => "", "result2" => "", "result3" => "", "result4" => "", "result5" => "", "result6" => "", "result1_points" => "", "result2_points" => "", "result3_points" => "", "result4_points" => "", "result5_points" => "", "result6_points" => "", "function_name"=>"", "function_name_points"=>"", "function_name_result"=>"", "function_name_result_points"=>"", "colon_points"=>"", "colon_result"=>"", "colon_result_points"=>"", "constraint"=>"", "constraint_points"=>"", "constraint_result"=>"", "constraint_result_points"=>"", "autoGrade" => "", "grade" => "", "comments" => "", "ID" => "");
      $arrQarr = array();

      foreach($qinfo as $info){
        
        $questionsArr["output1_points"]=$info['output1_points'];
        $questionsArr["output2_points"]=$info['output2_points'];
        $questionsArr["output3_points"]=$info['output3_points'];
        $questionsArr["output4_points"]=$info['output4_points'];
        $questionsArr["output5_points"]=$info['output5_points'];
        $questionsArr["output6_points"]=$info['output6_points'];

        $questionsArr["name"]=$info['name'];
        $questionsArr["description"]=$info['description'];
        $questionsArr["task"]=$info['task'];
        $questionsArr["input1"]=$info['input1'];
        $questionsArr["output1"]=$info['output1'];
        $questionsArr["input2"]=$info['input2'];
        $questionsArr["output2"]=$info['output2'];
        $questionsArr["solution"]=$info['solution'];
        $questionsArr["result1"]=$info['result1'];
        $questionsArr["result2"]=$info['result2'];
        $questionsArr["autoGrade"]=$info['autograde'];
        $questionsArr["grade"]=$info['grade'];
        $questionsArr["comments"]=$info['comments'];
        $questionsArr["ID"]=$info['id'];


           



        $questionsArr["input3"]=$info['input3'];
        $questionsArr["input4"]=$info['input4'];
        $questionsArr["input5"]=$info['input5'];
        $questionsArr["input6"]=$info['input6'];

        $questionsArr["output3"]=$info['output3'];
        $questionsArr["output4"]=$info['output4'];
        $questionsArr["output5"]=$info['output5'];
        $questionsArr["output6"]=$info['output6'];

        $questionsArr["result3"]=$info['result3'];
        $questionsArr["result4"]=$info['result4'];
        $questionsArr["result5"]=$info['result5'];
        $questionsArr["result6"]=$info['result6'];
        $questionsArr["result1_points"]=$info['result1_points'];
        $questionsArr["result2_points"]=$info['result2_points'];
        $questionsArr["result3_points"]=$info['result3_points'];
        $questionsArr["result4_points"]=$info['result4_points'];
        $questionsArr["result5_points"]=$info['result5_points'];
        $questionsArr["result6_points"]=$info['result6_points'];
 

        $questionsArr["function_name"]=$info['functionName'];
        $questionsArr["function_name_points"]=$info['functionNamePoints'];
        $questionsArr["function_name_result"]=$info['functionName_result'];
        $questionsArr["function_name_result_points"]=$info['function_name_result_points'];

        $questionsArr["colon_points"]=$info['colonPoints'];
        $questionsArr["colon_result"]=$info['colon_result'];
        $questionsArr["colon_result_points"]=$info['colon_result_points'];
        
       
        $cid=$q['constraintID'];
        $f = $conn->query("SELECT constraint_string FROM constraints WHERE id=$cid");
        $cn = $f->fetchColumn();
        $questionsArr["constraint"]=$cn;
      
        $questionsArr["constraint_points"]=$info['constraintStringPoints'];
        $questionsArr["constraint_result"]=$info['constraint_result'];
        $questionsArr["constraint_result_points"]=$info['constraint_result_points'];

        
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



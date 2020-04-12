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
  //$token = $json['token'];
  //echo json_encode($token);
  
  //user info
  $usersinfoquery = 'SELECT * FROM `users` WHERE `token`=:token';
  $usersinfo = $conn->prepare($usersinfoquery);
  $usersinfo->bindValue(':token', $json['token']);
  $usersinfo->execute();
  $uinfo = $usersinfo->fetchAll(\PDO::FETCH_ASSOC);

  foreach ($uinfo as $info){
    $fname=$info['full_name'];
    //echo json_encode($fname);
    $uid=$info['id'];
    //echo json_encode($sid);

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
      //echo $subid

      
      //echo json_encode($eid);

      $subQinfoquery = 'SELECT * FROM `submited_questions_with_info` WHERE `subID`=:s';
      $subQinfo = $conn->prepare($subQinfoquery);
      $subQinfo->bindValue(':s', $subid);
      $subQinfo->execute();
      $qinfo = $subQinfo->fetchAll(\PDO::FETCH_ASSOC);


      $questionsArr = array("name" => "", "description" => "", "task"=> "", "input1" => "", "output1" => "", "input2" => "", "output2" => "", "solution" => "", "result1" => "", "result2" => "", "autoGrade" => "", "grade" => "", "comments" => "", "ID" => "");
      $arrQarr = array();

      foreach($qinfo as $info){
        
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
        array_push($arrQarr, $questionsArr);

      }
      $user_exams_obj = array("studentName" => $fname, "examID" => $eid, "studentID" => $uid, "status" => $stat, "autoGrade" => $ag, "grade" => $g, "comments" => $c, "ID" => $subid, "questions" => $arrQarr);

      array_push($user_exams, $user_exams_obj);
    }
     
  }
 
  //encodes dataArr in json formatting
  $output_data = json_encode($user_exams);

  //json header
  header('Content-Type: application/json');
    
  echo $user_exams;
  echo "before null 2";
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
  echo "before null";
  echo $sql . "<br>" . $e->getMessage();
}

$conn = null;

?>



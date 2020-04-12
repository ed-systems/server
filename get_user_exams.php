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



       //echo "here1";
      $q = $conn->query("SELECT * FROM exams WHERE id=$eid");
        $en = $q->fetchColumn();
        //echo $en;
        //echo "here2";
      $q = $conn->query("SELECT description FROM exams WHERE id=$eid");
        $ed = $q->fetchColumn();
        //echo $ed;
        //echo "here3";



        $subminfoquery = 'SELECT * FROM `exams` WHERE `id`=:i';
        $subminfo = $conn->prepare($subminfoquery);
        $subminfo->bindValue(':i', $eid);
        $subminfo->execute();
        $minfo = $subminfo->fetchAll(\PDO::FETCH_ASSOC);

       // foreach($minfo as $info){

       //   echo $info['name'];

      //  }





      $user_exams_obj = array("studentName" => $fname, "examID" => $eid, "studentID" => $uid, "status" => $stat, "autoGrade" => $ag, "grade" => $g, "comments" => $c, "ID" => $subid, "questions" => $arrQarr);
      //$user_exams_obj = array("studentName" => $fname, "examID" => $eid, "name" = $en, "description" = $ed, "studentID" => $uid, "status" => $stat, "autoGrade" => $ag, "grade" => $g, "comments" => $c, "ID" => $subid, "questions" => $arrQarr);

      array_push($user_exams, $user_exams_obj);
    }
     echo $minfo['name'];
     echo json_encode($en);
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



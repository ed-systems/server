<?php

$json = json_decode(file_get_contents('php://input'), true);

$servername = "sql1.njit.edu";
$username = "npm26";
$password = "DBPassword1!";
$dbname = "npm26";


function enroll_student($json, $conn){

  $exmid=$json['examID'];
 

  $q = $conn->query("SELECT creatorID FROM exams WHERE id=$exmid");
    $cid = $q->fetchColumn();
   


  // insert submission with empty grades and comments
  $submissions_insert_query = 'INSERT INTO `submissions` (`examID`, `teacherID`, `studentID`, `status`, `autograde`, `grade`, `comments`) VALUES (?, ?, ?, ?, ?, ?, ?)'; //SELECT LAST_INSERT_ID();';
  $submissions_insert = $conn->prepare($submissions_insert_query);
  $submissions_insert->execute([$json['examID'], $cid, $json['studentID'], 0, 0, 0, ""]);



  $submission_id = $conn->lastInsertId();

  
  $exam_questions_query = 'SELECT * FROM `questions_join` WHERE `examID`=:examID';
 

  $exam_questions = $conn->prepare($exam_questions_query);
  
  
  $exam_questions->bindValue(':examID', $json['examID']);
 
  $exam_questions->execute();
 

  $submitted_questions_insert_query = 'INSERT INTO `submitted_questions` (`examID`, `questionID` ,`subID`, `solution`, `result1`, `result2`, `result3`, `result4`, `result5`, `result6`, `functionName_result`, `colon_result`, `constraint_result`, `result1_points`, `result2_points`, `result3_points`, `result4_points`, `result5_points`, `result6_points`, `function_name_result_points`, `colon_result_points`, `constraint_result_points`, `autograde`, `grade`, `comments`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);';
  
  $submitted_questions_insert = $conn->prepare($submitted_questions_insert_query);

  $questions = $exam_questions->fetchAll(\PDO::FETCH_ASSOC);
 
    
  $pp=$json['examID'];
  echo $pp;
 
  foreach ($questions as $question){
   
    $submitted_questions_insert->execute([$pp,$question['questionID'], $submission_id, "", "", "", "", "", "", "", false, false, false, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, ""]);
  
  }  

}


  try {
    
      $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
      enroll_student($json, $conn);

  }

  //err handling
  Catch(PDOException $e){
    echo $sql . "<br>" . $e->getMessage();
  }

  $conn = null;
?>
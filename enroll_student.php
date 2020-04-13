<?php
// pass json_decode'd data into these 
$json = json_decode(file_get_contents('php://input'), true);

$servername = "sql1.njit.edu";
$username = "npm26";
$password = "DBPassword1!";
$dbname = "npm26";


function enroll_student($json, $conn){

  $exmid=$json['examID'];
  //echo $exmid;

  $q = $conn->query("SELECT creatorID FROM exams WHERE id=$exmid");
    $cid = $q->fetchColumn();
   // echo $cid;


  // insert submission with empty grades and comments
  $submissions_insert_query = 'INSERT INTO `submissions` (`examID`, `teacherID`, `studentID`, `status`, `autograde`, `grade`, `comments`) VALUES (?, ?, ?, ?, ?, ?, ?)'; //SELECT LAST_INSERT_ID();';
  $submissions_insert = $conn->prepare($submissions_insert_query);
  $submissions_insert->execute([$json['examID'], $cid, $json['studentID'], 0, 0, 0, ""]);

//echo "we made it!";

  //get id
  //????$sub_id_q = 'SELECT LAST_INSERT_ID()';
  //????$sub_id = $conn->prepare($sub_id_q);
  //????$submission_id = $sub_id->fetchAll(\PDO::FETCH_ASSOC)['LAST_INSERT_ID()'];

  $submission_id = $conn->lastInsertId();
  ///echo "found an id " . $submission_id; 
  // for each question in exam, create record in submitted_questions, empty except for questionID and submissionID
  
  $exam_questions_query = 'SELECT * FROM `questions_join` WHERE `examID`=:examID';
 

  $exam_questions = $conn->prepare($exam_questions_query);
  
  
  $exam_questions->bindValue(':examID', $json['examID']);
 ########<br>SQLSTATE[HY000]: General error: 2014 Cannot execute queries while other unbuffered queries are active.  Consider using PDOStatement::fetchAll().  Alternatively, if your code is only ever going to run against mysql, you may enable query buffering by setting the PDO::MYSQL_ATTR_USE_BUFFERED_QUERY attribute.
 $exam_questions->execute();
 ######## see above error code 

///////////////////////////////////////////////////////////////////
  //echo "here0";

  $submitted_questions_insert_query = 'INSERT INTO `submitted_questions` (`questionID` ,`subID`, `solution`, `result1`, `result2`, `result3`, `result4`, `result5`, `result6`, `functionName_result`, `colon_result`, `constraint_result`, `result1_points`, `result2_points`, `result3_points`, `result4_points`, `result5_points`, `result6_points`,`autograde`, `grade`, `comments`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);';
  //echo "here1";
  $submitted_questions_insert = $conn->prepare($submitted_questions_insert_query);
  //echo "here2";
  $questions = $exam_questions->fetchAll(\PDO::FETCH_ASSOC);
  //($questions);
  //echo "here3";
    
  #issue
  //echo "these are questions " . $questions . " ";
  ##returns empty array ?????? because $exam_questions wasnt executed. see issue with $exam_questions exectuion error.
  foreach ($questions as $question){
    //echo "here4";
    $submitted_questions_insert->execute([$question['questionID'], $submission_id, "", "", "", "", "", "", "", false, false, false, 0, 0, 0, 0, 0, 0, 0, 0, ""]);
    //echo "here5";
  }  
  #issue
///////////////////////////////////////////////////////////////////
  //echo "here6";
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
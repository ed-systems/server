<?php

$json = json_decode(file_get_contents('php://input'), true);

$servername = "sql1.njit.edu";
$username = "npm26";
$password = "DBPassword1!";
$dbname = "npm26";

function save_teacher_submissions($json, $conn){
 
  $sid=$json['submissionID'];
 
  $update_examStatus_query = 'UPDATE `submissions` SET status = 3 WHERE id=:subID';

  $update_gradecomments = $conn->prepare($update_examStatus_query);
 
  $update_gradecomments->bindValue(':subID', $sid);
 
  $update_gradecomments->execute();

}


  try {
    
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
    save_teacher_submissions($json, $conn);
  
  }
  
  //err handling
  Catch(PDOException $e){
  echo $sql . "<br>" . $e->getMessage();
  }
  
  $conn = null;

?>
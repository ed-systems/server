<?php

// pass json_decode'd data into these 



$json = json_decode(file_get_contents('php://input'), true);

$servername = "sql1.njit.edu";
$username = "npm26";
$password = "DBPassword1!";
$dbname = "npm26";


//update status1

//curl answers to backend
//recieve data

//update submission autograde
//update submitted_question solution result 1 result 2 autograde


function querry_middle($in)
{

  $data = json_encode($in); // Encoding that data to pass it

  $ch = curl_init('https://web.njit.edu/~dsk43/cs490-middle/grade_question.php'); // Test URL
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); // HTTP request method
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data); // Passing data to the request
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Whatever
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($data))); // Setting necessary headers

  $result = curl_exec($ch); // Executing cURL HTTP request

  curl_close($ch); // Closing request

  header('Content-Type: application/json'); // Setting header for our PHP response
  return $result; // Returning result

}




function save_student_submissions($json, $conn)
{
  $update_status_query = 'UPDATE `submissions` SET status = 1 WHERE id = :id';
  $update_status = $conn->prepare($update_status_query);
  $update_status->bindValue(':id', $json['ID']);
  $update_status->execute();

  $autograde_sum = 0;






  $eid = $json['examID'];

  //$update_gradecomments_query = 'UPDATE `submissions` SET autograde=:autoG WHERE id=:subID';

  //$update_gradecomments = $conn->prepare($update_gradecomments_query);
  //$G = $json['autoGrade'];
 // $update_gradecomments->bindValue(':autoG', $G);

  $sid = $json['ID'];
  //$update_gradecomments->bindValue(':subID', $sid);

  //$update_gradecomments->execute();


  $q = $conn->query("SELECT studentID FROM submissions WHERE id=$sid");
  $uid = $q->fetchColumn();

  $q = $conn->query("SELECT token FROM users WHERE id=$uid");
  $tok = $q->fetchColumn();


  $questionsArr = array("solution" => "", "ID" => "");
  $arrQarr = array();

  //exam score counter
  $eMarks = 0;

  foreach ($json['questions'] as $question) {
    $update_questioncomments_query = 'UPDATE `submitted_questions` SET solution=:sol, result1=:res1, result2=:res2, autograde=:ag WHERE subID=:sd AND questionID=:qd';

    $qid = $question['ID'];

    $update_questioncomments = $conn->prepare($update_questioncomments_query);

    $S = $question['solution'];




    $update_questioncomments->bindValue(':sol', $S);

    $update_questioncomments->bindValue(':sd', $sid);

    $update_questioncomments->bindValue(':qd', $question['ID']);



    $questionsArr["solution"] = $question['solution'];

    $questionsArr["ID"] = $question['ID'];



    
    $q = $conn->query("SELECT points FROM exam_questions WHERE questionID= '$qid'");
    $pts = $q->fetchColumn();

    $q = $conn->query("SELECT input1 FROM questions WHERE id= '$qid'");
    $i1 = $q->fetchColumn();
    $q = $conn->query("SELECT input2 FROM questions WHERE id= '$qid'");
    $i2 = $q->fetchColumn();
    
    $q = $conn->query("SELECT output1 FROM questions WHERE id= '$qid'");
    $o1 = $q->fetchColumn();
    $q = $conn->query("SELECT output2 FROM questions WHERE id= '$qid'");
    $o2 = $q->fetchColumn();

    //$user_exams_obj = array("input1" => $question['input1'], "input2" => $question['input2'], "output1" => $question['output1'], "output2" => $question['output2'], "points" => $question['points'], "questionID" => $question['questionID'], "solution" => $question['solution']);
    $user_exams_obj = array("questionID" => $qid, "points" => $pts, "solution" => $S, "input1" => $i1,"output1" => $o1, "input2" => $i2, "output2" => $o2);
    //also send input/output points, function name&points, constraint & points, colon_points
    //echo json_encode($user_exams_obj);




    $resultspackage = querry_middle($user_exams_obj);

    $results = json_decode($resultspackage, true);

//autograde question\\
//add total of all results points, constrain points, name points, colon points; store in $qAg
//$eMarks = $eMarks + $qAg;
    $eMarks = $eMarks + $results['autoGrade'];

    $r1 = $results['result1'];
    $r2 = $results['result2'];
   /* $r3 = $results['result3'];
    $r4 = $results['result4'];
    $r5 = $results['result5'];
    $r6 = $results['result6'];*/

    $update_questioncomments->bindValue(':res1', $r1);
    $update_questioncomments->bindValue(':res2', $r2);
    /*$update_questioncomments->bindValue(':res3', $r3);
    $update_questioncomments->bindValue(':res4', $r4);   
    $update_questioncomments->bindValue(':res5', $r5);
    $update_questioncomments->bindValue(':res6', $r6);*/


    $rpt=0;
    /*$rpt=$rpt+$results['result1_points'];
    $rpt=$rpt+$results['result2_points'];
    $rpt=$rpt+$results['result3_points'];
    $rpt=$rpt+$results['result4_points'];
    $rpt=$rpt+$results['result5_points'];
    $rpt=$rpt+$results['result6_points'];*/

    $g = $rpt;
    $update_questioncomments->bindValue(':ag', $g);

    $update_questioncomments->execute();

    array_push($arrQarr, $questionsArr);
  }


 // $eid = $json['examID'];

  $update_gradecomments_query = 'UPDATE `submissions` SET autograde=:autoG WHERE id=:subID';

  $update_gradecomments = $conn->prepare($update_gradecomments_query);
  //$G = $json['autoGrade'];
  $update_gradecomments->bindValue(':autoG', $eMarks);

 // $sid = $json['ID'];
  $update_gradecomments->bindValue(':subID', $sid);

  $update_gradecomments->execute();

  // update exam submission record which includes the sum of the scores of the each question
}



try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  save_student_submissions($json, $conn);
} catch (PDOException $e) {
  echo $sql . "<br>" . $e->getMessage();
}

$conn = null;

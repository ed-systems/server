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

  //$autograde_sum = 0;






  $eid = $json['examID'];

  //$update_gradecomments_query = 'UPDATE `submissions` SET autograde=:autoG WHERE id=:subID';

  //$update_gradecomments = $conn->prepare($update_gradecomments_query);
  //$G = $json['autoGrade'];
 // $update_gradecomments->bindValue(':autoG', $G);

  $sid = $json['ID'];
  //$update_gradecomments->bindValue(':subID', $sid);

  //$update_gradecomments->execute();
//somecomment

  $q = $conn->query("SELECT studentID FROM submissions WHERE id=$sid");
  $uid = $q->fetchColumn();

  $q = $conn->query("SELECT token FROM users WHERE id=$uid");
  $tok = $q->fetchColumn();


  $questionsArr = array("solution" => "", "ID" => "");
  $arrQarr = array();

  //exam score counter
  $eMarks = 0;

  foreach ($json['questions'] as $question) {
    $update_questioncomments_query = 'UPDATE `submitted_questions` SET solution=:sol, result1=:res1, result2=:res2, result3=:res3, result4=:res4, result5=:res5, result6=:res6, result1_points=:resp1, result2_points=:resp2, result3_points=:resp3, result4_points=:resp4, result5_points=:resp5, result6_points=:resp6, autograde=:ag , functionName_result=:fnrr, constraint_result=:cnrr, colon_result=:clrr, colon_result_points=:clrpp, constraint_result_points=:cnrp, functionName_result_points=:fnrpp WHERE subID=:sd AND questionID=:qd';

    $qid = $question['ID'];

    $update_questioncomments = $conn->prepare($update_questioncomments_query);

    $S = $question['solution'];




    $update_questioncomments->bindValue(':sol', $S);

    $update_questioncomments->bindValue(':sd', $sid);

    $update_questioncomments->bindValue(':qd', $question['ID']);



    $questionsArr["solution"] = $question['solution'];

    $questionsArr["ID"] = $question['ID'];



    
    $q = $conn->query("SELECT points FROM exam_questions WHERE questionID= '$qid'");
    $pt = $q->fetchColumn();
    $pts = (int)$pt;

    $q = $conn->query("SELECT input1 FROM questions WHERE id= '$qid'");
    $i1 = $q->fetchColumn();
    $q = $conn->query("SELECT input2 FROM questions WHERE id= '$qid'");
    $i2 = $q->fetchColumn();
    $q = $conn->query("SELECT input3 FROM questions WHERE id= '$qid'");
    $i3 = $q->fetchColumn();
    $q = $conn->query("SELECT input4 FROM questions WHERE id= '$qid'");
    $i4 = $q->fetchColumn();
    $q = $conn->query("SELECT input5 FROM questions WHERE id= '$qid'");
    $i5 = $q->fetchColumn();
    $q = $conn->query("SELECT input6 FROM questions WHERE id= '$qid'");
    $i6 = $q->fetchColumn();
    
    
    $q = $conn->query("SELECT output1 FROM questions WHERE id= '$qid'");
    $o1 = $q->fetchColumn();
    $q = $conn->query("SELECT output2 FROM questions WHERE id= '$qid'");
    $o2 = $q->fetchColumn();
    $q = $conn->query("SELECT output3 FROM questions WHERE id= '$qid'");
    $o3 = $q->fetchColumn();
    $q = $conn->query("SELECT output4 FROM questions WHERE id= '$qid'");
    $o4 = $q->fetchColumn();
    $q = $conn->query("SELECT output5 FROM questions WHERE id= '$qid'");
    $o5 = $q->fetchColumn();
    $q = $conn->query("SELECT output6 FROM questions WHERE id= '$qid'");
    $o6 = $q->fetchColumn();


    $q = $conn->query("SELECT output1_points FROM exam_questions WHERE questionID= '$qid' AND examID=$eid");
    $op1 = $q->fetchColumn();
    $q = $conn->query("SELECT output2_points FROM exam_questions WHERE questionID= '$qid' AND examID=$eid");
    $op2 = $q->fetchColumn();
    $q = $conn->query("SELECT output3_points FROM exam_questions WHERE questionID= '$qid' AND examID=$eid");
    $op3 = $q->fetchColumn();
    $q = $conn->query("SELECT output4_points FROM exam_questions WHERE questionID= '$qid' AND examID=$eid");
    $op4 = $q->fetchColumn();
    $q = $conn->query("SELECT output5_points FROM exam_questions WHERE questionID= '$qid' AND examID=$eid");
    $op5 = $q->fetchColumn();
    $q = $conn->query("SELECT output6_points FROM exam_questions WHERE questionID= '$qid' AND examID=$eid");
    $op6 = $q->fetchColumn();

    
    //function name
    $q = $conn->query("SELECT functionName FROM questions WHERE id= '$qid'");
    $fn = $q->fetchColumn();
    //function name points
    $q = $conn->query("SELECT functionNamePoints FROM exam_questions WHERE questionID= '$qid' AND examID=$eid");
    $fnp = $q->fetchColumn();

    //constraint name
    $q = $conn->query("SELECT constraintID FROM questions WHERE id= '$qid'");
    $cid = $q->fetchColumn();
    $q = $conn->query("SELECT constraint_string FROM constraints WHERE id= '$cid'");
    $cs = $q->fetchColumn();

    //constraint points
    $q = $conn->query("SELECT constraintStringPoints FROM exam_questions WHERE questionID= '$qid' AND examID=$eid");
    $csp = $q->fetchColumn();
    
    //colon points
    $q = $conn->query("SELECT colonPoints FROM exam_questions WHERE questionID= '$qid' AND examID=$eid");
    $clnp = $q->fetchColumn();


    
    
    $user_exams_obj = array("questionID" => $qid, "points" => $pts, "solution" => $S, "function_name" => $fn, "function_name_points" => $fnp, "constraint" => $cs, "constraint_points" => $csp, "colon_points" => $clnp, "input1" => $i1, "input2" => $i2, "input3" => $i3, "input4" => $i4, "input5" => $i5, "input6" => $i6, "output1" => $o1, "output2" => $o2, "output3" => $o3, "output4" => $o4, "output5" => $o5, "output6" => $o6, "output1_points" => $op1, "output2_points" => $op2, "output3_points" => $op3, "output4_points" => $op4, "output5_points" => $op5, "output6_points" => $op6);
    //also send input/output points, function name&points, constraint & points, colon_points,
    //echo json_encode($user_exams_obj);


  
    $resultspackage = querry_middle($user_exams_obj);
    $results = json_decode($resultspackage, true);

    echo "BLABLA\n";
    echo json_encode($user_exams_obj);
    echo "\n";
    echo $resultspackage;
    echo "\n";
    echo "BLABLA\n";

    //echo $results;

    //autograde question\\
    //add total of all results points, constrain points, name points, colon points; store in $qAg
    //$eMarks = $eMarks + $qAg;
    $eMarks = $eMarks + $results['autoGrade'];
    //print_r($results['autoGrade']);
    //echo json_encode($eMarks);

    $update_questioncomments->bindValue(':res1', $results['result1']);
    $update_questioncomments->bindValue(':res2', $results['result2']);
    $update_questioncomments->bindValue(':res3', $results['result3']);
    $update_questioncomments->bindValue(':res4', $results['result4']);
    $update_questioncomments->bindValue(':res5', $results['result5']);
    $update_questioncomments->bindValue(':res6', $results['result6']);
    $update_questioncomments->bindValue(':resp1', $results['result1_points']);

    $update_questioncomments->bindValue(':resp2', $results['result2_points']);    
    $update_questioncomments->bindValue(':resp3', $results['result3_points']);
    $update_questioncomments->bindValue(':resp4', $results['result4_points']);
    $update_questioncomments->bindValue(':resp5', $results['result5_points']);
    $update_questioncomments->bindValue(':resp6', $results['result6_points']);
    $update_questioncomments->bindValue(':ag', $results['autoGrade']);

    $update_questioncomments->bindValue(':fnrr', $results['function_name_result']);
    $update_questioncomments->bindValue(':cnrr', $results['constraint_result']);
    $update_questioncomments->bindValue(':clrr', $results['colon_result']);
    $update_questioncomments->bindValue(':clrpp', $results['colon_result_points']);
    $update_questioncomments->bindValue(':cnrp', $results['constraint_result_points']);
    $update_questioncomments->bindValue(':fnrpp', $results['function_name_result_points']);

    functionName_result=:fnrr, constraint_result=:cnrr, colon_result=:clrr, colon_result_points=:clrpp, constraint_result_points=:cnrp, functionName_result_points=:fnrpp

    
    $update_questioncomments->execute();
  }


 // $eid = $json['examID'];

  $update_gradecomments_query = 'UPDATE `submissions` SET autograde=:autoG WHERE id=:subID';

  $update_gradecomments = $conn->prepare($update_gradecomments_query);
  //$G = $json['autoGrade'];
  $update_gradecomments->bindValue(':autoG', $eMarks);

 // $sid = $json['ID'];
  $update_gradecomments->bindValue(':subID', $sid);

  $update_gradecomments->execute();

  //echo $arrQarr;

  //echo json_encode($update_questioncomments);

//echo "here2"
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

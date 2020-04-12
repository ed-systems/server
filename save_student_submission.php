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
  //update status = 1
  $update_status_query = 'UPDATE `submissions` SET status = 1 WHERE id = :id';
  $update_status = $conn->prepare($update_status_query);
  $update_status->bindValue(':id', $json['ID']);
  $update_status->execute();
  //for each question...

  $autograde_sum = 0;
  //echo $json['ID'];
  //subid=$json['ID'];





  $eid = $json['examID'];

  $update_gradecomments_query = 'UPDATE `submissions` SET autograde=:autoG WHERE id=:subID';

  $update_gradecomments = $conn->prepare($update_gradecomments_query);

  //get grade from autograder
  $G = $json['autoGrade'];
  //$G=99;
  $update_gradecomments->bindValue(':autoG', $G);

  $sid = $json['ID'];
  $update_gradecomments->bindValue(':subID', $sid);

  $update_gradecomments->execute();


  $q = $conn->query("SELECT studentID FROM submissions WHERE id=$sid");
  $uid = $q->fetchColumn();

  $q = $conn->query("SELECT token FROM users WHERE id=$uid");
  $tok = $q->fetchColumn();


  //token



  $questionsArr = array("solution" => "", "ID" => "");
  $arrQarr = array();

  foreach ($json['questions'] as $question) {


    $update_questioncomments_query = 'UPDATE `submitted_questions` SET solution=:sol, result1=:res1, result2=:res2, autograde=:ag WHERE subID=:sd AND questionID=:qd';

    $qid = $question['ID'];

    $update_questioncomments = $conn->prepare($update_questioncomments_query);

    $S = $question['solution'];
    //echo json_encode($S);
    $update_questioncomments->bindValue(':sol', $S);

    //get grade from autoograde
    // $G=$question['autoGrade'];
    //$G=66;
    //$update_questioncomments->bindValue(':ag', $G);


    $update_questioncomments->bindValue(':sd', $sid);

    $update_questioncomments->bindValue(':qd', $question['ID']);


    //$update_questioncomments->execute();


    //return string
    $questionsArr["solution"] = $question['solution'];
    $questionsArr["ID"] = $question['ID'];

    $user_exams_obj = array("input1" => $question['input1'], "input2" => $question['input2'], "output1" => $question['output1'], "output2" => $question['output2'], "points" => $question['points'], "questionID" => $question['questionID'], "solution" => $question['solution']);

    $resultspackage = querry_middle($user_exams_obj);

    $results = json_decode($resultspackage, true);






    //echo $resultspackage[0];
    // echo $resultspackage[1];
    $r1 = $results['result1'];
    $r2 = $results['result2'];
    $update_questioncomments->bindValue(':res1', $r1);
    $update_questioncomments->bindValue(':res2', $r2);


    $g = $results['autoGrade'];
    $update_questioncomments->bindValue(':ag', $g);

    $update_questioncomments->execute();

    array_push($arrQarr, $questionsArr);
  }  //echo json_encode($user_exams_obj);


  //querry_middle($user_exams_obj);


  //$output_data = json_encode($user_exams_obj);

  //json header
  // header('Content-Type: application/json');


  // echo $output_data;  




  //foreach($json['questions'] as $question){



  // }



}



try {

  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  //echo "first";

  save_student_submissions($json, $conn);

  //echo json_encode($json['ID']);

  //$output_data = json_encode($saved_subs);

  //json header
  // header('Content-Type: application/json');


  //echo $output_data;  


}

//err handling
catch (PDOException $e) {
  echo $sql . "<br>" . $e->getMessage();
}

$conn = null;

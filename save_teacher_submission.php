<?php
  
  
// pass json_decode'd data into these 
$json = json_decode(file_get_contents('php://input'), true);

$servername = "sql1.njit.edu";
$username = "npm26";
$password = "DBPassword1!";
$dbname = "npm26";

function save_teacher_submissions($json, $conn){
 
  $eid=$json['examID'];
 
  $update_gradecomments_query = 'UPDATE `submissions` SET status = 2, grade=:grade, comments=:comm WHERE id=:subID';

  $update_gradecomments = $conn->prepare($update_gradecomments_query);
 
  $g=$json['grade'];
  $update_gradecomments->bindValue(':grade', $g);
 
  $c=$json['comments'];
  $update_gradecomments->bindValue(':comm', $c);
 
  $sid=$json['ID'];
  $update_gradecomments->bindValue(':subID', $sid);
 
  $update_gradecomments->execute();


  $q = $conn->query("SELECT studentID FROM submissions WHERE id=$sid");
        $uid = $q->fetchColumn();

  $q = $conn->query("SELECT token FROM users WHERE id=$uid");
        $tok = $q->fetchColumn();

  
  //token
  


  $questionsArr = array(
    "questionID"=>"",
    "grade"=>"",
    "comments"=>""
  );

  $arrQarr=array();
  
 foreach($json['questions'] as $question){

  
  $update_questioncomments_query = 'UPDATE `submitted_questions` SET colon_result_points=:crp, constraint_result_points=:corp, function_name_result_points=:fnrp, result1_points=:r1p, result2_points=:r2p, result3_points=:r3p, result4_points=:r4p, result5_points=:r5p, result6_points=:r6p, grade=:gr, comments=:comm WHERE subID=:sd AND questionID=:qd';
  $qid=$question['ID'];
 
  $update_questioncomments = $conn->prepare($update_questioncomments_query);
 
  
  $update_questioncomments->bindValue(':gr', $question['grade']);
  $update_questioncomments->bindValue(':crp', $question['colon_result_points']);
  $update_questioncomments->bindValue(':corp', $question['constraint_result_points']);
  $update_questioncomments->bindValue(':fnrp', $question['function_name_result_points']);

  $update_questioncomments->bindValue(':r1p', $question['result1_points']);
  $update_questioncomments->bindValue(':r2p', $question['result2_points']);
  $update_questioncomments->bindValue(':r3p', $question['result3_points']);
  $update_questioncomments->bindValue(':r4p', $question['result4_points']);
  $update_questioncomments->bindValue(':r5p', $question['result5_points']);
  $update_questioncomments->bindValue(':r6p', $question['result6_points']);
   
 
  $C=$question['comments'];
  
  $update_questioncomments->bindValue(':comm', $C);

  $update_questioncomments->bindValue(':sd', $sid);
  
  $update_questioncomments->bindValue(':qd', $question['ID']);
 

  $update_questioncomments->execute();


  $questionsArr["questionID"]=$question['ID'];
  $questionsArr["grade"]=$question['grade'];
  $questionsArr["comments"]=$question['comments'];
 
 
  array_push($arrQarr, $questionsArr);
 
}

//result5_points... , colon_result_points, constraint_result_points, function_name_result_points, points, autoGrade, comment - per question
// comments, grade,

  $user_exams_obj = array("token" => $tok, "examID" => $eid, "studentID" => $uid, "grade" => $g, "comments" => $c, "ID" => $sid, "questions" => $arrQarr);
  //echo json_encode($user_exams_obj);


  //encodes dataArr in json formatting
  $output_data = json_encode($user_exams_obj);

  //json header
  header('Content-Type: application/json');
      
  
  echo $output_data;


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
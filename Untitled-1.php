echo "here0";
function query_middle($in, $request_name){
  $middle_url = 'https://web.njit.edu/~dsk43/cs490-middle/%s';
  $curl = curl_init();
  $url = sprintf($middle_url, $request_name);
  curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $in
  ));
  $res = curl_exec($curl);
  curl_close($curl);
  return $res;
}
echo "here1";
function save_student_submissions($json, $conn){
    //update status = 1
    $update_status_query = 'UPDATE `submissions` SET status = 1 WHERE id == :id';
    $update_status = $conn->prepare($update_status_query);
    $update_status->bindValue(':id', $json['ID']);
    $update_status->execute();
    //for each question...
    $autograde_sum = 0;
    echo "here2";
    foreach($json['questions'] as $question){
      //update solution in submited_questions with given solution
      $update_solution_query = 'UPDATE `submitted_questions` SET solution = :solution WHERE questionID=:question_id';
      $update_solution = $conn->prepare($update_solution_query);
      $update_solution->bindValue(':solution', $question['solution']);
      $update_solution->bindValue(':question_id', $question['ID']);
      // get question from table to construct autograde json input
      $get_full_question_query = 'SELECT * FROM `questions` WHERE `id`=:question_id';
      $get_full_question = $conn->prepare($get_full_question_query);
      $get_full_question.bindValue(':question_id', $question['ID']);
      $get_full_question.execute();
      $full_question = $get_full_question->fetch(\PDO::FETCH_ASSOC);
      $autograde_in_arr = array(
        'questionID' => $full_question['id'],
        'points' => $full_question['points'],
        'solution' => $full_question['solution'],
        'input1' => $full_question['input1'],
        'output1' => $full_question['output1'],
        'input2' => $full_question['input2'],
        'output2' => $full_question['output2']        
        echo "here3";
      );
      // get autograde response, set submittion_question fields from responses
      $autograde_response = json_decode(query_middle(json_encode($autograde_in_arr, 'grade_question.php'), true);
      // ...add sum to total
      $autograde_sum += $autograde_response['autograde'];
      $update_autograde_query = 'UPDATE `submitted_questions` SET `result1`=:result1, `result2`=:result2, `autograde`=:autograde WHERE `questionID`=:question_id';
      $update_autograde = $conn->prepare($update_autograde_query);
      $update_autograde->bindValue(':result1', $autograde_response['result1']);
      $update_autograde->bindValue(':result2', $autograde_response['result2']);
      $update_autograde->bindValue(':autograde', $autograde_response['autograde']);
      $update_autograde->bindValue(':question_id', $question['ID']); 
      $update_autograde->execute();
      echo "here4";
    }
    //update autograde with sum of all autogrades
    $update_autograde_sum_query = 'UPDATE `submissions` SET autograde=:autograde_sum status=:stat WHERE id=:submission_id';
    $update_autograde_sum = $conn->prepare($update_autograde_sum_query);
    $update_autograde_sum->bindValue(':autograde_sum', $autograde_sum);
    $update_autograde_sum->bindValue(':stat', 1);
    $update_autograde_sum->bindValue(':submission_id', $submission_id);
    $update_autograde_sum->execute();
    echo "here5";
}
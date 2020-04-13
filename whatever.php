$q = $conn->query("SELECT points FROM exam_questions WHERE questionID= '$qid'");
    $pts = $q->fetchColumn();

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


    
    //$user_exams_obj = array("input1" => $question['input1'], "input2" => $question['input2'], "output1" => $question['output1'], "output2" => $question['output2'], "points" => $question['points'], "questionID" => $question['questionID'], "solution" => $question['solution']);
    $user_exams_obj = array("questionID" => $qid, "points" => $pts, "solution" => $S, "function_name" => $fn, "function_name_points" => $fnp, "constraint" => $cs, "constraint_points" => $csp, "colon_points" => $clnp, "input1" => $i1, "input2" => $i2, "input3" => $i3, "input4" => $i4, "input5" => $i5, "input6" => $i6, "output1" => $o1, "output2" => $o2, "output3" => $o3, "output4" => $o4, "output5" => $o5, "output6" => $o6, "output1_points" => $op1, "output2_points" => $op2, "output3_points" => $op3, "output_points4" => $op4, "output_points5" => $op5, "output6_points" => $op6);
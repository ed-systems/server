<?php
  $servername = "sql1.njit.edu";
  $username = "npm26";
  $password = "DBPassword1!";
  $dbname = "npm26";


 
  

        //recieve POST
        $input_data = json_decode(file_get_contents('php://input'), true);

        //pull post data
        $token = $input_data["token"];




    //try connect to database
  try {
    
      $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //check if teacher
        $q = $conn->query("SELECT id FROM auth_table WHERE token='$token'");
        $uid = $q->fetchColumn();

        $q = $conn->query("SELECT type FROM users WHERE id='$uid'");
        $typ = $q->fetchColumn();  


        if ($typ==1) {



        //query view
        $sql = "SELECT DISTINCT id FROM questions";

        //create array variable for examIds
        $questionIds=array();

        foreach ($conn->query($sql) as $row) {
          # code...
          array_push($questionIds,$row['id']);
        }

            $questions = array();

            foreach($questionIds as $id){

              $questionQuery = "SELECT * FROM questions WHERE id='$id'";

              $q = $conn->query("SELECT name FROM questions WHERE id='$id'");
              $n = $q->fetchColumn();

              $q = $conn->query("SELECT description FROM questions WHERE id='$id'");
              $d = $q->fetchColumn();
              
              $q = $conn->query("SELECT task FROM questions WHERE id='$id'");
              $t = $q->fetchColumn();              

              $q = $conn->query("SELECT input1 FROM questions WHERE id ='$id'");
              $i1 = $q->fetchColumn();  

              $q = $conn->query("SELECT output1 FROM questions WHERE id ='$id'");
              $o1 = $q->fetchColumn();

              $q = $conn->query("SELECT input2 FROM questions WHERE id ='$id'");
              $i2 = $q->fetchColumn();

              $q = $conn->query("SELECT output2 FROM questions WHERE id ='$id'");
              $o2 = $q->fetchColumn();

              $q = $conn->query("SELECT input3 FROM questions WHERE id ='$id'");
              $i3 = $q->fetchColumn();

              $q = $conn->query("SELECT output3 FROM questions WHERE id ='$id'");
              $o3 = $q->fetchColumn();

              $q = $conn->query("SELECT input4 FROM questions WHERE id ='$id'");
              $i4 = $q->fetchColumn();

              $q = $conn->query("SELECT output4 FROM questions WHERE id ='$id'");
              $o4 = $q->fetchColumn();

              $q = $conn->query("SELECT input5 FROM questions WHERE id ='$id'");
              $i5 = $q->fetchColumn();

              $q = $conn->query("SELECT output5 FROM questions WHERE id ='$id'");
              $o5 = $q->fetchColumn();

              $q = $conn->query("SELECT input6 FROM questions WHERE id ='$id'");
              $i6 = $q->fetchColumn();

              $q = $conn->query("SELECT output6 FROM questions WHERE id ='$id'");
              $o6 = $q->fetchColumn();


              $q = $conn->query("SELECT functionName FROM questions WHERE id ='$id'");
              $fn = $q->fetchColumn();
              $q = $conn->query("SELECT topicID FROM questions WHERE id ='$id'");
              $tid = $q->fetchColumn();
              $q = $conn->query("SELECT difficultyID FROM questions WHERE id ='$id'");
              $did = $q->fetchColumn();
              $q = $conn->query("SELECT constraintID FROM questions WHERE id ='$id'");
              $cid = $q->fetchColumn();


              //questions info obj
              $questions_obj = array("name"=> "$n", "description"=> "$d", "task"=>"$t", "function_name"=>"$fn", "constraintID"=>"$cid", "difficultyID"=>"$did", "topicID"=>"$tid","input1" => "$i1", "output1" => "$o1", "input2" => "$i2", "output2" => "$o2", "input3" => "$i3", "output3" => "$o3", "input4" => "$i4", "output4" => "$o4", "input5" => "$i5", "output5" => "$o5", "input6" => "$i6", "output6" => "$o6", "ID" => "$id");
              array_push($questions, $questions_obj);
            }




                //encodes dataArr in json formatting
                $output_data = json_encode($questions);

                //json header
                header('Content-Type: application/json');

                //curl responds with results
                echo $output_data;
          }//end of type check

    }//end of try
    //err handling
  Catch(PDOException $e){
      echo $sql . "<br>" . $e->getMessage();
    }
   
    //terminate connection
  $conn = null;
  
?>
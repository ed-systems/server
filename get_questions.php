<?php
  $servername = "sql1.njit.edu";
  $username = "npm26";
  $password = "DBPassword1!";
  $dbname = "npm26";


  //grab data from curl

  //DOWN DOWN\\
  

        //recieve POST
        $input_data = json_decode(file_get_contents('php://input'), true);

        //pull post data
        $token = $input_data["token"];

        //check if teacher
        //$q = $conn->query("SELECT id FROM auth_table WHERE token='$token'");
        //$uid = $q->fetchColumn();
        
       // $q = $conn->query("SELECT type FROM users WHERE id='$uid'");
       // $typ = $q->fetchColumn();       
        
        //echo $typ; 
//if ($typ==1) {
  # code...

  
  //UP UP\\

    /* hardcode argument */
    // HERE


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
          # code...
        


        //query view
        $sql = "SELECT DISTINCT id FROM questions";

        //create array variable for examIds
        $questionIds=array();

        foreach ($conn->query($sql) as $row) {
          # code...
          array_push($questionIds,$row['id']);
        }
        //print_r($questionIds);
        //echo "<br>";

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




              //questions info obj
              $questions_obj = array("name"=> "$n", "description"=> "$d", "task"=>"$t", "input1" => "$i1", "output1" => "$o1", "input2" => "$i2", "output2" => "$o2", "ID" => "$id");
              array_push($questions, $questions_obj);
            }
          //print_r($questions);




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
  

  /* prints array for formating test */
  //print_r($dataArr);
    
    //encodes dataArr in json formatting
   // $output_data = json_encode($questions);

    //json header
   // header('Content-Type: application/json');

    //curl responds with results
   // echo $output_data;
  //}
?>
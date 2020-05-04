<?php
  $servername = "sql1.njit.edu";
  $username = "npm26";
  $password = "DBPassword1!";
  $dbname = "npm26";




        //recieve POST
        $input_data = json_decode(file_get_contents('php://input'), true);

        //grab login data        
        $token = $input_data["token"];
        

    //try connect to database
  try {
    
      $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $q = $conn->query("SELECT id FROM auth_table WHERE token='$token'");
        $uid = $q->fetchColumn();

      $q = $conn->query("SELECT type FROM users WHERE id='$uid'");
        $typ = $q->fetchColumn();  
        //$typ=1;

      if ($typ==1) {
          # code...
        
        //query view
        $sql = "SELECT DISTINCT id FROM users WHERE type=2";

        //create array variable for examIds
        $studentIds=array();

        foreach ($conn->query($sql) as $row) {
          # code...
          array_push($studentIds,$row['id']);
        }
      

            $students = array();

            foreach($studentIds as $id){

              $studentQuery = "SELECT * FROM users WHERE id='$id'";

              $q = $conn->query("SELECT full_name FROM users WHERE id='$id'");
              $n = $q->fetchColumn();

              //questions info obj
              $student_obj = array("name"=> "$n", "ID"=> "$id");
              array_push($students, $student_obj);
            }
      


                //encodes dataArr in json formatting
                $output_data = json_encode($students);

                //json header
                header('Content-Type: application/json');

                //curl responds with results
                echo $output_data;
          }//end of type check

      //querry db for data

    }
    //err handling
  Catch(PDOException $e){
      echo $sql . "<br>" . $e->getMessage();
    }
   
    //terminate connection
  $conn = null;
  

?>
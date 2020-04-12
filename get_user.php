<?php
	$servername = "sql1.njit.edu";
	$username = "npm26";
	$password = "DBPassword1!";
	$dbname = "npm26";


	//grab username from curl

	//DOWN DOWN\\
 	

		    //recieve POST
    		$input_data = json_decode(file_get_contents('php://input'), true);

    		//grab login data        
    		$token = $input_data["token"];
    		
/*
	
	//UP UP\\

    /* hardcode get user test accepting username as input argument */
	  //$un='student1';


    //try connect to database
	try {
   	
   		$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
		// set the PDO error mode to exception
   		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


   		//querry db for data

      $q = $conn->query("SELECT id FROM auth_table WHERE token = '$token'");
      $uid = $q->fetchColumn();


   		$q = $conn->query("SELECT full_name FROM users WHERE id = '$uid'");
    		$full = $q->fetchColumn();
    		//echo $full;
   		$q = $conn->query("SELECT first_name FROM users WHERE id = '$uid'");
    		$first = $q->fetchColumn();
    		//echo $first;    	
   		$q = $conn->query("SELECT last_name FROM users WHERE id = '$uid'");
    		$last = $q->fetchColumn();
    		//echo $last;
   		$q = $conn->query("SELECT email FROM users WHERE id = '$uid'");
    		$em = $q->fetchColumn();
    		//echo $em;
   		$q = $conn->query("SELECT type FROM users WHERE id = '$uid'");
    		$t = $q->fetchColumn();
    		//echo $t;    		
   		$q = $conn->query("SELECT type_string FROM users WHERE id = '$uid'");
    		$tString = $q->fetchColumn();
    		//echo $tString;
   		//$q = $conn->query("SELECT token FROM users WHERE id = '$uid'");
    	//	$tok = $q->fetchColumn();
    		//echo $tok;

    	//package data in array
    	$dataArr = array("full_name"=> $full, "first_name"=> $first, 
    		"last_name"=> $last, "email"=> $em, "type"=> $t, "type_string"=> 
    		$tString, "token"=> $token, "ID" => $uid);

    }
    //err handling
	Catch(PDOException $e){
    	echo $sql . "<br>" . $e->getMessage();
    }
   
   	//terminate connection
	$conn = null;
	

	/* prints array for formating test */
	//print_r($dataArr);
    
    //encodes dataArr in json formatting
    $output_data = json_encode($dataArr);

    //json header
    header('Content-Type: application/json');

    //curl responds with results
    echo $output_data;


?>
<?php
    //recieve POST
    $input_data = json_decode(file_get_contents('php://input'), true);

    //grab login data        
    $un = $input_data["username"];
    $pw = sha1($input_data["password"]);

    //DB-INFO
    $servername = "sql1.njit.edu";
    $username = "npm26";
    $password = "DBPassword1!";
    $dbname = "npm26";

    //connect to db
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
           // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //query by username
        $q = $conn->query("SELECT password FROM users WHERE username='$un'");

        //grab passdata from query
        $passhash = $q->fetchColumn();
    }
    
    //error handling
    catch(PDOException $e){
        echo $sql . "<br>" . $e->getMessage();
    }
    
    //compare pass positive
    if($pw==$passhash){
        //print_r("SUCESSFULL login");
        $dataArr = array("database"=> array("success"=> true, "message"=> "Successful login with $un (database)." ),"webnjit"=> array("success"=> null,"message"=> null ));
    }

    //compare pass negative
    if($pw!=$passhash){
        //print_r("UNSUCESSFULL login");
        $dataArr = array("database"=> array("success"=> false, "message"=> "Unsuccessful login with $un (database)." ),"webnjit"=> array("success"=> null,"message"=> null ));
    }
    
    //close db connection
    $conn = null;
    
    //encode data for curl
    $output_data = json_encode($dataArr);

    //set response header
    header('Content-Type: application/json');

    //return result
    echo $output_data;
?>
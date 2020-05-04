<?php
    //recieve POST
    $input_data = json_decode(file_get_contents('php://input'), true);

    //grab login data        
    $un = $input_data["username"];
    $pw = sha1($input_data["password"]);


    function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
    }

    $tok=generateRandomString();


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
     
        $dataArr = array("token" => $tok); 

        
        $q = $conn->query("SELECT id FROM users WHERE username='$un'");
        $uid = $q->fetchColumn();

        $q = $conn->query("SELECT type FROM users WHERE username='$un'");
        $typ = $q->fetchColumn();

        $updateAuthTable = 'UPDATE `auth_table` SET token=:tk WHERE id=:usid';
        $updateTokAT = $conn->prepare($updateAuthTable);
        $updateTokAT->bindValue(':tk', $tok);
        $updateTokAT->bindValue(':usid', $uid);
        $updateTokAT->execute();

        $updateUserTable = 'UPDATE `users` SET token=:tk WHERE id=:usid';
        $updateTokUT = $conn->prepare($updateUserTable);
        $updateTokUT->bindValue(':tk', $tok);
        $updateTokUT->bindValue(':usid', $uid);
        $updateTokUT->execute();

        $dataArr = array("token" => $tok, "type" => $typ);

    }

    //compare pass negative
    if($pw!=$passhash){
        print_r("UNSUCESSFULL login");

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
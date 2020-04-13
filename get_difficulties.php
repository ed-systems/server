<?php


// pass json_decode'd data into these 
//$json = json_decode(file_get_contents('php://input'), true);

//header('Content-Type: application/json');


$servername = "sql1.njit.edu";
$username = "npm26";
$password = "DBPassword1!";
$dbname = "npm26";


try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "before func";




 
 
    $stmt = $conn->prepare("SELECT id FROM difficulties");
    $stmt->execute();

    $dInfo = $stmt->fetchAll(\PDO::FETCH_ASSOC);
 

    $difficulty_array=array();


        foreach($dInfo as $info){
            echo $info['difficulty_string'];
            //echo json_encode($info['id']);
            $dId = $info['id'];
            //echo json_encode($info['difficulty_string']); 
            $dName = $info['difficulty_string'];
            //echo json_encode($info['difficulty_description']);             
            $dDescription = $info['difficulty_description'];

            $difficulty_array_obj = array("id" => $dId, "name" => $dName, "description" => $dDescription);

            array_push($difficulty_array_obj, $difficulty_array);


        }


    //encodes dataArr in json formatting
    $output_data = json_encode($difficulty_array);

    //json header
    header('Content-Type: application/json');
    

    echo $output_data;  

    //print_r($difficulty_array);



    //echo "after func";
  } catch (PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
  }
  

$conn = null;


?>
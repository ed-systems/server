<?php


// pass json_decode'd data into these 
$json = json_decode(file_get_contents('php://input'), true);



$servername = "sql1.njit.edu";
$username = "npm26";
$password = "DBPassword1!";
$dbname = "npm26";


try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "before func";




    echo "here0";
    echo "here0.1";
    $stmt = $conn->prepare("SELECT id FROM difficulties");
    echo "here0.2";
    $dinfo = $stmt->execute();
    echo "here0.3";
echo "here1";
    $difficulty_array=array();
echo "here2";

        foreach($dinfo as $info){
echo "beginloop";
        
            $dId = $info['id'];
            $dName = $info['difficulty_string'];
            $dDescription = $info['difficulty_description'];
echo "midloop";
            $difficulty_array_obj=array("id" => $dId, "name" => $dName, "description" => $dDescription);
            array_push($difficulty_array_obj, $difficulty_array);
echo "endloop";

        }

echo "here3";
    //encodes dataArr in json formatting
    $output_data = json_encode($difficulty_array);

    //json header
    header('Content-Type: application/json');
    

    echo $output_data;  





    echo "after func";
  } catch (PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
  }
  

$conn = null;


?>
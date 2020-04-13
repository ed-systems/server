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


 
 
    $stmt = $conn->prepare("SELECT * FROM constraints");
    $stmt->execute();

    $cInfo = $stmt->fetchAll(\PDO::FETCH_ASSOC);
 

    $constraints_array=array();


        foreach($cInfo as $info){
            $cId = $info['id'];
            $cName = $info['constraint_string'];
            $cDescription = $info['constraint_description'];

            $constraint_array_obj = array("id" => $cId, "name" => $cName, "description" => $cDescription);

            array_push($constraints_array, $constraint_array_obj);
        }


    //encodes dataArr in json formatting
    $output_data = json_encode($constraints_array);

    //json header
    header('Content-Type: application/json');
    echo $output_data;  
  } catch (PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
  }
  

$conn = null;


?>
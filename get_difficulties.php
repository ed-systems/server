<?php


// pass json_decode'd data into these 
$json = json_decode(file_get_contents('php://input'), true);


//get difficulties
function getD(){


    $difficultyInfoQuery = 'SELECT * FROM difficulties';
    $difficultyInfo = $conn->prepare($difficultyInfoQuery);
    $difficultyInfo->execute();
    $dinfo = $difficultyInfo->fetchAll(\PDO::FETCH_ASSOC);
    

    $difficulty_array=array();


        foreach($dinfo as $info){
     
        
            $dId = $info['id'];
            $dName = $info['difficulty_string'];
            $dDescription = $info['difficulty_description'];

            $difficulty_array_obj=array("id" => $dId, "name" => $dName, "description" => $dDescription);
            array_push($difficulty_array_obj, $difficulty_array);


        }


    //encodes dataArr in json formatting
    $output_data = json_encode($difficulty_array);

    //json header
    header('Content-Type: application/json');
    

    echo $output_data;  


}


$servername = "sql1.njit.edu";
$username = "npm26";
$password = "DBPassword1!";
$dbname = "npm26";


try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    getD();
  } catch (PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
  }
  

$conn = null;


?>
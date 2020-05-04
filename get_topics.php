<?php



$servername = "sql1.njit.edu";
$username = "npm26";
$password = "DBPassword1!";
$dbname = "npm26";


try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

 
    $stmt = $conn->prepare("SELECT * FROM topics");
    $stmt->execute();

    $tInfo = $stmt->fetchAll(\PDO::FETCH_ASSOC);
 

    $topics_array=array();


        foreach($tInfo as $info){
            $tId = $info['id'];
            $tName = $info['topic_string'];
            $tDescription = $info['topic_description'];

            $topic_array_obj = array("id" => $tId, "name" => $tName, "description" => $tDescription);

            array_push($topics_array, $topic_array_obj);
        }


    //encodes dataArr in json formatting
    $output_data = json_encode($topics_array);

    //json header
    header('Content-Type: application/json');
    echo $output_data;  
  } catch (PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
  }
  

$conn = null;


?>
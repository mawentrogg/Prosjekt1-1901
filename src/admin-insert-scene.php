<?php 
session_start();
include_once 'includes/dbh.inc.php';

$SceneName = $_POST["sceneName"];
$SceneFestival = 0;
$FestivalName = $_POST["sceneFestival"];
$SceneCapacity = $_POST["capacity"];

$sqlScene = "SELECT * FROM Scene";
$resultScene = mysqli_query($conn, $sqlScene);
$sqlFestival = "SELECT * FROM Festival";
$resultFestival = mysqli_query($conn, $sqlFestival);

$_SESSION['sceneName'] = $SceneName;
$_SESSION['sceneAlreadyAdded'] = false;

//Går igjennom scene-databasen og sjekker om det allerede er en scene med det navnet
if(mysqli_num_rows($resultScene) > 0){
	while($row = mysqli_fetch_assoc($resultScene)){
		if(strtolower($SceneName) == strtolower($row["SceneName"])){
			$_SESSION['sceneAlreadyAdded'] = true;
		} 
	}
}

if(mysqli_num_rows($resultFestival) > 0){
	while($row = mysqli_fetch_assoc($resultFestival)){
		if(strtolower($FestivalName) == strtolower($row["FestivalName"])){
			$SceneFestival = $row["FestivalID"];
		}
	}
}


$sqlInsert = "INSERT INTO Scene (SceneName, FestivalID, Capacity) VALUES ('$SceneName', '$SceneFestival', '$SceneCapacity')";

if ((int)$_SESSION['sceneAlreadyAdded'] == false){
	if ($conn->query($sqlInsert) === true) {
    	echo "Data ble lagt til";
	} 
	else {
    	echo "Error: " . $sqlInsert . "<br>" . $conn->error;
	}
}
$_SESSION['taskDoneScene'] = true;
header("Location: admin-add-scene.php");
?>
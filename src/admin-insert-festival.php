<?php 
session_start();
include_once 'includes/dbh.inc.php';

$FestivalName = $_POST["festivalName"];
$FestivalDateStart = $_POST["festivalDateStart"];
$FestivalDateEnd = $_POST["festivalDateEnd"];

$sqlFestival = "SELECT * FROM Festival";
$resultFestival = mysqli_query($conn, $sqlFestival);

$_SESSION['festivalName'] = $FestivalName;

//Går igjennom festival-databasen og sjekker om det allerede er en festival med det navnet
if(mysqli_num_rows($resultFestival) > 0){
	while($row = mysqli_fetch_assoc($resultFestival)){
		if(strtolower($FestivalName) == strtolower($row["FestivalName"])){
			$_SESSION['festivalAlreadyAdded'] = true;
		}
	}
}

$sqlInsert = "INSERT INTO Festival (FestivalName, TimeStart, TimeEnd) VALUES ('$FestivalName', '$FestivalDateStart', '$FestivalDateEnd')";

if ((int)$_SESSION['festivalAlreadyAdded'] == false){
	if ($conn->query($sqlInsert) === true) {
    	echo "Data ble lagt til";
	} 
	else {
    	echo "Error: " . $sqlInsert . "<br>" . $conn->error;
	}
}
$_SESSION['taskDoneFestival'] = true;
header("Location: admin-add-festival.php");
?>
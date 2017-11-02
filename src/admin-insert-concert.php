<?php 
session_start();
include_once 'includes/dbh.inc.php';

//Oppretter variabler hentet fra admin-add-concert.php
$BandID = $_POST["band"];
$FestivalID = $_POST["festival"];
$SceneID = $_POST["scene"];
$TicketSold = $_POST["ticketSold"];

$sqlConcert = "SELECT * FROM Concert";
$resultConcert = mysqli_query($conn, $sqlConcert);

$_SESSION['concertName'] = $ConcertName;
$_SESSION['concertAlreadyAdded'] = false;

$startTime = $ConcertDateStart . " " . $ConcertTimeStart;
$endTime = $ConcertDateEnd . " " . $ConcertTimeEnd;

//Går igjennom scene-databasen og sjekker om det allerede er en scene med det navnet
/*
if(mysqli_num_rows($resultConcert) > 0){
	while($row = mysqli_fetch_assoc($resultConcert)){
		if(strtolower($SceneName) == strtolower($row["SceneName"]) && $FestivalID == $row["FestivalID"]){
			$_SESSION['sceneAlreadyAdded'] = true;
		} 
	}
}
*/

$sqlInsert = " INTO Concert (ConcertTimeStart, ConcertTimeEnd, SceneID, BandID, FestivalID, TicketPrice, TicketsSold)
		      VALUES ('$startTime', '$endTime', '$SceneID', '$BandID', '$FestivalID', '$TicketPrice', '$TicketSold')";

if ((int)$_SESSION['concertAlreadyAdded'] == false){
	if ($conn->query($sqlInsert) === true) {
    	echo "Data ble lagt til";
	} 
	else {
    	echo "Error: " . $sqlInsert . "<br>" . $conn->error;
	}
}
$_SESSION['taskDoneConcert'] = true;
header("Location: admin-add-concert.php");
?>
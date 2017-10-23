<?php

session_start();

include_once 'includes/dbh.inc.php';
$band = $_GET["band"];
$val = $_GET["val"];

$sql = "SELECT * FROM Booking_Offers WHERE bandName = '$band' AND Validation = $val";
$result = mysqli_query($conn, $sql);
$offer_result = $result->fetch_all();

$validation = $offer_result[0][1];
$bandName = $offer_result[0][2];
if($band == $bandName and $validation == $val){
    $id = $offer_result[0][0];
    $concertStart = $offer_result[0][3];
    $concertEnd = $offer_result[0][4];
    $sceneID = $offer_result[0][5];
    $email = $offer_result[0][6];
    $price = $offer_result[0][8];
    $festivalID = $offer_result[0][9];

    //Checking if offer is overlaping with an already sent offer
    validDateOffers($concertStart, $concertEnd, $festivalID, $sceneID, $conn);

    mail($email, utf8_decode("Booking offer for ") . utf8_decode($bandName),
	"Your band " . $bandName . " has received an offer to play at festiv4len on " . $date . ", " . $time . " on Stage " . $sceneID .
	"." . 
	"For the concert, you will be paid " . $price . "kr \n\nClick the following link to review your offer. \n\n\n"
	. "http://org.ntnu.no/festiv4len/Prosjekt1-1901/src/booking-reply.php?band=" . $bandName . "&val=" . $validation);

	$sqlUpdate = "UPDATE Booking_Offers SET Sent=1 WHERE BookingOfferID=" . $id;

	if ($conn->query($sqlUpdate) === TRUE) {
	    $_SESSION['sent'] = True;
        $_SESSION['failed'] = False;
	    $_SESSION['message'] = "An offer for " . str_replace('_', ' ', $bandName) . " to play at festival " . $festivalID ." from " . $concertStart ." to ". $concertEnd ." has been sent. An email will be sent to " . $email;
	    header("Location: offer-overview.php");
	    exit();
	} else {
	    echo "Error updating record: " . $conn->error;
	}
	}

else {
		echo "Error";
}


function validDateOffers($concertStart, $concertEnd, $festivalID, $sceneID, $conn){
    //Calulating concertStartTime and concertEndTime in seconds
    $requestedStartTime = strtotime($concertStart);
    $requestedEndTime = strtotime($concertEnd);

    //Checking all concerts in sent offers to find duplicates
    $sql = "SELECT * FROM Booking_Offers";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)){
        //Checking if offer is on same festival and scene and has been sent
        if($sceneID == $row['SceneID'] && $festivalID == $row['FestivalID'] && $row['Sent'] == 1){
            //Getting start and end time for concert
            $startTime = strtotime($row['ConcertTimeStart']);
            $endTime = strtotime($row['ConcertTimeEnd']);

            //Checking if times overlap
            $overlapedTime = False;
            if($requestedStartTime >= $startTime && $requestedStartTime <= $endTime){
                $overlapedTime = True;
            }
            if($requestedEndTime >= $startTime && $requestedEndTime <= $endTime){
                $overlapedTime = True;
            }
            if($requestedStartTime <= $startTime && $requestedEndTime >= $endTime){
                $overlapedTime = True;
            }

            //If concerts overlaps => send error message
            if($overlapedTime){
                $_SESSION['sent'] = False;
                $_SESSION['failed'] = True;
                $_SESSION['message'] = "ERROR: Concert overlap with already sent offer!";

                header("Location: offer-overview.php?FAILED");
                exit();

            }
        }

    }
}



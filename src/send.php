<?php
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
    $scene = $offer_result[0][5];
    $email = $offer_result[0][6];
    $price = $offer_result[0][8];

    mail($email, utf8_decode("Booking offer for ") . utf8_decode($bandName),
	"Your band " . $bandName . " has received an offer to play at festiv4len on " . $date . ", " . $time . " on Stage " . $scene .
	"." . 
	"For the concert, you will be paid " . $price . "kr \n\nClick the following link to review your offer. \n\n\n"
	. "http://org.ntnu.no/festiv4len/Prosjekt1-1901/src/booking-reply.php?band=" . $bandName . "&val=" . $validation);

	$sqlUpdate = "UPDATE Booking_Offers SET Sent=1 WHERE BookingOfferID=" . $id;

	if ($conn->query($sqlUpdate) === TRUE) {
	    header("Location: offer-overview.php");
	} else {
	    echo "Error updating record: " . $conn->error;
	}

    

	}

else {
		echo "Error";
	}

?>


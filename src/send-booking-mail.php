<?php
session_start();

include_once 'includes/dbh.inc.php';


$bandName = $_POST['bandName'];
$date = $_POST['date'];
$time = $_POST['time'];
$length = $_POST['length'];
$scene = $_POST['scene'];
$price = $_POST['price'];
$email = $_POST['email'];
$val = rand();


$startTime = date($date . ' ' . $time);


$endTime = date($date . ' ' . $time); //pluss $length minutter


$sql = "INSERT INTO Booking_Offers (BandName, Validation, ConcertTimeStart, ConcertTimeEnd, SceneID, ContactEmail, Accepted)
VALUES ('$bandName', $val, '$startTime', '$endTime', $scene, '$email', 0)";

if ($conn->query($sql) === TRUE) {
    mail($email, utf8_decode("Booking offer for ") . utf8_decode($bandName),
	"Your band " . $bandName . " has received an offer to play at festiv4len on " . $date . ", " . $time . " on Stage " . $scene .
	". Your set will last " . $length . " minutes\n\n" .
	"For the concert, you will be paid " . $price . "kr \n\nClick the following link to review your offer. \n\n\n"
	. "http://org.ntnu.no/festiv4len/Prosjekt1-1901/src/booking-reply.php?band=" . $bandName . "&val=" . $val);

	$_SESSION['sent'] = true;
	$_SESSION['mail'] = $email;
	$_SESSION['band'] = $bandName;

  header("Location: booking-offer.php");

} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}



?>

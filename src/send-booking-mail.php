<?php
session_start();


$bandName = $_POST['bandName'];
$date = $_POST['date'];
$price = $_POST['price'];
$email = $_POST['email'];

mail($email, utf8_decode("Booking offer for ") . utf8_decode($bandName), 
"Your band " . $bandName . " has received an offer to play at festiv4len on " . $date . "\n\n" . 
"For the concert, you will be paid " . $price . "kr \n\nClick the following link to review your offer. \n\n\n"
. "http://org.ntnu.no/festiv4len/");

$_SESSION['sent'] = true;
$_SESSION['mail'] = $email;
$_SESSION['band'] = $bandName;

header("Location: booking-offer.php");

?>

<?php
session_start();

include_once 'includes/dbh.inc.php';


$email = $_POST['email'];

$sql = "SELECT * FROM Booking_Offers WHERE ContactEmail = '$email'";

$result = mysqli_query($conn, $sql);
$offers = $result->fetch_all();

$length = sizeof($offers);

$links = "";

if ($length > 0 and $offers[0][6] == $email) {
  for ($i = 0; $i < $length; $i++) {
    $link = $offers[$i][2] . ":\nhttp://org.ntnu.no/festiv4len/Prosjekt1-1901/src/booking-reply.php?band=" . $offers[$i][2] . "&val=" . $offers[$i][1] . "\n\n";
    $links .= $link;
  }
  mail($email, utf8_decode("Booking offer for ") . utf8_decode($bandName),
  "Here are your booking links: \n\n\n" . $links);

  $_SESSION['sent'] = true;
  $_SESSION['mail'] = $email;

  header("Location: lost-url.php");


}
else {
  echo "You don't have any offers";
}


?>

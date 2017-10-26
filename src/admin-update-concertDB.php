<?php
session_start();
include_once 'includes/dbh.inc.php';

$ConcertID = $_POST["concerts"];
$TicketsSold = $_POST["ticketsSold"];

$sql = "UPDATE Concert SET TicketsSold='$TicketsSold' WHERE ConcertID = '$ConcertID'";

if ($conn->query($sql) === TRUE) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . $conn->error;
}

header("Location: admin-update-concert.php");
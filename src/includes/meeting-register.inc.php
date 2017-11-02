<?php

session_start();

if (isset($_POST['submit'])) {

    include_once 'dbh.inc.php';

    $concertID = mysqli_escape_string($conn, $_POST['ConcertID']);
    $date = mysqli_escape_string($conn, $_POST['date']);
    $time = mysqli_escape_string($conn, $_POST['time']);
    $combinedDT = $date . ' ' . $time;

    $combinedDT = date('Y-m-d H:i:s', strtotime($combinedDT));

    $sql = "UPDATE Concert SET MeetingTime = '$combinedDT' WHERE ConcertID = '$concertID'";
    mysqli_query($conn, $sql);


    header("Location: ../meeting.php");
    exit();
}
else {
    header("Location: ../index.php");
    exit();
}
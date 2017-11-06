<?php

session_start();

if (isset($_POST['submit'])) {

    include_once 'dbh.inc.php';

    $ConcertID = $_POST['concertID'];
    $TicketsSold = $_POST['ticketsSold'];
    $TicketPrice = $_POST['ticketPrice'];
    $TechTaskNum = $_POST['techTaskNum'];
    $CompletedTaskNum = $_POST['completedTaskNum'];


    if(!empty($TicketsSold)){
        $sql = "UPDATE Concert SET TicketsSold = '$TicketsSold' WHERE ConcertID = '$ConcertID'";
        mysqli_query($conn, $sql);
    }
    if(!empty($TicketPrice)){
        $sql = "UPDATE Concert SET TicketPrice = '$TicketPrice' WHERE ConcertID = '$ConcertID'";
        mysqli_query($conn, $sql);
    }
    if(!empty($TechTaskNum)){
        $sql = "UPDATE Concert SET TechTasks = '$TechTaskNum' WHERE ConcertID = '$ConcertID'";
        mysqli_query($conn, $sql);
    }
    if(!empty($CompletedTaskNum)) {
        $sql = "UPDATE Concert SET TasksCompleted = '$CompletedTaskNum' WHERE ConcertID = '$ConcertID'";
        mysqli_query($conn, $sql);
    }

    header("Location: ../admin-update-concert.php");
    exit();

}
else {
    header("Location: ../index.php");
    exit();
}
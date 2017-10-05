<?php

session_start();

if (isset($_POST['submit'])) {

    include_once 'dbh.inc.php';

    $ConcertID = $_POST['ConcertID'];
    $ConcertDemands = $_POST['ConcertDemands'];

    foreach ($ConcertDemands as  &$value){
        $sql = "INSERT INTO Concert_Demands (ConcertID, Demand) VALUES ('$ConcertID', '$value')";
        mysqli_query($conn, $sql);
    }

    header("Location: ../add-demands.php?DemandsAdded");
    exit();
}
else {
    header("Location: ../index.html");
    exit();
}
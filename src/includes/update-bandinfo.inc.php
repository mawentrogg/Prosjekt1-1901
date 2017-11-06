<?php

session_start();

if (isset($_POST['submit'])) {

    include_once 'dbh.inc.php';

    $BandID = $_POST['BandID'];
    $PopRank = $_POST['poprank'];
    $Sales = $_POST['sales'];


    if(!(empty($PopRank))){
        $sql = "UPDATE Band SET PopRank = '$PopRank' WHERE BandID = '$BandID'";
        mysqli_query($conn, $sql);
    }

    if(!(empty($Sales))){
        $sql = "UPDATE Band SET Sales = '$Sales' WHERE BandID = '$BandID'";
        mysqli_query($conn, $sql);
    }



    header("Location: ../admin-update-bandinfo.php");
    exit();

}
else {
    header("Location: ../index.php");
    exit();
}
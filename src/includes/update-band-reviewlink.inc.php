<?php

session_start();

if (isset($_POST['submit'])) {

    include_once 'dbh.inc.php';

    $BandID = $_POST['BandID'];
    $ReviewLink = $_POST['reviewlink'];

    if(!(empty($ReviewLink))){
        $sql = "UPDATE Band SET ReviewLink = '$ReviewLink' WHERE BandID = '$BandID'";
        mysqli_query($conn, $sql);
    }

    header("Location: ../admin-add-band-reviewlink.php");
    exit();

}
else {
    header("Location: ../index.php");
    exit();
}
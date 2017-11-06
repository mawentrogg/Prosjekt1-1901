<?php

session_start();

if (isset($_POST['submit'])) {

    include_once 'dbh.inc.php';

    $ConcertID = $_POST['ConcertID'];
    $Food = $_POST['food'];
    $Technical = $_POST['technical'];
    $Security = $_POST['security'];
    $Marketing = $_POST['marketing'];

    //Checking if Concert already has costs
    $sql = "SELECT * FROM Concert_Costs  WHERE ConcertID = '$ConcertID'";
    $result = mysqli_query($conn, $sql);
    $rowNum = mysqli_num_rows($result);

    if($rowNum > 0){
        if(!empty($Food)){
            $sql = "UPDATE Concert_Costs SET Food = '$Food' WHERE ConcertID = '$ConcertID'";
            mysqli_query($conn, $sql);
        }
        if(!empty($Technical)){
            $sql = "UPDATE Concert_Costs SET Technical = '$Technical' WHERE ConcertID = '$ConcertID'";
            mysqli_query($conn, $sql);
        }
        if(!empty($Security)){
            $sql = "UPDATE Concert_Costs SET Security = '$Security' WHERE ConcertID = '$ConcertID'";
            mysqli_query($conn, $sql);
        }
        if(!empty($Marketing)) {
            $sql = "UPDATE Concert_Costs SET Marketing = '$Marketing' WHERE ConcertID = '$ConcertID'";
            mysqli_query($conn, $sql);
        }
    }
    else{
        $sql = "INSERT INTO Concert_Costs (ConcertID, Food, Technical, Security, Marketing) VALUES ('$ConcertID', '$Food', '$Technical', '$Security', '$Marketing')";
        mysqli_query($conn, $sql);
    }

    header("Location: ../admin-update-concert-costs.php");
    exit();

}
else {
    header("Location: ../index.php");
    exit();
}
<?php

session_start();

if (isset($_POST['submit'])) {

    include_once 'dbh.inc.php';

    $BandID = mysqli_escape_string($conn, $_POST['BandID']);
    $BandReview = mysqli_escape_string($conn, $_POST['BandReview']);

    //Checking if BandID or BandReview is empty
    if(empty($BandID) || empty($BandReview)){
        $_SESSION['popup'] = True;
        $_SESSION['message'] = "Empty Fields";
        header("Location: ../band-review.php");
        exit();
    }

    //Checking if review is already written for band
    $replaceReview = False;
    $sql = "SELECT * FROM BandReview";
    $result = mysqli_query($conn, $sql);
    while($row = mysqli_fetch_assoc($result)){
        if($BandID == $row['BandID']){
            $replaceReview = True;
        }
    }

    //Insert review to database
    $sql = "INSERT INTO BandReview (BandID, BandReview) VALUES ('$BandID', '$BandReview')";
    if(mysqli_query($conn, $sql)){
        if($replaceReview){
            $_SESSION['popup'] = True;
            $_SESSION['message'] = "This band already had a review. It has been overwritten";
        }
        else{
            $_SESSION['popup'] = True;
            $_SESSION['message'] = "Review added";
        }

        header("Location: ../band-review.php");
        exit();
    }
    else{
        $_SESSION['popup'] = True;
        $_SESSION['message'] = "Failed to add review...";
        header("Location: ../band-review.php");
        exit();
    }


}
else {
    header("Location: ../index.php");
    exit();
}
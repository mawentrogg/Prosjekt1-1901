<?php

session_start();

if (isset($_POST['submit'])) {

    include_once 'dbh.inc.php';

    $ConcertID = $_POST['ConcertID'];
    $FirstName = $_POST['firstname'];
    $LastName = $_POST['lastname'];
    $PhoneNumber = $_POST['phone'];
    $Email = $_POST['email'];


    if(!(empty($FirstName) || empty($LastName) || empty($PhoneNumber) || empty($Email))){

        //Checking if Concert already has ContactInfo
        $sql = "SELECT * FROM Concert_ContactInfo  WHERE ConcertID = '$ConcertID'";
        $result = mysqli_query($conn, $sql);
        $rowNum = mysqli_num_rows($result);


        if($rowNum > 0){

            $sql = "UPDATE Concert_ContactInfo SET ContactFirstName = '$FirstName', ContactLastName = '$LastName', ContactPhone = '$PhoneNumber', ContactEmail = '$Email' WHERE ConcertID = '$ConcertID'";
            mysqli_query($conn, $sql);
        }
        else{
            $sql = "INSERT INTO Concert_ContactInfo (ConcertID, ContactFirstName, ContactLastName, ContactPhone, ContactEmail) VALUES ('$ConcertID','$FirstName', '$LastName', '$PhoneNumber', '$Email')";
            mysqli_query($conn, $sql);
        }
    }

    header("Location: ../admin-update-concert-contactinfo.php");
    exit();

}
else {
    header("Location: ../index.php");
    exit();
}
<?php

session_start();

if (isset($_POST['submit'])){

    include_once 'dbh.inc.php';

    $BandName = mysqli_real_escape_string($conn, $_POST['BandName']);
    $BandDemands = mysqli_real_escape_string($conn, $_POST['BandDemands']);


    //Error handlers

    //Check for empty fields
    if(empty($BandName) || empty($BandDemands)){
        header("Location: ../add-demands.php?demands=empty");
        exit();
    }
    else{
        $username = $_SESSION['u_username'];
        $sql = "SELECT * FROM Band WHERE BandName = '$BandName'";
        $result = mysqli_query($conn, $sql);

        if (!(mysqli_num_rows($result) == 1)) {
            header("Location: ../add-demands.php?demands=BandNotFound");
            exit();
        }
        else{
            while($row = mysqli_fetch_assoc($result)) {
                if($row['Manager'] == $username){
                    $sql = "UPDATE Band SET BandDemands = '$BandDemands' WHERE BandName = '$BandName';";
                    mysqli_query($conn, $sql);
                    header("Location: ../add-demands.php?demands=success");
                    exit();
                }
                else{
                    header("Location: ../add-demands.php?demands=erroasdr" . $username . "asdasd");
                    exit();
                }
            }
        }

    }

}
else {
    header("Location: ../index.html");
    exit();
}
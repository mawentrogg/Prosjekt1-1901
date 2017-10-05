<?php

session_start();

if (isset($_POST['submit'])) {

    include_once 'dbh.inc.php';

    $ConcertID = $_POST['ConcertID'];
    $ConcertDemands = $_POST['ConcertDemands'];

    $numDemands = count($ConcertDemands);
    $duplicate = 0;


    foreach ($ConcertDemands as  &$value){
        $sql = "SELECT * FROM Concert_Demands WHERE ConcertID = '$ConcertID'";
        $result = mysqli_query($conn, $sql);
        $shouldContinue = false;


        while($row = mysqli_fetch_assoc($result)){
            if(strcmp($row['Demand'],$value) == 0){
                $shouldContinue = true;
                continue;
            }
        }
        if($shouldContinue){
            $duplicate += 1;
            continue;
        }
        $sqlinsert = "INSERT INTO Concert_Demands (ConcertID, Demand) VALUES ('$ConcertID', '$value')";
        mysqli_query($conn, $sqlinsert);



    }

    header("Location: ../add-demands.php?DemandsAdded|duplicate=".$duplicate."/".$numDemands);
    exit();
}
else {
    header("Location: ../index.html");
    exit();
}
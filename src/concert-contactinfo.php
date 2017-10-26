<?php
session_start();

include_once 'includes/dbh.inc.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Concert Contact-Info</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body style="background-color: #3C6E71">

<div style="margin:0;height:100%;" class="flexBody">
    <div style="height:80vh;" class="flexWrapper">
        <p class="insideMenuHeader">Concert Contact-Info</p>
        <div class="flexWrapperInside">
            <table>
                <tr>
                    <th>Dato</th>
                    <th>Band / Artist</th>
                    <th>Scene</th>
                    <th>Contact Person</th>
                    <th>Phone Number</th>
                    <th>Email</th>
                </tr>

                <?php

                //Looping through concerts
                $sql = "SELECT * FROM Concert";
                $result = mysqli_query($conn, $sql);
                while($row = mysqli_fetch_assoc($result)){
                    $concertID = $row['ConcertID'];
                    $concertTime = date('d-m-y h:m', strtotime($row['ConcertTimeStart']));
                    $sceneID = $row['SceneID'];
                    $bandID = $row['BandID'];

                    //Getting band name
                    $sqlBand = "SELECT * FROM Band WHERE BandID = '$bandID'";
                    $resultBand = mysqli_query($conn, $sqlBand);
                    $rowBand = mysqli_fetch_assoc($resultBand);
                    $bandName = $rowBand['BandName'];

                    //Getting scene name
                    $sqlScene = "SELECT * FROM Scene WHERE SceneID = '$sceneID'";
                    $resultScene = mysqli_query($conn, $sqlScene);
                    $rowScene = mysqli_fetch_assoc($resultScene);
                    $sceneName = $rowScene['SceneName'];
                    $sceneCapacity = $rowScene['Capacity'];

                    //Getting contact info
                    $sqlInfo = "SELECT * FROM Concert_ContactInfo WHERE ConcertID = '$concertID'";
                    $resultInfo = mysqli_query($conn, $sqlInfo);
                    $rowInfo = mysqli_fetch_assoc($resultInfo);
                    $contactName = $rowInfo['ContactFirstName'] . " " . $rowInfo['ContactLastName'];
                    $contactPhone = $rowInfo['ContactPhone'];
                    $contactEmail = $rowInfo['ContactEmail'];



                    echo "<tr><td>" . $concertTime ."</td><td>" . $bandName . "</td><td>" . $sceneName . "</td><td>" . $contactName ."</td><td>" . $contactPhone ."</td><td>" . $contactEmail . "</td></tr>" ;

                }



                ?>
            </table>

        </div>

    </div>

</div>
</div>
</body>
</html>
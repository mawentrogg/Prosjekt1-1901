<?php
session_start();
//Checking if user is logged in
if(!(isset($_SESSION['u_id']))){
    header("Location: index.php");
}
include_once 'includes/dbh.inc.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Riggeoversikt</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body style="background-color: #3C6E71">
<div class="flexTop">
    <a class="hjemButton" href="<?php
    if(isset($_SESSION['u_id'])){
        echo $_SESSION['u_role'] . ".php";
    }
    else{
        echo "index.php";
    }
    ?>">Hjem</a>
    <p class="superHeader">Festiv4len</p>
    <form action="includes\logout.inc.php" method="post">
        <button type="submit" name="submit">Logg ut</button>
    </form>
</div>
<div style="margin:0;height:100%;" class="flexBody">
    <div style="height:80vh;" class="flexWrapper">
        <p class="insideMenuHeader" style="font-size: 20px; margin-bottom: 0">Du er logget inn som
            <?php
            $userLoggedIn = $_SESSION["u_username"];
            $sqlUsersTop = "SELECT * FROM Users WHERE UserUsername = '$userLoggedIn'";
            $resultUsersTop = mysqli_query($conn, $sqlUsersTop);
            $usersArrayTop = mysqli_fetch_assoc($resultUsersTop);
            $firstName = $usersArrayTop["UserFirstname"];

            echo $firstName;
            ?></p>
        <p class="insideMenuHeader">Rigge-oversikt</p>
        <div class="flexWrapperInside">
            <table>
                <tr>
                    <th>Dato</th>
                    <th>Band / Artist</th>
                    <th>Scene</th>
                    <th>Billettsalg</th>
                    <th>Billettinntekter</th>
                </tr>

                <?php

                //Looping through concerts
                $sql = "SELECT * FROM Concert";
                $result = mysqli_query($conn, $sql);
                while($row = mysqli_fetch_assoc($result)){
                    $concertTime = date('d-m-y h:m', strtotime($row['ConcertTimeStart']));
                    $sceneID = $row['SceneID'];
                    $bandID = $row['BandID'];
                    $ticketsSold = $row['TicketsSold'];
                    $ticketPrice = $row['TicketPrice'];

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

                    echo "<tr><td>" . $concertTime ."</td><td>" . $bandName . "</td><td>" . $sceneName . "</td><td>" . $ticketsSold ." / ". $sceneCapacity ."</td><td>" . $ticketsSold*$ticketPrice . ",-</td></tr>";

                }



                ?>
            </table>

        </div>

    </div>

</div>
</div>
</body>
</html>
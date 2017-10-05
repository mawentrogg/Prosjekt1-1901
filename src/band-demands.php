<!-- For brukerhistorie 4, Bookingansvarlig skal få oversikt over tekniske behov-->

<?php
session_start();

//Checking if user is logged in
if(!(isset($_SESSION['u_id']))){
    header("Location: index.html");
    exit();
}
else{
    if(!($_SESSION['u_role'] == "bookingans" or $_SESSION['u_role'] == "organizer" or $_SESSION['u_role'] == "tech")){
        header("Location: " . $_SESSION['u_role'] . ".php");
    }
}

include 'includes/dbh.inc.php';
$sql = "SELECT * FROM Concert";
$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Konsertkrav</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body style="background-color: #3C6E71">
<div class="flexBody">
<div class="flexTop">
        <a class="hjemButton" href="<?php
                    if(isset($_SESSION['u_id'])){
                        echo $_SESSION['u_role'] . ".php";
                    }
                    else{
                        echo "index.html";
                    }
                    ?>">Hjem</a>
        <p class="superHeader">Festiv4len</p>
        <form action="includes\logout.inc.php" method="post">
            <button type="submit" name="submit">Logg ut</button>
        </form> 
    </div>
    <div style="width:auto;height:auto" class="flexWrapper">
        <p class="insideMenuHeader" style="font-size: 20px; margin-bottom: 0">Du er logget inn som
            <?php
            $userLoggedIn = $_SESSION["u_username"];
            $sqlUsersTop = "SELECT * FROM Users WHERE UserUsername = '$userLoggedIn'";
            $resultUsersTop = mysqli_query($conn, $sqlUsersTop);
            $usersArrayTop = mysqli_fetch_assoc($resultUsersTop);
            $firstName = $usersArrayTop["UserFirstname"];
            echo $firstName;
            ?></p>

    <p class="insideMenuHeader">Konsertkrav</p>
    <div class="flexWrapperInside">
    <table>
        <tr>
            <th>Konsert</th>
            <th>Tekniske krav</th>
        </tr>
        <?php

        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while($row = mysqli_fetch_assoc($result)) {

                $concertID = $row['ConcertID'];
                $sqldemandID = "SELECT * FROM Concert_Demands WHERE ConcertID = '$concertID'";
                $resultdemandID = mysqli_query($conn, $sqldemandID);
                $row3 = mysqli_fetch_assoc($resultdemandID);
                $demands;

                echo "<tr> <td>" . "Scene " . $row['SceneID']. " | " . date('d.M.Y H:s',strtotime($row['ConcertTimeStart'])) .  "</td> <td>" . $demands . "</td>" . "</tr>";
        }
        }


        ?>
    </table>
    </div>


        <a class="helleButton" style='$style;'href='rigge-oversikt.php'>Tilbake</a>

    </div>
</div>
</body>
</html>
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
$sqlConcert = "SELECT * FROM Concert";
$sqlConcertDemands = "SELECT * FROM Concert_Demands";
$resultConcert = mysqli_query($conn, $sqlConcert);

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
    <div style="width:auto;height:70vh;" class="flexWrapper">
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

                if (mysqli_num_rows($resultConcert)){
                    while($row = mysqli_fetch_assoc($resultConcert)) {


                        $ConcertID = $row['ConcertID'];
                        $sqlConTech = "SELECT UserID FROM Concerts_UserTechnicians WHERE ConcertID = '$ConcertID'";
                        $resultConTech = mysqli_query($conn, $sqlConTech);
                        $conTechArray = mysqli_fetch_assoc($resultConTech);

                        //finner brukernavn slik at vi kan sjekke om den brukeren er innlogget og linja markeres grønt
                        $userIDConTech = $conTechArray['UserID'];
                        $sqlUsers = "SELECT * FROM Users WHERE UserID = '$userIDConTech'";
                        $resultUsers = mysqli_query($conn, $sqlUsers);
                        $usersArray= mysqli_fetch_assoc($resultUsers);
                        $userName = $usersArray['UserFirstname'];

                        //endrer bakgrunnsfarge dersom gjeldende bruker er pålogget
                        if($_SESSION["u_username"] == $usersArray['UserUsername']){
                            $style = 'background-color: #88cc88; border-radius:5px;';
                        }
                        else{
                            $style = 'background-color:#b2c2bf; border-radius:5px;';
                        }

                        //Finner demand
                        $sqlDemand = "SELECT * FROM Concert_Demands WHERE ConcertID = '$ConcertID'";
                        $resultDemand = mysqli_query($conn, $sqlDemand);
                        $demandArray = mysqli_fetch_assoc($resultDemand);

                        $outDemand = "";
                        while($rowDemand = mysqli_fetch_assoc($resultDemand)){
                            $outDemand = $outDemand. $rowDemand['Demand'] . ", ";
                        }

                        $outDemand = substr($outDemand, 0,-2);

                        echo "<tr> <td style='$style;'>" . date('d.M.Y H:s', strtotime($row['ConcertTimeStart'])) . " | " . "Scene " . $row['SceneID']  . "</td> <td style='$style'>" . $outDemand  . "</td>" . "</tr>";
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
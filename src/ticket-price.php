<!-- For brukerhistorie 12 (pri 10) Bookingsjef skal få generert et forslag til billettpris-->

<?php
session_start();

//Checking if user is logged in
if(!(isset($_SESSION['u_id']))){
    header("Location: index.php");
    exit();
}
else{
    if(!($_SESSION['u_role'] == "bookingsjef")){
        header("Location: " . $_SESSION['u_role'] . ".php");
    }
}

include 'includes/dbh.inc.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Generer billettpris</title>
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
<div style="margin: 0;height: 100%" class="flexBody">
    <div style="height: 75vh; width:100%" class="flexWrapper">
        <p class="insideMenuHeader" style="font-size: 20px; margin-bottom: 0">
            <?php
            $userLoggedIn = $_SESSION["u_username"];
            $sqlUsersTop = "SELECT * FROM Users WHERE UserUsername = '$userLoggedIn'";
            $resultUsersTop = mysqli_query($conn, $sqlUsersTop);
            $usersArrayTop = mysqli_fetch_assoc($resultUsersTop);
            $firstName = $usersArrayTop["UserFirstname"];
            ?></p>

        <p class="insideMenuHeader">Generer forslag til billettpris</p>
        <div class="flexWrapperInside">
            <table>

            </table>

            <table>
                <tr>
                    <th colspan="3">Konsert-Info</th>
                    <th colspan="6">Konsert-Kostnader</th>
                </tr>
                <tr>
                    <th>Band / Artist</th>
                    <th>Konsertdato</th>
                    <th>Scenekapasitet</th>
                    <th>Matvarer</th>
                    <th>Lyd / lys</th>
                    <th>Vakthold</th>
                    <th>Markedsføring</th>
                    <th>Totale kostnader</th>
                    <th>Billettpris</th>
                </tr>

                <?php
                    $sql = "SELECT * FROM Concert";
                    $result = mysqli_query($conn, $sql);
                    while($row = mysqli_fetch_assoc($result)){
                        $concertID = $row['ConcertID'];
                        $date = date('d-m-y H:i',strtotime($row['ConcertTimeStart']));
                        $bandID = $row['BandID'];
                        $sceneID = $row['SceneID'];

                        //Getting BandName
                        $sqlBand = "SELECT * FROM Band WHERE BandID = '$bandID'";
                        $resultBand = mysqli_query($conn, $sqlBand);
                        $rowBand = mysqli_fetch_assoc($resultBand);
                        $bandName = $rowBand['BandName'];

                        //Getting sceneCapacity
                        $sqlScene = "SELECT * FROM Scene WHERE SceneID = '$sceneID'";
                        $resultScene = mysqli_query($conn, $sqlScene);
                        $rowScene = mysqli_fetch_assoc($resultScene);
                        $sceneCapacity= $rowScene['Capacity'];

                        //Getting all the costs of the concert and formating to pretty
                        $sqlCosts = "SELECT * FROM Concert_Costs WHERE ConcertID = '$concertID'";
                        $resultCosts = mysqli_query($conn, $sqlCosts);
                        $rowCosts = mysqli_fetch_assoc($resultCosts);
                        $food = number_format($rowCosts['Food'], 0, ',', ' ');
                        $technical = number_format($rowCosts['Technical'], 0, ',', ' ');
                        $security = number_format($rowCosts['Security'], 0, ',', ' ');
                        $marketing = number_format($rowCosts['Marketing'], 0, ',', ' ');
                        $totalCosts = $rowCosts['Food'] + $rowCosts['Technical'] + $rowCosts['Security'] + $rowCosts['Marketing'];
                        $totalCostsFormat = number_format($totalCosts, 0, ',', ' ');

                        //Generate price
                        $price = ($totalCosts / $sceneCapacity) * 5;
                        $price = number_format(ceil($price / 5)*5, 0, ',', ' ');

                        if(empty($food) || empty($technical) || empty($security) || empty($marketing)){
                            echo "<tr><td>$bandName</td><td>$date</td><td>$sceneCapacity</td><td></td><td></td><td></td><td></td><td></td><td>TBD</td></tr>";
                        }
                        else{
                            //Add row to table
                            echo "<tr><td>$bandName</td><td>$date</td><td>$sceneCapacity</td><td>$food,-</td><td>$technical,-</td><td>$security,-</td><td>$marketing,-</td><td>$totalCostsFormat,-</td><td>$price,-</td></tr>";
                        }



                    }
                ?>


            </table>
        </div>
    </div>
</div>
</body>
</html>
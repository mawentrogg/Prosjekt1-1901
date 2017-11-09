<!-- BH Pri 8 -->

<?php
session_start();


//Checking if user is logged in
if(!(isset($_SESSION['u_id']))){
    header("Location: index.php");
    exit();
}
else{
    if(!($_SESSION['u_role'] == "bookingans")){
        header("Location: " . $_SESSION['u_role'] . ".php");
    }
}

include 'includes/dbh.inc.php';

$sqlScenes = "SELECT * FROM Scene";
$resultScene = mysqli_query($conn, $sqlScenes);
$scenes = "";

if(mysqli_num_rows($resultScene) > 0){
    while ($row = mysqli_fetch_assoc($resultScene)) {
        $scenes .= "<option value=".$row['SceneID']."> ID: " . $row["SceneID"] . " - Scenenavn: "  .$row['SceneName'] . " </option>";
    }
}
?>


?>

<!DOCTYPE html>
<html>
<head>
    <title>Finn band relatert til scene</title>
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
            echo "index.php";
        }
        ?>">Hjem</a>
        <p class="superHeader">Festiv4len</p>
        <form action="includes/logout.inc.php" method="post">
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



        <p class="insideMenuHeader">Finn konserter</p>
        <div class="flexWrapperInside">
            <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
                <Select name="SceneID">
                    <?php
                    echo $scenes;
                    ?>
                </Select>    
                <input type="submit" value="Hent konserter"> 
            </form>



            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // collect value of input field
                $SceneID = $_POST['SceneID'];
                if (empty($SceneID)) {
                    echo "ID er ikke spesifisert";

                } else {
                    $bandQuery = "SELECT * FROM Scene 
                    INNER JOIN Concert on Scene.SceneID = Concert.SceneID
                    INNER JOIN Festival on Concert.FestivalID=Festival.FestivalID
                    INNER JOIN Band on Band.BandID=Concert.BandID
                    WHERE Scene.SceneID=".$SceneID;
                    $lastBand="";

                    if($result = mysqli_query($conn, $bandQuery)) {
                        if(mysqli_num_rows($result) > 0) {

                            echo "<table>\r\n"
                            . "<tr>\r\n"
                            . "<th>Band</th>"
                            . "<th>Manager </th>"
                            . "<th>PopRank</th>"
                            . "<th>Salg</th>\r\n"
                            . "<th>Sjanger</th>\r\n"
                            . "</tr>\r\n";
                
                            while($row = mysqli_fetch_array($result)){
                                if(!($lastBand == $row['BandName'])){
                                    echo "<tr>";
                                    echo "<td>  " . $row['BandName'] . "</td>";
                                    //echo "<tr>";
                                    echo "<td> " . $row['Manager'] . "</td>";
                                    echo "<td> " . $row['PopRank'] . "</td>";
                                    echo "<td> " . $row['Sales'] . "</td>";
                                    echo "<td> " . $row['Genre'] . "</td>";
                                    echo "</tr>";
                                    $lastBand=$row['BandName'];

                                    
                                    
                                }else{//Samme band
                                    echo "</tr>";
                                }//Skal uansett ha siste konsertinfo.
                                
                                    echo "<tr id='".$row['BandID'].">" //style='display:none'>"
                                    . "<td>  </td>"
                                    . "<td> Konsertdato:". $row['ConcertTimeStart'] ."</td>"
                                    . "<td> Bilettsalg: ". $row['TicketsSold'] ."</td>"
                                    . "<td> Bilettpris: ". $row['TicketPrice'] ."</td>"
                                    . "<td> Festival - ". $row['FestivalName'] ."</td>"
                                    . "</tr>\r\n".
                                    "</tr>";

                                
                            }
                            echo "</table>";
                            mysqli_free_result($result);
                        } else {
                            echo "<p style='color: blue'>Ingen konserter funnet på denne scenen.";
                        }
                    } else {
                      echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
                   }
                   mysqli_close($conn);
                }
            }
            ?>

            <?php
            if(isset($_SESSION['popup']) && $_SESSION['popup']){
                $_SESSION['popup'] = False;
                $popupMessage = $_SESSION['message'];
                echo "<script type='text/javascript'> window.alert('$popupMessage')</script>";
            }
            ?>

        </div>
    </div>
</div>
</body>
</html>
<!-- BH Pri 8 -->

<?php
session_start();


//Checking if user is logged in
/*
if(!(isset($_SESSION['u_id']))){
    header("Location: index.php");
    exit();
}
else{
    if(!($_SESSION['u_role'] == "bookingans")){
        header("Location: " . $_SESSION['u_role'] . ".php");
    }
}*/

include 'includes/dbh.inc.php';

//Initialize
/*
$_SESSION["managerEpost"] = "kimmern92@gmail.com";
$_SESSION["bandID"] = "2";
$_SESSION["concertID"] = "36";
*/
$managerEpost = $_SESSION["managerEpost"];
$bandID = $_SESSION["bandID"];
$concertID = $_SESSION["concertID"];
$getConcertSongsSQL = "SELECT * FROM Band_Songs WHERE Band_Songs.ConcertID = '$concertID'";

?>


?>

<!DOCTYPE html>
<html>
<head>
    <title>Sett inn ny opptreden</title>
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
        <p class="insideMenuHeader" style="font-size: 20px; margin-bottom: 0">Du er logget inn som manager for
            <?php
            $bandQuery = "SELECT Band.BandName FROM Band WHERE Band.BandID = $bandID";
            $result = mysqli_query($conn, $bandQuery);
            while($row = mysqli_fetch_assoc($result)){
                echo $row['BandName']."<br>";
            }
            

            echo $managerEpost;
            ?></p>

        <p class="insideMenuHeader">Sett inn opptreden</p>
        <div class="flexWrapperInside">

        <div style="background-color:#353535; overflow-y: hidden" class="flexWrapperInside">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <label for="SongName">Sang navn</Label>
            <input type="text" name="SongName" value="<?php echo $songName; ?>">  
            <label for="SongGenre"> Sang Sjanger </Label>
            <input type="text" name="SongGenre" id="SongGenre" value="<?php echo $songGenre; ?>"> 
            <input type="submit" value="Legg til opptreden"> 
            </form>
           
            <?php

            
            $resultSongs = mysqli_query($conn, $getConcertSongsSQL);
            echo "<table>\r\n"
            . "<tr>\r\n"
            . "<th>ID</th>"
            . "<th>Sang</th>\r\n"
            . "<th>Sangsjanger</th>\r\n"
            . "</tr>\r\n";

            while($row = mysqli_fetch_assoc($resultSongs)){
                echo "<tr>";
                echo "<td>" . $row["SongID"] . "</td>";
                echo "<td>" . $row["SongName"] . "</td>";
                echo "<td>" . $row["SongGenre"] . "</td>";
                echo "</tr>";
            }
            echo "</Table>";

            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                } 
                $songName= $_POST["SongName"];
                $songGenre = $_POST["SongGenre"];
                echo "Song:".$songName." Genre:".$songGenre." BandID:".$bandID." ConcertID:".$concertID." Email:".$managerEpost; 

                $insertValuesSQL = "INSERT INTO Band_Songs (SongName, SongGenre, ConcertID)
                VALUES ('$songName', '$songGenre', '$concertID')";

                if ($conn->query($insertValuesSQL) === true) {
                    echo "Data ble lagt til";
                } 
                else {
                    echo "Error: " . $insertValuesSQL . "<br>" . $conn->error;
                }
                
                
            }
            else{
                echo "Nothing requested.";
            }
            $conn->close();
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
</div>
</body>
</html>
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

//Initialize
$_SESSION["ManagerEpost"] = "kimmern92@gmail.com";
$_SESSION["BandID"] = "2";
$_SESSION["ConcertID"] = "36";
$managerEpost = $_SESSION["ManagerEpost"];
$band = $_SESSION["BandID"];
$concert = $_SESSION["ConcertID"];
$getConcertSongsSQL = "SELECT * FROM Band_Songs WHERE Band_Songs.ConcertID = '$concert'";

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
        <p class="insideMenuHeader" style="font-size: 20px; margin-bottom: 0">Du er logget inn som
            <?php
            $managerEpost = $_SESSION["ManagerEpost"];
            

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
            <input type="submit" value="Hent band"> 
            </form>
           
            <?php

            
            $resultSongs = mysqli_query($conn, $getConcertSongsSQL);
            echo "<Table>";
            while($row = mysqli_fetch_assoc($resultSongs)){
                echo "<td>" . $row["SongID"] . "</td>";
                echo "<td>" . $row["SongName"] . "</td>";
                echo "<td>" . $row["SongGenre"] . "</td>";
            }
            echo "</Table>";

            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                } 
                $songName= $_POST["SongName"];
                $songGenre = $_POST["SongGenre"];

                $insertValuesSQL = "INSERT INTO Band_Songs (SongName, SongGenre, BandID, ConcertID)
                VALUES ($songName, $songGenre, $band, $concert)";

                if (mysqli_query($conn, $sql)) {
                    echo "New record created successfully";
                } else {
                    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                }
                
            }
            else{
                echo "Nothing requested.";
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
</div>
</body>
</html>
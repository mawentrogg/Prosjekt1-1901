<?php
session_start();
include_once 'includes/dbh.inc.php';

//Checking if user is logged in. If not sending back to proper site
if(!(isset($_SESSION['u_id']))){
    header("Location: index.html");
}
else{
    if(!($_SESSION['u_role'] == "admin")){
        header("Location: " . $_SESSION['u_role'] . ".php");
    }
}
 
$sqlFestival = "SELECT * FROM Festival";
$resultFestival = mysqli_query($conn, $sqlFestival);
$festivals = "";

if(mysqli_num_rows($resultFestival) > 0){
    while ($row = mysqli_fetch_assoc($resultFestival)) {
        $festivals .= "<option>" . $row["FestivalID"] . "</option>";
    }
}

$sqlBand = "SELECT * FROM Band";
$resultBand = mysqli_query($conn, $sqlBand);
$bands = "";

if(mysqli_num_rows($resultBand) > 0){
    while ($row = mysqli_fetch_assoc($resultBand)) {
        $bands .= "<option>" . $row["BandID"] . "</option>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Legg til konsert</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body style="background-color: #3C6E71">
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
	<div style="margin: 0;height: 100%" class="flexBody">
        <div style="width:50%;" class="flexWrapper">  
            <p class="insideMenuHeader">Legg til konsert</p>
            <div style="background-color:#353535; overflow-y: hidden" class="flexWrapperInside">
            <form method="post" action="admin-add-concert.php">
                <label>Festival-ID:</label>
                <select name="festival">
                    <?php  
                    echo $festivals;
                    ?>
                </select>
                <input type="submit" name="update" value="Update festival">
            </form>
                <form action="admin-insert-concert.php" method="post">
                    <label>Band-ID:</label>
                    <select name="band">
                        <?php          
                        echo $bands;
                        ?>
                    </select>
                    <label>Scene-ID:</label>
                    <select name="scene">
                        <?php  
                        $chosenFesival = $_POST["festival"];
                        echo $chosenFesival;
                        $sqlScene = "SELECT * FROM Scene WHERE FestivalID = $chosenFesival";
                        $resultScene = mysqli_query($conn, $sqlScene);
                        $scenes = "";

                        if(mysqli_num_rows($resultScene) > 0){
                            while ($row = mysqli_fetch_assoc($resultScene)) {
                                $scenes .= "<option>" . $row["SceneID"] . "</option>";
                            }
                        }
                        echo $scenes;
                        ?>
                    </select>
                    <label>Konsert-start:</label>
                    <input type="date" name="concertDateStart" required>
                    <input type="time" name="concertTimeStart" required>
                    <label>Festival-slutt:</label>
                    <input type="date" name="concertDateEnd" required>
                    <input type="time" name="concertTimeStart" required>
                    <label>Billettpris:</label>
                    <input type="number" name="ticketPrice" required>
                    <input type="submit" value="Submit">
                </form>

                <?php
                    if ($_SESSION['taskDoneConcert']){
                        if ($_SESSION['concertAlreadyAdded'] == true) {
                        echo "<p>Konserten " . $_SESSION['sceneName'] . " finnes allerede i databasen</p>";
                    }
                        else{
                            echo "<p>Konserten " . $_SESSION['sceneName'] . " ble lagt til i databasen</p>";
                        }
                        $_SESSION['taskDoneConcert'] = false;
                    }
                    
                ?>
            </div>	
        </div>
    </div>
</body>
</html>
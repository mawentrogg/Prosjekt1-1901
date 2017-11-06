<!-- For brukerhistorie 5, Manager skal legge til behov-->

<?php
session_start();


//Checking if user is logged in
if(!(isset($_SESSION['concertID']))){
    header("Location: index.php?Denied");
    exit();
}


include_once 'includes/dbh.inc.php';
$username = $_SESSION['u_username'];
$concertID = $_SESSION['concertID'];
//$concertID = 2;

$sql = "SELECT * FROM Concert WHERE ConcertID = '$concertID'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$bandID = $row['BandID'];
$date = strtotime($row['ConcertTimeStart']);
$date = date('d.M.Y H:s', $date);

$sql = "SELECT * FROM Band WHERE BandID = '$bandID'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$bandName = $row['BandName'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Legge til bandkrav</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body style="background-color: #3C6E71">
<div style="justify-content: center;" class="flexTop">
    <p class="superHeader">Festiv4len</p>
    </div>
    <div style="margin: 0;height: 100%" class="flexBody">
        <div style="width: 100vh ; height: 75vh;" class="flexWrapper">
            <form action="includes/insert-demands.inc.php" method="POST">
                <?php echo "<p style='font-size: 25px' class='indexHeader'>Legge til bandkrav: <br> " . $bandName . " - " . $date . "</p>"; ?>

                <label>Demands: </label>
                <?php
                    $sql = "SELECT * FROM Technical_Demands";
                    $result = mysqli_query($conn, $sql);
                    if(mysqli_num_rows($result) > 0){
                        while($row = mysqli_fetch_assoc($result)){
                            echo "<br><input type = 'checkbox' name = 'ConcertDemands[]' value = " . $row['DemandText'] . ">" . $row['DemandText'] . "</input>";
                        }
                    }
                    echo "<input type = 'hidden' name = 'ConcertID' value='$concertID'></input>";
                ?>
                <input type="submit" name ="submit" value="Legg til krav"/>
            </form>
        </div>
    </div>
</body>
</html>
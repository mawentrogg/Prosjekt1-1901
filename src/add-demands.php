<!-- For brukerhistorie 5, Manager skal legge til behov-->

<?php
session_start();

//Checking if user is logged in
if(!(isset($_SESSION['u_id']))){
    header("Location: index.html");
    exit();
}
else{
    if(!($_SESSION['u_role'] == "manager")){
        header("Location: " . $_SESSION['u_role'] . ".php");
    }
}
include_once 'includes/dbh.inc.php';
$username = $_SESSION['u_username'];
//$concertID = $_SESSION['concertID'];
$concertID = 2;

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
    <title>Band Demands</title>
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
        <div style="width:auto" class="flexWrapper">
            <form action="includes/insert-demands.inc.php" method="POST">
                <?php echo "<p class='indexHeader'>Technical demands: <br> " . $bandName . " - " . $date . "</p>"; ?>

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
                <input type="submit" name ="submit" value="Add demands"/>
            </form>
        </div>
    </div>
</body>
</html>
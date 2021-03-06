<?php
session_start();
include_once 'includes/dbh.inc.php';

//Checking if user is logged in. If not sending back to proper site
if(!(isset($_SESSION['u_id']))){
    header("Location: index.php");
}
else{
    if(!($_SESSION['u_role'] == "admin")){
        header("Location: " . $_SESSION['u_role'] . ".php");
    }
}

$sqlBand = "SELECT * FROM Band";
$resultBand = mysqli_query($conn, $sqlBand);
$bands = "";

if(mysqli_num_rows($resultBand) > 0){
    while ($row = mysqli_fetch_assoc($resultBand)) {
        $bands .= "<option>" . $row["BandID"] . " - ". $row['BandName'] ."</option>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Legg til link for anmeldelse av band</title>
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
    <div style="width:50%; " class="flexWrapper">
        <p class="insideMenuHeader">Admin//Legg til link for anmeldelse av band</p>
        <div style="background-color:#353535; overflow-y: hidden;" class="flexWrapperInside">
            <form action="includes/update-band-reviewlink.inc.php" method="post">
                <label>Band-ID:</label>
                <select name="BandID" required>
                    <?php
                    echo $bands;
                    ?>
                </select>
                <label>Popularity rank:</label>
                <input type="url" name="reviewlink">
                <input type="submit" name="submit" value="Legg til link for anmeldelse av band">
            </form>
        </div>
    </div>
</div>
</body>
</html>
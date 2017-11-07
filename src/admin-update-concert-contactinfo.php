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

$sqlConcert = "SELECT * FROM Concert";
$resultConcert = mysqli_query($conn, $sqlConcert);
$concerts = "";

if(mysqli_num_rows($resultConcert) > 0){
    while ($row = mysqli_fetch_assoc($resultConcert)) {
        $concerts .= "<option>" . $row["ConcertID"] . "</option>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Oppdater kontaktinfo for konsert</title>
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
        <p class="insideMenuHeader">Admin//Oppdater kontaktinfo for konsert</p>
        <div style="background-color:#353535; overflow-y: hidden;" class="flexWrapperInside">
            <form action="includes/update-concert-contactinfo.inc.php" method="post">
                <label>Konsert-ID:</label>
                <select name="ConcertID" required>
                    <?php
                    echo $concerts;
                    ?>
                </select>
                <label>Fornavn:</label>
                <input type="text" name="firstname" required>
                <label>Etternavn:</label>
                <input type="text" name="lastname" required>
                <label>Telefonnummer:</label>
                <input type="number" name="phone" required>
                <label>E-post:</label>
                <input type="text" name="email" required>
                <input type="submit" name="submit" value="Legg til kontakinfo">
            </form>
        </div>
    </div>
</div>
</body>
</html>
<?php
session_start();
include_once 'includes\dbh.inc.php';

//Checking if user is logged in. If not sending back to proper site
if(!(isset($_SESSION['u_id']))){
    header("Location: index.html");
}
else{
    if(!($_SESSION['u_role'] == "bookingans")){
        header("Location: " . $_SESSION['u_role'] . ".php");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Organizer</title>
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
    <div class="flexBody">
        <div style="width:50%" class="flexWrapper">
            <div class="flexWrapperInside">
                <table>
                    <tr>
                        <th style="color: white; background-color: #353535;">Oversikt:</th>
                    </tr>
                    <tr>
                        <td><a href="booking-offer.php">Send Booking Offer</a></td>
                    </tr>
                    <tr>
                        <td><a href="band-demands.php">Band Demands</a></td>
                    </tr>
                    <tr>
                        <td><a href="rigge-oversikt.php">Riggeoversikt</a></td>
                    </tr>
                    <tr>
                    <td><a href="band.php">Band-oversikt</a></td>
                </tr>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
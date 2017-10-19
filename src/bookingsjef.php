<?php
session_start();
include_once 'includes\dbh.inc.php';

//Checking if user is logged in. If not sending back to proper site
if(!(isset($_SESSION['u_id']))){
    header("Location: index.php");
}
else{
    if(!($_SESSION['u_role'] == "bookingsjef")){
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
                        echo "index.php";
                    }
                    ?>">Hjem</a>
        <p class="superHeader">Festiv4len</p>
        <form action="includes\logout.inc.php" method="post">
            <button type="submit" name="submit">Logg ut</button>
        </form> 
    </div>
    <div style="margin: 0;height: 100%" class="flexBody">
        <div style="width:50%; height: 70vh;" class="flexWrapper">
            <p class="insideMenuHeader">Bookingsjef//Oversikt</p>
            <div class="flexWrapperInside">
                <table>
                    <tr>
                        <th style="color: white; background-color: #353535;">Oversikt</th>
                    </tr>
                    <tr>
                        <td><a href="concert-report.php">Se konsertrapport</a></td>
                    </tr>
                    <tr>
                        <td><a href="kalender.php">Kalender</a></td>
                    </tr>
                    <tr>
                        <td><a href="offer-overview.php">Oversikt over bookingtilbud</a></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
<?php
session_start();
include_once 'includes\dbh.inc.php';

//Checking if user is logged in. If not sending back to proper site
if(!(isset($_SESSION['u_id']))){
    header("Location: index.html");
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

    <div class="flexBody">
        <div class="flexWrapper">
            <form action="includes\logout.inc.php" method="post">
                <button type="submit" name="submit">Logg ut</button>
            </form>
            <div class="flexWrapperInside">
                <table>
                    <tr>
                        <th style="color: white; background-color: #353535;">Oversikt</th>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
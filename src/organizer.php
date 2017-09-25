<?php
session_start();
include_once 'includes\dbh.inc.php';

//Checking if user is logged in. If not sending back to proper site
if(!(isset($_SESSION['u_id']))){
    header("Location: index.html");
}
else{
    if(!($_SESSION['u_username'] == "organizer")){
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
			<p class="insideMenuHeader">Organizer//Oversikt</p>
			<div class="flexWrapperInside">
				<table>
					<tr>
						<td><a href="rigge-oversikt.php">Rigge-oversikt</a></td>
					</tr>
				</table>
			</div>
			<form action="includes\logout.inc.php" method="post">
			<button type="submit" name="submit">Logg ut</button>
			</form> 
        </div>
    </div>
</body>
</html>
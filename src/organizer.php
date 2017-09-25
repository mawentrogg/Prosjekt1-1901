<?php
session_start();
include_once 'includes\dbh.inc.php';

//Checking if user is logged in
if(!(isset($_SESSION['u_id']))){
    header("Location: index.html");
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
			<div class="flexWrapperTop">
				<p class="menuHeader">Arrangør//Admin</p>
				<form action="includes\logout.inc.php" method="post">
				<button type="submit" name="submit">Logg ut</button>
				</form>
				</div>
<<<<<<< HEAD
			<div class="flexWrapperInside">
				<table>
					<tr>
						<td><a href="rigge-oversikt.php">Rigge-oversikt</a></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
=======
        </div>
    </div>
>>>>>>> master
</body>
</html>
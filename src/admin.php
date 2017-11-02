<?php
session_start();
include_once 'includes\dbh.inc.php';

//Checking if user is logged in. If not sending back to proper site
if(!(isset($_SESSION['u_id']))){
    header("Location: index.html");
}
else{
    if(!($_SESSION['u_role'] == "admin")){
        header("Location: " . $_SESSION['u_role'] . ".php");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Admin</title>
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
			<p class="insideMenuHeader">Admin//Oversikt</p>
			<div class="flexWrapperInside">
				<table>
					<tr>
                        <td><a href="admin-add-festival.php">Legg til festival</a></td>
                    </tr>
                    <tr>
                        <td><a href="admin-add-scene.php">Legg til scene</a></td>
                    </tr>
                    <tr>
                        <td><a href="admin-add-concert.php">Legg til konsert</a></td>
                    </tr>
                    <tr>
                        <td><a href="admin-update-concert.php">Oppdater konsert</a></td>
                    </tr>
				</table>
			</div>
        </div>
    </div>
</body>
</html>
<?php
session_start();
include_once 'includes\dbh.inc.php';

//Checking if user is logged in. If not sending back to proper site
if(!(isset($_SESSION['u_id']))){
    header("Location: index.html");
}
else{
    if(!($_SESSION['u_role'] == "organizer")){
        header("Location: " . $_SESSION['u_role'] . ".php");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Arrangør</title>
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
		<div class="flexWrapper">
			<p class="insideMenuHeader">Arrangør//Oversikt</p>
			<div class="flexWrapperInside">
				<table>
					<tr>
						<td><a href="rigge-oversikt.php">Rigge-oversikt</a></td>
					</tr>
					<tr>
						<td><a href="konsert-oversikt-organizer.php">Konsert-oversikt</a></td>
					</tr>
					
				</table>
			</div>
        </div>
    </div>
</body>
</html>
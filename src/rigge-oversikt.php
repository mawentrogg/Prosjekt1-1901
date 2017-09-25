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
	<title>Riggeoversikt</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body style="background-color: #3C6E71">
	<div class="flexBody">
    	<div class="flexWrapper">
   			<p class="insideMenuHeader">Rigge-oversikt</p>
        	<div class="flexWrapperInside">
				<table>
					<tr>
						<th>RiggeID</th>
						<th>RiggeRolle</th>
					</tr>
					<tr>
						<td>HENTINNFRADATABASE</td>
						<td>HENTINNFRADATABASE</td>
					</tr>
				</table>
			</div>

			<a class="hjemButton" href="<?php
                    if(isset($_SESSION['u_id'])){
                        echo $_SESSION['u_role'] . ".php";
                    }
                    else{
                        echo "index.html";
                    }
                    ?>">Hjem</a>

            <form action="includes\logout.inc.php" method="post">
				<button type="submit" name="submit">Logg ut</button>
			</form> 
		</div>
	</div>
</body>
</html>
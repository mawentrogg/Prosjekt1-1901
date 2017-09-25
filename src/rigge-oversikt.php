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
    	<div style="width:auto" class="flexWrapper">
   			<p class="insideMenuHeader">Rigge-oversikt</p>
        	<div class="flexWrapperInside">
				<table>
					<tr>
						<th>Dato/tid</th>
						<th>Scene</th>
            			<th>Artist</th>
            			<th>Lyd</th>
            			<th>Lys</th>
            			<th>Krav</th>
					</tr>
					<tr>
						<td>I morgen</td>
						<td>Moroscenen</td>
            			<td>Teletubbies</td>
            			<td>Roy Gunnar</td>
            			<td>Vigdis Sløyfrid</td>
            			<td>4 mikrofoner, 8 flasker cava, 3 horer</td>
					</tr>
					<tr>
			            <td>18/10</td>
			            <td>Moroscenen</td>
			            <td>Fantorangen</td>
			            <td>Glen Kennetd</td>
			            <td>Mygghild</td>
			            <td>Sjokoladekake</td>
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

	</table>

	</div>
</body>
</html>
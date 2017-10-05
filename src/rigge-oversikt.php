<?php
    session_start();
    //Checking if user is logged in
    if(!(isset($_SESSION['u_id']))){
        header("Location: index.html");
    }
    include_once 'includes/dbh.inc.php';
    $sqlConcert = "SELECT * FROM Concert";
    $sqlBand = "SELECT * FROM Band";
    $resultConcert = mysqli_query($conn, $sqlConcert);
?>

<!DOCTYPE html>
<html>
<head>
	<title>Riggeoversikt</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body style="background-color: #3C6E71">
	<div class="flexBody">
    	<div style="width:auto;height:70vh;" class="flexWrapper">
        <p class="insideMenuHeader" style="font-size: 20px; margin-bottom: 0">Du er logget inn som
        <?php
        $userLoggedIn = $_SESSION["u_username"];
        $sqlUsersTop = "SELECT * FROM Users WHERE UserUsername = '$userLoggedIn'";
        $resultUsersTop = mysqli_query($conn, $sqlUsersTop);
        $usersArrayTop = mysqli_fetch_assoc($resultUsersTop);
        $firstName = $usersArrayTop["UserFirstname"];

        echo $firstName;
        ?></p>
   			<p class="insideMenuHeader">Rigge-oversikt</p>
        	<div class="flexWrapperInside">
				<table>
					<tr>
						<th>Dato/tid</th>
						<th>Scene</th>
            			<th>Artist</th>
            			<th>Tekniker</th>
					</tr>

                    <?php
                    if (mysqli_num_rows($resultConcert)){
                        while($row = mysqli_fetch_assoc($resultConcert)) {

                            $BandID = $row['BandID'];
                            $sqlBandID = "SELECT * FROM Band WHERE BandID = '$BandID'";
                            $resultIDBand = mysqli_query($conn, $sqlBandID);
                            $row3 = mysqli_fetch_assoc($resultIDBand);
                            $bandName = $row3['BandName'];

                            $ConcertID = $row['ConcertID'];
                            $sqlConTech = "SELECT UserID FROM Concerts_UserTechnicians WHERE ConcertID = '$ConcertID'";
                            $resultConTech = mysqli_query($conn, $sqlConTech);
                            $conTechArray = mysqli_fetch_assoc($resultConTech);
                            $userIDConTech = $conTechArray['UserID'];
                            $sqlUsers = "SELECT * FROM Users WHERE UserID = '$userIDConTech'";
                            $resultUsers = mysqli_query($conn, $sqlUsers);
                            $usersArray= mysqli_fetch_assoc($resultUsers);
                            $userName = $usersArray['UserFirstname'];


                            if($_SESSION["u_username"] == $usersArray['UserUsername']){
                                $style = 'background-color: #384745; color:white; border-radius:5px;';
                            }
                            else{
                                $style = 'background-color:#b2c2bf; border-radius:5px;';
                            }

                            echo "<tr> <td style='$style;'>" . $row['ConcertTimeStart'] . "</td> <td  style='$style;'>" . $row['SceneID'] . "</td> <td  style='$style;'> ". $bandName. "</td> <td  style='$style;'> " . $userName. "</td></tr>";
                        }
                    }


                    ?>

				</table>
			</div>

            <a class='hjemButton' style='$style;'href='band-demands.php'>Se krav</a>

		</div>

	</table>

	</div>
</body>
</html>
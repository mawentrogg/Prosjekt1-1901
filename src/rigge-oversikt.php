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
    $resultBand = mysqli_query($conn, $sqlBand);


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

                    <?php


                    if (mysqli_num_rows($resultConcert) > 0 and mysqli_num_rows($resultBand) > 0){
                        while($row = mysqli_fetch_assoc($resultConcert) and $row2 = mysqli_fetch_assoc($resultBand)) {




                            $BandID = $row['BandID'];
                            $sqlID = "SELECT * FROM Band WHERE BandID = '$BandID'";
                            $resultIDBand = mysqli_query($conn, $sqlID);
                            $row3 = mysqli_fetch_assoc($resultIDBand);
                            $bandName = $row3['BandName'];

                            $SoundID = $row['SoundID'];
                            $sqlIDSound = "SELECT * FROM Technicians WHERE TechID = '$SoundID'";
                            $resultIDSound = mysqli_query($conn, $sqlIDSound);
                            $row4 = mysqli_fetch_assoc($resultIDSound);
                            $SoundName = $row4['TechName'];

                            $LightID = $row['LightID'];
                            $sqlIDLight = "SELECT * FROM Technicians WHERE TechID = '$LightID'";
                            $resultIDLight = mysqli_query($conn, $sqlIDLight);
                            $row5 = mysqli_fetch_assoc($resultIDLight);
                            $LightName = $row5['TechName'];

                            echo "<tr> <td>" . $row['ConcertTimeStart'] . "</td> <td>" . $row['SceneID'] . "</td> <td> ". $bandName. "</td> <td> " . $SoundName. "</td> <td>" . $LightName . "</td></tr>";


                            

                        }
                    }


                    ?>

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
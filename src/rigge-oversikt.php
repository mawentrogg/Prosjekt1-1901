<?php
    session_start();
    //Checking if user is logged in
    if(!(isset($_SESSION['u_id']))){
        header("Location: index.php");
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
        <div style="height: 75vh;" class="flexWrapper">
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
                        <th>Legg til oppmøtetid</th>
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

                            $sqlMeeting = "SELECT * FROM Concert WHERE ConcertID = '$ConcertID'";
                            $resultMeeting = mysqli_query($conn, $sqlMeeting);
                            $meetingArray = mysqli_fetch_assoc($resultMeeting);
                            $meetingTime = $meetingArray[MeetingTime];


                            if($_SESSION["u_username"] == $usersArray['UserUsername']){
                                //endrer bakgrunnsfarge
                                $style = 'background-color: #88cc88; border-radius:5px;';
                                $meeting = "<a href='meeting.php'>Legg til oppmøtetid</a>";
                            }
                            else{
                                $style = 'background-color:#b2c2bf; border-radius:5px;';
                                $meeting = 'Ingen oppmøtetid';
                            }

                            if($meetingTime != 0000-00-00){
                                $meeting = strtotime($meetingTime);
                                $meeting = date('d-m-y H:i', $meeting);
                            }

                            echo "<tr> <td style='$style;'>" . $row['ConcertTimeStart']. "</td> <td  style='$style;'>" . $row['SceneID'] . "</td> <td  style='$style;'> ". $bandName. "</td> <td  style='$style;'> " . $userName. "</td><td style='$style;'>$meeting</td></tr>";
                        }
                    }


                    ?>
				</table>

			</div>

            <a class='helleButton' style='$style;'href='band-demands.php'>Se krav</a>
			</div>

		</div>
	</div>
</body>
</html>
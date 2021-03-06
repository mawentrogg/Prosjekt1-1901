<?php
    session_start();

    //Checking if user is logged in. If not sending back to proper site
    //I'll just let this stand for now, I will assume it works, this still needs testing.
    //https://www.tutorialspoint.com/mysqli/ is good for this stuff.
    if(!(isset($_SESSION['u_id']))){
        header("Location: index.php");
    }
    else if(!(($_SESSION['u_role'] == "bookingans") || ($_SESSION['u_role'] =="bookingsjef"))){
            header("Location: " . $_SESSION['u_role'] . ".php");
    }
?>
<!DOCTYPE html>
<html>
<head>
	<title>Band-oversikt</title>
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
        
        <?php
            $conn = mysqli_connect("mysql.stud.ntnu.no", "kimera_gruppe4", "festiv4l", "kimera_gruppe4");
            if(! $conn ) {
                die('Could not connect: ' . mysqli_error());
             }
            

             $bandQuery = "SELECT Band.BandID, Band.BandName, Band.PopRank, Band.Sales, Band.BandGenre, Concert.ConcertTimeStart, Concert.BandID, Concert.FestivalID, Festival.FestivalID, Festival.FestivalName
                        FROM Band 
                        INNER JOIN Concert ON Band.BandID=Concert.BandID
                        INNER JOIN Festival ON Concert.FestivalID=Festival.FestivalID";
            ?>
            <p class="insideMenuHeader">Band-oversikt</p>
        	<div class="flexWrapperInside">
                <?php
                if($result = mysqli_query($conn, $bandQuery)) {
                    if(mysqli_num_rows($result) > 0) {

                        echo "<table>\r\n"
                        . "<tr>\r\n"
                        . "<th>Navn</th>"
                        . "<th>Pop.rangering</th>"
                        . "<th>Salg</th>"
                        . "<th>Sjanger</th>\r\n"
                        . "<th>Tidligere Konsert</th>\r\n"
                        . "</tr>\r\n";
            
                        while($row = mysqli_fetch_array($result)){
                            echo "<tr>";
                            echo "<td>" . $row['BandName'] . "</td>";
                            echo "<td>" . $row['PopRank'] . "</td>";
                            echo "<td>" . $row['Sales'] . "</td>";
                            echo "<td>" . $row['BandGenre'] . "</td>";

                            //I belive this needs some onClick functionality, checking php compatibility.
                            //This entire table should be refered to whenever anyone clicks an individual band name, eventlistener and ID of the selected item somehow. 
                            //Defaulting to listing one of the concerts...
                            echo "<td>" . $row['ConcertTimeStart'] . " @ " . $row['FestivalName'] ."</td>";
                            echo "</tr>";
                         }
                         echo "</table>";
                         mysqli_free_result($result);
                      } else {
                         echo "No records matching your query were found.";
                      }
                   } else {
                      echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
                   }
                   mysqli_close($conn);
                ?>
            </div> 
		</div>

	</table>

	</div>
</body>
</html>

            
        

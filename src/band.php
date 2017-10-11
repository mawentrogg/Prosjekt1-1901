<?php
    session_start();

    //Checking if user is logged in. If not sending back to proper site
    //I'll just let this stand for now, I will assume it works, this still needs testing.
    //https://www.tutorialspoint.com/mysqli/ is good for this stuff.
    if(!(isset($_SESSION['u_id']))){
        header("Location: index.html");
    }
    else if(!($_SESSION['u_role'] == "bookingans")){
            header("Location: " . $_SESSION['u_role'] . ".php");
    }
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
                        echo "index.html";
                    }
                    ?>">Hjem</a>
        <p class="superHeader">Festiv4len</p>
        <form action="includes\logout.inc.php" method="post">
            <button type="submit" name="submit">Logg ut</button>
        </form> 
    </div>
	<div style="margin:0; height:100%" class="flexBody">
    	<div style="height:80vh;" class="flexWrapper">
        
        <?php
            $conn = mysqli_connect("mysql.stud.ntnu.no", "kimera_gruppe4", "festiv4l", "kimera_gruppe4");
            if(! $conn ) {
                die('Could not connect: ' . mysqli_error());
             }
            

            $bandQuery = "SELECT Band.BandID, Band.BandName, BandInfo.BandID, BandInfo.PopRank, BandInfo.Sales, BandInfo.Genre 
                        FROM Band 
                        INNER JOIN BandInfo ON Band.BandID=BandInfo.BandID";
            ?>
            <p class="insideMenuHeader">Band-oversikt</p>
        	<div class="flexWrapperInside">
                <?php
                if($result = mysqli_query($conn, $bandQuery)) {
                    if(mysqli_num_rows($result) > 0) {

                        echo "<table>\r\n"
                        . "<tr>\r\n"
                        . "<th>Name</th>"
                        . "<th>Popularity Rank</th>"
                        . "<th>Sales</th>"
                        . "<th>Genre</th>\r\n"
                        . "<th>Tidligere Konsert</th>\r\n"
                        . "</tr>\r\n";
            
                        while($row = mysqli_fetch_array($result)){
                            echo "<tr>";
                            echo "<td>" . $row['BandName'] . "</td>";
                            echo "<td>" . $row['PopRank'] . "</td>";
                            echo "<td>" . $row['Sales'] . "</td>";
                            echo "<td>" . $row['Genre'] . "</td>";

                            //I belive this needs some onClick functionality, checking php compatibility.
                            //This entire table should be refered to whenever anyone clicks an individual band name, eventlistener and ID of the selected item somehow. 
                            echo "<td> snart fikset </td>";
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

            
        

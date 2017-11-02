<?php
session_start();
include_once 'includes/dbh.inc.php';

//Checking if user is logged in. If not sending back to proper site
if(!(isset($_SESSION['u_id']))){
    header("Location: index.php");
}
else{
    if(!($_SESSION['u_role'] == "foodservice")){
        header("Location: " . $_SESSION['u_role'] . ".php");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Konsert-oversikt</title>
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
    <div style="width: 80%; height: 75vh;" class="flexWrapper">
        <p class="insideMenuHeader">Konsert-oversikt</p> 
        <div class="flexWrapperInside">
        <?php

        //Henter inn diverse fra databasen
        $sqlConcert = "SELECT * FROM Concert ORDER BY ConcertTimeStart;";
        $resultConcert = mysqli_query($conn, $sqlConcert);

        echo "<table>"
              . "<tr>"
              . "<th>Band</th>"
              . "<th>Musikksjanger</th>"
              . "<th>Konsert-start</th>"
              . "<th>Konsert-slutt</th>"
              . "<th>Publikumsantall</th>"
              . "</tr>";

        date_default_timezone_set("Europe/Oslo");
        if(mysqli_num_rows($resultConcert) > 0){
            while($row1 = mysqli_fetch_assoc($resultConcert)) {
                $concertID = $row1["ConcertID"];
                $bandID = $row1["BandID"];

                $sqlBand = "SELECT * FROM Band WHERE BandID = '$bandID'";
                $resultBand = mysqli_query($conn, $sqlBand);
                $row2 = mysqli_fetch_assoc($resultBand);

                $sqlConcertReport = "SELECT * FROM Concert_Report WHERE ConcertID = '$concertID'";
                $resultConcertReport = mysqli_query($conn, $sqlConcertReport);
                $row3 = mysqli_fetch_assoc($resultConcertReport);

                $sqlBandInfo = "SELECT * FROM BandInfo WHERE BandID = '$bandID'";
                $resultBandInfo = mysqli_query($conn, $sqlBandInfo);
                $row4 = mysqli_fetch_assoc($resultBandInfo);

                $BandName = $row2["BandName"];
                $Genre = $row4["Genre"];
                $ConcertStart = $row1["ConcertTimeStart"];
                $ConcertEnd = $row1["ConcertTimeEnd"];
                $Attendance = $row3["Attendance"];

                    //Strings to display time
                $displayStart = strtotime($ConcertStart);
                $displayStart = date('d.M.Y H:s', $displayStart);
                $displayEnd = strtotime($ConcertEnd);
                $displayEnd = date('H:s', $displayEnd);
                
                $unix_concert_time_start = strtotime($ConcertStart);
                $unix_concert_time_end = strtotime($ConcertEnd);
                $unix_time_now = strtotime("now");
                $concert_has_started = FALSE;
                $concert_has_ended = FALSE;
                $concert_row_class = "notStarted";
                
                if ($unix_time_now > $unix_concert_time_start) {
                    $concert_has_started = TRUE;
                    $concert_row_class = "started";
                    if ($unix_time_now > $unix_concert_time_end) {
                        $concert_has_ended = TRUE;
                        $concert_row_class = "finished";
                    }
                }

                echo "<tr class='$concert_row_class'>"
                      . "<td>$BandName</td>"
                      . "<td>$Genre</td>"
                      . "<td>$displayStart</td>"
                      . "<td>$displayEnd</td>"
                      . "<td>$Attendance</td>"
                      . "</tr>";
            }
        }

        echo "</table>";


        ?>
        </div>
    </div>
</body>
</html>
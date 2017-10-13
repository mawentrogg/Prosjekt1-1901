<!-- For brukerhistorie 11, Bookingsjef skal se en rapport over tidligere konserter-->

<?php
session_start();

//Checking if user is logged in
if(!(isset($_SESSION['u_id']))){
    header("Location: index.php");
    exit();
}
else{
    if(!($_SESSION['u_role'] == "bookingsjef")){
        header("Location: " . $_SESSION['u_role'] . ".php");
    }
}

if (!isset($_POST['submit'])){
    header("Location: concert-report.php");
    exit();
}
include 'includes/dbh.inc.php';
$sceneID = $_POST['sceneID'];

$sql = "SELECT * FROM Scene WHERE SceneID = '$sceneID'";
$result = mysqli_query($conn, $sql);
$sceneArray = mysqli_fetch_assoc($result);
$sceneName = $sceneArray['SceneName'];
$festivalID = $sceneArray['FestivalID'];

$sql = "SELECT * FROM Festival WHERE FestivalID = '$festivalID'";
$result = mysqli_query($conn, $sql);
$festivalArray = mysqli_fetch_assoc($result);
$festivalName = $festivalArray['FestivalName'];

?>

<!DOCTYPE html>
<html>
<head>
    <title>Konsertrapport</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body style="background-color: #3C6E71">
<div class="flexBody">
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

        <p class="insideMenuHeader">Konsertrapport</p>
        <div class="flexWrapperInside">
            <table>
                <tr>
                    <th>Date</th>
                    <th>Band</th>
                    <th>Sjanger</th>
                    <th>Publikumsantall</th>
                    <th>Inntekter</th>
                    <th>Utgifter</th>
                    <th>Fortjeneste</th>
                </tr>

                <?php

                //Variables for scene summary
                $sumAttendance = 0;
                $sumIncome = 0;
                $sumOutcome = 0;

                $sqlConcert = "SELECT * FROM Concert WHERE SceneID = '$sceneID'";
                $resultConcert = mysqli_query($conn, $sqlConcert);
                while($row = mysqli_fetch_assoc($resultConcert)){

                    //Checking if concert is to be added, based on date
                    $currentTime = time();
                    $dateSeconds = strtotime($row['ConcertTimeStart']);

                    if($currentTime > $dateSeconds){
                        //Getting BandID and date
                        $bandID = $row['BandID'];
                        $date = date('d.M.Y H:s', strtotime($row['ConcertTimeStart']));

                        //Getting bandName from BandID
                        $sqlBand = "SELECT * FROM Band WHERE BandID = '$bandID'";
                        $resultBand = mysqli_query($conn, $sqlBand);
                        $bandArray = mysqli_fetch_assoc($resultBand);
                        $bandName = $bandArray['BandName'];

                        //Getting bandGenre from BandID
                        $sqlBand = "SELECT * FROM BandInfo WHERE BandID = '$bandID'";
                        $resultBand = mysqli_query($conn, $sqlBand);
                        $bandArray = mysqli_fetch_assoc($resultBand);
                        $bandGenre = $bandArray['Genre'];

                        //Getting attendance and economy from ConcertID
                        $concertID = $row['ConcertID'];
                        $sqlReport = "SELECT * FROM Concert_Report WHERE ConcertID = '$concertID'";
                        $resultReport = mysqli_query($conn, $sqlReport);
                        $reportArray = mysqli_fetch_assoc($resultReport);
                        $attendance = $reportArray['Attendance'];
                        $outcome = $reportArray['Outcome'];
                        $income = $reportArray['Income'];
                        $profit = $income - $outcome;

                        $sumAttendance += $attendance;
                        $sumIncome += $income;
                        $sumOutcome += $outcome;
                        $sumProfit = $sumIncome - $sumOutcome;


                        echo "<tr><td>" . $date . "</td> <td>" . $bandName . "</td> <td>" . $bandGenre . "</td> <td>" . $attendance . "</td> 
                            <td>" . $income . ",-</td>  <td>" . $outcome . ",-</td>   <td>" . $profit . ",-</td></tr>";
                    }
                }
                ?>
            </table>
            <label>Oppsummering scene:</label>
            <table>
                <tr>
                    <th>SceneID</th>
                    <th>SceneNavn</th>
                    <th>Festival</th>
                    <th>Publikumsantall</th>
                    <th>Inntekter</th>
                    <th>Utgifter</th>
                    <th>Fortjeneste</th>
                </tr>
                <?php
                    echo"<tr><td>" . $sceneID . "</td><td>". $sceneName ."</td><td>". $festivalName ."</td><td>". $sumAttendance ."</td><td>". $sumIncome ."</td>
                        <td>". $sumOutcome ."</td><td>". $sumProfit ."</td></tr>";
                ?>
            </table>

        </div>
    </div>
</div>
</body>
</html>
<!-- For brukerhistorie 11, Bookingsjef skal se en rapport over tidligere konserter-->

<?php
session_start();

//Checking if user is logged in
if(!(isset($_SESSION['u_id']))){
    header("Location: index.html");
    exit();
}
else{
    if(!($_SESSION['u_role'] == "bookingsjef")){
        header("Location: " . $_SESSION['u_role'] . ".php");
    }
}

include 'includes/dbh.inc.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Konsertkrav</title>
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
            echo "index.html";
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
                    $sqlReport = "SELECT * FROM Concert_Report";
                    $resultReport = mysqli_query($conn, $sqlReport);
                    while($row = mysqli_fetch_assoc($resultReport)){

                        //Getting BandID and date from concertID
                        $concertID = $row['ConcertID'];
                        $sqlConcert = "SELECT * FROM Concert WHERE ConcertID = '$concertID'";
                        $resultConcert = mysqli_query($conn, $sqlConcert);
                        $concertArray = mysqli_fetch_assoc($resultConcert);
                        $bandID = $concertArray['BandID'];
                        $date = date('d.M.Y H:s', strtotime($concertArray['ConcertTimeStart']));

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

                        //Beregne fortjeneste
                        $outcome = $row['Outcome'];
                        $income = $row['Income'];
                        $profit = $income - $outcome;

                        echo "<tr><td>" . $date . "</td> <td>" . $bandName . "</td> <td>" . $bandGenre . "</td> <td>" . $row['Attendance'] . "</td> 
                               <td>" . $income . ",-</td>  <td>" . $outcome . ",-</td>   <td>" . $profit . ",-</td>";
                    }


                ?>


            </table>
        </div>
    </div>
</div>
</body>
</html>
<!-- For brukerhistorie 4, Bookingansvarlig skal få oversikt over tekniske behov-->

<?php
session_start();

//Checking if user is logged in
if(!(isset($_SESSION['u_id']))){
    header("Location: index.html");
    exit();
}
else{
    if(!($_SESSION['u_role'] == "bookingans" or $_SESSION['u_role'] == "organizer" or $_SESSION['u_role'] == "tech")){
        header("Location: " . $_SESSION['u_role'] . ".php");
    }
}

include 'includes/dbh.inc.php';
$sql = "SELECT * FROM Band";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Band Demands</title>
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
    <div style="width:auto;height:auto" class="flexWrapper">
    <p class="insideMenuHeader">Band-krav</p>
    <div class="flexWrapperInside">
    <table>
        <tr>
            <th>Band</th>
            <th>Demands</th>
        </tr>
        <?php

        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while($row = mysqli_fetch_assoc($result)) {
                echo "<tr> <td>" . $row['BandName'] . "</td> <td>" . $row['BandDemands'] . "</td>" . "</tr>";
            }
        }
        ?>
    </table>
    </div>


        <a class="hjemButton" style='$style;'href='rigge-oversikt.php'>Tilbake</a>

    </div>
</div>
</body>
</html>
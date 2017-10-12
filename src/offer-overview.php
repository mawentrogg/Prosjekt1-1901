<!-- For brukerhistorie 12, Bookingsjef skal få oversikt over bookingtilbud som er sendt-->

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
    <title>Bookingtilbud - oversikt</title>
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

        <p class="insideMenuHeader">Bookingtilbud - oversikt</p>
        <div class="flexWrapperInside">
            <table>
                <tr>
                    <th>BookingOfferID</th>
                    <th>BandName</th>
                    <th>Genre</th>
                    <th>SceneID</th>
                    <th>ConcertTime Start</th>
                    <th>ConcertTime End</th>
                    <th>Price</th>
                    <th>Contact Email</th>
                    <th>Accepted</th>
                    <th>Send Offer</th>
                </tr>

                <?php
                $sql = "SELECT * FROM Booking_Offers";
                $result = mysqli_query($conn, $sql);
                while($row = mysqli_fetch_assoc($result)){

                    if($row['Accepted'] == 1){
                        $accepted = "True";
                    }
                    else{
                        $accepted = "False";
                    }

                    echo "<tr><td>" . $row['BookingOfferID'] . "</td> 
                    <td>" . $row['BandName'] . "</td>
                    <td>" . $row['Genre'] . "</td>
                    <td>" . $row['SceneID'] . "</td>
                    <td>" . date('d.M.Y H:s', strtotime($row['ConcertTimeStart'])) . "</td>
                    <td>" . date('d.M.Y H:s', strtotime($row['ConcertTimeEnd'])) . "</td> 
                    <td>" . $row['Price'] . "</td> 
                    <td>" . $row['ContactEmail'] . "</td><td>" . $accepted  . "</td>";
                    if ($row['Sent'] == 1) {
                        echo '<td> Sent </td>';
                    }
                    else {
                        echo "<td style='background-color: green'> <a class='btn btn-primary btn-lg' onclick='return confirm(\"Are you sure you want to send the offer?\");' href='send.php?band=" . $row['BandName'] . "&val=" .   $row['Validation'] . "'>Send</a></td> </td>";
                    }
                }


                ?>


            </table>
        </div>
    </div>
</div>
</body>
</html>
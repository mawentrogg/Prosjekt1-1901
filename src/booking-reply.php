<?php
session_start();
include_once 'includes/dbh.inc.php';

$band = $_GET["band"]

?>



<!DOCTYPE html>
<html>
<head>
    <title>Reply to booking offer</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body style="background-color: #3C6E71">
    <div class="flexBody">
        <div style="width:auto;height:70vh;" class="flexWrapper">
            <p class="insideMenuHeader">Your booking offer</p>
            <div class="flexWrapperInside" style="background-color: #353535">

              <?php
              $sql = "SELECT * FROM Booking_Offers WHERE bandName = '$band'";
              $result = mysqli_query($conn, $sql);
              $offer_result = $result->fetch_all();
              if ($offer_result[0][6] == 0) {
                <table>
                  <tr>
                    <th>
                  </tr>
                </table>
                echo "ikke accepted";
                echo '<input type="submit" value="Accept">
                <input type="submit" value="Decline">';
                //legg til band
                //legg til konsert
              } else {
                echo "accepted";
                //header("Location: add-demands.php");
                //$concertId = hent concert id
              }
              ?>







            </div>


        </div>

    </table>

    </div>
</body>
</html>

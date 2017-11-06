
<?php
session_start();
include_once 'includes/dbh.inc.php';
$band = $_GET["band"];
$val = $_GET["val"];
?>



<!DOCTYPE html>
<html>
<head>
    <title>Reply to booking offer</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script>
      function confirmDelete() {
        return confirm("Are you sure you want to decline the offer?");
      }
    </script>
</head>
<body style="background-color: #3C6E71">
<div style="justify-content: center;" class="flexTop">
    <p class="superHeader">Festiv4len</p>
    </div>
    <div style="margin:0; height: 100%" class="flexBody">
        <div style="height: 75vh;" class="flexWrapper">
            <p class="insideMenuHeader">Your booking offer</p>
            <div class="flexWrapperInside" style="overflow-y:hidden; background-color: #353535">

              <?php
              $sql = "SELECT * FROM Booking_Offers WHERE bandName = '$band' AND Validation = $val";
              $result = mysqli_query($conn, $sql);
              $offer_result = $result->fetch_all();
              $validation = $offer_result[0][1];
              $bandName = $offer_result[0][2];
              if($band == $bandName and $validation == $val)
              {
                $concertStart = $offer_result[0][3];
                $concertEnd = $offer_result[0][4];
                $scene = $offer_result[0][5];
                $email = $offer_result[0][6];
                $genre = $offer_result[0][7];
                $id = $offer_result[0][0];
                $festival = $offer_result[0][9];
                $price = $offer_result[0][8];
                  echo "<table>
                    <tr>
                      <th>Band</th>
                      <th>Concert start time</th>
                      <th> Concert end time </th>
                      <th> Scene </th>
                    </tr>
                    <tr>
                      <th>" . $bandName . " </th>
                      <th>" . $concertStart . " </th>
                      <th>" . $concertEnd . " </th>
                      <th>" . $scene . " </th>
                    </tr>
                  </table>";
                  if ($offer_result[0][11] == 0) {
                  ob_start();
                  echo '<form method="post"> <input type="submit" value="Accept" name="accept"></form>
                  <form method="post" onsubmit="return confirmDelete();"> <input style="background-color: red" type="submit" value="Decline" name="decline" ></form>';
                  if(isset($_POST['accept'])){
                    ob_end_clean();
                    $updateAccept = "UPDATE Booking_Offers SET Accepted=1 WHERE BookingOfferID=" . $id;
                    if ($conn->query($updateAccept) === TRUE) {
                        $sql2 = "INSERT IGNORE INTO Band (BandName, BandGenre, Manager) VALUES ('$bandName', '$genre', '$email')";
                        if ($conn->query($sql2) === TRUE) {
                          $sql3 = "SELECT * FROM Band WHERE bandName = '$band'";
                          $result2 = mysqli_query($conn, $sql3);
                          $offer_result2 = $result2->fetch_all();
                          $BandID = $offer_result2[0][0];
                          $sql4 = "INSERT IGNORE INTO Concert (ConcertTimeStart, ConcertTimeEnd, SceneID, BandID, FestivalID, TicketPrice)
                          VALUES ('$concertStart', '$concertEnd', $scene, $BandID, $festival, $price)";
                          if ($conn->query($sql4) === TRUE) {

                              $concertID2 = mysqli_insert_id($conn);

                              //Assign random tech to a concert
                              $sqlTech = "SELECT * FROM Users WHERE UserRole = 'tech'";
                              $resultTech = mysqli_query($conn, $sqlTech);
                              $techArray = mysqli_fetch_all($resultTech);
                              $randTechUserID =  $techArray[array_rand($techArray)][0];
                              $sqlInsertTech = "INSERT INTO Concerts_UserTechnicians (ConcertID, UserID) VALUES('$concertID2', '$randTechUserID')";
                              mysqli_query($conn, $sqlInsertTech);

                              echo 'The Offer has been accepted, click below to add technical demands<br>';
                              echo "Your concert ID: <br><br>" . $concertID2;
                              echo '<form action="add-demands.php"> <input type="submit" value="Add demands"></form>';
                          }
                          else {
                              echo "Error: " . $sql4 . "<br>" . $conn->error;
                          }
                        } else {
                            echo "Error: " . $sql2 . "<br>" . $conn->error;
                        }
                    } else {
                        echo "Error updating record: " . $conn->error;
                    }
                  }
                  if(isset($_POST['decline'])){
                    $delete = "DELETE FROM Booking_Offers WHERE BandName= '$band'";
                    if ($conn->query($delete) === TRUE) {
                        echo "Offer declined";
                        header("Refresh:3");
                    } else {
                        echo "Error deleting offer: " . $conn->error;
                    }
                  }
                } else {
                  $bands = "SELECT * FROM Band WHERE bandName = '$band'";
                  $result2 = mysqli_query($conn, $bands);
                  $offer_result2 = $result2->fetch_all();
                  $BandID = $offer_result2[0][0];
                  $concert = "SELECT * FROM Concert WHERE BandID = " . $BandID;
                  $result3 = mysqli_query($conn, $concert);
                  $concertResult = $result3->fetch_all();
                  $_SESSION['concertID'] = $concertResult[0][0];
                  $concertID2 = $concertResult[0][0];
                  echo '<p>You have already accepted the offer, click below to add technical demands.<br><br></p>';
                  echo "Your concert ID: " . $concertID2;
                  echo '<form action="add-demands.php"> <input type="submit" value="Add demands"></form>';
                }
              } else {
                echo "Your band " . $band . "does not have an offer";
              }
              ?>

            </div>


        </div>

    </table>

    </div>
</body>
</html>

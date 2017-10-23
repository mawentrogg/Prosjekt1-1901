<!-- For brukerhistorie 18, Bookingansvarlig skal legge til omtale av band-->

<?php
session_start();

//Checking if user is logged in
if(!(isset($_SESSION['u_id']))){
    header("Location: index.php");
    exit();
}
else{
    if(!($_SESSION['u_role'] == "bookingans")){
        header("Location: " . $_SESSION['u_role'] . ".php");
    }
}

include 'includes/dbh.inc.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Band-Review</title>
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
        <form action="includes/logout.inc.php" method="post">
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

        <p class="insideMenuHeader">Add Band-Review</p>
        <div class="flexWrapperInside">
            <form onsubmit = "return confirm('Are you sure you want to send the review?')" action = "includes/insert-band-review.inc.php" method="POST">
                <select name="BandID">
                    <?php
                    $sql = "SELECT * FROM Band";
                    $result = mysqli_query($conn, $sql);
                    while($row = mysqli_fetch_assoc($result)){
                        echo "<option value = " . $row['BandID'] . "> BandID: " . $row['BandID'] . " - " . $row['BandName'] . "</option>";
                    }
                    ?>
                </select>
                <br>
                <textarea name="BandReview" style = "width: 300px; height: 100px"
                          placeholder="Write a short review (max 140 characters)..." maxlength="140"
                          oninvalid="this.setCustomValidity('Please write a review')" oninput="setCustomValidity('')" required></textarea>
                <input type="submit" name="submit" value = "Add review of band">
            </form>

            <?php
            if(isset($_SESSION['popup']) && $_SESSION['popup']){
                $_SESSION['popup'] = False;
                $popupMessage = $_SESSION['message'];
                echo "<script type='text/javascript'> window.alert('$popupMessage')</script>";
            }
            ?>

        </div>
    </div>
</div>
</body>
</html>
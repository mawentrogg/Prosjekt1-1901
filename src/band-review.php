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
    <title>Legg til omtale</title>
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
        <div style="width:50%; height: auto;" class="flexWrapper">
        <p class="insideMenuHeader">Legg til omtale</p>
        <div class="flexWrapperInside" style="overflow-y: hidden;">
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
                <textarea name="BandReview""     
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
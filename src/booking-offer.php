<?php
session_start();

//Checking if user is logged in
if(!(isset($_SESSION['u_id']))){
    header("Location: index.html");
    exit();
}
else{
    if(!($_SESSION['u_role'] == "bookingans")){
        header("Location: " . $_SESSION['u_role'] . ".php");
    }
}
include_once 'includes/dbh.inc.php';
$username = $_SESSION['u_username'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Send booking offer</title>
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
            <p class="insideMenuHeader">Send booking offer</p>
            <div class="flexWrapperInside" style="background-color: #353535">



                <form action="send-booking-mail.php" method="post">
                    Band name:<br>
                    <input type="text" name="bandName"><br>
                    Date:<br>
                    <input type="date" name="date"><br>
                    Time:<br>
                    <input type="time" name="time"><br>
                    Length of set in minutes:
                    <input type="number" name="length"><br>
                    Stage/scene:<br>
                    <input type="number" name="scene"><br>
                    Price:<br>
                    <input type="number" name="price"><br>
                    Contact e-mail:<br>
                    <input type="email" name=email><br><br>
                    <input type="submit" value="Submit">
                </form>

                <?php
                    if (isset($_SESSION['sent']) && $_SESSION['sent']) {
                      echo "<p>An offer to " . $_SESSION['band'] . " has been sent to " . $_SESSION['mail'] . "</p>";
                      session_destroy();
                    }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
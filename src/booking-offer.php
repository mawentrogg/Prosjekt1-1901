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


$sql = "SELECT * FROM Festival";
$result = mysqli_query($conn, $sql);
$festival = $result->fetch_all();

$festivals = "";
$length = sizeof($festival);

for ($i = 0; $i < $length; $i++) {
        $festivals .= "<option>" . $festival[$i][1] . "</option>";
    }

$sql2 = "SELECT * FROM Scene";
$result2 = mysqli_query($conn, $sql2);
$scene = $result2->fetch_all();


$scenes = "";
$length = sizeof($scene);

for ($i = 0; $i < $length; $i++) {
        $scenes .= "<option>" . $scene[$i][1] . "</option>";
    }


?>

<!DOCTYPE html>
<html>
<head>
    <title>Send booking offer</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body style="background-color: #3C6E71">
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
    <div style="margin:0; height:100%" class="flexBody">
        <div style="width:80vh;height:100%;" class="flexWrapper">
            <p class="insideMenuHeader">Send booking offer</p>
            <div class="flexWrapperInside" style="background-color:#353535; overflow-y: hidden;">
                <form action="insert-offer.php" method="post">
                    Festival:
                    <select name="festival">
                        <?php
                        echo $festivals; 
                        ?>
                    </select><br>
                    Band name:<br>
                    <input type="text" name="bandName"><br>
                    Genre:<br>
                    <input type="text" name="genre"><br>
                    Date:<br>
                    <input type="date" name="date"><br>
                    Time:<br>
                    <input type="time" name="time"><br>
                    Length of set in minutes:
                    <input min="0" type="number" name="length"><br>
                    Scene:
                    <select name="scene">
                        <?php
                        echo $scenes; 
                        ?>
                    </select><br>
                    Price:<br>
                    <input min="0" type="number" name="price"><br>
                    Contact e-mail:<br>
                    <input type="email" name=email><br><br>
                    <input type="submit" value="Submit">
                </form>

                <?php
                    if (isset($_SESSION['sent']) && $_SESSION['sent']) {
                      echo "An offer to " . $_SESSION['band'] . " has been sent. <br> An email will be sent to " . $_SESSION['mail'] . " after bookingsjef has reviewed the offer";
                      session_destroy();
                    }
                ?>
            </div>
        </div>
    </table>
    </div>
</body>
</html>
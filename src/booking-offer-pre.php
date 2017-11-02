
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
include_once 'includes/dbh.inc.php';
$username = $_SESSION['u_username'];


$sql = "SELECT * FROM Festival";
$result = mysqli_query($conn, $sql);
$festival = $result->fetch_all();

$festivals = "";
$length = sizeof($festival);

for ($i = 0; $i < $length; $i++) {
        $festivals .= "<option>" . $festival[$i][1] . "   (" . date('d/m/Y', strtotime($festival[$i][2])) . " - " . date('d/m/Y', strtotime($festival[$i][3])) .  ")</option>";
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
                        echo "index.php";
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
                <form action="booking-offer.php" method="post">
                    Festival:
                    <select name="festival" required>
                        <?php
                        echo $festivals; 
                        ?>
                    </select><br>
                    
                    <input type="submit" value="Submit">
                </form>
            </div>
        </div>
    </table>
    </div>

    <?php
    if (isset($_SESSION['sent']) && $_SESSION['sent']) {
        $_SESSION['sent'] = False;
        $_SESSION['failed'] = False;
        $band = $_SESSION['band'];
        $mail = $_SESSION['mail'];
        $popupMessage = $_SESSION['message'];

        echo "<script type='text/javascript'> window.alert('$popupMessage')</script>";
        exit();
    }
    elseif (isset($_SESSION['failed']) && $_SESSION['failed']){
        $_SESSION['sent'] = False;
        $_SESSION['failed'] = False;
        $popupMessage = $_SESSION['message'];
        echo "<script type='text/javascript'> window.alert('$popupMessage')</script>";
        exit();
    }
    ?>
    
</body>
</html>
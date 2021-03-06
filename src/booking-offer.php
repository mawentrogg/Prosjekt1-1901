
<?php
session_start();

$festivalTitle = $_POST['festival'];
$festivalpost = substr($_POST['festival'],0,strrpos($_POST['festival'],'('));


//Checking if user is logged in
if(!(isset($_SESSION['u_id']))){
    header("Location: index.php");
    exit();
}
else{
    if(!($_SESSION['u_role'] == "bookingans" or $_SESSION['u_role'] == "bookingsjef")){
        header("Location: " . $_SESSION['u_role'] . ".php");
    }
}
include_once 'includes/dbh.inc.php';
$username = $_SESSION['u_username'];


$sql = "SELECT * FROM Festival WHERE FestivalName = '$festivalpost'";
$result = mysqli_query($conn, $sql);
$festival = $result->fetch_all();

$festivals = "";
$length = sizeof($festival);

$festivalId = $festival[0][0];

for ($i = 0; $i < $length; $i++) {
        $festivals .= "<option>" . $festival[$i][1] . "   (" . date('d/m/Y', strtotime($festival[$i][2])) . " - " . date('d/m/Y', strtotime($festival[$i][3])) .  ")</option>";
    }

$sql2 = "SELECT * FROM Scene WHERE FestivalID = $festivalId";
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
            <p class="insideMenuHeader">Send booking offer, <?php echo $festivalTitle; ?> </p>
            <div class="flexWrapperInside" style="background-color:#353535; overflow-y: hidden;">
                <form action="includes\insert-offer.inc.php" method="post">
                        <?php
                        echo "<input type=\"hidden\" value=\"" . $festivalpost .  "\"name=\"festival\" />"

                        ?>
                    Band name:<br>
                    <input type="text" name="bandName" required><br>
                    Genre:<br>
                    <input type="text" name="genre" required><br>
                    Date:<br>
                    <input type="date" name="date" required><br>
                    Time:<br>
                    <input type="time" name="time" required><br>
                    Length of set in minutes:
                    <input min="1" type="number" name="length" required><br>
                    Scene:
                    <select name="scene" required>
                        <?php
                        echo $scenes; 
                        ?>
                    </select><br>
                    Price:<br>
                    <input min="0" type="number" name="price" required><br>
                    Contact e-mail:<br>
                    <input type="email" name=email required><br><br>
                    <input type="submit" value="Submit">
                </form>
            </div>
        </div>
    </table>
    </div>
</body>
</html>
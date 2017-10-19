<?php
session_start();
include_once 'includes/dbh.inc.php';

//Checking if user is logged in. If not sending back to proper site
if(!(isset($_SESSION['u_id']))){
    header("Location: index.html");
}
else{
    if(!($_SESSION['u_role'] == "admin")){
        header("Location: " . $_SESSION['u_role'] . ".php");
    }
}
 
$sqlFestival = "SELECT * FROM Festival";
$resultFestival = mysqli_query($conn, $sqlFestival);
$festivals = "";

if(mysqli_num_rows($resultFestival) > 0){
    while ($row = mysqli_fetch_assoc($resultFestival)) {
        $festivals .= "<option>" . $row["FestivalName"] . "</option>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Legg til scene</title>
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
	<div class="flexBody">
		<div style="width:50%; height: auto;" class="flexWrapper">
            <p class="insideMenuHeader">Legg til scene</p>
            <div style="background-color:#353535; overflow-y: hidden" class="flexWrapperInside">
                <form action="admin-insert-scene.php" method="post">
                    <label>Scenenavn:</label>
                    <input type="text" name="sceneName" required>
                    <label>Festival-start:</label>
                    <select name="sceneFestival">
                        <?php  
                        echo $festivals;
                        ?>
                    </select>
                    <label>Kapasitet:</label>
                    <input type="number" name="capacity" required>
                    <input type="submit" value="Submit">
                </form>

                <?php
                    if ($_SESSION['taskDoneScene']){
                        if ($_SESSION['sceneAlreadyAdded'] == true) {
                        echo "<p>Scenen " . $_SESSION['sceneName'] . " finnes allerede i databasen</p>";
                    }
                        else{
                            echo "<p>Scenen " . $_SESSION['sceneName'] . " ble lagt til i databasen</p>";
                        }
                        $_SESSION['taskDoneScene'] = false;
                    }
                    
                ?>
            </div>	
        </div>
    </div>
</body>
</html>
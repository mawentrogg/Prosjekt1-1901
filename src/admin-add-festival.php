<?php
session_start();
include_once 'includes\dbh.inc.php';

//Checking if user is logged in. If not sending back to proper site
if(!(isset($_SESSION['u_id']))){
    header("Location: index.html");
}
else{
    if(!($_SESSION['u_role'] == "admin")){
        header("Location: " . $_SESSION['u_role'] . ".php");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Legg til festival</title>
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
            <p class="insideMenuHeader">Legg til festival</p>
            <div style="background-color:#353535; overflow-y: hidden" class="flexWrapperInside">
                <form action="admin-insert-festival.php" method="post">
                    <label>Festivalnavn:</label>
                    <input type="text" name="festivalName" required>
                    <label>Festival-start:</label>
                    <input type="date" name="festivalDateStart" required>
                    <label>Festival-slutt:</label>
                    <input type="date" name="festivalDateEnd" required>
                    <input type="submit" value="Submit">
                </form>

                <?php
                    if ($_SESSION['taskDone']){
                        if ($_SESSION['festivalAlreadyAdded'] == true) {
                        echo "<p>Festivalen " . $_SESSION['festivalName'] . " finnes allerede i databasen</p>";
                    }
                        else{
                            echo "<p>Festivalen " . $_SESSION['festivalName'] . " ble lagt til i databasen</p>";
                        }
                    }
                    
                ?>
            </div>	
        </div>
    </div>
</body>
</html>
<!-- For brukerhistorie 11, Bookingsjef skal se en rapport over tidligere konserter-->

<?php
session_start();

//Checking if user is logged in
if(!(isset($_SESSION['u_id']))){
    header("Location: index.php");
    exit();
}
else{
    if(!($_SESSION['u_role'] == "bookingsjef")){
        header("Location: " . $_SESSION['u_role'] . ".php");
    }
}

include 'includes/dbh.inc.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Konsertrapport</title>
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
        <form action="includes\logout.inc.php" method="post">
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

        <p class="insideMenuHeader">Konsertrapport</p>
        <div class="flexWrapperInside">
            <form action = "concert-report-scene.php" method="POST">
                <label>Scene: </label>
                <select name="sceneID">
                    <?php
                    $sql = "SELECT * FROM Scene";
                    $result = mysqli_query($conn, $sql);
                    while($row = mysqli_fetch_assoc($result)){
                        echo "<option value = " . $row['SceneID'] . "> Scene: ID " . $row['SceneID'] . " - " . $row['SceneName'] . "</option>";
                    }
                    ?>
                </select>
                <input type="submit" name="submit" value = "Choose scene">
            </form>


        </div>
    </div>
</div>
</body>
</html>
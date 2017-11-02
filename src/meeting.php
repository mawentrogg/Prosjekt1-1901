<!-- For brukerhistorie 18, Bookingansvarlig skal legge til omtale av band-->

<?php
session_start();

//Checking if user is logged in
if(!(isset($_SESSION['u_id']))){
    header("Location: index.php");
    exit();
}

else{
    if(!($_SESSION['u_role'] == "tech")){
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

        <p class="insideMenuHeader">Registrer oppmøte</p>
        <div class="flexWrapperInside">
            <form onsubmit = "window.alert('Oppmøte registrert!')" action = "includes/meeting-register.inc.php" method="POST">
                <select name="ConcertID">
                    <?php
                    $sql = "SELECT * FROM Concerts_UserTechnicians";
                    $result = mysqli_query($conn, $sql);
                    while($row = mysqli_fetch_assoc($result)){

                        if($row['UserID'] == $_SESSION['u_id']){
                            $concertID = $row['ConcertID'];

                            //Getting info from concertTable
                            $sqlConcert = "SELECT * FROM Concert WHERE ConcertID = '$concertID'";
                            $resultConcert = mysqli_query($conn, $sqlConcert);
                            $rowConcert = mysqli_fetch_assoc($resultConcert);

                            //ConcertVariables
                            $concertID = $row['ConcertID'];
                            $concertTime = date('d-m-y H:i',strtotime($rowConcert['ConcertTimeStart']));
                            $sceneID = $rowConcert['SceneID'];

                            //Scenename
                            $sqlScene = "SELECT * FROM Scene WHERE SceneID = '$sceneID'";
                            $resultScene = mysqli_query($conn, $sqlScene);
                            $rowScene = mysqli_fetch_assoc($resultScene);
                            $sceneName = $rowScene['SceneName'];


                            echo "<option value = " . $rowConcert['ConcertID'] . ">Concert: ". $concertTime . " - ". $sceneName . "</option>";
                        }
                    }
                    ?>
                </select>
                <br>
                <input type="date" name="date" required>
                <input type="time" name="time" required>
                <input type="submit" name="submit" value = "Registrer oppmøte">
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
<!-- For brukerhistorie 5, Manager skal legge til behov-->

<?php
session_start();

//Checking if user is logged in
if(!(isset($_SESSION['u_id']))){
    header("Location: index.html");
    exit();
}
else{
    if(!($_SESSION['u_role'] == "manager")){
        header("Location: " . $_SESSION['u_role'] . ".php");
    }
}
include_once 'includes/dbh.inc.php';
$username = $_SESSION['u_username'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Band Demands</title>
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
        <div style="width:auto" class="flexWrapper">
            <form action="includes/insert-demands.inc.php" method="POST">
                <p class="indexHeader">Add technical demands for a concert</p>
                <label>Choose a Concert: </label>

                    <?php

                    echo "Håvard sender concertID hit!<br>"

                    /*

                    $sql = "SELECT * FROM Concert";
                    $result = mysqli_query($conn, $sql);


                    echo "<select name = 'ConcertID'>";
                    while($row = mysqli_fetch_assoc($result)){
                        echo "<option value = " . $row['ConcertID'] . "> ConcertID: " . $row['ConcertID'] . "</option>";
                    }
                    echo "</select><br>";
                    */
                    ?>
                <label>Demands: </label>
                <?php
                    $sql = "SELECT * FROM Technical_Demands";
                    $result = mysqli_query($conn, $sql);
                    if(mysqli_num_rows($result) > 0){
                        while($row = mysqli_fetch_assoc($result)){
                            echo "<br><input type = 'checkbox' name = 'ConcertDemands[]' value = " . $row['DemandText'] . ">" . $row['DemandText'] . "</input>";
                        }
                    }

                ?>
                <br><br><a class="hjemButton" href="<?php
                if(isset($_SESSION['u_id'])){
                    echo $_SESSION['u_role'] . ".php";
                }
                else{
                    echo "index.html";
                }
                ?>">Hjem</a><br><br>
                <input type="submit" name = 'submit' value="Add demands"/>
            </form>
            <form action="view-demands-manager.php" method="POST">
                <input type="submit" name = 'submit' value="View existing demands"/>
            </form>
        </div>
    </div>
</body>
</html>
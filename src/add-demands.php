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
    <div class="flexBody">
        <div style="width:auto" class="flexWrapper">
            <form action="includes/insert-demands.inc.php" method="POST">
                <p class="indexHeader">Add demands for band/artist</p>
                <label>Name of band/artist: </label>

                    <?php
                    $sql = "SELECT * FROM Band WHERE Manager = '$username'";
                    $result = mysqli_query($conn, $sql);
                    if(mysqli_num_rows($result) > 0){
                        echo "<select name = 'BandName'>";
                        while($row = mysqli_fetch_assoc($result)){
                            echo "<option value = " . $row['BandName'] . ">" . $row['BandName'] . "</option>";
                        }
                        echo "</select><br>";
                    }
                    else{
                        echo $username . " is not manager of a band <br>";
                    }
                    ?>
                <label>Demands: </label><br>
                <textarea name="BandDemands" rows="10" cols="80"></textarea>
                <a class="hjemButton" href="<?php
                if(isset($_SESSION['u_id'])){
                    echo $_SESSION['u_role'] . ".php";
                }
                else{
                    echo "index.html";
                }
                ?>">Hjem</a>
                <input type="submit" name = 'submit' value="Add demands"/>
            </form>
            <form action="view-demands-manager.php" method="POST">
                <input type="submit" name = 'submit' value="View existing demands"/>
            </form>
        </div>
    </div>

</div>

</body>
</html>
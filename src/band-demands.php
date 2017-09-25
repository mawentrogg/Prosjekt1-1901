<?php
session_start();

//Checking if user is logged in
if(!(isset($_SESSION['u_id']))){
    header("Location: index.html");
    exit();
}

include 'includes/dbh.inc.php';
$sql = "SELECT * FROM Band";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Band Demands</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body style="background-color: #3C6E71">
<div class="flexBody">
    <a href="<?php
    if(isset($_SESSION['u_id'])){
        echo $_SESSION['u_role'] . ".php";
    }
    else{
        echo "index.html";
    }
    ?>">Hjem</a>

    <table>
        <tr>
            <th>Band</th>
            <th>Demands</th>
        </tr>
        <?php

        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while($row = mysqli_fetch_assoc($result)) {
                echo "<tr> <th>" . $row['BandName'] . "</th> <th>" . $row['BandDemands'] . "</th>" . "</tr>";
            }
        }
        ?>




    </table>

</div>

</body>
</html>
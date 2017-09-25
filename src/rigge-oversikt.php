<?php
    session_start();
    include_once 'includes\dbh.inc.php';

    //Checking if user is logged in
    if(!(isset($_SESSION['u_id']))){
        header("Location: index.html");
    }
?>

<!DOCTYPE html>
<html>
<head>
	<title>Riggeoversikt</title>
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
			<th>Dato/tid</th>
			<th>Scene</th>
            <th>Artist</th>
            <th>Lyd</th>
            <th>Lys</th>
            <th>Krav</th>
		</tr>
		<tr>
			<th>I morgen</th>
			<th>Moroscenen</th>
            <td>Teletubbies</td>
            <td>Roy Gunnar</td>
            <td>Vigdis Sløyfrid</td>
            <td>4 mikrofoner, 8 flasker cava, 3 horer</td>

		</tr>
        <tr>
            <th>18/10</th>
            <th>Moroscenen</th>
            <th>Fantorangen</th>
            <th>Glen Kenneth</th>
            <th>Mygghild</th>
            <th></th>
        </tr>


	</table>

	</div>

</body>
</html>
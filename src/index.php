<?php
    session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body style="background-color: #3C6E71">
<div class="indexBody">
	<div class="indexWrapper">
		<form action="includes/login.inc.php" method="post">
		<p class="indexHeader">Festiv4len//Admin</p>
			<label>Username: </label>
			<input type="text" name="username" required>
			<label>Password: </label>
			<input type="password" name="password" required>
			<input type="submit" name = 'submit' value="Login"/>
		</form>

        <?php
            if(isset($_SESSION['failed']) && $_SESSION['failed']){
                $popupMessage = $_SESSION['message'];
                echo "<script type='text/javascript'> window.alert('$popupMessage')</script>";
            }
        ?>

	</div>
</div>
</body>
</html>
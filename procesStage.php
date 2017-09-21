<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<?php 
	$mysqli = mysqli_connect("mysql.stud.ntnu.no", "kimera_gruppe4", "festiv4l", "kimera_gruppe4");
	echo "success";
	$sql_query = "SELECT * FROM users WHERE user_username=?;";
	$prepared_statement = $mysqli->prepare($sql_query);
	$userName = $_POST["username"];
	$passWord = $_POST["password"];
	$prepared_statement->bind_param("s", $userName);
	$prepared_statement->execute();
	$result = $prepared_statement->get_result();
	$array_result = $result->fetch_all();
	print_r($array_result);
	
	if(empty($userName) && empty($passWord)){
		header("Location: index.html");
	}
	else{
		if($passWord == $array_result[0][3]){
			if($array_result[0][1] == "organizer"){
				header("Location: organizer.php");
			}
			if($array_result[0][1] == "tech"){
				header("Location: tech.php");
			}
			if($array_result[0][1] == "booking1"){
				header("Location: booking1.php");
			}
			if($array_result[0][1] == "booking2"){
				header("Location: booking2.php");
			}
			if($array_result[0][1] == "manager"){
				header("Location: manager.php");
			}
	}
		else{
			header("Location: index.html"); exit();
		}
	}	
 ?>
</body>
</html>
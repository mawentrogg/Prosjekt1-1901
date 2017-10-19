<?php
session_start();

//Checking if user is logged in. If not sending back to proper site
if(!(isset($_SESSION['u_id']))){
    header("Location: index.php");
}
else{
    if(!($_SESSION['u_role'] == "bookingsjef")){
        header("Location: " . $_SESSION['u_role'] . ".php");
    }
}

$dbServername = "mysql.stud.ntnu.no";
$dbUsername = "kimera_gruppe4";
$dbPassword = "festiv4l";
$dbName = "kimera_gruppe4";

$conn = mysqli_connect($dbServername, $dbUsername, $dbPassword, $dbName);



$now = date('Y-m-d', time());

$thisWeek = date('W', strtotime($now));

$formFestival = $_POST['festival'];
if( isset($_POST['week-number']) )	{
	$week = $_POST['week-number'];
}	else {
	$week = $thisWeek;
}









$day = date('d', strtotime($now));
$month = date('m', strtotime($now));
$year = date('Y', strtotime($now));


$first = date('Y-m-01', strtotime($now));
$dayOfWeek = date('D', strtotime($first));

$weekDates = [];


if ($week < 10) {
	$weekFormat = sprintf("%02d", $week);
}	else {
	$weekFormat = $week;
}

for($d=1; $d<=7; $d++)
{
    array_push($weekDates,date('d/m', strtotime($year."W".$weekFormat.$d)));
}



$sql = "SELECT * FROM Festival";
$result = mysqli_query($conn, $sql);
$festival = $result->fetch_all();

$festivals = "";
$length = sizeof($festival);

for ($i = 0; $i < $length; $i++) {
        $festivals .= "<option>" . $festival[$i][1] . "</option>";
    }

$weekNumbers = "";

for ($y = 1; $y <= 52; $y++) {
	if ($y == $week) {
		$weekNumbers .=  "<option selected>" . $y . "</option>";
	} else {
	$weekNumbers .=  "<option>" . $y . "</option>";
	}
}

?>


<!DOCTYPE html>
<html>
<hea<!DOCTYPE html>
<html>
<head>
	<title>Organizer</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body style="background-color: #3C6E71">

    <div class="flexBody">
        <div class="flexWrapper">
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

				<?php 
					echo "<h1> Festival: " . $formFestival . " " . $weekFormat .  "</h1>";
					?>
               
               
               <form action="kalender.php" method="post"> 
               <select name="festival" onchange="this.form.submit()">
               		<?php
               			echo $festivals;
               		?>
            	</select>
            	<select name="week-number" onchange="this.form.submit()">
               		<?php
               			echo $weekNumbers;
               		?>
            	</select>
            	

            	</form>               		

               <?php



               	

               	switch($dayOfWeek)
				{ 
					case "Sun": $blank = 0; break; 
					case "Mon": $blank = 1; break; 
					case "Tue": $blank = 2; break; 
					case "Wed": $blank = 3; break; 
					case "Thu": $blank = 4; break; 
					case "Fri": $blank = 5; break; 
					case "Sat": $blank = 6; break; 
				 }

				$daysInMonth = cal_days_in_month(0, $month, $year) ;




               
			    echo "<table>";
              	for ($i = 0; $i<13; $i++) {
               		if ($i == 0) {
               		echo "<table style=\"width:100%\">
					  <tr>
					    <th></th>
					    <th>Mandag " . $weekDates[0] . "</th> 
					    <th>Tirsdag " . $weekDates[0] . "</th> 
					    <th>Onsdag " . $weekDates[0] . "</th> 
					    <th>Torsdag " . $weekDates[0] . "</th> 
					    <th>Fredag " . $weekDates[0] . "</th> 
					    <th>Lørdag " . $weekDates[0] . "</th> 
					    <th>Søndag " . $weekDates[0] . "</th> 
					  </tr>";
               	}
               		else {
               			echo "<tr>";
               			for ($y = 0; $y < 8; $y++) {

               				$tid = ($i-1)*2;
               				$tid2 = $tid+2;

               				if ($y == 0) {
               					echo "<td>" . $tid . ":00-" . $tid2 . ".00</td>";
               				}
               				else {
               					echo "<td></td>";
               				}
               			}
               			echo "<tr>";
               		}
           		}
           		echo "</table>";

           		?>
               	
					  
					    
					   
				




        </div>
    </div>

</body>
</html>



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
$sql = "SELECT * FROM Festival";
$result = mysqli_query($conn, $sql);
$festival = $result->fetch_all();
$length = sizeof($festival);
$formFestival = "";
if ( isset($_POST['festival'])) {
	$formFestival = $_POST['festival'];
}
else {
	for ($i = 0; $i < $length; $i++) {
	if (strtotime($festival[$i][2]) < time() and strtotime($festival[$i][3]) > time()) {
		$formFestival = $festival[$i][1];
		break;
	}
}
	if($formFestival == "") {
		$formFestival = $festival[0][0];
	}	
}
if( isset($_POST['week-number']) )	{
	$week = $_POST['week-number'];
}	else {
	$week = $thisWeek;
}
$month = date('m', strtotime($now));
$year = date('Y', strtotime($now));
$first = date('Y-m-01', strtotime($now));
$dayOfWeek = date('D', strtotime($first));
$startOfWeekDate = date('Y-m-d', strtotime($year . "W" . $week));
$startOfWeek = strtotime($startOfWeekDate . " 00:00");
$zero = time() - (time() - $startOfWeek);
$endOfWeek = $startOfWeek + 7*24*60*60 + 3600;
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
$festivals = "";
for ($i = 0; $i < $length; $i++) {
	if ($festival[$i][1] == $formFestival) {
        $festivals .= "<option selected>" . $festival[$i][1] . "</option>";
	} else {
		$festivals .= "<option>" . $festival[$i][1] . "</option>";
    }
}
$weekNumbers = "";
for ($y = 1; $y <= 52; $y++) {
	if ($y == $week) {
		$weekNumbers .=  "<option selected>" . $y . "</option>";
	} else {
	$weekNumbers .=  "<option>" . $y . "</option>";
	}
}
$sqlfest = "SELECT * FROM Festival where FestivalName = '$formFestival'";
$result = mysqli_query($conn, $sqlfest);
$festivalfest = $result->fetch_all();
$festivalID = $festivalfest[0][0];
$sqlscene = "SELECT * FROM Scene where FestivalID = $festivalID";
$result = mysqli_query($conn, $sqlscene);
$scenelist = $result->fetch_all();
if( isset($_POST['scene']) )	{
	$scene = $_POST['scene'];
}	else {
	$scene = $scenelist[0][1];
}
$sqlsceneId = "SELECT * FROM Scene where SceneName = '$scene'";
$result = mysqli_query($conn, $sqlsceneId);
$sceneIdList = $result->fetch_all();
$sceneId = $sceneIdList[0][0];
$scenes = "";
for ($i = 0; $i < sizeof($scenelist) ; $i++) {
	if ($scenelist[$i][1] == $scene) {
		$scenes .=  "<option selected>" . $scenelist[$i][1] . "</option>";
	} else {
	$scenes .=  "<option>" . $scenelist[$i][1] . "</option>";
	}
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Kalender</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body style="background-color: #3C6E71">
<body style="background-color: #3C6E71">
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
    <div style="margin: 0;height: 100%" class="flexBody">
        <div style="width:100%; height: 80vh;" class="flexWrapper">
        <p class="insideMenuHeader"><?php 
					echo "<br>" . $formFestival . ", uke " . $weekFormat;
					?>
			<form class="kalenderform" action="kalender.php" method="post"> 
               <select name="festival" onchange="this.form.submit()" style="width:auto;">
               		<?php
               			echo $festivals;
               		?>
            	</select>
            	<select name="week-number" onchange="this.form.submit()" style="width:auto;">
               		<?php
               			echo $weekNumbers;
               		?>
            	</select>
            	<select name="scene" onchange="this.form.submit()" style="width:auto;">
               		<?php
               			echo $scenes;
               		?>
            	</select>
            	

            	</form>
			</p>
        <div class="flexWrapperInside">		

				
               
               
                              		

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
				$daysInMonth = cal_days_in_month(0, $month, $year);
				$sqlConcert = "SELECT * FROM Concert WHERE FestivalID = $festivalID AND SceneID = $sceneId ORDER BY ConcertTimeStart";
				$result = mysqli_query($conn, $sqlConcert);
				$concerts = $result->fetch_all();
 
 
				
				
				
				
               
			    echo "<table>";
              	for ($i = 0; $i<13; $i++) {
               		if ($i == 0) {
               		echo "<table id=\"cal\" style=\"width:100%\">
					  <tr>
					    <th></th>
					    <th>Mandag " . $weekDates[0] . "</th> 
					    <th>Tirsdag " . $weekDates[1] . "</th> 
					    <th>Onsdag " . $weekDates[2] . "</th> 
					    <th>Torsdag " . $weekDates[3] . "</th> 
					    <th>Fredag " . $weekDates[4] . "</th> 
					    <th>Lørdag " . $weekDates[5] . "</th> 
					    <th>Søndag " . $weekDates[6] . "</th> 
					  </tr>";
               	}
               		else {
               			echo "<tr>";
               			for ($y = 0; $y < 8; $y++) {
               				$tid = ($i-1)*2;
               				$tid2 = $tid+2;
               				if ($y == 0) {
               					echo "<th>" . $tid . ":00-" . $tid2 . ".00</th>";
               				}
               				else {
               					echo "<td></td>";
               				}
               			}
               			echo "</tr>";
               		}
           		}
           		echo "</table>";
           		$sqlBand = "SELECT * FROM Band";
				$resultBand = mysqli_query($conn, $sqlBand);
				$bands = $resultBand->fetch_all();
				echo "<p style=\"color: red\">";
				
           		
				function timeToPercent($id, $zero, $time1, $time2, $bandId, $bands) {
					$len = sizeof($bands);
					for ($i = 0; $i < $len; $i ++) {
						if ($bandId == $bands[$i][0]) {
							$band = $bands[$i][1];
						}
					}
					
					$unixTime1 = strtotime($time1);
					$unixTime2 = strtotime($time2);
					$percent1 = ($unixTime1 - $zero)/604800;
					$percent2 = ($unixTime2 - $zero)/604800;
					return array($id, $percent1, $percent2, $band);
					
				}
				$length = sizeof($concerts);
				
				$times = array();
				for ($i = 0; $i < $length; $i++) {
					
					if (strtotime($concerts[$i][1]) >= $zero and strtotime($concerts[$i][1]) < $zero + 604800) {
						$lol = timeToPercent($concerts[$i][0], $zero, $concerts[$i][1], $concerts[$i][2], $concerts[$i][4], $bands);
						array_push($times, $lol);
					}
				}
				$tableTimes = array();
				$columnTime = 0;
				for ($i = 0; $i < 84; $i++) {
					$columnPercent = $columnTime/(604800);
					$tableTime = array($i, $columnPercent);
					array_push($tableTimes, $tableTime);
					$columnTime += 7200;
				}
				echo "</p>";
           		?>
               	
					  
					    
					   
				



        </div>
    </div>
</div>

<script type="text/javascript">
var times = <?php echo json_encode($times); ?>;
var tableTimes = <?php echo json_encode($tableTimes); ?>;
console.log(times);
var tr = document.getElementById("cal").getElementsByTagName("td");
var calendar = []
var a = 0;
for (var y = 0; y<7; y++) {
	for (var i = 0; i < 84; i+=7) {
		calendar.push(tr[i+y]);
	}
}
var len = times.length;
for (var i = 0; i < len; i++) {
	table = times[i][1] * 84;
	table2 = times[i][2] * 84;
	band = times[i][3]
	colorCalendar(table, table2, i, band);
}
		
function colorCalendar(table1, table2, far, band) {
		var col = ["DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet", "DodgerBlue", "Orange", "Tomato", "MediumSeaGreen", "Gray", "SlateBlue", "Violet"]
		var start = Math.floor(table1);		
		var stop = Math.floor(table2);
		var percent1 = ((table1 - start)*100).toFixed(0) + "%";
		var percent2 = ((table2 - stop)*100).toFixed(0) + "%";
		var percent11 = ((table1 - start)*100+1).toFixed(0) + "%";
		var percent21 = ((table2 - stop)*100+1).toFixed(0) + "%";
		console.log(percent11);
		console.log("neste");
		for (var i = start; i <= stop; i++) {
			if (i == start) {
				if (percent1 == "0%" && stop >= start+1) {
					calendar[i].style.backgroundColor = col[far];
					calendar[i].innerHTML = band;
				}
				else {
				calendar[i].style.background = "linear-gradient(to top, " + col[far] + " " + percent1 +", #b2c2bf " + percent11 + ")";
					calendar[i+1].innerHTML = band;
				}
			}
			else if (i == stop) {
				if (percent2 == "0%") {
					;
				}
				else {
					calendar[i].style.background = "linear-gradient(to bottom, " + col[far] + " " + percent2 +", #b2c2bf " + percent21 + ")";
				}
				
			}
			else {
				calendar[i].style.backgroundColor = col[far];
		}
	}
}
</script>

</body>
</html>
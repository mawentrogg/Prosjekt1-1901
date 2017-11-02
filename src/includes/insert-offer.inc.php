<?php
session_start();

include_once 'dbh.inc.php';


$festival = mysqli_real_escape_string($conn, $_POST['festival']);
$bandName = mysqli_real_escape_string($conn, str_replace(' ', '_', $_POST['bandName']));
$genre = mysqli_real_escape_string($conn, $_POST['genre']);
$date = mysqli_real_escape_string($conn, $_POST['date']);
$time = mysqli_real_escape_string($conn, $_POST['time']);
$length = mysqli_real_escape_string($conn, $_POST['length']);
$scene = mysqli_real_escape_string($conn, $_POST['scene']);
$price = mysqli_real_escape_string($conn, $_POST['price']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$val = rand();


//Checking if any fields are empty. If so, returning to homepage
if(empty($festival) || empty($bandName) || empty($genre) || empty($date) || empty($time) || empty($length) || empty($scene) || empty($price) || empty($email)){
    $_SESSION['sent'] = False;
    $_SESSION['failed'] = True;
    $_SESSION['message'] = "Missing fields";
    header("Location: ..\booking-offer-pre.php?EmptyFields");
    exit();
}

//Checking if date is valid based on the date of the chosen festival
validDateFestival($date, $time, $length, $festival, $conn);

//Checking if date and time is valid based on concerts already booked
validDateConcert($date, $time, $length, $festival, $scene, $conn);

//Checking if date and time is valid based on offers already sent
validDateOffers($date, $time, $length, $festival, $scene, $conn);


$sql3 = "SELECT * FROM Scene WHERE SceneName = '$scene'";
$result = mysqli_query($conn, $sql3);
$scene_result = $result->fetch_all();

$scene = $scene_result[0][0];

$sql2 = "SELECT * FROM Festival WHERE FestivalName = '$festival'";
$result2 = mysqli_query($conn, $sql2);
$festival_result = $result2->fetch_all();

$festivalID = $festival_result[0][0];

$startTime = $date . ' ' . $time;

$endTimeTime = strtotime($startTime);
$endTime =  $endTimeTime+ $length*60;
$endTime = date('Y-m-d H:i:s', $endTime);

$sql = "INSERT INTO Booking_Offers (Validation, BandName, ConcertTimeStart, ConcertTimeEnd, SceneID, ContactEmail, Genre, Price, FestivalID, Sent, Accepted)

VALUES ($val, '$bandName','$startTime', '$endTime', $scene, '$email', '$genre', $price, 
	$festivalID, 0, 0)";

if ($conn->query($sql) === TRUE) {

	$_SESSION['sent'] = True;
    $_SESSION['failed'] = False;
	$_SESSION['mail'] = $email;
	$_SESSION['band'] = $bandName;
	$_SESSION['message'] = "An offer for " . str_replace('_', ' ', $bandName) . " to play at " . $festival ." on " . $date . " " . $time ." has been sent. An email will be sent to " . $email . " after bookingsjef has reviewed the offer";
    header("Location: ../" . $_SESSION['u_role'] . ".php");
    exit();

} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

function validDateFestival($date, $time, $length, $festival, $conn){

    //Calulating concertStartTime and concertEndTime in seconds
    $concertStartTime = strtotime($date.$time);
    $concertEndTime = $concertStartTime + ($length * 60);

    //Getting start and end time for festival
    $sql = "SELECT * FROM Festival WHERE FestivalName = '$festival'";
    $result = mysqli_query($conn, $sql);;
    $festivalArray = mysqli_fetch_assoc($result);
    $festivalStartTime = strtotime($festivalArray['TimeStart']);
    $festivalEndTime = strtotime($festivalArray['TimeEnd']);


    //Checking if concert start and end is between start and end of festival
    if(!($concertStartTime >= $festivalStartTime && $concertEndTime <= $festivalEndTime)){
        $_SESSION['sent'] = False;
        $_SESSION['failed'] = True;
        $_SESSION['message'] = "Chosen date of concert is outside range of the festival " . $festival;
        header("Location: ..\booking-offer-pre.php?Invalid_Date_and_Time");
        exit();

    }
}

function validDateConcert($date, $time, $length, $festival, $scene, $conn){
    //Getting SceneID from SceneName
    $sql = "SELECT * FROM Scene WHERE SceneName = '$scene'";
    $result = mysqli_query($conn, $sql);;
    $sceneArray = mysqli_fetch_assoc($result);
    $sceneID = $sceneArray['SceneID'];

    //Getting FestivalID from FestivalName
    $sql = "SELECT * FROM Festival WHERE FestivalName = '$festival'";
    $result = mysqli_query($conn, $sql);;
    $festivalArray = mysqli_fetch_assoc($result);
    $festivalID = $festivalArray['FestivalID'];

    //Calulating concertStartTime and concertEndTime in seconds
    $requestedStartTime = strtotime($date.$time);
    $requestedEndTime = $requestedStartTime + ($length * 60);

    //Checking all concerts to find duplicates
    $sql = "SELECT * FROM Concert";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)){
        //Getting start and end time for concert
        $startTime = strtotime($row['ConcertTimeStart']);
        $endTime = strtotime($row['ConcertTimeEnd']);

        //Checking if times overlap
        $overlapedTime = False;
        if($requestedStartTime >= $startTime && $requestedStartTime <= $endTime){
            $overlapedTime = True;
        }
        if($requestedEndTime >= $startTime && $requestedEndTime <= $endTime){
            $overlapedTime = True;
        }
        if($requestedStartTime <= $startTime && $requestedEndTime >= $endTime){
            $overlapedTime = True;
        }

        //If concerts overlaps => send error message
        if($overlapedTime && $sceneID == $row['SceneID'] && $festivalID == $row['FestivalID']){
            $_SESSION['sent'] = False;
            $_SESSION['failed'] = True;
            $_SESSION['message'] = "Chosen concert overlaps with already booked concert";
            header("Location: ..\booking-offer-pre.php?Invalid_Date_and_Time");
            exit();
        }
    }
}

function validDateOffers($date, $time, $length, $festival, $scene, $conn){
    //Getting SceneID from SceneName
    $sql = "SELECT * FROM Scene WHERE SceneName = '$scene'";
    $result = mysqli_query($conn, $sql);;
    $sceneArray = mysqli_fetch_assoc($result);
    $sceneID = $sceneArray['SceneID'];

    //Getting FestivalID from FestivalName
    $sql = "SELECT * FROM Festival WHERE FestivalName = '$festival'";
    $result = mysqli_query($conn, $sql);;
    $festivalArray = mysqli_fetch_assoc($result);
    $festivalID = $festivalArray['FestivalID'];

    //Calulating concertStartTime and concertEndTime in seconds
    $requestedStartTime = strtotime($date.$time);
    $requestedEndTime = $requestedStartTime + ($length * 60);

    //Checking all concerts in sent offers to find duplicates
    $sql = "SELECT * FROM Booking_Offers";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)){
        //Getting start and end time for concert
        $startTime = strtotime($row['ConcertTimeStart']);
        $endTime = strtotime($row['ConcertTimeEnd']);

        //Checking if times overlap
        $overlapedTime = False;
        if($requestedStartTime >= $startTime && $requestedStartTime <= $endTime){
            $overlapedTime = True;
        }
        if($requestedEndTime >= $startTime && $requestedEndTime <= $endTime){
            $overlapedTime = True;
        }
        if($requestedStartTime <= $startTime && $requestedEndTime >= $endTime){
            $overlapedTime = True;
        }

        //If concerts overlaps => send error message
        if($overlapedTime && $sceneID == $row['SceneID'] && $festivalID == $row['FestivalID']){
            $_SESSION['sent'] = False;
            $_SESSION['failed'] = True;
            $_SESSION['message'] = "Chosen concert overlaps with already sent offer.";
            header("Location: ..\booking-offer-pre.php?Invalid_Date_and_Time");
            exit();
        }
    }
}

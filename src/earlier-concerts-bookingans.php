
<head>
    <link rel="stylesheet" href="">
    <title>Oversikt over tidligere konserter</title>
</head>

<body>

<?php

session_start();
include_once 'includes/dbh.inc.php';

//Checking if user is logged in. If not sending back to proper site
if(!(isset($_SESSION['u_id']))){
    header("Location: index.php");
}
else{
    if(!($_SESSION['u_role'] == "organizer")){
        header("Location: " . $_SESSION['u_role'] . ".php");
    }
}

// Author: Matias

// Note: this code will bring errors if there are ' in a genre

function sql_query_array_result($mysqli, $query, $associative=TRUE){
    $mysqli_result = $mysqli->query($query);
    
    if (!$mysqli_result) {
        echo "<p>Error in query " . $mysqli->error . "</p>";
    }
    
    if ($associative) {
        $mysqli_array_result = $mysqli_result->fetch_all(MYSQLI_ASSOC);
    } else {
        $mysqli_array_result = $mysqli_result->fetch_all();
    }
    
    return $mysqli_array_result;
}

function sql_1param_array_result($mysqli, $query, $param, $types, $associative=TRUE){
    $prepared_statement = $mysqli->prepare($query);
    $prepared_statement->bind_param($types, $param);
    $prepared_statement->execute();
    $scene_name_result = $prepared_statement->get_result();
    
    if ($associative) {
        $scene_name_array_result = $scene_name_result->fetch_all(MYSQLI_ASSOC);
    } else {
        $scene_name_array_result = $scene_name_result->fetch_all();
    }
    
    return $scene_name_array_result;
}

function possible_genres ($mysqli) {
    
    // To get all the possible genre
    $possible_genres_query = "SHOW COLUMNS FROM Earlier_Concert LIKE 'genre';";
    
    // Possible genres becomes a string like this:
    // "enum('rock','pop','country','genre1','genre2','genre3')"
    $possible_genres_enum = sql_query_array_result($mysqli, $possible_genres_query)[0][Type];
    $length = strlen($possible_genres_enum);
    // This removes "enum(" at start and ")" at end, so now you just have
    //"'rock','pop','country','genre1','genre2','genre3'"
    $possible_genres_with_apostrophes = substr($possible_genres_enum, 5, $length-5-1);
    // This removes all "'"
    $possible_genres_with_commas = str_replace("'", "", $possible_genres_with_apostrophes);
    // Turns the string into an array, seperated by ","
    $possible_genres_array = explode(",", $possible_genres_with_commas);
    return $possible_genres_array;
}

if ($_SESSION["u_role"] == "organizer") {
    
    $mysqli = mysqli_connect("mysql.stud.ntnu.no", "kimera_gruppe4", "festiv4l", "kimera_gruppe4");
    if($mysqli->connect_error){
        echo "<p>failure to connect to database " . $conn->connect_error . "</p>";
    }
    
    if (isset($_GET["genre"])) {
        $genre = filter_input(INPUT_GET, "genre");
    }
    else {
        $genre = "alle";
    }
    
    $possible_genres_array = possible_genres($mysqli);
    
    if ($genre!="alle" && !in_array($genre, $possible_genres_array)) {
        print("\r\n<p>Error: genre finnes ikke</p>\r\n");
    }
    
    echo "<form action='earlier-concerts-bookingans.php' method='get' id='sjanger-form'>\r\n"
        . "<p id='sjanger-p'>Sjanger:\r\n"
        . "<select id='sjanger-select' name='genre' form='sjanger-form'>\r\n"
        . "<option class='sjanger-option' value='alle'>Alle</option>\r\n";
    
    foreach ($possible_genres_array as $current_genre) {
        $genre_capitalized = ucfirst($current_genre);
        if ($current_genre == $genre) {
            echo "<option class='sjanger-option' value='$current_genre' selected='selected'>$genre_capitalized</option>\r\n";
        }
        else {
            echo "<option class='sjanger-option' value='$current_genre'>$genre_capitalized</option>\r\n";
        }
    }
    
    echo "</select>\r\n"
        . "<input id='sjanger-submit' type='submit' value='Søk'>\r\n"
        . "</p>\r\n"
        . "</form>\r\n";
    
    echo "<table id='earlierConcerts'>\r\n"
       . "<tr>\r\n"
       . "<th>Band</th>"
             . "<th>Publikum</th>"
             . "<th>Scene</th>"
             . "<th>Festival</th>"
             . "<th>Sjanger</th>"
             . "<th>Dato</th>\r\n"
       . "</tr>\r\n";
    
    // concert_table is used to display concert data in a table
    $concert_table = array();
    
    // Fill in concert table with data from database
    if ($genre=="alle"){
        $query_all_concerts 
                = "SELECT * FROM Earlier_Concert ORDER BY Attendance DESC";
        $concert_result = sql_query_array_result($mysqli, $query_all_concerts);
        foreach ($concert_result as $row) {
            
            $band_name = sql_1param_array_result(
                    $mysqli, 
                    "SELECT BandName FROM Band WHERE BandID = ? LIMIT 1",
                    $row["BandID"],
                    "i")
                    [0]["BandName"];
            
            $attendance = $row["Attendance"];
            
            $scene_name = sql_1param_array_result(
                    $mysqli, 
                    "SELECT EarlierSceneName FROM Earlier_Scene WHERE EarlierSceneID = ? LIMIT 1",
                    $row["EarlierSceneID"],
                    "i")
                    [0]["EarlierSceneName"];
            
            $festival_name = sql_1param_array_result(
                    $mysqli, 
                    "SELECT EarlierFestivalName FROM Earlier_Festival WHERE EarlierFestivalID = ? LIMIT 1",
                    $row["EarlierFestivalID"],
                    "i")
                    [0]["EarlierFestivalName"];
            
            $genre_uncapitalized = $row["Genre"];
            $genre = ucfirst($genre_uncapitalized);
            
            $date_sql_style = $row["ConcertTimeStart"];
            $unix_timestamp = strtotime($date_sql_style);
            $date = date("d.m.y", $unix_timestamp);
            
            $concert_table_row = array(
                "band" => $band_name,
                "attendance" => $attendance,
                "scene_name" => $scene_name,
                "festival_name" => $festival_name,
                "genre" => $genre,
                "date" => $date
            );
            array_push($concert_table, $concert_table_row);
        }
    }
    else {
        $query_concerts_one_genre 
                = "SELECT * FROM Earlier_Concert WHERE Genre=? ORDER BY Attendance DESC";
        $concert_result = sql_1param_array_result($mysqli, $query_concerts_one_genre, $genre, "s");
        foreach ($concert_result as $row) {
            
            $band_name = sql_1param_array_result(
                    $mysqli, 
                    "SELECT BandName FROM Band WHERE BandID = ? LIMIT 1",
                    $row["BandID"],
                    "i")
                    [0]["BandName"];
            
            $attendance = $row["Attendance"];
            
            $scene_name = sql_1param_array_result(
                    $mysqli, 
                    "SELECT EarlierSceneName FROM Earlier_Scene WHERE EarlierSceneID = ? LIMIT 1",
                    $row["EarlierSceneID"],
                    "i")
                    [0]["EarlierSceneName"];
            
            $festival_name = sql_1param_array_result(
                    $mysqli, 
                    "SELECT EarlierFestivalName FROM Earlier_Festival WHERE EarlierFestivalID = ? LIMIT 1",
                    $row["EarlierFestivalID"],
                    "i")
                    [0]["EarlierFestivalName"];
            
            $genre_uncapitalized = $row["Genre"];
            $genre = ucfirst($genre_uncapitalized);
            
            $date_sql_style = $row["ConcertTimeStart"];
            $unix_timestamp = strtotime($date_sql_style);
            $date = date("d.m.y", $unix_timestamp);
            
            $concert_table_row = array(
                "band" => $band_name,
                "attendance" => $attendance,
                "scene_name" => $scene_name,
                "festival_name" => $festival_name,
                "genre" => $genre,
                "date" => $date
            );
            array_push($concert_table, $concert_table_row);
        }
    }
    
    foreach ($concert_table as $row) {
        echo "<tr>\r\n"
           . "<td>" . $row["band"] . "</td>"
             . "<td>" . $row["attendance"] . "</td>"
             . "<td>" . $row["scene_name"] . "</td>"
             . "<td>" . $row["festival_name"] . "</td>"
             . "<td>" . $row["genre"] . "</td>"
             . "<td>" . $row["date"] . "</td>\r\n"
           . "</tr>\r\n";
    }
    
    echo "<table>\r\n";
    
}
else {
    echo "<p>Du har ikke tilgang, du er " . $_SESSION["u_role"] . "</p>";
}

?>

</body>
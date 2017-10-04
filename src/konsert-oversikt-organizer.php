
<?php

// For brukerhistorie 2, Arrangør skal få oversikt over alle konserter på alle scener

if ($_SESSION["u_role"] == "organizer") {
    
    $mysqli = mysqli_connect("mysql.stud.ntnu.no", "kimera_gruppe4", "festiv4l", "kimera_gruppe4");
    
    if($mysqli->connect_error){
        echo("failure to connect to database " . $conn->connect_error);
    }
    
    $concert_result = $mysqli->query("SELECT * FROM Concert;");
    if (!$concert_result) {
        echo "\r\n[query failed " . $mysqli->error . "]\r\n";
    }
    $array_concert_result = $concert_result->fetch_all(MYSQLI_ASSOC);
    
    
    echo "<table>\r\n"
            . "<tr>\r\n"
            . "<th>Band</th>"
            . "<th>Scene</th>"
            . "<th>Concert start</th>"
            . "<th>Concert end</th>\r\n"
            . "</tr>\r\n";
    
    foreach ($array_concert_result as $row){
        
        $query_scene_name = "SELECT SceneName FROM Scene WHERE SceneID=? LIMIT 1;";
        $prepared_statement2 = $mysqli->prepare($query_scene_name);
        $scene_id = $row["SceneID"];
        $prepared_statement2->bind_param("i", $scene_id);
        $prepared_statement2->execute();
        $scene_name_result = $prepared_statement2->get_result();
        $scene_name_array_result = $scene_name_result->fetch_all();
        $scene_name = $scene_name_array_result[0][0];
        
        $query_band_name = "SELECT BandName FROM Band WHERE BandID=? LIMIT 1;";
        $prepared_statement3 = $mysqli->prepare($query_band_name);
        $band_id = $row["BandID"];
        $prepared_statement3->bind_param("i", $scene_id);
        $prepared_statement3->execute();
        $band_name_result = $prepared_statement3->get_result();
        $band_name_array_result = $band_name_result->fetch_all();
        $band_name = $band_name_array_result[0][0];
        
        echo "<tr>\r\n"
                . "<td>" . $band_name . "</td>"
                . "<td>" . $scene_name . "</td>"
                . "<td>" . $row["ConcertTimeStart"] . "</td>"
                . "<td>" . $row["ConcertTimeEnd"] . "</td>"
                . "</tr>\r\n";
    }
    
    echo "</table>\r\n";
    
}
else {
    echo "<p>Du har ikke lov til å se denne siden</p>";
}

?>

<?php
session_start();

include_once 'includes/dbh.inc.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Oversikt - PR</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
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

<div style="margin:0;height:100%;" class="flexBody">
    <div style="height:80vh;" class="flexWrapper">
        <p class="insideMenuHeader">Konsertoversikt//PR-Ansvarlig</p>
        <div class="flexWrapperInside">
            <table>
                <tr>
                    <th>Band/Artist</th>
                    <th>Dato</th>
                    <th>Salgstall</th>
                    <th>Kontaktperson</th>
                    <th>Telefon</th>
                    <th>Epost</th>
                    <th>Presseomtale</th>
                </tr>

                <?php

                //Looping through concerts
                $sql = "SELECT * FROM Concert";
                $result = mysqli_query($conn, $sql);
                while($row = mysqli_fetch_assoc($result)){
                    $concertID = $row['ConcertID'];
                    $concertTime = date('d-m-y', strtotime($row['ConcertTimeStart']));
                    $sceneID = $row['SceneID'];
                    $bandID = $row['BandID'];

                    //Getting band name
                    $sqlBand = "SELECT * FROM Band WHERE BandID = '$bandID'";
                    $resultBand = mysqli_query($conn, $sqlBand);
                    $rowBand = mysqli_fetch_assoc($resultBand);
                    $bandName = $rowBand['BandName'];

                    //Getting scene name
                    $sqlScene = "SELECT * FROM Scene WHERE SceneID = '$sceneID'";
                    $resultScene = mysqli_query($conn, $sqlScene);
                    $rowScene = mysqli_fetch_assoc($resultScene);
                    $sceneName = $rowScene['SceneName'];
                    $sceneCapacity = $rowScene['Capacity'];

                    //Få tak i salgstall
                    $sqlSalg = "SELECT * FROM Concert WHERE BandID = '$bandID'";
                    $resultSalg = mysqli_query($conn, $sqlSalg);
                    $rowSalg = mysqli_fetch_assoc($resultSalg);
                    $Solgt = $rowSalg['TicketsSold'];

                    //Få tak i link
                    $sqlLink = "SELECT * FROM Band WHERE BandID = '$bandID'";
                    $resultLink = mysqli_query($conn, $sqlLink);
                    $rowLink = mysqli_fetch_assoc($resultLink);
                    $Link = $rowLink['ReviewLink'];

                    //Getting contact info
                    $sqlInfo = "SELECT * FROM Concert_ContactInfo WHERE ConcertID = '$concertID'";
                    $resultInfo = mysqli_query($conn, $sqlInfo);
                    $rowInfo = mysqli_fetch_assoc($resultInfo);
                    $contactName = $rowInfo['ContactFirstName'] . " " . $rowInfo['ContactLastName'];
                    $contactPhone = $rowInfo['ContactPhone'];
                    $contactEmail = $rowInfo['ContactEmail'];

                    echo "<tr>
                        <td>" . $bandName . "</td>
                        <td>" . $concertTime ."</td>
                        <td>" . $Solgt . '/' . $sceneCapacity . "</td>
                        <td>" . $contactName ."</td>
                        <td>" . $contactPhone ."</td>
                        <td>" . $contactEmail . "</td>
                        <td><a target='_blank' href='$Link'>Link</a></td>
                    </tr>" ;

                }

                ?>
            </table>

        </div>

    </div>

</div>
</div>
</body>
</html>
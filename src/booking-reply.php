<!DOCTYPE html>
<html>
<head>
    <title>Reply to booking offer</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body style="background-color: #3C6E71">
    <div class="flexBody">
     <div class="flexTop">
        <a class="hjemButton" href="<?php
                    if(isset($_SESSION['u_id'])){
                        echo $_SESSION['u_role'] . ".php";
                    }
                    else{
                        echo "index.html";
                    }
                    ?>">Hjem</a>
        <p class="superHeader">Festiv4len</p>
        <form action="includes\logout.inc.php" method="post">
            <button type="submit" name="submit">Logg ut</button>
        </form> 
    </div>
        <div style="width:auto;height:70vh;" class="flexWrapper">
            <p class="insideMenuHeader">Your booking offer</p>
            <div class="flexWrapperInside" style="background-color: #353535">



                
                            





            </div>

            
        </div>

    </table>

    </div>
</body>
</html>
<?php
session_start();
include_once 'includes/dbh.inc.php';
?>



<!DOCTYPE html>
<html>
<head>
    <title>Lost url</title>
    <link rel="stylesheet" type="text/css" href="style.css">

</head>
<body style="background-color: #3C6E71">
    <div class="flexBody">
        <div style="width:auto;height:70vh;" class="flexWrapper">
            <p class="insideMenuHeader">New booking-url</p>
            <div class="flexWrapperInside" style="background-color: #353535">
              <form action="send-ny-mail.php" method="post">
                Enter your email address below, and we’ll send you a new link to review your offer. <br>
                  <input type="email" name="email"><br>
                  <input type="submit" value="Send">
              </form>

              <?php
                  if (isset($_SESSION['sent']) && $_SESSION['sent']) {
                    echo "A new url has been sent to " . $_SESSION['mail'] . "</p>";
                    session_destroy();
                  }

              ?>







            </div>


        </div>

    </table>

    </div>
</body>
</html>

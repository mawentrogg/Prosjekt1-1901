<?php
    include_once 'header.php';
    include_once 'includes\dbh.inc.php'
?>

        <section class = "main-container">
            <div class=""main-wrapper">
                <h2>Home</h2>

                <?php
                    if(isset($_SESSION['u_id'])){

                        echo nl2br("Welcome ".$_SESSION['u_first']." ".$_SESSION['u_last']."\n");
                        echo nl2br("Email: ".$_SESSION['u_email']."\n");
                        echo nl2br("Username: ".$_SESSION['u_uid']."\n");

                    }
                ?>
            </div>
        </section>

<?php
    include_once 'footer.php';
?>
<?php

session_start();

if(isset($_POST['submit'])){
    include 'dbh.inc.php';

    $uid = mysqli_real_escape_string($conn, $_POST['uid']);
    $pwd = mysqli_real_escape_string($conn, $_POST['pwd']);

    //Error handlers

    //Check if inputs are empty
    if(empty($uid) || empty($pwd)){
        header("Location: ../index.php?login=empty");
        exit();
    }
    else{
        $sql = "SELECT * FROM users WHERE user_uid='$uid'";
        $result = mysqli_query($conn, $sql);
        $resultCheck = mysqli_num_rows($result);
        if($resultCheck < 1){
            header("Location: ../index.php?login=error");
            exit();
        }
        else{
            if($row = mysqli_fetch_assoc($result)){
                //Verifying the password
                $hashedPwdCheck = password_verify($pwd, $row['user_password']);
                if($hashedPwdCheck == false){
                    header("Location: ../index.php?login=error");
                    exit();
                }
                elseif($hashedPwdCheck == true){
                    //Log in the user here!
                    $_SESSION['u_id'] = $row['user_id'];
                    $_SESSION['u_role'] = $row['user_role'];
                    $_SESSION['u_username'] = $row['user_username'];
                    $_SESSION['u_password'] = $row['user_password'];
                    header("Location: ../index.php?login=success");
                    exit();
                }
            }
        }
    }
}
else{
    header("Location: ../index.php?login=error");
    exit();
}
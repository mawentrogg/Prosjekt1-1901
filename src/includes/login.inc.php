<?php

session_start();

if(isset($_POST['submit'])){
    include 'dbh.inc.php';

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    //Error handlers

    //Check if inputs are empty
    if(empty($username) || empty($password)){
        header("Location: ../index.html?login=empty");
        exit();
    }
    else{
        $sql = "SELECT * FROM Users WHERE user_username='$username'";
        $result = mysqli_query($conn, $sql);
        $resultCheck = mysqli_num_rows($result);
        if($resultCheck < 1){
            header("Location: ../index.html?login=error");
            exit();
        }
        else{
            if($row = mysqli_fetch_assoc($result)){
                //Verifying the password
                if(!($password == $row['user_password'])){
                    header("Location: ../index.html?login=error");
                    exit();
                }
                else{
                    //Log in the user here!
                    $_SESSION['u_id'] = $row['user_id'];
                    $_SESSION['u_role'] = $row['user_role'];
                    $_SESSION['u_username'] = $row['user_username'];

                    header("Location: ../" . $_SESSION['u_role'] . ".php");
                    exit();
                }
            }
        }
    }
}
else{
    header("Location: ../index.html?login=error");
    exit();
}
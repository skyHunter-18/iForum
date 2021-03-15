<?php

    $showError = 'false';
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        include '_dbconnect.php';
        $user_email = $_POST['signUpEmail'];
        $user_password = $_POST['signUpPassword'];
        $user_c_password = $_POST['signUpCPassword'];
        
        //check whether this email exists in database
        $existsql = "SELECT * FROM `users` WHERE `user_email` = '$user_email'";
        $result = mysqli_query($connection, $existsql);
        $num_rows = mysqli_num_rows($result);
        if ($num_rows>0) {
            $showError = "Email already in use";
        }
        else {
            if ($user_password == $user_c_password) {
                $hash = password_hash($user_password, PASSWORD_DEFAULT);
                $sql = "INSERT INTO `users` (`user_email`, `user_password`, `user_tstamp`) VALUES ('$user_email', '$hash', current_timestamp())";
                $result = mysqli_query($connection, $sql);
                if ($result) {
                    $showAlert = true;
                    header("Location: /forums/index.php?signupsuccess=true");
                    exit();
                }

            }
            else {
                // $showError = "Passwords do not match";
                // header("Location: /forums/index.php?signupsuccess=false");
                // exit();
            }
        }
    }

?>
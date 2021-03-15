<?php

    $showError = 'false';
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        include '_dbconnect.php';
        $user_email = $_POST['loginEmail'];
        $user_pass= $_POST['loginPassword'];

        $sql = "SELECT * FROM `users` WHERE `user_email` = '$user_email'";
        $result = mysqli_query($connection, $sql);
        $numRows = mysqli_num_rows($result);
        if ($numRows == 1) {
            $row = mysqli_fetch_array($result);
            if (password_verify($user_pass, $row['user_password'])) {
                session_start();
                $_SESSION['loggedin'] = true;
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['useremail'] = $user_email;
                echo "Logged in " . $user_email;
            }
            header('Location:/forums/index.php');
        }
        header('Location:/forums/index.php');
    }
?>
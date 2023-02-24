<?php

if (isset($_POST['login-btn'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if ($email && $password){
        $login_sql = "SELECT u_id, first_name, password FROM users2 WHERE user_name = '$email' OR email = '$email'";
        $login_result = $conn->query($login_sql);
        if ($login_result->num_rows > 0){
            $row = $login_result->fetch_assoc();
            if (password_verify($password, $row['password'])){
                $_SESSION['first_name'] = $row['first_name'];
                $_SESSION['user_id'] = $row['u_id'];
                $_SESSION['asdjhvgjahfjierhvbdjfks-nina'] = session_id();
                $_SESSION['time'] = time();

                $update_sql = "UPDATE users2 SET date_last_login = NOW() WHERE u_id = " . $_SESSION['user_id'];
                if ($conn->query($update_sql)){
                    $message = "<p>You have logged in succesfully.</p>";
                    $email = $password = "";
                } else {
                    $message = "<p>There was a problem. $conn->error</p>";
                }
            } else {
                $message = "<p>Invalid username or password</p>";
            }
        } else {
            $message = "<p>Invalid username or password</p>";
        }
    } else {
        $message = "<p>Both fields are required.</p>";
    }
}

?>
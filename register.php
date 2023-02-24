<?php
$page_title = "Register";
$body_class = "register";
require("includes/connect.php");
include ("includes/header.php");

if (isset($_POST['register-btn'])) {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $user_name = trim($_POST['user_name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $pass_go = true;

    if ($user_name || $email) {
        $check_sql = "SELECT u_id FROM users2 WHERE email = '$email'";
        $check_res = $conn->query($check_sql);
        if ($check_res->num_rows > 0) {
            $message .= "<p>That email is already taken. Please choose another.</p>";
            $pass_go = FALSE;
        } else {
            $check_sql = "SELECT u_id FROM users2 WHERE user_name = '$user_name'";
            $check_res = $conn->query($check_sql);
            if ($check_res->num_rows > 0) {
                $message .= "<p>That username is already taken. Please choose another.</p>";
                $pass_go = FALSE;
            } else {
                $pass_go = TRUE;
            }
        }
    }

    if (!$first_name || !$last_name || !$user_name || !$email || !$password) {
        $message .= "<p>All fields are required. $conn->error</p>";
        $pass_go = FALSE;
    }

    if ($first_name) {
        $first_name = filter_var($first_name, FILTER_SANITIZE_STRING);
        if ($first_name == FALSE) { 
            $message .= "<p>Please enter a first name with no HTML in it.</p>";
            $pass_go = FALSE;
        } else {
            if ($last_name) {
                $last_name = filter_var($last_name, FILTER_SANITIZE_STRING);
                if ($last_name == FALSE) { 
                    $message .= "<p>Please enter a last name with no HTML in it.</p>";
                    $pass_go = FALSE;
                }
            }
        }
    }

    if ($user_name) {
        $user_name = filter_var($user_name, FILTER_SANITIZE_STRING);
        if ($user_name == FALSE) { 
            $message .= "<p>Please enter a user name with no HTML in it.</p>";
            $pass_go = FALSE;
        } else {
            if (strpos($user_name, " ") == TRUE) {
                $message .= "<p>Please enter a user name with no spaces in it.</p>";
                $pass_go = FALSE;
            }
        }
    }

    if ($email) {
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        if ($email == FALSE) {
            $message .= "<p>Email should not have HTML in them.</p>";
            $pass_go = FALSE;
        } else {
            $email = filter_var($email, FILTER_VALIDATE_EMAIL);
            if ($email == FALSE) {
                $message .= "<p>Email failed to validate.</p>";
                $pass_go = FALSE;
            } else {
                $pattern = "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^";
                if (preg_match($pattern, $email) == FALSE) {
                    $message .= "<p>Email does not fit pattern.</p>";
                    $pass_go = FALSE;
                }
            }
        }
    }

    if (strlen($password) >= 6) {
        if (preg_match('`[A-Z]`',$password)) {
            if (preg_match('`[a-z]`',$password)) {
                if (preg_match('`[0-9]`',$password)) {
                    $pass_go = TRUE;
                } else {
                    $message .= "<p>Password must contain a number.</p>";
                    $pass_go = FALSE;
                }
            } else {
                $message .= "<p>Password must contain a lower case letter.</p>";
                $pass_go = FALSE;
            }
        } else {
            $message .= "<p>Password must contain an upper case letter.</p>";
            $pass_go = FALSE;
        }
    } else {
        $message .= "<p>Password must be a minimum of 6 characters</p>";
        $pass_go = FALSE;
    }

    if ($pass_go == TRUE) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $insert_sql = "INSERT INTO users2 (first_name, last_name, user_name, email, password) VALUES ('$first_name', '$last_name', '$user_name', '$email', '$hash')";

        if ($conn->query($insert_sql)) {
            $message .= "<p>You are now registered!</p>";
            $first_name = $last_name = $user_name = $email = $password = "";
        } else {
            $message .= "<p>There was a problem. $conn->error</p>";
        }
    }


}

?>
<div class="register">
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="POST" class="register-form">
        <h2>Register an Account</h2>
        <?php if($message):?>
            <div class="message">
                <?php echo "<p>$message</p>";?>
            </div>
        <?php endif?>
        <div>
            <label for="first_name">First Name</label>
            <input type="text" id="first_name" name="first_name" value="<?php echo $first_name;?>">
        </div>
    
        <div>
            <label for="last_name">Last Name</label>
            <input type="text" id="last_name" name="last_name" value="<?php echo $last_name;?>">
        </div>
    
        <div>
            <label for="user_name">User Name</label>
            <input type="text" id="user_name" name="user_name" value="<?php echo $user_name;?>">
        </div>
    
        <div>
            <label for="email">Email</label>
            <input type="text" id="email" name="email" value="<?php echo $email;?>">
        </div>
    
        <div>
            <label for="password">Password</label>
            <input type="text" id="password" name="password" value="<?php echo $password;?>">
        </div>
    
        <div><input type="submit" name="register-btn" value="Register!"></div>
    </form>
</div>

<?php include ("includes/footer.php"); ?>
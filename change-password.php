<?php
$page_title = "Change Password";
$body_class = "changepass";
session_start();
require("includes/connect.php");
include ("includes/header.php");

if (isset($_POST['change-pass-btn'])) {
    $password = trim($_POST['password']);
    $newpassword = trim($_POST['newpassword']);
    if ($newpassword && $password){
        $changepass_sql = "SELECT u_id, password FROM users2 WHERE u_id = " .$_SESSION['user_id']."";
        $changepass_result = $conn->query($changepass_sql);
        if ($changepass_result->num_rows > 0){
            $row = $changepass_result->fetch_assoc();
            if (password_verify($password, $row['password'])){
                if (strlen($newpassword) >= 6) {
                    if (preg_match('`[A-Z]`',$newpassword)) {
                        if (preg_match('`[a-z]`',$newpassword)) {
                            if (preg_match('`[0-9]`',$newpassword)) {
                                $_SESSION['user_id'] = $row['u_id'];
                                $_SESSION['asdjhvgjahfjierhvbdjfks-nina'] = session_id();
                                $newhash = password_hash($newpassword, PASSWORD_DEFAULT);
                                $update_sql = "UPDATE users2 SET password = '$newhash' WHERE u_id = " . $_SESSION['user_id'];
                                if ($conn->query($update_sql)){
                                    $message = "<p>Your password has been changed successfully.</p>";
                                    $newpassword = $password = "";
                                } else {
                                    $message = "<p>There was a problem. $conn->error</p>";
                                }
                            } else {
                                $message = "<p>New password must contain a number.</p>";
                            }
                        } else {
                            $message = "<p>New password must contain a lower case letter.</p>";
                        }
                    } else {
                        $message = "<p>New password must contain an upper case letter.</p>";
                    }
                } else {
                    $message = "<p>Password must be a minimum of 6 characters</p>";
                }
            } else {
                $message = "<p>Invalid current password</p>";
            }
        } else {
            $message = "<p>Invalid current password</p>";
        }
    } else {
        $message = "<p>Both fields are required.</p>";
    }
}

?>
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="POST" class="register-form">
    <h2>Change Your Password</h2>
    <?php if ($message): ?>
        <div class="message"><?php echo $message;?></div>
    <?php endif ?>
    <div>
        <label for="password">Current Password</label>
        <input type="text" id="password" name="password" value="<?php echo $password;?>">
    </div>

    <div>
        <label for="newpassword">New Password</label>
        <input type="text" id="newpassword" name="newpassword" value="<?php echo $newpassword;?>">
    </div>

    <div><input type="submit" name="change-pass-btn" value="Change Password"></div>
</form>

<?php include ("includes/footer.php"); ?>
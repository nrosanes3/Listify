<?php

if (isset($_SESSION['time'])){
    $current_time = time();
    $expiry = 10 * 60;

    if ($current_time > $_SESSION['time'] + $expiry){
        session_destroy();
        header("location:index.php?m=loggedout");
    } else {
        $_SESSION['time'] = time();
    }
}
?>
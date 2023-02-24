<?php

if(isset($_GET['m'])) {
    $m = $_GET['m'];

    switch ($m) {
        case 'loggedout':
            $message = "<p>You have been automatically logged out due to inactivity.</p>";
            break;
        case 'logout': 
            $message = "<p>You logged out. Log back in to manage your ads.</p>";
            break;
        case 'notloggedin':
            $message = "<p>Sorry you were not logged in</p>";
            break;
        case 'admarkedfilled':
            $message = "<p>Congratulations on filling your job posting!</p>";
            break;
        case 'notrightuser':
            $message = "<p>That was not your ad.</p>";
            break;
        case 'admarkedavailable':
            $message = "<p>Your ad is now available!</p>";
            break;
        case 'admarkeddeleted':
            $message = "<p>Ad deleted.</p>";
            break;
        default:
            $message = $m;
            break;
    }
}
?>
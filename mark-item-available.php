<?php
session_start();
include("includes/connect.php");

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    if(!isset($_SESSION['asdjhvgjahfjierhvbdjfks-nina'])) {
        header("Location: index.php?m=notloggedin");
    } else {
        $sql = "SELECT u_id FROM for_sale WHERE ad_id = $id AND u_id = " . $_SESSION['user_id'];
        $res = $conn->query($sql);
        if ($res->num_rows > 0) {
            $update_sql = "UPDATE for_sale set item_sold_yn = 'N' WHERE ad_id = $id";
            if ($conn->query($update_sql)) {
                $ref = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH);
                header("Location: $ref?m=admarkedavailable");
            } else {
                $string = $conn->error;
                header("Location: index.php?m=$string");
            }
        } else {
            header("Location: index.php?m=notrightuser");
        }
    }
}

?>
<?php

$db_server = "localhost";
$db_username = "nrosanes3";
$db_password = "Lipgloss98!";
$database = "nrosanes3";

$conn = new mysqli($db_server, $db_username, $db_password, $database);

if ($conn->connect_error){
    die("Connection failed:" . $conn->connect_error);
}

foreach ($_POST as $key => $value) {
    $_POST[$key] = mysqli_real_escape_string($conn,$value);
}

foreach ($_GET as $key => $value) {
    $_GET[$key] = mysqli_real_escape_string($conn,$value);
}

if (!defined("BASE_URL")) {
    define("BASE_URL", "http://nrosanes3.dmitstudent.ca/dmit2025/lab7/");
}

if (!defined("THIS_PAGE")){
    define("THIS_PAGE", $_SERVER['PHP_SELF']);
}

?>
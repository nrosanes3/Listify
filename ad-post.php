<?php
include ("image-functions.php");
include ("includes/connect.php");
$thumb_folder = "thumbnails/";
$display_folder = "display/";
$original_folder = "uploaded_files/";

if (isset($_POST['ad-btn'])){
    $post_title = trim($_POST['title']);
    $post_ad = trim($_POST['ad']);
    $price = trim($_POST['price']);

    // imagefile
    $image_name = $_FILES["myfile"]["name"];
    $type = $_FILES["myfile"]["type"];
    $tmp_name = $_FILES["myfile"]["tmp_name"];
    $error = $_FILES["myfile"]["error"];
    $size = $_FILES["myfile"]["size"];
    $id = trim($_GET['id']);

    $view_details = $_GET['view'];
    $by_user = $_GET['by_user'];

    $valid = true;

    $post_ad = filter_var($post_ad, FILTER_SANITIZE_STRING);
    if ($post_ad == FALSE) {
        $message .= "<p>Please enter an ad with no HTML in it.</p>";
        $valid = false;
    }

    $post_title = filter_var($post_title, FILTER_SANITIZE_STRING);
    if ($post_title == FALSE) {
        $message .= "<p>Please enter a title with no HTML in it.</p>";
        $valid = false;
    }

    $image_name = filter_var($image_name, FILTER_SANITIZE_STRING);
    if ($image_name == FALSE) {
        $message .= "<p>Please enter a file name with no HTML in it.</p>";
        $valid = false;
    }

    if ($error > 0) {
        $message .= "<p>There was an error of type $error that occured.</p>";
        $valid = false;
    }

    if ($size > 1000000) {
        $message = "<p>File is too big. 1MB limit</p>";
        $valid = false;
    }

    $allowed_file_types = array("image/jpeg", "image/pjpeg", "image/png", "image/webp");
    if (!in_array($type, $allowed_file_types)) {
        $message .= "<p>Only jpg, png, and webp files are allowed.</p>";
        $valid = false;
    }

    if ($valid == true) {
        if ($post_ad && $post_title && $price && $image_name) {
            $upload_file = $original_folder . $image_name;
            if (move_uploaded_file($tmp_name, $upload_file)) {
                if ($type == 'image/jpeg'){
                    resize_image_jpeg($upload_file, $thumb_folder, 175);
                    resize_image_jpeg($upload_file, $display_folder, 1000);
                } else {
                    if ($type == 'image/png'){
                        resize_image_png($upload_file, $thumb_folder, 175);
                        resize_image_png($upload_file, $display_folder, 1000);
                    } else {
                        if ($type == 'image/webp') {
                            resize_image_webp($upload_file, $thumb_folder, 175);
                            resize_image_webp($upload_file, $display_folder, 1000);
                        }
                    }
                }
                $post_title = ucwords($post_title);
                $post_ad = ucfirst($post_ad);
                if ($id || $view_details){
                    $sql = "UPDATE for_sale SET title = '$post_title', ad = '$post_ad', image_name = '$image_name', price = '$price' WHERE ad_id = $id OR ad_id = $view_details";
                } else {
                    $sql = "INSERT into for_sale (title, ad, image_name, price, u_id) VALUES ('$post_title', '$post_ad', '$image_name', '$price', '" .$_SESSION['user_id']."')";
                }
                if ($conn->query($sql)) {
                    $message = "<p>Image uploaded and inserted into db successfully!</p>";
                } else {
                    $message = "<p>There was a problem. $conn->error</p>";
                }

                $message = "<p>Ad posted successfully!</p>";
            } else {
                $message = "<p>There was a problem uploading.</p>";
            }
        } else {
            $message = "<p>All fields are required.</p>";
        }
    }

}

?>
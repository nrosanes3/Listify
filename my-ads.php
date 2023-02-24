<?php
$page_title = "My Ads";
$body_class = "myads";
session_start();

$limit = 4;
$page = 1;
$orderBy = "date_posted";
$order = " DESC ";
$start_position = 0;
$limit_string = " LIMIT $start_position, $limit ";

require ("includes/connect.php");
include("messages.php");
include("session-time-check.php");
include("login-post.php");
include("ad-post.php");

if (isset($_GET)) {
    extract($_GET);
}

include("search-post.php");

if ($id) {
    $get_sql = "SELECT title, ad, image_name, price from for_sale WHERE ad_id = $id AND u_id = " . $_SESSION['user_id'];
    $get_res = $conn->query($get_sql);
    if ($get_res->num_rows > 0){
        $get_row = $get_res->fetch_assoc();
        $post_ad = $get_row['ad'];
        $post_title = $get_row['title'];
        $image_name = $get_row['image_name'];
        $price = $get_row['price'];
    } else {
        $message = "<p>Unable to retrieve information.</p>";
    }
}

//count of records to paginate
$count_sql = "SELECT COUNT(*) AS row_count FROM for_sale WHERE deleted_yn = 'N' AND item_sold_yn = 'N' $seach_part ORDER BY $orderBy $order $limit_string";
$count_result = $conn->query($count_sql);
if ($count_result->num_rows > 0){
    $count_row = $count_result->fetch_assoc();
    $count_of_records = $count_row['row_count'];
}
if ($count_of_records > $limit) {
    $end = round($count_of_records % $limit, 0);
    $splits = round(($count_of_records - $end) / $limit, 0);
    $num_pages = $splits;
    if ($end != 0) {
        $num_pages++;
    }
    $start_position = ($page * $limit) - $limit;
    $limit_string = " LIMIT $start_position, $limit ";
}
?>

<?php require("includes/header.php");?>
<div class="ad-form">
    <h2>Post An Ad</h2>
    <?php if($message):?>
        <div class="message">
            <?php echo "<p>$message</p>";?>
        </div>
    <?php endif?>
    <?php include("ad-form.php");;?>
</div>
<div class="jobs">
    <?php
    $list_sql = "SELECT ad_id, title, ad, date_posted, user_name, users2.u_id, item_sold_yn, deleted_yn, image_name, price from for_sale INNER JOIN users2 ON for_sale.u_id = users2.u_id WHERE deleted_yn = 'N' AND users2.u_id = " .$_SESSION['user_id']." $search_part ORDER BY $orderBy $order $limit_string";
    $list_result = $conn->query($list_sql);
    ?>
    <?php if ($list_result->num_rows > 0):?>

        <h2>My Ads</h2>
            <?php while($row = $list_result->fetch_assoc()):?>
                <?php extract($row);?>
                <div class="ads "> 
                    <h3><?php echo $title;?></h3> 
                    <p class="ad-date">Posted on <?php echo date("M d, Y g:i a", strtotime($date_posted))?> by <a href="index.php?by_user=<?php echo $user_name; ?>"> <?php echo ucfirst($user_name); ?></a></p> 
                    <p class="ad-description"><?php echo $ad;?></p>
                    <img src="display/<?php echo $image_name?>" alt="<?php echo $image_name?>">
                    <p class="ad-description">$<?php echo $price;?></p>
                    <div class="ad-links">
                        <?php if ($_SESSION['user_id'] == $u_id):?>
                            <a href="my-ads.php?id=<?php echo $ad_id; ?>">Edit</a>
                            <?php if ($item_sold_yn == 'Y'):?>
                                <a href="mark-item-available.php?id=<?php echo $ad_id;?>">Mark Item Available</a>
                            <?php else: ?>
                                <a href="mark-item-filled.php?id=<?php echo $ad_id;?>">Mark Item Sold</a>
                            <?php endif ?>
                            <a href="mark-item-deleted.php?id=<?php echo $ad_id; ?>">Delete Permanently</a>
                        <?php endif ?>
                    </div>
                </div>
            <?php endwhile ?>
            <!-- pagination goes here -->
            <?php if ($count_of_records > $limit):?>
                <ul class="pagination">
                    <li>Pages:</li>
                    <?php
                    $next_page = $page + 1;
                    $previous_page = $page - 1;
                    $anchor_string = THIS_PAGE."?search=$search&orderby=$orderBy&order=$order&limit=$limit&page=";
                    ?>
                    <?php if ($page > 1): ?>
                        <li><a href="<?php echo $anchor_string.$previous_page;?>"><< Prev</a></li>
                    <?php endif ?>
                    <?php for ($i=1; $i <= $num_pages; $i++):?>
                        <?php if ($i != $page):?>
                            <li><a href="<?php echo $anchor_string.$i;?>"><?php echo $i;?></a></li>
                        <?php else: ?>
                            <li><?php echo $i; ?></li>
                        <?php endif ?>
                    <?php endfor ?>
                    <?php if ($page < $num_pages): ?>
                        <li><a href="<?php echo $anchor_string.$next_page;?>">Next >></a></li>
                    <?php endif ?>
                </ul>
            <?php endif ?>

    <?php endif?>
</div>

<?php require("includes/footer.php");?>
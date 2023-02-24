<?php
$page_title = "Home";
$body_class = "home";
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

$view_details = $_GET['view'];
$by_user = $_GET['by_user'];

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
<div class="search">
    <?php include("search-form.php");?>
</div>

<div class="jobs">
<?php if($view_details):?>
    <?php
    $list_sql = "SELECT ad_id, title, ad, date_posted, user_name, users2.u_id, item_sold_yn, deleted_yn, image_name, price from for_sale INNER JOIN users2 ON for_sale.u_id = users2.u_id WHERE deleted_yn = 'N' AND item_sold_yn = 'N' AND ad_id = $view_details $search_part ORDER BY $orderBy $order $limit_string";
    $list_result = $conn->query($list_sql);
    ?>
    <?php if ($list_result->num_rows > 0):?>
        <h2>View One Ad</h2>
        <a href="index.php" class="back-menu"><< Back to All Ads</a>
        <?php if($row = $list_result->fetch_assoc()):?>
                <?php extract($row);?>
                <?php  $id;?>
                <div class="ads">
                    <h3><?php echo $title;?></h3> 
                    <p class="ad-date">Posted on <?php echo date("M d, Y g:i a", strtotime($date_posted))?> by <a href="index.php?by_user=<?php echo $user_name; ?>"> <?php echo ucfirst($user_name); ?></a></p> 
                    <img src="display/<?php echo $image_name?>" alt="<?php echo $image_name?>">
                    <p class="ad-description">$<?php echo $price;?></p>

                    <div>
                        <p class="ad-description"><?php echo $ad;?></p>
                    </div>

                    <div class="ad-links">
                        <?php if ($_SESSION['user_id'] == $u_id):?>
                            <a href="index.php?view=<?php echo $ad_id; ?>">Edit</a>
                            <?php if ($item_sold_yn == 'N'):?>
                                <a href="mark-item-sold.php?id=<?php echo $ad_id;?>">Mark Item Sold</a>
                            <?php endif ?>
                            <a href="mark-item-deleted.php?id=<?php echo $ad_id; ?>">Delete Permanently</a>
                        <?php endif ?>
                    </div>
                </div>
            <?php endif ?>
<?php endif ?>
<?php elseif ($by_user):?>
    <?php
    $list_sql = "SELECT ad_id, title, ad, date_posted, user_name, users2.u_id, item_sold_yn, deleted_yn, image_name, price from for_sale INNER JOIN users2 ON for_sale.u_id = users2.u_id WHERE deleted_yn = 'N' AND item_sold_yn = 'N' AND user_name = $by_user $search_part ORDER BY $orderBy $order $limit_string";
    $list_result = $conn->query($list_sql);    
    ?>
    <?php if ($list_result->num_rows > 0):?>
            <h2>Ads by <?php echo $user_name; ?></h2>
            <a href="index.php" class="back-menu"><< Back to All Ads</a>
            <?php while($row = $list_result->fetch_assoc()):?>
                <?php extract($row);?>
                <?php echo $user_name?>
                <div class="ads">
                    <h3><?php echo $title;?></h3> 
                    <p class="ad-date">Posted on <?php echo date("M d, Y g:i a", strtotime($date_posted))?> by <a href="index.php?by_user=<?php echo $user_name; ?>"> <?php echo ucfirst($user_name); ?></a></p> 
                    <img src="thumbnails/<?php echo $image_name?>" alt="<?php echo $image_name?>">
                    <p class="ad-description">$<?php echo $price;?></p>

                    <div>
                        <p class="ad-description"><?php echo mb_strimwidth("$ad", 0, 100, "...")?></p>
                        <a href="index.php?view=<?php echo $ad_id; ?>" class="view-details">View details</a>
                    </div>

                    <div class="ad-links">
                        <?php if ($_SESSION['user_id'] == $u_id):?>
                            <a href="index.php?id=<?php echo $ad_id; ?>">Edit</a>
                            <?php if ($item_sold_yn == 'N'):?>
                                <a href="mark-item-sold.php?id=<?php echo $ad_id;?>">Mark Item Sold</a>
                            <?php endif ?>
                            <a href="mark-item-deleted.php?id=<?php echo $ad_id; ?>">Delete Permanently</a>
                        <?php endif ?>
                    </div>
                </div>
            <?php endwhile ?>
<?php endif ?>
<?php else: ?>
    <?php
    $list_sql = "SELECT ad_id, title, ad, date_posted, user_name, users2.u_id, item_sold_yn, deleted_yn, image_name, price from for_sale INNER JOIN users2 ON for_sale.u_id = users2.u_id WHERE deleted_yn = 'N' AND item_sold_yn = 'N' $search_part ORDER BY $orderBy $order $limit_string";
    $list_result = $conn->query($list_sql);
    ?>
    <?php if ($list_result->num_rows > 0):?>
            <h2>All Sale Items</h2>
            <?php while($row = $list_result->fetch_assoc()):?>
                <?php extract($row);?>
                <div class="ads">
                    <h3><?php echo $title;?></h3> 
                    <p class="ad-date">Posted on <?php echo date("M d, Y g:i a", strtotime($date_posted))?> by <a href="index.php?by_user=<?php echo $user_name; ?>"> <?php echo ucfirst($user_name); ?></a></p> 
                    <img src="thumbnails/<?php echo $image_name?>" alt="<?php echo $image_name?>">
                    <p class="ad-description">$<?php echo $price;?></p>

                    <div>
                        <p class="ad-description"><?php echo mb_strimwidth("$ad", 0, 100, "...")?></p>
                        <a href="index.php?view=<?php echo $ad_id; ?>" class="view-details">View details</a>
                    </div>

                    <div class="ad-links">
                        <?php if ($_SESSION['user_id'] == $u_id):?>
                            <a href="index.php?id=<?php echo $ad_id; ?>">Edit</a>
                            <?php if ($item_sold_yn == 'N'):?>
                                <a href="mark-item-sold.php?id=<?php echo $ad_id;?>">Mark Item Sold</a>
                            <?php endif ?>
                            <a href="mark-item-deleted.php?id=<?php echo $ad_id; ?>">Delete Permanently</a>
                        <?php endif ?>
                    </div>
                </div>
            <?php endwhile ?>
<?php endif ?>
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

<?php
if($_SESSION['user_id']) {
    echo "<div class=\"log logged-in\">";
    echo "<h2>Post An Ad</h2>";
    if($message){
        echo "<div class=\"message\">";
        echo "<p>$message</p>";
        echo "</div>";
    }
    include("ad-form.php");
    echo "</div>";
} else {
    echo "<div class=\"log logged-out\">";
    echo "<h2>Login</h2>";
    if($message){
        echo "<div class=\"message\">";
        echo "<p>$message</p>";
        echo "</div>";
    }
    include("login-form.php");
    echo "</div>";
}
?>


<?php require("includes/footer.php");?>
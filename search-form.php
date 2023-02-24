<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="GET" class="search-form form">
    <h2>Search An Ad</h2>
    <div>
        <label for="search">Search</label>
        <input type="text" name="search" id="search" value="<?php echo $search ?>">
    </div>
    <div>
        <label for="orderby">Sort By</label>
        <select name="orderby">
            <option value="title" <?php if ($orderby == "title") {echo "selected";}?>>Title</option>
            <option value="date_posted" <?php if ($orderby == "date_posted") {echo "selected";}?>>Date Posted</option>
            <option value="user_name" <?php if ($orderby == "user_name") {echo "selected";}?>>User Name</option>
            <option value="" <?php if ($orderby == "") {echo "selected";}?>>Order Entered</option>
        </select>
    </div>
    <div>
        <label for="order">Sort Order</label>
        <select name="order" id="order">
            <option value="ASC" <?php if ($order == "ASC") {echo "selected";}?>>Ascending</option>
            <option value="DESC" <?php if ($order == "DESC") {echo "selected";}?>>Descending</option>
        </select>
    </div>
    <div>
        <label for="limit">Per Page</label>
        <select name="limit" id="limit">
            <option value="3" <?php if ($limit == "3") {echo "selected";}?>>3</option>
            <option value="4" <?php if ($limit == "4") {echo "selected";}?>>4</option>
            <option value="5" <?php if ($limit == "5") {echo "selected";}?>>5</option>
            <option value="10" <?php if ($limit == "10") {echo "selected";}?>>10</option>
        </select>
    </div>
    <div>
        <input type="submit" name="search-form" value="Search">
    </div>

</form>
<form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']);?>" method="POST" class="ad-form form" enctype="multipart/form-data">
    <div>
        <label for="title">Title</label>
        <input type="text" id="title" name="title" value="<?php echo $post_title;?>">
    </div>

    <div>
        <label for="ad">Ad</label>
        <textarea name="ad" id="ad" cols="30" rows="10"><?php echo $post_ad;?></textarea>
    </div>

    <div class="file-image">
        <label for="myfile">Select an Image</label>
        <input type="file" name="myfile" id="myfile">
    </div>

    <div>
        <label for="price">Price</label>
        <input type="number"  name="price" id="price" step="any" value="<?php echo $price;?>">
    </div>

    <div><input type="submit" name="ad-btn" value="Post ad"></div>
</form>

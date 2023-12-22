<?php
require('top.inc.php');

$condition = $condition1 = '';

if ($_SESSION['ADMIN_ROLE'] == 1) {
    $condition = " where p.added_by='" . $_SESSION['ADMIN_ID'] . "'";
    $condition1 = " and added_by='" . $_SESSION['ADMIN_ID'] . "'";
}

$categories_id = $sub_categories_id = $name = $mrp = $price = $qty = $image = $short_desc = $description = $meta_title = $meta_desc = $meta_keyword = $best_seller = '';
$multipleImageArr = [];
$product = $msg = '';
$image_required = 'required';

if (isset($_GET['pi']) && $_GET['pi'] > 0) {
    $pi = get_safe_value($con, $_GET['pi']);
    $delete_sql = "delete from product_images where id='$pi'";
    mysqli_query($con, $delete_sql);
}

if (isset($_GET['id']) && $_GET['id'] != '') {
    $image_required = '';
    $id = get_safe_value($con, $_GET['id']);
    $res = mysqli_query($con, "select * from product where id='$id' $condition1");
    $check = mysqli_num_rows($res);
    if ($check > 0) {
        $row = mysqli_fetch_assoc($res);
        $categories_id = $row['categories_id'];
        $sub_categories_id = $row['sub_categories_id'];
        $name = $row['name'];
        $mrp = $row['mrp'];
        $price = $row['price'];
        $qty = $row['qty'];
        $short_desc = $row['short_desc'];
        $description = $row['description'];
        $meta_title = $row['meta_title'];
        $meta_desc = $row['meta_desc'];
        $meta_keyword = $row['meta_keyword'];
        $best_seller = $row['best_seller'];
        $image = $row['image'];
        $resMultipleImage = mysqli_query($con, "select id,product_images from product_images where product_id='$id'");
        if (mysqli_num_rows($resMultipleImage) > 0) {
            $jj = 0;
            while ($rowMultipleImage = mysqli_fetch_assoc($resMultipleImage)) {
                $multipleImageArr[$jj]['product_images'] = $rowMultipleImage['product_images'];
                $multipleImageArr[$jj]['id'] = $rowMultipleImage['id'];
                $jj++;
            }
        }
    } else {
        header('location:product.php');
        die();
    }
}

if (isset($_POST['submit'])) {
    // pr($_FILES);
    // prx($_POST);
    $categories_id = get_safe_value($con, $_POST['categories_id']);
    $sub_categories_id = get_safe_value($con, $_POST['sub_categories_id']);
    $name = get_safe_value($con, $_POST['name']);
    $mrp = get_safe_value($con, $_POST['mrp']);
    $price = get_safe_value($con, $_POST['price']);
    $qty = get_safe_value($con, $_POST['qty']);
    $filename = get_safe_value($con, $_FILES['image']['name']);          //for file
    $filetemp = $_FILES['image']['tmp_name'];                            //for file
    $short_desc = get_safe_value($con, $_POST['short_desc']);
    $description = get_safe_value($con, $_POST['description']);
    $meta_title = get_safe_value($con, $_POST['meta_title']);
    $meta_desc = get_safe_value($con, $_POST['meta_desc']);
    $meta_keyword = get_safe_value($con, $_POST['meta_keyword']);
    $best_seller = get_safe_value($con, $_POST['best_seller']);

    $res = mysqli_query($con, "select product.* from product where product.name='$name' $condition1");
    $check = mysqli_num_rows($res);

    if ($check > 0) {
        if (isset($_GET['id']) && $_GET['id'] != '') {
            $getData = mysqli_fetch_assoc($res);
            if ($id == $getData['id']) {
                // blank
            } else {
                $msg = "Product already exists";
            }
        } else {
            $msg = "Product already exist";
        }
    }

    //update time validation
    if (isset($_GET['id']) && ($_GET['id']) == 0) {
        if ($_FILES['image']['type'] != 'image/png' && $_FILES['image']['type'] != 'image/jpg' && $_FILES['image']['type'] != 'image/jpeg' && $_FILES['image']['type'] != 'image/webp') {
            $msg = "Only Webp, PNG, JPG, and JPEG files are allowed";
        }
    } else {
        if ($_FILES['image']['type'] != '') {
            if ($_FILES['image']['type'] != 'image/png' && $_FILES['image']['type'] != 'image/jpg' && $_FILES['image']['type'] != 'image/jpeg' && $_FILES['image']['type'] != 'image/webp') {
                $msg = "Only Webp, PNG, JPG, and JPEG files are allowed";
            }
        }
    }
    //multiple images validation
    if (isset($_FILES['product_images'])) {
        foreach ($_FILES['product_images']['type'] as $key => $val) {
            if ($_FILES['product_images']['type'][$key] != '') {
                if ($_FILES['product_images']['type'][$key] != 'image/png' && $_FILES['product_images']['type'][$key] != 'image/jpg' && $_FILES['product_images']['type'][$key] != 'image/jpeg' && $_FILES['product_images']['type'][$key] != 'image/webp') {
                    $msg = "Only Webp, PNG, JPG, and JPEG files are allowed";
                }
            }
        }
    }

    if ($msg == '') {
        if (isset($_GET['id']) && $_GET['id'] != '') {
            if ($filename != '') {
                $image = rand(11111111, 99999999) . '_' . $filename;                       //name change part
                move_uploaded_file($filetemp, PRODUCT_IMAGE_SERVER_PATH . $image);          //image folder path part

                $update_sql = "update product set categories_id='$categories_id',sub_categories_id='$sub_categories_id',name='$name',mrp='$mrp',price='$price',qty='$qty',image='$image',short_desc='$short_desc',description='$description',meta_title='$meta_title',meta_desc='$meta_desc',meta_keyword='$meta_keyword',best_seller='$best_seller',update_datetime='$date_time' where id='$id'";
            } else {
                $update_sql = "update product set categories_id='$categories_id',sub_categories_id='$sub_categories_id',name='$name',mrp='$mrp',price='$price',qty='$qty',short_desc='$short_desc',description='$description',meta_title='$meta_title',meta_desc='$meta_desc',meta_keyword='$meta_keyword',best_seller='$best_seller',update_datetime='$date_time' where id='$id'";
            }
            mysqli_query($con, $update_sql);
        } else {
            $image = rand(11111111, 99999999) . '_' . $filename;                       //name change part
            move_uploaded_file($filetemp, PRODUCT_IMAGE_SERVER_PATH . $image);          //image folder path part

            mysqli_query($con, "insert into product(categories_id,sub_categories_id,name,mrp,price,qty,image,short_desc,description,meta_title,meta_desc,meta_keyword,status,insert_datetime,best_seller,added_by)values('$categories_id','$sub_categories_id','$name','$mrp','$price','$qty','$image','$short_desc','$description','$meta_title','$meta_desc','$meta_keyword',1,'$date_time','$best_seller','" . $_SESSION['ADMIN_ID'] . "')");
            $id = mysqli_insert_id($con);
        }
        /*Product multiple image start*/
        if (isset($_GET['id']) && $_GET['id'] != '') {
            foreach ($_FILES['product_images']['name'] as $key => $val) {
                if ($_FILES['product_images']['name'][$key] != '') {
                    if (isset($_POST['product_images_id'][$key])) {
                        $image = rand(11111111, 99999999) . '_' . $_FILES['product_images']['name'][$key];
                        move_uploaded_file($_FILES['product_images']['tmp_name'][$key], PRODUCT_MULTIPLE_IMAGE_SERVER_PATH . $image);

                        mysqli_query($con, "update product_images set product_images='$image' where id='" . $_POST['product_images_id'][$key] . "'");
                    } else {
                        $image = rand(11111111, 99999999) . '_' . $_FILES['product_images']['name'][$key];
                        move_uploaded_file($_FILES['product_images']['tmp_name'][$key], PRODUCT_MULTIPLE_IMAGE_SERVER_PATH . $image);

                        mysqli_query($con, "insert into product_images(product_id,product_images)values('$id','$image')");
                    }
                }
            }
        } else {
            if (isset($_FILES['product_images']['name'])) {
                foreach ($_FILES['product_images']['name'] as $key => $val) {
                    $image = rand(11111111, 99999999) . '_' . $_FILES['product_images']['name'][$key];
                    move_uploaded_file($_FILES['product_images']['tmp_name'][$key], PRODUCT_MULTIPLE_IMAGE_SERVER_PATH . $image);

                    mysqli_query($con, "insert into product_images(product_id,product_images)values('$id','$image')");
                }
            }
        }
        /*Product multiple image end*/
        header('location:product.php');
        die();
    }
}

?>
<div class="content pb-0">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <form method="post" enctype="multipart/form-data">
                        <div class="card-header"><strong>Product</strong><small> Form</small></div>
                        <div class="card-body card-block">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label for="categories" class="form-control-label">Categories</label>
                                        <select class="form-control" onchange="get_sub_cat('')" name="categories_id" id="categories_id" required>
                                            <option>Select category</option>
                                            <?php
                                            $res = mysqli_query($con, "select id,categories from categories order by categories asc");
                                            while ($row = mysqli_fetch_assoc($res)) {
                                                if ($row['id'] == $categories_id) {
                                                    echo "<option selected value='" . $row['id'] . "'>" . $row['categories'] . "</option>";
                                                } else {
                                                    echo "<option value='" . $row['id'] . "'>" . $row['categories'] . "</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="categories" class="form-control-label">Sub-Categories</label>
                                        <select class="form-control" name="sub_categories_id" id="sub_categories_id">
                                            <option>Select sub-category</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name" class="form-control-label">Product Name</label>
                                <input type="text" name="name" value="<?php echo $name ?>" placeholder="Enter product name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <label for="categories" class="form-control-label">Best Seller</label>
                                        <select class="form-control" name="best_seller" required>
                                            <option value="">Select</option>
                                            <?php
                                            if ($best_seller == 1) {
                                                echo '<option value="1" selected>Yes</option>;
                                              <option value="0">No</option>';
                                            } else if ($best_seller == 0) {
                                                echo '<option value="1">Yes</option>;
                                              <option value="0" selected>No</option>';
                                            } else {
                                                echo '<option value="1">Yes</option>;
                                              <option value="0">No</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="name" class="form-control-label">MRP</label>
                                        <input type="number" name="mrp" value="<?php echo $mrp ?>" placeholder="Enter product MRP" class="form-control" required>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="name" class="form-control-label">Price</label>
                                        <input type="number" name="price" value="<?php echo $price ?>" placeholder="Enter product price" class="form-control" required>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="name" class="form-control-label">Qty</label>
                                        <input type="text" name="qty" value="<?php echo $qty ?>" placeholder="Enter product qty" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row" id="image_box">
                                    <div class="col-lg-10">
                                        <label for="image" class="form-control-label">Image</label>
                                        <input type="file" name="image" class="form-control" <?php echo $image_required ?>>
                                        <?php
                                        if ($image != '') {
                                            echo "<a target='_blank' href='" . PRODUCT_IMAGE_SITE_PATH . $image . "'><img width='100px'; src='" . PRODUCT_IMAGE_SITE_PATH . $image . "'></a>";
                                        }
                                        ?>
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="image" class="form-control-label"></label>
                                        <button id="" type="button" onclick="add_more_images()" class="btn btn-lg btn-info btn-block">
                                            <span id="payment-button-amount">Add Image</span>
                                        </button>
                                    </div>
                                    <?php
                                    if (isset($multipleImageArr[0])) {
                                        foreach ($multipleImageArr as $list) {
                                            echo '<div class="col-lg-6" id="add_image_box_' . $list['id'] . '" style="margin-top: 10px;"><label for="image" class="form-control-label">Image</label><input type="file" name="product_images[]" class="form-control"><a style="color:white"; href="manage_product.php?id=' . $id . '&pi=' . $list['id'] . '"><button type="button" class="btn btn-lg btn-danger btn-block"><span id="payment-button-amount">Remove</span></button></a>';
                                            echo "<a target='_blank' href='" . PRODUCT_MULTIPLE_IMAGE_SITE_PATH . $list['product_images'] . "'><img width='100px'; src='" . PRODUCT_MULTIPLE_IMAGE_SITE_PATH . $list['product_images'] . "'></a>";
                                            echo '<input type="hidden" name="product_images_id[]" value="' . $list['id'] . '"></div>';
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name" class="form-control-label">Short Description</label>
                                <textarea name="short_desc" placeholder="Enter short description" class="form-control" required><?php echo $short_desc ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="name" class="form-control-label">Description</label>
                                <textarea name="description" placeholder="Enter description" class="form-control"><?php echo $description ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="name" class="form-control-label">Meta Title</label>
                                <textarea name="meta_title" placeholder="Enter meta title" class="form-control"><?php echo $meta_title ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="name" class="form-control-label">Meta Description</label>
                                <textarea name="meta_desc" placeholder="Enter meta description" class="form-control"><?php echo $meta_desc ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="name" class="form-control-label">Meta Keyword</label>
                                <textarea name="meta_keyword" placeholder="Enter meta keyword" class="form-control"><?php echo $meta_keyword ?></textarea>
                            </div>
                            <button id="payment-button" type="submit" name="submit" class="btn btn-lg btn-info btn-block">
                                <span id="payment-button-amount">Submit</span>
                            </button>
                            <div class="field_error"><?php echo $msg ?></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function get_sub_cat(sub_cat_id) {
        var categories_id = jQuery('#categories_id').val();
        jQuery.ajax({
            url: 'get_sub_cat.php',
            type: 'post',
            data: {
                categories_id: categories_id,
                sub_cat_id: sub_cat_id
            },
            success: function(result) {
                jQuery('#sub_categories_id').html(result);
            }
        });
    }

    var total_image = 1;

    function add_more_images() {
        total_image++;
        var html = '<div class="col-lg-6" id="add_image_box_' + total_image + '" style="margin-top: 10px;"><label for="image" class="form-control-label">Image</label><input type="file" name="product_images[]" class="form-control" required><button type="button" onclick=remove_image(\'' + total_image + '\') class="btn btn-lg btn-danger btn-block"><span id="payment-button-amount">Remove</span></button></div>';
        jQuery("#image_box").append(html);
    }

    function remove_image(id) {
        jQuery('#add_image_box_' + id).remove();
    }
</script>
<?php require('footer.inc.php') ?>
<script>
    <?php
    if (isset($_GET['id'])) {
    ?>
        get_sub_cat(<?php echo $sub_categories_id ?>);
    <?php } ?>
</script>
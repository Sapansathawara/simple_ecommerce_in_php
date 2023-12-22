<?php
require('top.inc.php');

isAdmin();
$categories = $sub_categories = $msg = '';
if (isset($_GET['id']) && $_GET['id'] != '') {
    $id = get_safe_value($con, $_GET['id']);
    $res = mysqli_query($con, "select * from sub_categories where id='$id'");
    $check = mysqli_num_rows($res);
    if ($check > 0) {
        $row = mysqli_fetch_assoc($res);
        $sub_categories = $row['sub_categories'];
        $categories = $row['categories_id'];
    } else {
        header('location:sub_categories.php');
        die();
    }
}

if (isset($_POST['submit'])) {
    $categories = get_safe_value($con, $_POST['categories_id']);
    $sub_categories = get_safe_value($con, $_POST['sub_categories']);
    $res = mysqli_query($con, "select * from sub_categories where categories_id='$categories' and sub_categories='$sub_categories'");
    $check = mysqli_num_rows($res);
    if ($check > 0) {
        if (isset($_GET['id']) && $_GET['id'] != '') {
            $getData = mysqli_fetch_assoc($res);
            if ($id == $getData['id']) {
                // blank
            } else {
                $msg = "Sub-Category already exists";
            }
        }
        $msg = "Sub-Category already exists";
    }
    if ($msg == '') {
        if (isset($_GET['id']) && $_GET['id'] != '') {
            mysqli_query($con, "update sub_categories set categories_id='$categories',sub_categories='$sub_categories' where id='$id'");
        } else {
            mysqli_query($con, "insert into sub_categories(categories_id,sub_categories,status)values('$categories','$sub_categories','1')");
        }
        header('location:sub_categories.php');
        die();
    }
}

?>
<div class="content pb-0">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <form method="post">
                        <div class="card-header"><strong>Sub-Categories</strong><small> Form</small></div>
                        <div class="card-body card-block">
                            <div class="form-group">
                                <label for="categories" class=" form-control-label">Categories</label>
                                <select name="categories_id" class="form-control" required>
                                    <option value="">Select category</option>
                                    <?php
                                    $res = mysqli_query($con, "select * from categories where status='1'");
                                    while ($row = mysqli_fetch_assoc($res)) {
                                        if ($row['id'] == $categories) {
                                            echo "<option value=" . $row['id'] . " selected>" . $row['categories'] . "</option>";
                                        } else {
                                            echo "<option value=" . $row['id'] . ">" . $row['categories'] . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="name" class="form-control-label">Sub-Category</label>
                                <input type="text" name="sub_categories" value="<?php echo $sub_categories ?>" placeholder="Enter sub-category" class="form-control" required>
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
<?php require('footer.inc.php') ?>
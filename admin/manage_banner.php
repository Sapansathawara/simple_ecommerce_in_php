<?php
require('top.inc.php');

isAdmin();
$heading1 = $heading2 = $btn_txt = $btn_link = $order_no = $image = $msg = '';
$image_required = 'required';

if (isset($_GET['id']) && $_GET['id'] != '') {
    $id = get_safe_value($con, $_GET['id']);
    $image_required = '';
    $res = mysqli_query($con, "select * from banner where id='$id'");
    $check = mysqli_num_rows($res);
    if ($check > 0) {
        $row = mysqli_fetch_assoc($res);
        $heading1 = $row['heading1'];
        $heading2 = $row['heading2'];
        $btn_txt = $row['btn_txt'];
        $btn_link = $row['btn_link'];
        $order_no = $row['order_no'];
        $image = $row['image'];
    } else {
        header('location:banner.php');
        die();
    }
}

if (isset($_POST['submit'])) {
    $heading1 = get_safe_value($con, $_POST['heading1']);
    $heading2 = get_safe_value($con, $_POST['heading2']);
    $btn_txt = get_safe_value($con, $_POST['btn_txt']);
    $btn_link = get_safe_value($con, $_POST['btn_link']);
    $order_no = get_safe_value($con, $_POST['order_no']);
    $filename = get_safe_value($con, $_FILES['image']['name']);          //for file
    $filetemp = $_FILES['image']['tmp_name'];                            //for file

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

    $msg = "";

    if ($msg == '') {
        if (isset($_GET['id']) && $_GET['id'] != '') {
            if ($filename != '') {
                $image = rand(11111111, 99999999) . '_' . $filename;                       //name change part
                move_uploaded_file($filetemp, BANNER_SERVER_PATH . $image);          //image folder path part
                mysqli_query($con, "update banner set heading1='$heading1',heading2='$heading2',btn_txt='$btn_txt',btn_link='$btn_link',order_no='$order_no',image='$image' where id='$id'");
            } else {
                mysqli_query($con, "update banner set heading1='$heading1',heading2='$heading2',btn_txt='$btn_txt',btn_link='$btn_link',order_no='$order_no' where id='$id'");
            }
        } else {
            $image = rand(11111111, 99999999) . '_' . $filename;                       //name change part
            move_uploaded_file($filetemp, BANNER_SERVER_PATH . $image);          //image folder path part
            mysqli_query($con, "insert into banner(heading1,heading2,btn_txt,btn_link,image,order_no,status)values('$heading1','$heading2','$btn_txt','$btn_link','$image','$order_no','1')");
        }
        header('location:banner.php');
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
                        <div class="card-header"><strong>Banner</strong><small> Form</small></div>
                        <div class="card-body card-block">
                            <div class="form-group">
                                <label for="categories" class=" form-control-label">Heading 1</label>
                                <input type="text" name="heading1" value="<?php echo $heading1 ?>" placeholder="Enter banner heading1" class="form-control" required>
                                <label for="categories" class=" form-control-label">Heading 2</label>
                                <input type="text" name="heading2" value="<?php echo $heading2 ?>" placeholder="Enter banner heading2" class="form-control" required>
                                <label for="categories" class=" form-control-label">Button Text</label>
                                <input type="text" name="btn_txt" value="<?php echo $btn_txt ?>" placeholder="Enter button text" class="form-control">
                                <label for="categories" class=" form-control-label">Button Link</label>
                                <input type="text" name="btn_link" value="<?php echo $btn_link ?>" placeholder="Enter button link" class="form-control">
                                <label for="categories" class=" form-control-label">Order No</label>
                                <input type="text" name="order_no" value="<?php echo $order_no ?>" placeholder="Enter order no" class="form-control">
                                <label for="categories" class=" form-control-label">Image</label>
                                <input type="file" name="image" value="<?php echo $image ?>" <?php echo $image_required ?> placeholder="Enter image" class="form-control">
                                <?php
                                if ($image != '') {
                                    echo "<a target='_blank' href='" . BANNER_SITE_PATH . $image . "'><img width='100px'; src='" . BANNER_SITE_PATH . $image . "'></a>";
                                }
                                ?>
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
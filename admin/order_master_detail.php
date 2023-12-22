<?php
require('top.inc.php');
isAdmin();
$order_id = get_safe_value($con, $_GET['id']);
$coupon_details = mysqli_fetch_assoc(mysqli_query($con, "select coupon_value,coupon_code from `order` where id='$order_id'"));
$coupon_value = $coupon_details['coupon_value'];
$coupon_code = $coupon_details['coupon_code'];

if (isset($_POST['update_order_status'])) {
    $update_order_status = $_POST['update_order_status'];

    $update_sql = '';
    if ($update_order_status == 3) {
        $length = $_POST['length'];
        $breadth = $_POST['breadth'];
        $height = $_POST['height'];
        $weight = $_POST['weight'];

        $update_sql = ", length='$length',breadth='$breadth',height='$height',weight='$weight' ";
    }

    if ($update_order_status == '5') {
        mysqli_query($con, "update `order` set order_status='$update_order_status',payment_status='Success' where id='$order_id'");
    } else {
        mysqli_query($con, "update `order` set order_status='$update_order_status' $update_sql where id='$order_id'");
    }
    if ($update_order_status == 3) {
        $token = validShipRocketToken($con);
        placeShipRocketOrder($con, $token, $order_id);
    }
    if ($update_order_status == 4) {
        $ship_order = mysqli_fetch_assoc(mysqli_query($con, "select ship_order_id from `order` where id='$order_id'"));
        if ($ship_order['ship_order_id'] > 0) {
            $token = validShipRocketToken($con);
            cancelShipRocketOrder($token, $ship_order['ship_order_id']);
        }
    }
}
?>
<div class="content pb-0">
    <div class="orders">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="box-title">Order Details</h4>
                    </div>
                    <div class="card-body--">
                        <div class="table-stats order-table ov-h">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="product-remove">Product Name</th>
                                        <th class="product-name">Product Image</th>
                                        <th class="product-price">Qty</th>
                                        <th class="product-stock-stauts">Price</th>
                                        <th class="product-stock-stauts">Total Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT DISTINCT od.id, od.*, p.name,p.image,o.address,o.city,o.pincode FROM order_detail as od INNER JOIN `order` as o ON od.order_id=o.id INNER JOIN product as p ON od.product_id=p.id WHERE od.order_id='$order_id'";
                                    // echo $sql;
                                    $res = mysqli_query($con, $sql);
                                    $total_price = 0;
                                    while ($row = mysqli_fetch_assoc($res)) {
                                        $address = $row['address'];
                                        $city = $row['city'];
                                        $pincode = $row['pincode'];
                                        $total_price = $total_price + ($row['qty'] * $row['price']);
                                    ?>
                                        <tr>
                                            <td class="product-add-to-cart"><?php echo $row['name'] ?></a></td>
                                            <td class="product-name"><img width="130px" src="<?php echo PRODUCT_IMAGE_SITE_PATH . $row['image'] ?>"></td>
                                            <td class="product-name"><?php echo $row['qty'] ?></td>
                                            <td class="product-name"><?php echo $row['price'] ?></td>
                                            <td class="product-name"><?php echo $row['qty'] * $row['price'] ?></td>
                                        </tr>
                                    <?php }
                                    if ($coupon_value != '') {
                                    ?>
                                        <tr>
                                            <td colspan="3"></td>
                                            <td class="product-name">Coupon Value<?php echo "  (Apply code - {$coupon_code})" ?></td>
                                            <td class="product-name"><?php echo $coupon_value ?></td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <td colspan="3"></td>
                                        <td class="product-name">Total Price</td>
                                        <td class="product-name"><?php echo $total_price-$coupon_value ?></td>
                                    </tr>
                                </tbody>
                            </table>
                            <div id="address_details">
                                <strong>Address :</strong>
                                <?php echo $address ?>, <?php echo $city ?> - <?php echo $pincode ?><br><br>
                                <strong>Order Status :</strong>
                                <?php
                                // $order_status_arr = [];
                                $order_status_sql = "SELECT os.name FROM order_status AS os
                                                     INNER JOIN `order` AS o ON os.id = o.order_status
                                                     WHERE o.id='$order_id'";
                                //  echo $order_status_sql;
                                $order_status_arr = mysqli_fetch_assoc(mysqli_query($con, $order_status_sql));
                                echo $order_status_arr['name'];
                                ?>
                                <div>
                                    <form method="post">
                                        <select class="form-control" name="update_order_status" id="update_order_status" required onchange="select_status()">
                                            <option value="">Select Status</option>
                                            <?php
                                            $res = mysqli_query($con, "select * from order_status");
                                            while ($row = mysqli_fetch_assoc($res)) {
                                                echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                                            }
                                            ?>
                                        </select>
                                        <div id="Shipped_box" style="display: none;">
                                            <table>
                                                <tr>
                                                    <td><input type="text" name="length" class="form-control" placeholder="Length"></td>
                                                    <td><input type="text" name="breadth" class="form-control" placeholder="Breadth"></td>
                                                    <td><input type="text" name="height" class="form-control" placeholder="Height"></td>
                                                    <td><input type="text" name="weight" class="form-control" placeholder="Weight"></td>
                                                </tr>
                                            </table>
                                        </div>
                                        <input type="submit" class="form-control">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function select_status() {
        var update_order_status = jQuery('#update_order_status').val();
        if (update_order_status == 3) {
            jQuery("#Shipped_box").show();
        }
    }
</script>

<?php require('footer.inc.php') ?>
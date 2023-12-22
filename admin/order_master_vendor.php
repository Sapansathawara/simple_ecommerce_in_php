<?php
require('top.inc.php');

?>
<div class="content pb-0">
    <div class="orders">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="box-title">Order Master</h4>
                    </div>
                    <div class="card-body--">
                        <div class="table-stats order-table ov-h">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="product-remove"><span class="nobr">Order ID</span></th>
                                        <th class="product-name"><span class="nobr">Product / QTY</span></th>
                                        <th class="product-price"><span class="nobr"> Address </span></th>
                                        <th class="product-stock-stauts"><span class="nobr"> Payment Type </span></th>
                                        <th class="product-add-to-cart"><span class="nobr">Payment Status</span></th>
                                        <th class="product-add-to-cart"><span class="nobr">Order Status</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT od.qty, p.name, os.name AS order_status_str, o.* FROM order_detail AS od
                                            INNER JOIN product AS p ON p.id = od.product_id
                                            INNER JOIN `order` AS o ON o.id = od.order_id
                                            INNER JOIN order_status AS os ON os.id = o.order_status
                                            WHERE p.added_by = " . $_SESSION['ADMIN_ID'] . "
                                            ORDER BY o.id DESC";

                                    $res = mysqli_query($con, $sql);
                                    while ($row = mysqli_fetch_assoc($res)) { ?>
                                        <tr>
                                            <td class="product-add-to-cart"><?php echo $row['id'] ?></td>
                                            <td class="product-name"><?php echo $row['name'] ?><br><?php echo $row['qty'] ?></td>
                                            <td class="product-name">
                                                <?php echo $row['address'] ?><br>
                                                <?php echo $row['city'] ?><br>
                                                <?php echo $row['pincode'] ?><br>
                                            </td>
                                            <td class="product-name"><?php echo $row['payment_type'] ?></td>
                                            <td class="product-name"><?php echo $row['payment_status'] ?></td>
                                            <td class="product-name"><?php echo $row['order_status_str'] ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require('footer.inc.php') ?>
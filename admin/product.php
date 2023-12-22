<?php
require('top.inc.php');

$condition = $condition1 = '';

if($_SESSION['ADMIN_ROLE']==1){
    $condition=" where p.added_by='".$_SESSION['ADMIN_ID']."'";
    $condition1=" and added_by='".$_SESSION['ADMIN_ID']."'";
}

if (isset($_GET['type']) && $_GET['type'] != '') {
    $type = get_safe_value($con, $_GET['type']);
    if ($type == 'status') {
        $operation = get_safe_value($con, $_GET['operation']);
        $id = get_safe_value($con, $_GET['id']);

        if ($operation == 'active') {
            $status = '1';
        } else {
            $status = '0';
        }
        $update_status_sql = "update product set status='$status' where id='$id' $condition1";
        mysqli_query($con, $update_status_sql);
    }
    if ($type == 'delete') {
        $id = get_safe_value($con, $_GET['id']);

        $delete_sql = "delete from product where id='$id' $condition1";
        mysqli_query($con, $delete_sql);
    }
}

$sql = "SELECT p.*, c.categories as category_name
        FROM product as p INNER JOIN categories as c ON p.categories_id = c.id $condition
        ORDER BY p.id DESC";

$res = mysqli_query($con, $sql);

?>
<div class="content pb-0">
    <div class="orders">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="box-title">Product </h4>
                        <h4 class="box-title"><a href="manage_product.php">Add Product</a> </h4>
                    </div>
                    <div class="card-body--">
                        <div class="table-stats order-table ov-h">
                            <table class="table ">
                                <thead>
                                    <tr>
                                        <th class="serial">#</th>
                                        <th>ID</th>
                                        <th>Categories</th>
                                        <th>Name</th>
                                        <th>Image</th>
                                        <th>MRP</th>
                                        <th>Price</th>
                                        <th>Qty</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    while ($row = mysqli_fetch_assoc($res)) { ?>
                                        <tr>
                                            <td class="serial"><?php echo $i ?></td>
                                            <td><?php echo $row['id'] ?></td>
                                            <td><?php echo $row['category_name'] ?></td>
                                            <td><?php echo $row['name'] ?></td>
                                            <td><a><img src="<?php echo PRODUCT_IMAGE_SITE_PATH . $row['image'] ?>"></a></td>
                                            <td><?php echo $row['mrp'] ?></td>
                                            <td><?php echo $row['price'] ?></td>
                                            <td><?php echo $row['qty'] ?><br />
                                                <?php
                                                $productSoldQtyByProductId = productSoldQtyByProductId($con, $row['id']);
                                                $pending_qty = $row['qty'] - $productSoldQtyByProductId;
                                                ?>
                                                Pending Qty : <?php echo $pending_qty ?>
                                            </td>
                                            <td>
                                                <?php
                                                if ($row['status'] == 1) {
                                                    echo "<span class='badge badge-complete'><a href='?type=status&operation=deactive&id=" . $row['id'] . "'>Active</a></span>&nbsp";
                                                } else {
                                                    echo "<span class='badge badge-pending'><a href='?type=status&operation=active&id=" . $row['id'] . "'>Deactive</a></span>&nbsp";
                                                }
                                                echo "<span class='badge badge-edit'><a href='manage_product.php?id=" . $row['id'] . "'>Edit</a></span>&nbsp";
                                                echo "<span class='badge badge-delete'><a href='?type=delete&id=" . $row['id'] . "'>Delete</a></span>";
                                                ?>
                                            </td>
                                        </tr>
                                    <?php
                                        $i++;
                                    }
                                    ?>
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
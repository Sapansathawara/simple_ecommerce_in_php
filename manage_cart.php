<?php
require('connection.inc.php');
require('function.inc.php');
require('add_to_cart.inc.php');

$pid = get_safe_value($con, $_POST['pid']);
$qty = get_safe_value($con, $_POST['qty']);
$type = get_safe_value($con, $_POST['type']);

$productSoldQtyByProductId = productSoldQtyByProductId($con, $pid);
$productQty = productQty($con, $pid);

$pending_qty = $productQty - $productSoldQtyByProductId;

$obj = new add_to_cart();          //if and else condition ma repeat thay che etle condition ni bahar aapi
if ($qty < 1) {
    // $obj = new add_to_cart();

    if ($type == 'remove') {            //remove type if condition ma etle aapyu kem ke jyare security check ma qty minus ma apiye tyare e remove thai sake.
        $obj->removeProduct($pid, $qty);
    }
    
} else {
    if ($qty > $pending_qty) {
        echo "not_available";
        die();
    }

    // $obj = new add_to_cart();

    if ($type == 'add') {
        $obj->addProduct($pid, $qty);
    }
    if ($type == 'remove') {
        $obj->removeProduct($pid, $qty);
    }
    if ($type == 'update') {
        $obj->updateProduct($pid, $qty);
    }
}

echo $obj->totalProduct();

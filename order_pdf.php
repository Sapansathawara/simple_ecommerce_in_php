<?php
include('vendor/autoload.php');
require('connection.inc.php');
require('function.inc.php');

if (!$_SESSION['ADMIN_LOGIN']) {
    if (!isset($_SESSION['USER_ID'])) {
        die();
    }
}

$order_id = get_safe_value($con, $_GET['id']);
$coupon_details = mysqli_fetch_assoc(mysqli_query($con, "select coupon_value,coupon_code from `order` where id='$order_id'"));
$coupon_value = $coupon_details['coupon_value'];
$coupon_code = $coupon_details['coupon_code'];

$stylesheet = file_get_contents('style.css');

$html .= '<div class="wishlist-table table-responsive">
    <table>
        <thead>
            <tr>
                <th class="product-remove">Product Name</th>
                <th class="product-name">Product Image</th>
                <th class="product-price">Qty</th>
                <th class="product-stock-stauts">Price</th>
                <th class="product-stock-stauts">Total Price</th>
            </tr>
        </thead>
        <tbody>';

if (isset($_SESSION['ADMIN_LOGIN'])) {
    $sql = "SELECT DISTINCT od.id, od.*, p.name,p.image FROM order_detail as od INNER JOIN `order` as o ON od.order_id=o.id INNER JOIN product as p ON od.product_id=p.id WHERE od.order_id='$order_id'";
} else {
    $uid = $_SESSION['USER_ID'];
    $sql = "SELECT DISTINCT od.id, od.*, p.name,p.image FROM order_detail as od INNER JOIN `order` as o ON od.order_id=o.id INNER JOIN product as p ON od.product_id=p.id WHERE od.order_id='$order_id' AND o.user_id='$uid'";
}

$res = mysqli_query($con, $sql);
$total_price = 0;

if (mysqli_num_rows($res) == 0) {
    die();
}
while ($row = mysqli_fetch_assoc($res)) {
    $total_price = $total_price + ($row['qty'] * $row['price']);
    $pp = $row['qty'] * $row['price'];

    $html .= '<tr>
                <td class="product-add-to-cart">' . $row['name'] . '</td>
                <td class="product-name"><img src="' . PRODUCT_IMAGE_SITE_PATH . $row['image'] . '"></td>
                <td class="product-name">' . $row['qty'] . '</td>
                <td class="product-name">' . $row['price'] . '</td>
                <td class="product-name">' . $pp . '</td>
            </tr>';
}
if ($coupon_value != '') {
    $html .= '<tr>
        <td colspan="3"></td>
        <td class="product-name">Coupon Value  (Apply code - '.$coupon_code.')</td>
        <td class="product-name">'.$coupon_value.'</td>
    </tr>';
}
$html .= '<tr>
            <td colspan="3"></td>
            <td class="product-name">Total Price</td>
            <td class="product-name">' . $total_price - $coupon_value . '</td>
        </tr>';
$html .= '</tbody>
    </table>
</div>';

$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);
$file = time() . '.pdf';
$mpdf->Output($file, 'D');

<?php
// require('connection.inc.php');

function pr($arr)
{
    echo '<pre>';
    print_r($arr);
}

function prx($arr)
{
    echo '<pre>';
    print_r($arr);
    die();
}

function get_safe_value($con, $str)
{
    if ($str != '') {
        $str = trim($str);
        return strip_tags(mysqli_real_escape_string($con, $str));
    }
}

function productSoldQtyByProductId($con, $pid)
{
    $sql = "select sum(od.qty) as qty from order_detail as od inner join `order` as o on o.id=od.order_id where od.product_id=$pid and o.order_status!=4 and ((o.payment_type='payu' and o.payment_status='Success') or (o.payment_type='COD' and o.payment_status!=''))";
    $res = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($res);
    return $row['qty'];
}

function validShipRocketToken($con)
{
    date_default_timezone_set('Asia/Kolkata');
    $row = mysqli_fetch_assoc(mysqli_query($con, "select * from shiprocket_token"));
    $added_on = strtotime($row['added_on']);
    $current_time = strtotime(date('Y-m-d h:i:s'));
    $diff_time = $current_time - $added_on;
    // echo $diff_time;
    if ($diff_time > 86400) {
        $token = generateShipRocketTokena($con);
    } else {
        $token = $row['token'];
    }
    return $token;
}

function generateShipRocketTokena($con)
{
    date_default_timezone_set('Asia/Kolkata');
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://apiv2.shiprocket.in/v1/external/auth/login",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{\n    \"email\": \"SHIPROCKET_TOKEN_EMAIL\",\n    \"password\": \"SHIPROCKET_TOKEN_PASSWORD\"\n}",
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json"
        ),
    ));
    $SR_login_Response = curl_exec($curl);
    curl_close($curl);
    $SR_login_Response_out = json_decode($SR_login_Response);
    $token = $SR_login_Response_out->{'token'};

    $added_on = date('Y-m-d h:i:s');
    mysqli_query($con, "update shiprocket_token set token='$token',added_on='$added_on' where id=1");

    return $token;
}

function placeShipRocketOrder($con, $token, $order_id)
{
    $row_order = mysqli_fetch_assoc(mysqli_query($con, "select o.*, u.name,u.email,u.mobile from `order` as o inner join users as u on o.user_id=u.id where o.id='$order_id'"));
    $order_date = $row_order['added_on'];
    $name = $row_order['name'];
    $email = $row_order['email'];
    $mobile = $row_order['mobile'];
    $address = $row_order['address'];
    $pincode = $row_order['pincode'];
    $city = $row_order['city'];
    $length = $row_order['length'];
    $breadth = $row_order['breadth'];
    $height = $row_order['height'];
    $weight = $row_order['weight'];
    $payment_type = $row_order['payment_type'];
    if ($payment_type == 'payu') {
        $payment_type = 'Prepaid';
    }
    $total_price = $row_order['total_price'];

    $res = mysqli_query($con, "select od.*, p.name from order_detail as od inner join product as p on p.id=od.product_id where od.order_id='$order_id'");
    $html = '';
    while ($row = mysqli_fetch_assoc($res)) {
        $html .= '{
            "name": "' . $row['name'] . '",
            "sku": "999",
            "units": ' . $row['qty'] . ',
            "selling_price": "' . $row['price'] . '",
            "discount": "",
            "tax": "",
            "hsn": ""
            }';
    }
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://apiv2.shiprocket.in/v1/external/orders/create/adhoc",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => '{"order_id": "' . $order_id . '",
            "order_date": "' . $order_date . '",
            "pickup_location": "Ashram",
            "billing_customer_name": "' . $name . '",
            "billing_last_name": "",
            "billing_address": "' . $address . '",
            "billing_address_2": "",
            "billing_city": "' . $city . '",
            "billing_pincode": "' . $pincode . '",
            "billing_state": "Delhi",
            "billing_country": "India",
            "billing_email": "' . $email . '",
            "billing_phone": "' . $mobile . '",
            "shipping_is_billing": true,
            "shipping_customer_name": "",
            "shipping_last_name": "",
            "shipping_address": "",
            "shipping_address_2": "",
            "shipping_city": "",
            "shipping_pincode": "",
            "shipping_country": "",
            "shipping_state": "",
            "shipping_email": "",
            "shipping_phone": "",
            "order_items": [
                ' . $html . '
            ],
            "payment_method": "' . $payment_type . '",
            "shipping_charges": 0,
            "giftwrap_charges": 0,
            "transaction_charges": 0,
            "total_discount": 0,
            "sub_total": "' . $total_price . '",
            "length": "' . $length . '",
            "breadth": "' . $breadth . '",
            "height": "' . $height . '",
            "weight": "' . $weight . '"
              }',
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "Authorization: Bearer $token"
        ),
    ));
    $SR_login_Response = curl_exec($curl);
    curl_close($curl);
    $SR_login_Response_out = json_decode($SR_login_Response);
    $ship_order_id = $SR_login_Response_out->order_id;
    $ship_shipment_id = $SR_login_Response_out->shipment_id;

    mysqli_query($con, "update `order` set ship_order_id='$ship_order_id', ship_shipment_id='$ship_shipment_id' where id='$order_id'");

    echo "Order id :- " . $ship_order_id . '<br>';
    echo "Shipment id :- " . $ship_shipment_id;

    // echo '<pre>';
    // print_r($SR_login_Response);
}

function cancelShipRocketOrder($token, $ship_order_id)
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://apiv2.shiprocket.in/v1/external/orders/cancel",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{\n  \"ids\": [".$ship_order_id."]\n}",
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "Authorization: Bearer $token"
        ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
}

function isAdmin(){
    if(!isset($_SESSION['ADMIN_LOGIN'])){
        ?>
        <script>
            window.location.href='login.php';
        </script>
        <?php
    }
    if($_SESSION['ADMIN_ROLE']==1){
        ?>
        <script>
            window.location.href='product.php';
        </script>
        <?php
    }
}

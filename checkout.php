<?php
require('top.php');

if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
?>
    <script>
        window.location.href = 'index.php';
    </script>
<?php }

$cart_total = '0';
if (isset($_POST['submit'])) {
    $address = get_safe_value($con, $_POST['address']);
    $city = get_safe_value($con, $_POST['city']);
    $pincode = get_safe_value($con, $_POST['pincode']);
    $payment_type = get_safe_value($con, $_POST['payment_type']);
    $user_id = $_SESSION['USER_ID'];
    foreach ($_SESSION['cart'] as $key => $val) {
        $productArr = get_product($con, '', '', $key);
        $price = $productArr[0]['price'];
        $qty = $val['qty'];
        $cart_total = $cart_total + ($price * $qty);
    }
    $total_price = $cart_total;
    $payment_status = "pending";
    if ($payment_type == 'COD') {
        $payment_status = "success";
    }
    $order_status = "1";
    $added_on = date('Y-m-d H:i:s');

    if (isset($_SESSION['COUPON_ID'])) {
        $coupon_id = $_SESSION['COUPON_ID'];
        $coupon_code = $_SESSION['COUPON_CODE'];
        $coupon_value = $_SESSION['COUPON_VALUE'];
        $total_price=$total_price-$coupon_value;
        unset($_SESSION['COUPON_ID']);
        unset($_SESSION['COUPON_CODE']);
        unset($_SESSION['COUPON_VALUE']);
    } else {
        $coupon_id = '';
        $coupon_code = '';
        $coupon_value = '';
    }

    mysqli_query($con, "insert into `order`(user_id,address,city,pincode,payment_type,total_price,payment_status,order_status,added_on,coupon_id,coupon_code,coupon_value)values('$user_id','$address','$city','$pincode','$payment_type','$total_price','$payment_status','$order_status','$added_on','$coupon_id','$coupon_code','$coupon_value')");

    $order_id = mysqli_insert_id($con);

    foreach ($_SESSION['cart'] as $key => $val) {
        $productArr = get_product($con, '', '', $key);
        $price = $productArr[0]['price'];
        $qty = $val['qty'];

        mysqli_query($con, "insert into `order_detail`(order_id,product_id,qty,price)values('$order_id','$key','$qty','$price')");
    }
    unset($_SESSION['cart']);
    sentInvoice($con,$order_id);
?>
    <script>
        window.location.href = 'thank_you.php';
    </script>
<?php }
?>
<div class="offset__wrapper">
    <!-- Start Search Popap -->
    <div class="search__area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="search__inner">
                        <form action="#" method="get">
                            <input placeholder="Search here... " type="text">
                            <button type="submit"></button>
                        </form>
                        <div class="search__close__btn">
                            <span class="search__close__btn_icon"><i class="zmdi zmdi-close"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Search Popap -->
</div>
<!-- End Offset Wrapper -->
<!-- Start Bradcaump area -->
<div class="ht__bradcaump__area" style="background: rgba(0, 0, 0, 0) url(images/bg/4.jpg) no-repeat scroll center center / cover ;">
    <div class="ht__bradcaump__wrap">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="bradcaump__inner">
                        <nav class="bradcaump-inner">
                            <a class="breadcrumb-item" href="index.html">Home</a>
                            <span class="brd-separetor"><i class="zmdi zmdi-chevron-right"></i></span>
                            <span class="breadcrumb-item active">checkout</span>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Bradcaump area -->
<!-- cart-main-area start -->
<div class="checkout-wrap ptb--100">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="checkout__inner">
                    <div class="accordion-list">
                        <div class="accordion">
                            <?php
                            $accordion_class = 'accordion__title';
                            if (!isset($_SESSION['USER_LOGIN'])) {
                                $accordion_class = 'accordion__hide';
                            ?>
                                <div class="accordion__title">
                                    Checkout Method
                                </div>
                                <div class="accordion__body">
                                    <div class="accordion__body__form">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="checkout-method__login">
                                                    <form id="login-form" action="#" method="post">
                                                        <h5 class="checkout-method__title">Login</h5>
                                                        <div class="single-input">
                                                            <label for="user-email">Email Address</label>
                                                            <input type="email" name="login_email" id="login_email" placeholder="Your Email*" style="width:100%">
                                                            <span class="field_error" id="login_email_error"></span>
                                                        </div>
                                                        <div class="single-input">
                                                            <label for="user-pass">Password</label>
                                                            <input type="password" name="login_password" id="login_password" placeholder="Your Password*" style="width:100%">
                                                            <span class="field_error" id="login_password_error"></span>
                                                        </div>
                                                        <p class="require">* Required fields</p>
                                                        <div class="dark-btn">
                                                            <button type="button" onclick="user_login()" class="fv-btn">Login</button>
                                                        </div>
                                                        <div class="form-output login_msg">
                                                            <p class="form-messege field_error"></p>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="checkout-method__login">
                                                    <form action="#" method="post">
                                                        <h5 class="checkout-method__title">Register</h5>
                                                        <div class="single-input">
                                                            <label for="user-email">Name</label>
                                                            <input type="text" name="name" id="name" placeholder="Your Name*" style="width:100%">
                                                            <span class="field_error" id="name_error"></span>
                                                        </div>
                                                        <div class="single-input">
                                                            <label for="user-email">Email Address</label>
                                                            <input type="text" name="email" id="email" placeholder="Your Email*" style="width:100%">
                                                            <span class="field_error" id="email_error"></span>
                                                        </div>
                                                        <div class="single-input">
                                                            <label for="user-email">Mobile</label>
                                                            <input type="number" name="mobile" id="mobile" placeholder="Your Mobile*" style="width:100%">
                                                            <span class="field_error" id="mobile_error"></span>
                                                        </div>
                                                        <div class="single-input">
                                                            <label for="user-pass">Password</label>
                                                            <input type="password" name="password" id="password" placeholder="Your Password*" style="width:100%">
                                                            <span class="field_error" id="password_error"></span>
                                                        </div>
                                                        <div class="dark-btn">
                                                            <button type="button" class="fv-btn" onclick="user_register()">Register</button>
                                                        </div>
                                                        <div class="form-output register_msg">
                                                            <p class="form-messege field_error"></p>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="<?php echo $accordion_class ?>">
                                Address Information
                            </div>
                            <form method="post">
                                <div class="accordion__body">
                                    <div class="bilinfo">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="single-input">
                                                    <input type="text" name="address" placeholder="Street Address" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="single-input">
                                                    <input type="text" name="city" placeholder="City/State" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="single-input">
                                                    <input type="text" name="pincode" placeholder="Post code/ zip" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="<?php echo $accordion_class ?>">
                                    payment information
                                </div>
                                <div class="accordion__body">
                                    <div class="paymentinfo">
                                        <div class="single-method">
                                            COD <input type="radio" name="payment_type" value="COD" required>
                                            &nbsp;PayU <input type="radio" name="payment_type" value="payu" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="single-method">
                                    <input type="submit" class="buynow fv-btn" name="submit">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="order-details">
                    <h5 class="order-details__title">Your Order</h5>
                    <div class="order-details__item">
                        <?php
                        $cart_total = '0';
                        foreach ($_SESSION['cart'] as $key => $val) {
                            $productArr = get_product($con, '', '', $key);
                            $pname = $productArr[0]['name'];
                            $mrp = $productArr[0]['mrp'];
                            $price = $productArr[0]['price'];
                            $image = $productArr[0]['image'];
                            $qty = $val['qty'];
                            $cart_total = $cart_total + ($price * $qty);
                        ?>
                            <div class="single-item">
                                <div class="single-item__thumb">
                                    <img src="<?php echo PRODUCT_IMAGE_SITE_PATH . $image ?>">
                                </div>
                                <div class="single-item__content">
                                    <a href="#"><?php echo $pname ?></a>
                                    <span class="price"><?php echo $price * $qty ?></span>
                                </div>
                                <div class="single-item__remove">
                                    <a href="javascript:void(0)" onclick="manage_cart('<?php echo $key ?>','remove')"><i class="icon-trash icons"></i></a>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="ordre-details__total" style="display: none;" id="coupon_box">
                        <h5>Coupon Value</h5>
                        <span class="price" id="coupon_price"></span>
                    </div>
                    <div class="ordre-details__total">
                        <h5>Order total</h5>
                        <span class="price" id="order_total_price"><?php echo $cart_total ?></span>
                    </div>
                    <div class="ordre-details__total bilinfo">
                        <input type="textbox" id="coupon_str" class="coupon_style">
                        <input type="button" class="fv-btn" onclick="set_coupon()" name="submit" value="Apply Coupon">
                    </div>
                    <div id="coupon_result"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function set_coupon() {
        var coupon_str = jQuery('#coupon_str').val();
        if (coupon_str != '') {
            jQuery('#coupon_result').html('');
            jQuery.ajax({
                url: 'set_coupon.php',
                type: 'post',
                data: {
                    coupon_str: coupon_str
                },
                success: function(result) {
                    var data = jQuery.parseJSON(result);
                    if (data.is_error == 'yes') {
                        jQuery('#coupon_box').hide();
                        jQuery('#coupon_result').html(data.dd);
                        jQuery('#order_total_price').html(data.result);
                    }
                    if (data.is_error == 'no') {
                        jQuery('#coupon_box').show();
                        jQuery('#coupon_price').html(data.dd);
                        jQuery('#order_total_price').html(data.result);
                    }
                    console.log(data.is_error);
                }
            });
        }
    }
</script>
<?php
if (isset($_SESSION['COUPON_ID'])) {
    unset($_SESSION['COUPON_ID']);
    unset($_SESSION['COUPON_CODE']);
    unset($_SESSION['COUPON_VALUE']);
}
require('footer.php'); ?>
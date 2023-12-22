<?php
require('connection.inc.php');
require('function.inc.php');

$login_email = get_safe_value($con, $_POST['login_email']);
$login_password = get_safe_value($con, $_POST['login_password']);

$res = mysqli_query($con, "select * from users where email='$login_email' and password='$login_password'");
$check_user = mysqli_num_rows($res);
if ($check_user > 0) {
    $row = mysqli_fetch_assoc($res);
    $_SESSION['USER_LOGIN'] = 'yes';
    $_SESSION['USER_ID'] = $row['id'];
    $_SESSION['USER_NAME'] = $row['name'];

    if (isset($_SESSION['WISHLIST_ID']) && $_SESSION['WISHLIST_ID'] != '') {
        wishlist_add($con, $_SESSION['USER_ID'], $_SESSION['WISHLIST_ID'], $date_time);
        unset($_SESSION['WISHLIST_ID']);
    }
    echo "valid";
} else {
    echo "wrong";
}

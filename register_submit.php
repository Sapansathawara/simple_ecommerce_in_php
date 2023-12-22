<?php
require('connection.inc.php');
require('function.inc.php');

$name = get_safe_value($con, $_POST['name']);
$email = get_safe_value($con, $_POST['email']);
$mobile = get_safe_value($con, $_POST['mobile']);
$password = get_safe_value($con, $_POST['password']);
$added_on = date('Y-m-d h:i:s');

$check_user = mysqli_num_rows(mysqli_query($con, "select * from users where email='$email'"));
$check_mobile = mysqli_num_rows(mysqli_query($con, "select * from users where mobile='$mobile'"));

if ($check_user > 0) {
    echo "email_present";
} else if ($check_mobile > 0) {
    echo "mobile_present";
} else {
    mysqli_query($con, "insert into users(name,email,mobile,password,added_on)values('$name','$email','$mobile','$password','$added_on')");
    echo "insert";
}

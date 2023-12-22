<?php
require('connection.inc.php');
require('function.inc.php');

$type = get_safe_value($con, $_POST['type']);

if ($type == 'email') {
    $email = get_safe_value($con, $_POST['email']);
    $check_user = mysqli_num_rows(mysqli_query($con, "select * from users where email='$email'"));
    if ($check_user > 0) {
        echo "email_present";
        die();
    }

    $otp = rand(1111, 9999);
    $_SESSION['EMAIL_OTP'] = $otp;
    $html = "$otp is your otp";

    include('smtp/PHPMailerAutoload.php');
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = "smtp.gmail.com";
    $mail->Port = 587;
    // $mail->SMTPDebug = 2;
    $mail->SMTPSecure = "tls";
    $mail->SMTPAuth = true;
    $mail->Username = SMTP_EMAIL;
    $mail->Password = SMTP_PASSWORD;
    $mail->SetFrom(SMTP_EMAIL);
    $mail->addAddress($email);
    $mail->IsHTML(true);
    $mail->Subject = "New OTP";
    $mail->Body = $html;
    $mail->SMTPOptions = array('ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => false
    ));
    if ($mail->send()) {
        echo "done";
    } else {
        // echo "Error occur";
    }
}
// mobile OTP verification code (Only getting normal message as mention below in code, but not getting otp)
if ($type == 'mobile') {
    $mobile = get_safe_value($con, $_POST['mobile']);
    $check_mobile = mysqli_num_rows(mysqli_query($con, "select * from users where mobile='$mobile'"));
    if ($check_mobile > 0) {
        echo "mobile_present";
        die();
    }
    $otp = rand(1111, 9999);
    $_SESSION['MOBILE_OTP'] = $otp;
    $message = "$otp is your otp";

    $mobile = '91' . $mobile;
    $apiKey = urlencode('NDEzMzZlNmI0NDUyNTE0NjU2NTk0YzYxNzQ3MzMzMzg=');
    $numbers = array($mobile);
    $sender = urlencode('600010');
    $message = rawurlencode('Hi there, thank you for sending your first test message from Textlocal. See how you can send effective SMS campaigns here: https://tx.gl/r/2nGVj/');
    $numbers = implode(',', $numbers);
    $data = array('apikey' => $apiKey, 'numbers' => $numbers, "sender" => $sender, "message" => $message);
    $ch = curl_init('https://api.textlocal.in/send/');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    echo "done";
}

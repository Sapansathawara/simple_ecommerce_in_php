<?php
require('top.inc.php');

isAdmin();
$username = $password = $email = $mobile = '';
$msg = '';
if (isset($_GET['id']) && $_GET['id'] != '') {
    $id = get_safe_value($con, $_GET['id']);
    $res = mysqli_query($con, "select * from admin_users where id='$id'");
    $check = mysqli_num_rows($res);
    if ($check > 0) {
        $row = mysqli_fetch_assoc($res);
        $username = $row['username'];
        $password = $row['password'];
        $email = $row['email'];
        $mobile = $row['mobile'];
    } else {
        header('location:vendor_management.php');
        die();
    }
}

if (isset($_POST['submit'])) {
    $username = get_safe_value($con, $_POST['username']);
    $password = get_safe_value($con, $_POST['password']);
    $email = get_safe_value($con, $_POST['email']);
    $mobile = get_safe_value($con, $_POST['mobile']);

    $res = mysqli_query($con, "select * from admin_users where username='$username'");
    $check = mysqli_num_rows($res);

    if ($check > 0) {
        if (isset($_GET['id']) && $_GET['id'] != '') {
            $getData = mysqli_fetch_assoc($res);
            if ($id == $getData['id']) {
                // blank
            } else {
                $msg = "Username already exists";
            }
        } else {
            $msg = "Username already exist";
        }
    }

    if ($msg == '') {
        if (isset($_GET['id']) && $_GET['id'] != '') {
            $update_sql = "update admin_users set username='$username',password='$password',email='$email',mobile='$mobile' where id='$id'";
            mysqli_query($con, $update_sql);
        } else {
            mysqli_query($con, "insert into admin_users(username,password,email,mobile,role,status)values('$username','$password','$email','$mobile',1,1)");
        }
        header('location:vendor_management.php');
        die();
    }
}

?>
<div class="content pb-0">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <form method="post" enctype="multipart/form-data">
                        <div class="card-header"><strong>Vendor Management</strong><small> Form</small></div>
                        <div class="card-body card-block">
                            <div class="form-group">
                                <label for="name" class="form-control-label">Username</label>
                                <input type="text" name="username" value="<?php echo $username ?>" placeholder="Enter username" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="name" class="form-control-label">Password</label>
                                <input type="text" name="password" value="<?php echo $password ?>" placeholder="Enter password" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="name" class="form-control-label">Email</label>
                                <input type="email" name="email" value="<?php echo $email ?>" placeholder="Enter email" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="name" class="form-control-label">Mobile</label>
                                <input type="text" name="mobile" value="<?php echo $mobile ?>" placeholder="Enter mobile" class="form-control" required>
                            </div>
                            <button id="payment-button" type="submit" name="submit" class="btn btn-lg btn-info btn-block">
                                <span id="payment-button-amount">Submit</span>
                            </button>
                            <div class="field_error"><?php echo $msg ?></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require('footer.inc.php') ?>
function submit_message() {
    var name = jQuery("#name").val();
    var email = jQuery("#email").val();
    var mobile = jQuery("#mobile").val();
    var comment = jQuery("#comment").val();

    if (name == '') {
        alert('Please enter name');
    } else if (email == '') {
        alert('Please enter email');
    } else if (mobile == '') {
        alert('Please enter mobile');
    } else if (comment == '') {
        alert('Please enter comment');
    } else {
        jQuery.ajax({
            url: 'send_message.php',
            type: 'post',
            data: {
                name: name,
                email: email,
                mobile: mobile,
                comment: comment
            }, success: function (response) {
                // alert(response);
                jQuery("#flash-message").html("Message sent successfully.").fadeIn();

                // Hide the success message after 3 seconds (3000 milliseconds)
                setTimeout(function () {
                    jQuery("#flash-message").fadeOut();
                }, 3000);
            }
        });
    }
}

function user_register() {
    jQuery('.field_error').html('');
    var name = jQuery("#name").val();
    var email = jQuery("#email").val();
    var mobile = jQuery("#mobile").val();
    var password = jQuery("#password").val();
    var is_error = '';
    if (name == '') {
        jQuery('#name_error').html('Please enter name');
        is_error = 'yes';
    } else if (email == '') {
        jQuery('#email_error').html('Please enter email');
        is_error = 'yes';
    } else if (mobile == '') {
        jQuery('#mobile_error').html('Please enter mobile');
        is_error = 'yes';
    } else if (password == '') {
        jQuery('#password_error').html('Please enter password');
        is_error = 'yes';
    } else if (is_error == '') {
        jQuery.ajax({
            url: 'register_submit.php',
            type: 'post',
            data: {
                name: name,
                email: email,
                mobile: mobile,
                password: password
            }, success: function (result) {
                // alert(response);
                result = result.trim();
                if (result == 'email_present') {
                    $msg = jQuery("#email_error").html("Email id already present").fadeIn();
                }
                if (result == 'mobile_present') {
                    $msg = jQuery("#mobile_error").html("Mobile number already present").fadeIn();
                }
                if (result == 'insert') {
                    $msg = jQuery(".register_msg p").html("Thank you for registration").fadeIn();
                }
                // Hide the success message after 3 seconds (3000 milliseconds)
                setTimeout(function () {
                    $msg.fadeOut();
                }, 5000);
            }
        });
    }
}

function user_login() {
    jQuery('.field_error').html('');
    var login_email = jQuery("#login_email").val();
    var login_password = jQuery("#login_password").val();
    var is_error = '';
    if (login_email == '') {
        jQuery('#login_email_error').html('Please enter email');
        is_error = 'yes';
    } else if (login_password == '') {
        jQuery('#login_password_error').html('Please enter password');
        is_error = 'yes';
    } else if (is_error == '') {
        jQuery.ajax({
            url: 'login_submit.php',
            type: 'post',
            data: {
                login_email: login_email,
                login_password: login_password
            }, success: function (result) {
                // alert(response);
                result = result.trim();
                if (result == 'wrong') {
                    // console.log(result);
                    $msg = jQuery(".login_msg p").html("Please enter valid login details").fadeIn();
                }
                if (result == 'valid') {
                    window.location.href = window.location.href;
                }
                // Hide the success message after 3 seconds (3000 milliseconds)
                setTimeout(function () {
                    $msg.fadeOut();
                }, 5000);
            }
        });
    }
}

function manage_cart(pid, type, is_checkout) {
    if (type == 'update') {
        var qty = jQuery("#" + pid + "qty").val();
    } else {
        var qty = jQuery("#qty").val();
    }
    jQuery.ajax({
        url: 'manage_cart.php',
        type: 'post',
        data: {
            pid: pid,
            qty: qty,
            type: type
        }, success: function (result) {
            result = result.trim();
            if (type == 'update' || type == 'remove') {
                window.location.href = window.location.href;
            }
            if (result == 'not_available') {
                alert('Qty not available');
            } else {
                jQuery('.htc__qua').html(result);
                if (is_checkout == 'yes') {
                    window.location.href = 'checkout.php';
                }
            }
        }
    });
}

function sort_product_drop(cat_id, site_path) {
    var sort_product_id = jQuery('#sort_product_id').val();
    // alert(cat_id);
    window.location.href = site_path + "categories.php?id=" + cat_id + "&sort=" + sort_product_id;
}

function wishlist_manage(pid, type) {
    jQuery.ajax({
        url: 'wishlist_manage.php',
        type: 'post',
        data: {
            pid: pid,
            type: type
        }, success: function (result) {
            result = result.trim();
            if (result == 'not_login') {
                window.location.href = 'login.php';
            } else {
                jQuery('.htc__wishlist').html(result);
            }
        }
    });
}

jQuery('.imageZoom').imgZoom();
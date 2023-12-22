<?php
ob_start();
require('top.php');
$product_id = mysqli_real_escape_string($con, $_GET['id']);
if ($product_id > 0) {
    $get_product = get_product($con, '', '', $product_id);
} else {
?>
    <script>
        window.location.href = 'index.php';
    </script>
<?php }

$resMultipleImages = mysqli_query($con, "select * from product_images where product_id='$product_id'");
$multipleImages = [];
if (mysqli_num_rows($resMultipleImages) > 0) {
    while ($rowMultipleImages = mysqli_fetch_assoc($resMultipleImages)) {
        $multipleImages[] = $rowMultipleImages['product_images'];
    }
}
?>
<!-- Start Bradcaump area -->
<div class="ht__bradcaump__area" style="background: rgba(0, 0, 0, 0) url(images/bg/4.jpg) no-repeat scroll center center / cover ;">
    <div class="ht__bradcaump__wrap">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="bradcaump__inner">
                        <nav class="bradcaump-inner">
                            <a class="breadcrumb-item" href="index.php">Home</a>
                            <span class="brd-separetor"><i class="zmdi zmdi-chevron-right"></i></span>
                            <a class="breadcrumb-item" href="categories.php?id=<?php echo $get_product[0]['categories_id'] ?>"><?php echo $get_product[0]['category_name'] ?></a>
                            <span class="brd-separetor"><i class="zmdi zmdi-chevron-right"></i></span>
                            <span class="breadcrumb-item active"><?php echo $get_product[0]['name'] ?></span>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Bradcaump area -->
<!-- Start Product Details Area -->
<section class="htc__product__details bg__white ptb--100">
    <!-- Start Product Details Top -->
    <div class="htc__product__details__top">
        <div class="container">
            <div class="row">
                <div class="col-md-5 col-lg-5 col-sm-12 col-xs-12">
                    <div class="htc__product__details__tab__content">
                        <!-- Start Product Big Images -->
                        <div class="product__big__images">
                            <div class="portfolio-full-image tab-content">
                                <div role="tabpanel" class="tab-pane fade in active imageZoom" id="img-tab-1">
                                    <img data-origin="<?php echo PRODUCT_IMAGE_SITE_PATH . $get_product[0]['image'] ?>" src="<?php echo PRODUCT_IMAGE_SITE_PATH . $get_product[0]['image'] ?>" alt="full-image">
                                </div>
                                <?php if (isset($multipleImages[0])) { ?>
                                    <div id="multiple_images">
                                        <?php
                                        foreach ($multipleImages as $list) {
                                            echo "<img src='" . PRODUCT_MULTIPLE_IMAGE_SITE_PATH . $list . "' onclick=showMultipleImage('" . PRODUCT_MULTIPLE_IMAGE_SITE_PATH . $list . "')>";
                                        }
                                        ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <!-- End Product Big Images -->
                    </div>
                </div>
                <div class="col-md-7 col-lg-7 col-sm-12 col-xs-12 smt-40 xmt-40">
                    <div class="ht__product__dtl">
                        <h2><?php echo $get_product[0]['name'] ?></h2>
                        <ul class="pro__prize">
                        <li><?php echo $get_product[0]['price'] ?></li>&nbsp;&nbsp;
                        <del><li class="old__prize"><?php echo $get_product[0]['mrp'] ?></del></li>
                        </ul>
                        <p class="pro__info"><?php echo $get_product[0]['short_desc'] ?></p>
                        <div class="ht__pro__desc">
                            <div class="sin__desc">
                                <?php
                                $productSoldQtyByProductId = productSoldQtyByProductId($con, $get_product[0]['id']);

                                $pending_qty = $get_product[0]['qty'] - $productSoldQtyByProductId;

                                $cart_show = 'yes';
                                if ($get_product[0]['qty'] > $productSoldQtyByProductId) {
                                    $stock = 'In Stock';
                                } else {
                                    $stock = 'Not in Stock';
                                    $cart_show = '';
                                }
                                ?>
                                <p><span>Availability:</span> <?php echo $stock ?></p>
                            </div>
                            <?php
                            if ($cart_show != '') {
                            ?>
                                <div class="sin__desc">
                                    <p><span>Qty:</span>
                                        <select id="qty">
                                            <?php
                                            for ($i = 1; $i <= $pending_qty; $i++) {
                                                echo "<option>$i</option>";
                                            }
                                            ?>
                                        </select>
                                    </p>
                                </div>
                            <?php } ?>
                            <div class="sin__desc align--left">
                                <p><span>Categories:</span></p>
                                <ul class="pro__cat__list">
                                    <li><a href="#"><?php echo $get_product[0]['category_name'] ?></a></li>
                                </ul>
                            </div>
                            <?php
                            if ($cart_show != '') {
                            ?>
                                <div class="send__btn">
                                    <a class="fr__btn" href="javascript:void(0)" onclick="manage_cart('<?php echo $get_product[0]['id'] ?>','add')">Add to cart</a>
                                    <a class="fr__btn buy_now" href="javascript:void(0)" onclick="manage_cart('<?php echo $get_product[0]['id'] ?>','add','yes')">Buy Now</a>
                                </div>
                            <?php } ?>
                            <div id="social_share_box">
                                <a target="_blank" href="https://facebook.com/share.php?u=<?php echo $meta_url ?>"><img src="images/icons/facebook.png"></a>
                                <a target="_blank" href="https://twitter.com/share?text=<?php echo $get_product[0]['name'] ?>&url=<?php echo $meta_url ?>"><img src="images/icons/twitter.png"></a>
                                <a target="_blank" href="https://api.whatsapp.com/send?text=<?php echo urlencode($get_product[0]['name']) ?> <?php echo $meta_url ?>"><img src="images/icons/whatsapp.png"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- End Product Details Top -->
</section>
<!-- End Product Details Area -->
<!-- Start Product Description -->
<section class="htc__produc__decription bg__white">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <!-- Start List And Grid View -->
                <ul class="pro__details__tab" role="tablist">
                    <li role="presentation" class="description active"><a href="#description" role="tab" data-toggle="tab">description</a></li>
                </ul>
                <!-- End List And Grid View -->
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="ht__pro__details__content">
                    <!-- Start Single Content -->
                    <div role="tabpanel" id="description" class="pro__single__content tab-pane fade in active">
                        <div class="pro__tab__content__inner">
                            <p><?php echo $get_product[0]['description'] ?></p>
                        </div>
                    </div>
                    <!-- End Single Content -->

                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Product Description -->
<!-- Start Recently view -->
<?php
// unset($_COOKIE['recently_viewed']);
if (isset($_COOKIE['recently_viewed'])) {
    $arrRecentView = unserialize($_COOKIE['recently_viewed']);
    $countRecentView = count($arrRecentView);
    $countStartRecentView = $countRecentView - 4;

    if ($countRecentView > 4) {
        $arrRecentView = array_slice($arrRecentView, $countStartRecentView, 4);
    }
    $recentViewId = implode(",", $arrRecentView);
    $res = mysqli_query($con, "SELECT * FROM product WHERE id IN ($recentViewId)");
?>
    <section class="htc__produc__decription bg__white">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 title__line--6">
                    <h2 style="font-size: 25px;font-weight: 500;">Recently Viewed</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="ht__pro__details__content">
                        <div class="row">
                            <?php while ($list = mysqli_fetch_assoc($res)) { ?>
                                <div class="col-xs-3">
                                    <div class="category">
                                        <div class="ht__cat__thumb">
                                            <a href="product.php?id=<?php echo $list['id'] ?>">
                                                <img src="<?php echo PRODUCT_IMAGE_SITE_PATH . $list['image'] ?>" alt="product images">
                                            </a>
                                        </div>
                                        <div class="fr__hover__info">
                                            <ul class="product__action">
                                                <li><a href="javascript:void(0)" onclick="wishlist_manage('<?php echo $list['id'] ?>','add')"><i class="icon-heart icons"></i></a></li>
                                                <li><a href="javascript:void(0)" onclick="manage_cart('<?php echo $list['id'] ?>','add')"><i class="icon-handbag icons"></i></a></li>
                                            </ul>
                                        </div>
                                        <div class="fr__product__inner">
                                            <h4><a href="product-details.html"><?php echo $list['name'] ?></a></h4>
                                            <ul class="fr__pro__prize">
                                                <li><?php echo $list['price'] ?></li>&nbsp;&nbsp;
                                                <del><li class="old__prize"><?php echo $list['mrp'] ?></del></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php
    $arrRec = unserialize($_COOKIE['recently_viewed']);
    if (($key = array_search($product_id, $arrRec)) !== false) {
        unset($arrRec[$key]);
    }
    $arrRec[] = $product_id;
    setcookie('recently_viewed', serialize($arrRec), time() + 60 * 60 * 24 * 365);
} else {
    $arrRec[] = $product_id;
    setcookie('recently_viewed', serialize($arrRec), time() + 60 * 60 * 24 * 365);
}
?>
<script>
    function showMultipleImage(im) {
        jQuery('#img-tab-1').html("<img src='" + im + "' data-origin='" + im + "'>");
        jQuery('.imageZoom').imgZoom();
    }
</script>
<?php
require('footer.php');
ob_flush();
?>
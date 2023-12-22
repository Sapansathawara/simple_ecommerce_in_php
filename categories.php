<?php
require('top.php');
if (!isset($_GET['id']) && $_GET['id'] != '') {
?>
    <script>
        window.location.href = 'index.php';
    </script>
<?php }
$cat_id = get_safe_value($con, $_GET['id']);

$sub_categories = '';
if (isset($_GET['sub_categories'])) {
    $sub_categories = get_safe_value($con, $_GET['sub_categories']);
}
$sort_order = "";
$price_high_selected = "";
$price_low_selected = "";
$new_selected = "";
$old_selected = "";

if (isset($_GET['sort'])) {
    $sort = get_safe_value($con, $_GET['sort']);
    if ($sort == "price_low") {
        $sort_order = " order by p.price asc ";
        $price_high_selected = "selected";
    }
    if ($sort == "price_high") {
        $sort_order = " order by p.price desc ";
        $price_low_selected = "selected";
    }
    if ($sort == "new") {
        $sort_order = " order by p.id desc ";
        $new_selected = "selected";
    }
    if ($sort == "old") {
        $sort_order = " order by p.id asc ";
        $old_selected = "selected";
    }
}

if ($cat_id > 0) {
    $get_categories = get_product($con, '', $cat_id, '', '', $sort_order, '', $sub_categories);
} else {
?>
    <script>
        window.location.href = 'index.php';
    </script>
<?php }
?>
<div class="ht__bradcaump__area" style="background: rgba(0, 0, 0, 0) url(images/bg/4.jpg) no-repeat scroll center center / cover ;">
    <div class="ht__bradcaump__wrap">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="bradcaump__inner">
                        <nav class="bradcaump-inner">
                            <a class="breadcrumb-item" href="index.php">Home</a>
                            <span class="brd-separetor"><i class="zmdi zmdi-chevron-right"></i></span>
                            <span class="breadcrumb-item active">Products</span>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Bradcaump area -->
<!-- Start Product Grid -->
<section class="htc__product__grid bg__white ptb--100">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="htc__product__rightidebar">
                    <div class="htc__grid__top">
                        <div class="htc__select__option">
                            <select class="ht__select" onchange="sort_product_drop('<?php echo $cat_id ?>','<?php echo SITE_PATH ?>')" id="sort_product_id">
                                <option value="">Default softing</option>
                                <option value="price_low" <?php echo $price_high_selected ?>>Sort by price low to high</option>
                                <option value="price_high" <?php echo $price_low_selected ?>>Sort by price high to low</option>
                                <option value="new" <?php echo $new_selected ?>>Sort by new first</option>
                                <option value="old" <?php echo $old_selected ?>>Sort by old first</option>
                            </select>
                        </div>
                    </div>
                    <!-- Start Product View -->
                    <div class="row">
                        <?php if (count($get_categories) > 0) { ?>
                            <div class="shop__grid__view__wrap">
                                <div role="tabpanel" id="grid-view" class="single-grid-view tab-pane fade in active clearfix">
                                    <?php
                                    foreach ($get_categories as $list) {
                                    ?>
                                        <div class="col-md-4 col-lg-3 col-sm-4 col-xs-12">
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
                        <?php } else { ?>
                            <div class="section__title--2">
                                <p>Data not found</p>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Product Grid -->
<input type="hidden" id="qty" value="1" />
<?php require('footer.php'); ?>
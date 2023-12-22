<?php require('top.php');

$resBanner = mysqli_query($con, "select * from banner where status='1' order by order_no asc");

?>
<!-- Start Slider Area -->
<?php if (mysqli_num_rows($resBanner) > 0) { ?>
    <div class="slider__container slider--one bg__cat--3">
        <div class="slide__container slider__activation__wrap owl-carousel">
            <?php while ($rowBanner = mysqli_fetch_assoc($resBanner)) { ?>
                <div class="single__slide animation__style01 slider__fixed--height">
                    <div class="container">
                        <div class="row align-items__center">
                            <div class="col-md-7 col-sm-7 col-xs-12 col-lg-6">
                                <div class="slide">
                                    <div class="slider__inner">
                                        <h2><?php echo $rowBanner['heading1'] ?></h2>
                                        <h1><?php echo $rowBanner['heading2'] ?></h1>
                                        <?php if ($rowBanner['btn_link'] != '' && $rowBanner['btn_txt'] != '') { ?>
                                            <div class="cr__btn">
                                                <a href="<?php echo $rowBanner['btn_link'] ?>" target="_blank"><?php echo $rowBanner['btn_txt'] ?></a>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-5 col-xs-12 col-md-5">
                                <div class="slide__thumb">
                                    <img src="<?php echo BANNER_SITE_PATH . $rowBanner['image'] ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
<?php } ?>
<!-- Start Slider Area -->
<!-- Start Category Area -->
<section class="htc__category__area ptb--100">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="section__title--2 text-center">
                    <h2 class="title__line">New Arrivals</h2>
                    <p>Explore our latest arrivals in Men's and Women's fashion, plus cutting-edge gadgets in Electronics. Stay ahead with our freshest additions!</p>
                </div>
            </div>
        </div>
        <div class="htc__product__container">
            <div class="row">
                <div class="product__list clearfix mt--30">
                    <?php
                    $get_product = get_product($con, 4);
                    foreach ($get_product as $list) {
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
                                    <h4><a href="product.php?id=<?php echo $list['id'] ?>"><?php echo $list['name'] ?></a></h4>
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
</section>
<!-- End Category Area -->
<!-- Start Product Area -->
<section class="ftr__product__area ptb--100">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="section__title--2 text-center">
                    <h2 class="title__line">Best Seller</h2>
                    <p>Discover our top picks - the best sellers in fashion and electronics. Shop the favorites that everyone loves!</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="product__list clearfix mt--30">
                <?php
                $get_product = get_product($con, 4, '', '', '', '', 'yes');
                foreach ($get_product as $list) {
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
                                <h4><a href="product.php?id=<?php echo $list['id'] ?>"><?php echo $list['name'] ?></a></h4>
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
</section>
<!-- End Product Area -->
<input type="hidden" id="qty" value="1" />
<?php require('footer.php'); ?>
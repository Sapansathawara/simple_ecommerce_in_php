<div class="header__right">
    <?php
    $class = "mr15";
    if (isset($_SESSION['USER_LOGIN'])) {
        $class = "";
    }
    ?>
    <div class="header__search search search__open <?php echo $class ?>">
        <a href="#"><i class="icon-magnifier icons"></i></a>
    </div>
    <div class="header__account">
        <?php if (isset($_SESSION['USER_LOGIN'])) {
        ?>
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Hi <?php echo $_SESSION['USER_NAME'] ?>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="my_order.php">Order</a>
                                <a class="dropdown-item" href="profile.php">Profile</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="logout.php">Logout</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        <?php
        } else {
            echo '<a href="login.php" class="mr15">Login/Register</a>';
        }
        ?>
    </div>
    <div class="htc__shopping__cart">
        <?php if (isset($_SESSION['USER_ID'])) { ?>
            <a class="cart__menu" href="wishlist.php"><i class="icon-heart icons"></i></a>
            <a href="wishlist.php"><span class="htc__wishlist"><?php echo $wishlist_count ?></span></a>
        <?php } ?>
    </div>&nbsp&nbsp
    <div class="htc__shopping__cart">
        <a class="cart__menu" href="cart.php"><i class="icon-handbag icons"></i></a>
        <a href="cart.php"><span class="htc__qua"><?php echo $totalProduct ?></span></a>
    </div>
</div>
--------------------------------------------------

<div class="header__right">
    <div class="header__search search search__open mr15">
        <a href="#"><i class="icon-magnifier icons"></i></a>
    </div>
    <div class="header__account">
        <a href="login.php" class="mr15">Login/Register</a>
    </div>
    <div class="htc__shopping__cart">
    </div>&nbsp;&nbsp;
    <div class="htc__shopping__cart">
        <a class="cart__menu" href="cart.php"><i class="icon-handbag icons"></i></a>
        <a href="cart.php"><span class="htc__qua">0</span></a>
    </div>
</div>
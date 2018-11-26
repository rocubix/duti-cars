<?php
include "../../src/database.php";

const AVAILABILITY_UNAVAILABLE = 0;
const AVAILABILITY_AVAILABLE = 1;
const AVAILABILITY_RESERVED = 2;
const AVAILABILITY_SOLD = 3;

if ( isset($_GET['disable_id']) && $_GET['disable_id'] != "" ) {
    $sql = "UPDATE products SET availability = '".AVAILABILITY_UNAVAILABLE."' WHERE id = " . $_GET['disable_id'];
    $DB->query($sql);
}

$sql = $DB->query("SELECT * FROM products");
$products = $sql->fetch_all(MYSQLI_ASSOC);


?>
<html>
<head>
    <title>Admin</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/assets/css/admin/main.css">
    <!------ Include the above in your HEAD tag ---------->
    <style>
        #product_table tbody tr:hover {
            background-color: #ff25e9;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-default navbar-static-top">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle navbar-toggle-sidebar collapsed">
                MENU
            </button>
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/index.php">
                Administrator
            </a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <form class="navbar-form navbar-left" method="GET" role="search">
                <div class="form-group">
                    <input type="text" name="q" class="form-control" placeholder="Search">
                </div>
                <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i></button>
            </form>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="http://www.pingpong-labs.com" target="_blank">Visit Site</a></li>
                <li class="dropdown ">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        Account
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li class="dropdown-header">SETTINGS</li>
                        <li class=""><a href="#">Other Link</a></li>
                        <li class=""><a href="#">Other Link</a></li>
                        <li class=""><a href="#">Other Link</a></li>
                        <li class="divider"></li>
                        <li><a href="#">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
<div class="container-fluid main-container">
    <div class="col-xs-2 sidebar">
        <div class="row">
            <!-- uncomment code for absolute positioning tweek see top comment in css -->
            <div class="absolute-wrapper"></div>
            <!-- Menu -->
            <div class="side-menu">
                <nav class="navbar navbar-default" role="navigation">
                    <!-- Main Menu -->
                    <div class="side-menu-container">
                        <ul class="nav navbar-nav">
                            <li class="active"><a href="/admin/products/index.php"><span
                                            class="glyphicon glyphicon-dashboard"></span> Products</a>
                            </li>
                            <li><a href="/admin/products/index.php"><span class="glyphicon glyphicon-plane"></span>
                                    Images</a></li>
                            <li><a href="/admin/features/index.php"><span class="glyphicon glyphicon-cloud"></span>
                                    Features</a></li>
                            <li><a href="/admin/characteristics/index.php"><span
                                            class="glyphicon glyphicon-cloud"></span> Characteristics</a></li>
                            <li><a href="/admin/reservations/index.php"><span class="glyphicon glyphicon-cloud"></span>
                                    Reservations</a></li>
                            <!-- Dropdown-->
                            <li class="panel panel-default" id="dropdown">
                                <a data-toggle="collapse" href="#dropdown-lvl1">
                                    <span class="glyphicon glyphicon-user"></span> Sub Level <span class="caret"></span>
                                </a>

                                <!-- Dropdown level 1 -->
                                <div id="dropdown-lvl1" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <ul class="nav navbar-nav">
                                            <li><a href="#">Link</a></li>
                                            <li><a href="#">Link</a></li>
                                            <li><a href="#">Link</a></li>

                                            <!-- Dropdown level 2 -->
                                            <li class="panel panel-default" id="dropdown">
                                                <a data-toggle="collapse" href="#dropdown-lvl2">
                                                    <span class="glyphicon glyphicon-off"></span> Sub Level <span
                                                            class="caret"></span>
                                                </a>
                                                <div id="dropdown-lvl2" class="panel-collapse collapse">
                                                    <div class="panel-body">
                                                        <ul class="nav navbar-nav">
                                                            <li><a href="#">Link</a></li>
                                                            <li><a href="#">Link</a></li>
                                                            <li><a href="#">Link</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </li>

                            <li><a href="#"><span class="glyphicon glyphicon-signal"></span> Link</a></li>

                        </ul>
                    </div><!-- /.navbar-collapse -->
                </nav>

            </div>
        </div>
    </div>
    <div class="col-xs-10 content">
        <div class="panel panel-default">
            <div class="panel-heading">
                Products
                <a class="btn btn-primary btn-xs pull-right" href="/admin/products/products.php">Add a new product</a>
            </div>
            <div class="panel-body">
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                    tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                    quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                    consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                    cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                    proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                </p>

                <hr>

                <div id="product_table" class="table-responsive">
                    <table class="table active">
                        <thead>

                        <tr>
                            <th>ID</th>
                            <th>Product Code</th>
                            <th>Brand</th>
                            <th>Model</th>
                            <th>Price</th>
                            <th>Availability</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <?php
                        foreach ($products as $product) {

                            ?>
                            <tr>
                                <td><?= $product['id'] ?></td>
                                <td><?= $product['product_code'] ?></td>
                                <td><?= $product['brand'] ?></td>
                                <td><?= $product['model'] ?></td>
                                <td><?= $product['price'] ?></td>
                                <td>
                                    <?php
                                    switch ($product['availability']) {
                                        case AVAILABILITY_UNAVAILABLE:
                                            echo "unavailable";
                                            break;
                                        case AVAILABILITY_AVAILABLE:
                                            echo "available";
                                            break;
                                        case AVAILABILITY_RESERVED:
                                            echo "reserved";
                                            break;
                                        case AVAILABILITY_SOLD:
                                            echo "sold";
                                            break;
                                    }

                                    ?></td>
                                <td>
                                    <a class="btn-link" href="/admin/products/products.php?id=<?= $product['id'] ?>">Edit</a>
                                    <?php
                                    if($product['availability'] == AVAILABILITY_AVAILABLE){
                                        ?>
                                        <a class="btn btn-danger" href="?disable_id=<?= $product['id'] ?>">Disable</a>
                                    <?php
                                    }
                                    ?>

                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
<footer class="pull-left footer">
    <p class="col-md-12">
    <hr class="divider">
    Copyright &COPY; 2018 <a href="http://www.pingpong-labs.com">Gravitano</a>
    </p>
</footer>
</div>

<!--    scripts-->
<script>

    // $('#product_table tbody tr').hover(function (event) {
    //     if(event.type == "mouseenter"){
    //         $(this).addClass("active")
    //     }else{
    //         $(this).removeClass("active")
    //     }
    // });

    $(function () {
        $('.navbar-toggle-sidebar').click(function () {
            $('.navbar-nav').toggleClass('slide-in');
            $('.side-body').toggleClass('body-slide-in');
            $('#search').removeClass('in').addClass('collapse').slideUp(200);
        });

        $('#search-trigger').click(function () {
            $('.navbar-nav').removeClass('slide-in');
            $('.side-body').removeClass('body-slide-in');
            $('.search-input').focus();
        });
    });
</script>

</body>
</html>



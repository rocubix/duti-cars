<?php
include "../../src/database.php";

const AVAILABILITY_UNAVAILABLE = 0;
const AVAILABILITY_AVAILABLE = 1;
const AVAILABILITY_RESERVED = 2;
const AVAILABILITY_SOLD = 3;


//echo '<pre>';
//var_dump($_GET);
//echo '</pre>';

$form['data']['productCode'] = isset($_GET['productCode'])?$_GET['productCode']:'';
$form['data']['brand'] = isset($_GET['brand'])?$_GET['brand']:'';
$form['data']['model'] = isset($_GET['model'])?$_GET['model']:'';
$form['data']['price'] = isset($_GET['price'])?$_GET['price']:'';
$form['data']['availability'] = isset($_GET['availability'])?$_GET['availability']:'';
$form['data']['description'] = isset($_GET['description'])?$_GET['description']:'';



if (isset($_GET['id']) && $_GET['id'] != ''){

    $sql = "SELECT * FROM products WHERE id = ".$_GET['id'];
    $result = $DB->query($sql);

    $form['data'] = $result->fetch_assoc();
    $form['data']['productCode'] = $form['data']['product_code'];
    unset($form['data']['product_code']);

}



if(isset($_GET['save']) && $_GET['save'] == true){

    $form['data']['productCode'] = isset($_GET['productCode'])?$_GET['productCode']:'';
    $form['data']['brand'] = isset($_GET['brand'])?$_GET['brand']:'';
    $form['data']['model'] = isset($_GET['model'])?$_GET['model']:'';
    $form['data']['price'] = isset($_GET['price'])?$_GET['price']:'';
    $form['data']['availability'] = isset($_GET['availability'])?$_GET['availability']:'';
    $form['data']['description'] = isset($_GET['description'])?$_GET['description']:'';

    $form['success'] = false;
    $form['error'] = null;

    if(
        (isset($_GET['productCode']) && $_GET['productCode'] != '' ) &&
        (isset($_GET['brand']) && $_GET['brand'] != '' ) &&
        (isset($_GET['model']) && $_GET['model'] != '' ) &&
        (isset($_GET['price']) && $_GET['price'] != '' ) &&
        (isset($_GET['availability']) && $_GET['availability'] != '' )
    ){
        if(strlen($_GET['productCode']) == 8 ){
            if(is_float((float)$_GET['price']) && $_GET['price'] > 0){
                if (is_numeric($_GET['availability'])){
//                    stripping special characters
                    $form['data']['productCode'] = htmlspecialchars($form['data']['productCode'],ENT_QUOTES );
                    $form['data']['brand'] = htmlspecialchars($form['data']['brand'],ENT_QUOTES );
                    $form['data']['model'] = htmlspecialchars($form['data']['model'],ENT_QUOTES );
                    $form['data']['description'] = htmlspecialchars($form['data']['description'],ENT_QUOTES );
                    if (isset($form['data']['id']) && $form['data']['id'] != ''){
//                    updating database
                        $sql = "
                            UPDATE products
                            SET product_code = '".$form['data']['productCode']."',
                            brand = '".$form['data']['brand']."',
                            model = '".$form['data']['model']."',
                            price = '".$form['data']['price']."',
                            availability = '".$form['data']['availability']."',
                            description = '".$form['data']['description']."'
                            WHERE id = ".$form['data']['id'].";
                            
                            ";
                    }else{
//                    inserting into database
                        $sql = "INSERT INTO products VALUES (
                        null ,
                        '".$form['data']['productCode']."',
                        '".$form['data']['brand']."',
                        '".$form['data']['model']."',
                        '".str_replace(',','.',$form['data']['price'])."',
                        '".$form['data']['availability']."',
                        '".$form['data']['description']."',
                        null,
                        NOW()
                        );";
                    }
                    /** @var $DB mysqli */
                    if($DB->query($sql)){
                        if(!isset($form['data']['id'])){
                            $form['data']['id'] = $DB->insert_id;
                            $form['message'] = "Succesfuly insterted into db";
                        }else{
                            $form['message'] = "Succesfuly updated into db";
                        }
                        header('Location: /admin/products/products.php?id='.$form['data']['id']."&message=".$form['message']);
                        $form['success'] = true;
                    }else{
                        $form['error'][] = mysqli_error($DB);
                    }
                }else{
                    $form['error'][] = "the availability value is incorrect";
                }
            }else{
                $form['error'][] = "the price in incorrect";
            }
        }else{
            $form['error'][] = "the product code in incorrect";
        }
    }else{
        $form['error'][] = "all required values must be filled";
    }
}

//echo '<pre>';
//var_dump($form);
//echo '</pre>';

?>

<html>
<head>
    <title>Add/Edit Products</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/assets/css/admin/main.css">
    <!------ Include the above in your HEAD tag ---------->
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

        <?php
            if(isset($_GET['message']) && $_GET['message']){
                ?>
                <div class="alert alert-success" role="alert">
                    <span class="glyphicon glyphicon glyphicon-ok-circle" aria-hidden="true"></span>
                    <span class="sr-only">Error:</span>
                    <?= $_GET['message'] ?>
                </div>
        <?php
            }
            if(isset($form['error'])){
                foreach ($form['error'] as $error){
                    ?>
                    <div class="alert alert-danger" role="alert">
                        <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                        <span class="sr-only">Error:</span>
                        <?= $error ?>
                    </div>
                    <?php
                }
            }
        ?>

        <div class="panel panel-default">
            <div class="panel-heading">
                Add/Edit Products
            </div>
            <div class="panel-body">
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                    tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                    quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                    consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                    cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                    proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                </p>
                <form class="form-horizontal">
<!--                    <input type="hidden" name="characteristics[]">-->
<!--                    <input type="hidden" name="characteristics[]">-->
                    <?php
                    if(isset($form['data']['id']) && $form['data']['id'] !=''){
                        ?>
                        <input type="hidden" name="id" value="<?= $form['data']['id'] ?>">
                    <?php
                    }
                    ?>

                    <div class="form-group">
                        <label class="control-label col-sm-2" for="productCode">Product Code:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="productCode" placeholder="" name="productCode" value="<?= $form['data']['productCode'] ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-2" for="brand">Brand:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="brand" placeholder="" name="brand" value="<?= $form['data']['brand']?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-2" for="model">Model:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="model" placeholder="" name="model" value="<?= $form['data']['model']?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-2" for="price">Price:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="price" placeholder="" name="price" value="<?= $form['data']['price']?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-2" for="availability">Availability:</label>

                        <div class="col-sm-10">
                            <select class="form-control" id="availability" name="availability"  >
                                <option id="availability" value="<?= AVAILABILITY_UNAVAILABLE?>"<?=($form['data']['availability'] == AVAILABILITY_UNAVAILABLE)?' selected':'' ?>>Unavailable</option>
                                <option id="availability" value="<?= AVAILABILITY_AVAILABLE?>"<?=($form['data']['availability'] == AVAILABILITY_AVAILABLE)?' selected':'' ?>>Available</option>
                                <option id="availability" value="<?= AVAILABILITY_RESERVED?>"<?=($form['data']['availability'] == AVAILABILITY_RESERVED)?' selected':'' ?>>Reserved</option>
                                <option id="availability" value="<?= AVAILABILITY_SOLD?>"<?=($form['data']['availability'] == AVAILABILITY_SOLD)?' selected':'' ?>>Sold</option>
                            </select>
                               <!--<input type="text" class="form-control" id="availability" placeholder="" name="availability" value= --><?/*= $form['data']['availability']*/?>

                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-2" for="description">Description:</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" rows="5" id="description" name="description"><?= $form['data']['description']?></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-default" name="save" value="true">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <footer class="pull-left footer">
        <p class="col-md-12">
        <hr class="divider">
        Copyright &COPY; 2015 <a href="http://www.pingpong-labs.com">Gravitano</a>
        </p>
    </footer>
</div>

<!--    scripts-->
<script>
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




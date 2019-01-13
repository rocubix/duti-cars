<?php
include "../../src/database.php";


const AVAILABILITY_UNAVAILABLE = 0;
const AVAILABILITY_AVAILABLE = 1;
const AVAILABILITY_RESERVED = 2;
const AVAILABILITY_SOLD = 3;
const AVAILABILITY_CANCELED = 4;

const STATUS_UNAVAILABLE = 0;
const STATUS_AVAILABLE = 1;
const STATUS_RESERVED = 2;
const STATUS_SOLD = 3;
const STATUS_CANCELED = 4;


$sql = $DB->query("SELECT * FROM reservations WHERE id = " . $_GET['id']);
$reservations = $sql->fetch_all(MYSQLI_ASSOC);
//
//echo '<pre>';
//var_dump($_GET);
//echo '</pre>';
//
//die();

$form['data']['productCode'] = isset($_GET['productCode']) ? $_GET['productCode'] : '';
$form['data']['brand'] = isset($_GET['brand']) ? $_GET['brand'] : '';
$form['data']['model'] = isset($_GET['model']) ? $_GET['model'] : '';
$form['data']['price'] = isset($_GET['price']) ? $_GET['price'] : '';
$form['data']['availability'] = isset($_GET['availability']) ? $_GET['availability'] : '';
$form['data']['description'] = isset($_GET['description']) ? $_GET['description'] : '';

//retiving info from web page!

if(isset($_GET['characteristics'])){
    foreach ($_GET['characteristics'] as $characteristic){
        $form['data']['characteristics'][] = $characteristic;
    }
}

if(isset($_GET['features'])){
    foreach ($_GET['features'] as $feature){
        $form['data']['features'][] = $feature;
    }
}

if (isset($_GET['id']) && $_GET['id'] != '') {

    $sql = "SELECT * FROM products WHERE id = " . $_GET['id'];
    $result = $DB->query($sql);

    if($result && $result->num_rows){
        $form['data'] = $result->fetch_assoc();
        $form['data']['productCode'] = $form['data']['product_code'];
        unset($form['data']['product_code']);

        $sql = "SELECT * FROM characteristics WHERE product_id = " . $_GET['id'];
        $result = $DB->query($sql);
        $form['data']['characteristics'] = $result->fetch_all(MYSQLI_ASSOC);

        $sql = "SELECT * FROM features WHERE product_id = " . $_GET['id'];
        $result = $DB->query($sql);
        $form['data']['features'] = $result->fetch_all(MYSQLI_ASSOC);
    }else{
        $form['error'][] = 'Warning your id is invalid! You either copied a wrong link or you destroyed the integrity of the database!';
    }

}


if (isset($_GET['save']) && $_GET['save'] == true) {

    $form['data']['productCode'] = isset($_GET['productCode']) ? $_GET['productCode'] : '';
    $form['data']['brand'] = isset($_GET['brand']) ? $_GET['brand'] : '';
    $form['data']['model'] = isset($_GET['model']) ? $_GET['model'] : '';
    $form['data']['price'] = isset($_GET['price']) ? $_GET['price'] : '';
    $form['data']['availability'] = isset($_GET['availability']) ? $_GET['availability'] : '';
    $form['data']['description'] = isset($_GET['description']) ? $_GET['description'] : '';
    $form['data']['characteristics'] = isset($_GET['characteristics']) ? $_GET['characteristics'] : '';
    $form['data']['features'] = isset($_GET['features']) ? $_GET['features'] : '';
    $form['success'] = true;
    $form['error'] = null;


    //Check if product values are correct.
    if (
        (isset($_GET['productCode']) && $_GET['productCode'] != '') &&
        (isset($_GET['brand']) && $_GET['brand'] != '') &&
        (isset($_GET['model']) && $_GET['model'] != '') &&
        (isset($_GET['price']) && $_GET['price'] != '') &&
        (isset($_GET['availability']) && $_GET['availability'] != '')
    ) {
        if (strlen($_GET['productCode']) == 8) {
            if (is_numeric($_GET['price']) && $_GET['price'] > 0) {
                if (is_numeric($_GET['availability'])) {
                    //this is empty moved in form $success
                } else {
                    $form['error'][] = "the availability value is incorrect";
                    $form['success'] = false;
                }
            } else {
                $form['error'][] = "the price in incorrect";
                $form['success'] = false;
            }
        } else {
            $form['error'][] = "the product code in incorrect";
            $form['success'] = false;
        }
    } else {
        $form['error'][] = "all required values must be filled";
        $form['success'] = false;
    }


    //Inserting Product into database
    if($form['success'] == true){
//      stripping special characters
        $form['data']['productCode'] = htmlspecialchars($form['data']['productCode'], ENT_QUOTES);
        $form['data']['brand'] = htmlspecialchars($form['data']['brand'], ENT_QUOTES);
        $form['data']['model'] = htmlspecialchars($form['data']['model'], ENT_QUOTES);
        $form['data']['description'] = htmlspecialchars($form['data']['description'], ENT_QUOTES);
        if (isset($form['data']['id']) && $form['data']['id'] != '') {
//          updating database
            $sql = "
                UPDATE products
                SET product_code = '" . $form['data']['productCode'] . "',
                brand = '" . $form['data']['brand'] . "',
                model = '" . $form['data']['model'] . "',
                price = '" . $form['data']['price'] . "',
                availability = '" . $form['data']['availability'] . "',
                description = '" . $form['data']['description'] . "'
                WHERE id = " . $form['data']['id'] . ";
                
                ";
        } else {
//          inserting into database
            $sql = "INSERT INTO products VALUES (
                null ,
                '" . $form['data']['productCode'] . "',
                '" . $form['data']['brand'] . "',
                '" . $form['data']['model'] . "',
                '" . str_replace(',', '.', $form['data']['price']) . "',
                '" . $form['data']['availability'] . "',
                '" . $form['data']['description'] . "',
                null,
                NOW()
                );";
        }
        //Check if product query execution is successful
        /** @var $DB mysqli */
        if ($DB->query($sql)) {
            if (!isset($form['data']['id'])) {
                $form['data']['id'] = $DB->insert_id;
                $form['message'] = "Succesfuly insterted into db";
            } else {
                //add new characteristics in db
                $form['message'] = "Succesfuly updated into db";
            }
        } else {
            $form['error'][] = mysqli_error($DB);
            $form['success'] = false;
        }
    }

    //TODO[rocubix]: here we should update the reservations to reflect the current car status



    if($form['success'] == true){

        $sql = "SELECT * FROM characteristics WHERE product_id = " . $_GET['id'];
        $result = $DB->query($sql);
        $db['characteristics'] = $result->fetch_all(MYSQLI_ASSOC);

        foreach($db['characteristics'] as $dbCharacteristic){
            $found = false;
            foreach ($form['data']['characteristics'] as $formCharacteristic){
                if(isset($formCharacteristic['id'])){
                    if($dbCharacteristic['id'] == $formCharacteristic['id']){
                        $found = true;
                    }
                }
            }
            if($found == false){
                $sql = "DELETE FROM characteristics
                        WHERE id = " . $dbCharacteristic['id'] . ";
                        ";
                if(!$DB->query($sql)){
                    $form['error'][] = 'unable to delete characteristic';
                }
            }
        }

        $sql = "SELECT * FROM features WHERE product_id = " . $_GET['id'];
        $result = $DB->query($sql);
        $db['features'] = $result->fetch_all(MYSQLI_ASSOC);
        foreach($db['features'] as $dbFeature){
            $found = false;
            foreach ($form['data']['features'] as $formFeature){
                if(isset($formFeature['id'])){
                    if($dbFeature['id'] == $formFeature['id']){
                        $found = true;
                    }
                }
            }
            if($found == false){
                $sql = "DELETE FROM features
                        WHERE id = " . $dbFeature['id'] . ";
                        ";
                if(!$DB->query($sql)){
                    $form['error'][] = 'unable to delete feature';
                }
            }
        }


//        $form['data']['characteristics'] = htmlspecialchars($form['data']['characteristics'], ENT_QUOTES);
        if (isset($form['data']['characteristics'])){
            foreach ($form['data']['characteristics'] as $characteristic){
                if (isset($characteristic['name']) && $characteristic['name'] != '') {
                    if (isset($characteristic['title']) && $characteristic['title'] != '') {
                        $characteristic['name'] = htmlspecialchars($characteristic['name'],ENT_QUOTES);
                        $characteristic['title'] = htmlspecialchars($characteristic['title'],ENT_QUOTES);
                        //


                        if (isset($characteristic['id']) && $characteristic['id'] != ''){

                            // update db
                            $sql = "
                            UPDATE characteristics
                            SET name = '" . $characteristic['name'] . "',
                            title = '" . $characteristic['title'] . "'

                            WHERE id = " . $characteristic['id'] . ";
                            
                            ";
                        }else{
                            //insert data into db
                            $sql = "INSERT INTO characteristics VALUES (
                            null ,
                            '" . $form['data']['id'] . "' ,
                            '" . $characteristic['name'] . "',
                            '" . $characteristic['title'] . "'
                            );";
                        }
                        /** @var $DB mysqli */
                        if (!$DB->query($sql)) {
                            $form['warning'][]= "caracteristica nu a putut fi inserata in baza de date!";
                        }
                    }else{
                        $form['warning'][]= "valoarea este gresita!";
                    }
                }else{
                    $form['warning'][]= "numele este gresit!";
                };
            }
        }
    }

    if($form['success'] == true){
        //$form['data']['features'] = htmlspecialchars($form['data']['features'], ENT_QUOTES);
        if (isset($form['data']['features'])){
            foreach ($form['data']['features'] as $feature){
                if (isset($feature['name']) && $feature['name'] != '') {
                    $characteristic['name'] = htmlspecialchars($characteristic['name'],ENT_QUOTES);
                        if (isset($feature['id']) && $feature['id'] != ''){
                            // update db
                            $sql = "
                            UPDATE features
                            SET name = '" . $feature['name'] . "'

                            WHERE id = " . $feature['id'] . ";
                            
                            ";
                        }else{
                            //insert data into db
                            $sql = "INSERT INTO features VALUES (
                            null ,
                            '" . $form['data']['id'] . "' ,
                            '" . $feature['name'] . "'
                            );";

                        }
                        /** @var $DB mysqli */
                        if (!$DB->query($sql)) {
                            $form['warning'][]= "featur-ul nu a putut fi inserata in baza de date!";
                        }
                    }else{
                        $form['warning'][]= "featur-ul este gresit!";
                    };
            }
        }
    }

    if($form['success'] == true){
        header('Location: /admin/products/products.php?id=' . $form['data']['id'] . "&message=" . $form['message']);
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
                            <li><a href="/admin/images/index.php"><span class="glyphicon glyphicon-cloud"></span>
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

                            <li><a href="/admin/uploads/upload.php"><span class="glyphicon glyphicon-signal"></span> Upload</a></li>

                        </ul>
                    </div><!-- /.navbar-collapse -->
                </nav>

            </div>
        </div>
    </div>
    <div class="col-xs-10 content">

        <?php
        if (isset($_GET['message']) && $_GET['message']) {
            ?>
            <div class="alert alert-success" role="alert">
                <span class="glyphicon glyphicon glyphicon-ok-circle" aria-hidden="true"></span>
                <span class="sr-only">Error:</span>
                <?= $_GET['message'] ?>
            </div>
            <?php
        }
        if (isset($form['error'])) {
            foreach ($form['error'] as $error) {
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

                    <?php
                    if (isset($form['data']['id']) && $form['data']['id'] != '') {
                        ?>
                        <input type="hidden" name="id" value="<?= $form['data']['id'] ?>">
                        <?php
                    }
                    ?>

                    <div class="form-group">
                        <label class="control-label col-sm-2" for="productCode">Product Code:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="productCode" placeholder="" name="productCode"
                                   value="<?= $form['data']['productCode'] ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-2" for="brand">Brand:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="brand" placeholder="" name="brand"
                                   value="<?= $form['data']['brand'] ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-2" for="model">Model:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="model" placeholder="" name="model"
                                   value="<?= $form['data']['model'] ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-2" for="price">Price:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="price" placeholder="" name="price"
                                   value="<?= $form['data']['price'] ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-2" for="availability">Availability:</label>

                        <div class="col-sm-10">
                            <select class="form-control" id="availability" name="availability">
                                <option id="availability"
                                        value="<?= AVAILABILITY_UNAVAILABLE ?>"<?= ($form['data']['availability'] == AVAILABILITY_UNAVAILABLE) ? ' selected' : '' ?>>
                                    Unavailable
                                </option>
                                <option id="availability"
                                        value="<?= AVAILABILITY_AVAILABLE ?>"<?= ($form['data']['availability'] == AVAILABILITY_AVAILABLE) ? ' selected' : '' ?>>
                                    Available
                                </option>
                                <option id="availability"
                                        value="<?= AVAILABILITY_RESERVED ?>"<?= ($form['data']['availability'] == AVAILABILITY_RESERVED) ? ' selected' : '' ?>>
                                    Reserved
                                </option>
                                <option id="availability"
                                        value="<?= AVAILABILITY_SOLD ?>"<?= ($form['data']['availability'] == AVAILABILITY_SOLD) ? ' selected' : '' ?>>
                                    Sold
                                </option>
                                <option id="availability"
                                        value="<?= AVAILABILITY_CANCELED ?>"<?= ($form['data']['availability'] == AVAILABILITY_CANCELED) ? ' selected' : '' ?>>
                                    Canceled
                                </option>
                            </select>
                            <!--<input type="text" class="form-control" id="availability" placeholder="" name="availability" value= --><? /*= $form['data']['availability']*/ ?>

                        </div>
                    </div>

<!--                    CHARACTERISTICS-->

                    <div class="form-group characteristics">
                        <label class="control-label col-sm-2" for="availability">Characteristics:</label>
                        <div class="col-sm-10">

                            <?php

                            $number_of_characteristics = 0;
                            if (isset($form['data']['characteristics'])) {
                                foreach ($form['data']['characteristics'] as $count => $characteristic) {
                                    $number_of_characteristics = $count;
                                    ?>
                                    <div class="row characteristic">
                                        <input type="hidden" name="characteristics[<?= $count ?>][id]"
                                               value="<?= $characteristic['id'] ?>">
                                        <div class="col-sm-9 values">
                                            <span>Name</span>
                                            <input type="text" class="form-control" placeholder=""
                                                   name="characteristics[<?= $count ?>][name] "
                                                   value="<?= $characteristic['name'] ?>">
                                            <span>Value</span>
                                            <input type="text" class="form-control" placeholder=""
                                                   name="characteristics[<?= $count ?>][title]"
                                                   value="<?= $characteristic['title'] ?>">
                                        </div>
                                        <div class="col-sm-3 actions">
                                            <button type="button" class="btn btn-danger remove_characteristic"
                                                    name="remove" value="true">Remove
                                            </button>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                            ?>



                            <div class="row characteristic">
                                <div class="col-sm-9 values">
                                    <span>Name</span>
                                    <input type="text" class="form-control" placeholder="" name="characteristics[<?=$number_of_characteristics+1?>][name]" value="">
                                    <span>Value</span>
                                    <input type="text" class="form-control" placeholder="" name="characteristics[<?=$number_of_characteristics+1?>][title]" value="">
                                </div>
                                <div class="col-sm-3 actions">
                                    <button type="button" class="btn btn-danger remove_characteristic" name="remove" value="true">Remove</button>
                                </div>
                            </div>





                        </div>
                        <div class="col-sm-offset-10 col-sm-2">
                            <button type="button" class="btn pull-right" id="add_characteristic" name="add" value="true">Add</button>
                        </div>

                    </div>


<!--                    FEATURES-->

                    <div class="form-group features">
                        <label class="control-label col-sm-2" for="availability">Features:</label>
                        <div class="col-sm-10">
                            <?php
                            $number_of_features = 0;
                            if (isset($form['data']['features'])) {
                                foreach ($form['data']['features'] as $count => $feature) {
                                    $number_of_features = $count;
                                    ?>

                                    <div class="row feature">
                                        <input type="hidden" name="features[<?= $count ?>][id]"
                                               value="<?= $feature['id'] ?>">
                                        <div class="col-sm-9 values">
                                            <span>Name</span>
                                            <input type="text" class="form-control" id="features[<?= $count ?>][name]"
                                                   placeholder="" name="features[0][name]"
                                                   value="<?= $feature['name'] ?>">
                                        </div>
                                        <div class="col-sm-3 actions">
                                            <button type="button" class="btn btn-danger remove_feature" name="remove"
                                                    value="true">Remove
                                            </button>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                            ?>




                            <div class="row feature">
                                <input type="hidden" name="features[1][id]" value="">
                                <div class="col-sm-9 values">
                                    <span>Name</span>
                                    <input type="text" class="form-control" id="features[1][name]" placeholder="" name="features[1][name]" value="">
                                </div>
                                <div class="col-sm-3 actions">
                                    <button type="button" class="btn btn-danger remove_feature" name="remove" value="true">Remove</button>
                                </div>
                            </div>

                        </div>

                        <div class="col-sm-offset-10 col-sm-2">
                            <button type="button" class="btn pull-right" id="add_feature" name="add" value="true">Add</button>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="control-label col-sm-2" for="description">Description:</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" rows="5" id="description"
                                      name="description"><?= $form['data']['description'] ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-default" name="save" value="true">Submit</button>
                        </div>
                    </div>
                </form>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Add/Edit Images
                        </div>
                        <div class="panel-body">

                            <form class="form-inline">
                                <input type="hidden" name="id" value="">
                                <div class="form-group">
                                    <div class="col-xs-4">
                                        <img src="https://www.w3schools.com/html/workplace.jpg" class="img-responsive">
                                    </div>
                                </div>
                                <div class="form-group">
                                <button type="button" class="btn btn-danger">Remove</button>
                                </div>
                            </form>
                            <div class="form-group">
                                <button type="button" class="btn">Brouse</button>
                                <button type="button" class="btn btn-danger">Upload</button>
                            </div>


                        </div>
                    </div>
                </form>
                <div id="product_table" class="table-responsive">
                    <table class="table active">
                        <thead
                        <tr>
                            <th>ID</th>
                            <th>Product Code</th>
                            <th>Availability</th>
                            <th>Status</th>


                        </tr>
                        </thead>
                        <?php
                        foreach ($reservations as $reservation) {

                            ?>

                            <tr>
                            <td><?= $reservation['id'] ?></td>
                            <td><?= $reservation['product_id'] ?></td>
                            <td>
                                <?php
                                switch ($reservation['status']) {
                                    case STATUS_UNAVAILABLE:
                                        echo "unavailable";
                                        break;
                                    case STATUS_AVAILABLE:
                                        echo "available";
                                        break;
                                    case STATUS_RESERVED:
                                        echo "reserved";
                                        break;
                                    case STATUS_SOLD:
                                        echo "sold";
                                        break;
                                    case STATUS_CANCELED:
                                        echo "canceled";
                                        break;
                                }

                                ?>
                            </td>
                            <td>
                            <?php
                            foreach ($reservations as $reservation) {

                                ?>
                                <?php


                                ?>
                                </td>

                                </tr>

                                <?php
                            }
                            ?>
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
    <footer class="pull-left footer">
        <p class="col-md-12">
        <hr class="divider">
        Copyright &COPY; 2015 <a href="http://www.pingpong-labs.com">Gravitano</a>
        </p>
    </footer>
</div>

<!--    scripts-->
<script>


    var no_of_characteristics = <?= $number_of_characteristics + 2 ?>;
    var no_of_features = <?= $number_of_features + 2 ?>;

    function new_characteristic_html(no_caracteristic){
        return '<div class="row characteristic">\n' +
        '                                <div class="col-sm-9 values">\n' +
        '                                    <span>Name</span>\n' +
        '                                    <input type="text" class="form-control" placeholder="" name="characteristics['+ no_caracteristic +'][name]" value="">\n' +
        '                                    <span>Value</span>\n' +
        '                                    <input type="text" class="form-control" placeholder="" name="characteristics['+ no_caracteristic +'][title]" value="">\n' +
        '                                </div>\n' +
        '                                <div class="col-sm-3 actions">\n' +
        '                                    <button type="button" class="btn btn-danger remove_characteristic" name="remove" value="true">Remove</button>\n' +
        '                                </div>\n' +
        '                            </div>';
    }

    function new_feature_html(no_feature) {
        return '<div class="row feature">\n' +
            '<div class="col-sm-9 values">\n' +
            '    <span>Name</span>\n' +
            '    <input type="text" class="form-control" id="features[' + no_feature + '][name]" placeholder="" name="features[' + no_feature + '][name]" value="">\n' +
            '</div>\n' +
            '<div class="col-sm-3 actions">\n' +
            '    <button type="button" class="btn btn-danger remove_feature" name="remove" value="true">Remove</button>\n' +
            '</div>\n' +
            '</div>';
    };


    $(function () {
        addRemoveAbility()
        $('#add_characteristic').click(function () {
            $('.characteristics .col-sm-10').append(new_characteristic_html(no_of_characteristics));
            no_of_characteristics = no_of_characteristics + 1;
            addRemoveAbility();
        })
        $('#add_feature').click(function () {
            $('.features .col-sm-10').append(new_feature_html(no_of_features));
            no_of_features = no_of_features + 1;
            addRemoveAbility();
        })
    });

    function addRemoveAbility(){
        $('.remove_characteristic').click(function () {
            $(this).parent().parent().remove()
        })

        $('.remove_feature').click(function () {
            $(this).parent().parent().remove()
        })
    }


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




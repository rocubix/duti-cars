<?php
include "../../src/database.php";
const STATUS_UNAVAILABLE = 0;
const STATUS_AVAILABLE = 1;
const STATUS_RESERVED = 2;
const STATUS_SOLD = 3;

$form['data']['product_id'] = isset($_GET['product_id']) ? $_GET['product_id'] : '';
$form['data']['name'] = isset($_GET['name']) ? $_GET['name'] : '';
$form['data']['email'] = isset($_GET['email']) ? $_GET['email'] : '';
$form['data']['telephone'] = isset($_GET['telephone']) ? $_GET['telephone'] : '';
$form['data']['note'] = isset($_GET['note']) ? $_GET['note'] : '';
$form['data']['status'] = isset($_GET['status']) ? $_GET['status'] : '';

$sql = 'SELECT id,product_code,brand,model FROM products;';
$result = $DB->query($sql);
$products = $result->fetch_all(MYSQLI_ASSOC);

if (isset($_GET['id']) && $_GET['id'] != '') {

    $sql = "SELECT * FROM reservations WHERE id = " . $_GET['id'];
    $result = $DB->query($sql);

    if ($result && $result->num_rows) {
        $form['data'] = $result->fetch_assoc();
    } else {
        $form['error'][] = 'Warning your id is invalid! You either copied a wrong link or you destroyed the integrity of the database!';
    }

}

if (isset($_GET['save']) && $_GET['save'] == true) {
    $form['data']['product_id'] = isset($_GET['product_id']) ? $_GET['product_id'] : '';
    $form['data']['name'] = isset($_GET['name']) ? $_GET['name'] : '';
    $form['data']['email'] = isset($_GET['email']) ? $_GET['email'] : '';
    $form['data']['telephone'] = isset($_GET['telephone']) ? $_GET['telephone'] : '';
    $form['data']['note'] = isset($_GET['note']) ? $_GET['note'] : '';
    $form['data']['status'] = isset($_GET['status']) ? $_GET['status'] : '';

    $form['success'] = true;
    $form['error'] = null;


    //Check if product values are correct.
    if (
        (isset($_GET['product_id']) && $_GET['product_id'] != '') &&
        (isset($_GET['name']) && $_GET['name'] != '') &&
        (isset($_GET['email']) && $_GET['email'] != '' && filter_var($_GET['email'], FILTER_VALIDATE_EMAIL)) && //TODO[rocubix]: validare email in php
        (isset($_GET['telephone']) && $_GET['telephone'] != '' && strlen($_GET['telephone']) >= 10) &&  //TODO[rocubix]: validare minim 10 caractere
        (isset($_GET['status']) && $_GET['status'] != '')
    ) {
//        TODO[rocubix]:product_id nu are status 0 sau 3 altfel imi returnezi eroare.
        $sql = "SELECT * FROM products WHERE id = " . $_GET['product_id'];
        $result = $DB->query($sql);
        $product = $result->fetch_assoc();
        if ($product['availability'] == STATUS_UNAVAILABLE || $product['availability'] == STATUS_SOLD){
//            $form['error'][] = "this car is unavailable";
//            $form['success'] = false;
        }

    } else {
        $form['error'][] = "all required values must be filled";
        $form['success'] = false;
    }


    //Inserting Product into database
    if ($form['success'] == true) {
//      stripping special characters
        $form['data']['name'] = htmlspecialchars($form['data']['name'], ENT_QUOTES);
        $form['data']['email'] = htmlspecialchars($form['data']['email'], ENT_QUOTES);
        $form['data']['note'] = htmlspecialchars($form['data']['note'], ENT_QUOTES);
        if (isset($form['data']['id']) && $form['data']['id'] != '') {
//          updating database
            $sql = "
                UPDATE reservations
                SET product_id = '" . $form['data']['product_id'] . "',
                name = '" . $form['data']['name'] . "',
                email = '" . $form['data']['email'] . "',
                telephone = '" . $form['data']['telephone'] . "',
                note = '" . $form['data']['note'] . "',
                status = '" . $form['data']['status'] . "'
                WHERE id = " . $form['data']['id'] . ";
                
                ";
        } else {
//          inserting into database
            $sql = "INSERT INTO reservations VALUES (
                null ,
                '" . $form['data']['product_id'] . "',
                '" . $form['data']['name'] . "',
                '" . $form['data']['email'] . "',
                '" . $form['data']['telephone'] . "',
                '" . $form['data']['note'] . "',
                '" . $form['data']['status'] . "',
                NOW(),
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

    if ($form['success'] == true) {
        header('Location: /admin/reservations/reservation.php?id=' . $form['data']['id'] . "&message=" . $form['message']);
    }
}
?>


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

                            <li><a href="#"><span class="glyphicon glyphicon-signal"></span> Link</a></li>

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
                        <label class="control-label col-sm-2" for="product_id">Product:</label>
                        <div class="col-sm-10">
                            <!--                            <input type="text" class="form-control" id="product_id" placeholder="" name="product_id" value="-->
                            <? //= $form['data']['product_id'] ?><!--">-->
                            <select class="form-control" id="sel1" name="product_id">
                                <option>Please select a value</option>
                                <?php
                                foreach ($products as $product) {
                                    ?>
                                    <option value="<?= $product['id'] ?>" <?= (isset($_GET['id']) && $form['data']['product_id'] == $product['id']) ? 'selected' : '' ?>><?= '[' . $product['product_code'] . ']' . $product['brand'] . ':' . $product['model'] ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-2" for="name">Name:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="name" placeholder="" name="name"
                                   value="<?= $form['data']['name'] ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-2" for="email">Email:</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" id="email" placeholder="" name="email"
                                   value="<?= $form['data']['email'] ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-2" for="telephone">Telephone:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="telephone" placeholder="" name="telephone"
                                   value="<?= $form['data']['telephone'] ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-2" for="note">Note:</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" rows="5" id="note"
                                      name="note"><?= $form['data']['note'] ?></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-2" for="status">Status:</label>

                        <div class="col-sm-10">
                            <select class="form-control" id="status" name="status">
                                <option id="status"
                                        value="<?= STATUS_UNAVAILABLE ?>"<?= ($form['data']['status'] == STATUS_UNAVAILABLE) ? ' selected' : '' ?>>
                                    Unavailable
                                </option>
                                <option id="status"
                                        value="<?= STATUS_AVAILABLE ?>"<?= ($form['data']['status'] == STATUS_AVAILABLE) ? ' selected' : '' ?>>
                                    Available
                                </option>
                                <option id="status"
                                        value="<?= STATUS_RESERVED ?>"<?= ($form['data']['status'] == STATUS_RESERVED) ? ' selected' : '' ?>>
                                    Reserved
                                </option>
                                <option id="status"
                                        value="<?= STATUS_SOLD ?>"<?= ($form['data']['status'] == STATUS_SOLD) ? ' selected' : '' ?>>
                                    Sold
                                </option>
                            </select>
                            <!--<input type="text" class="form-control" id="availability" placeholder="" name="availability" value= --><? /*= $form['data']['availability']*/ ?>

                        </div>
                    </div>

                    <div class="col-sm-offset-10 col-sm-2">
                        <button type="submit" class="btn pull-right" id="add_reservation" name="save" value="true">Add
                        </button>
                    </div>


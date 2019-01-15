<?php
/**
 * Created by PhpStorm.
 * User: Andrei
 * Date: 12/30/2018
 * Time: 10:16 PM
 */
include "../../src/database.php";



$length = 10;
$randomString = substr(str_shuffle(md5(time())), 0, $length);
$target_dir = "../uploads/trash/";
$form['success'] = null;
if (isset($_POST["submit"]))
{
    $form['success'] = true;
    $form['error'] = null;
    $target_file = $target_dir . $randomString . ($_FILES["fileToUpload"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    if ($_FILES["fileToUpload"]["size"] > 1024 * 1024 * 0.01 && $_FILES["fileToUpload"]["size"] < 1024 * 1024 * 20) {
        if ($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg"
            || $imageFileType == "gif") {
            if ($form['success'] = false){
                $form['error'][] = "Sorry, your file was not uploaded.";
            }else{
                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                    $form['success'] = "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
                    $sql = "INSERT INTO images VALUES (
                    null ,
                    10 ,
                    '" . $_FILES["fileToUpload"]["name"] . "',
                    '" . $randomString . "'
                    );";
                    if ($DB->query($sql)) {
                        if (!isset($form['data']['id'])) {
                            $form['data']['id'] = $DB->insert_id;
                            $form['success'] = "Succesfuly insterted into db";

                        } else {
                            $form['success'] = false;
                            $form['error'][] = "Succesfuly insterted somewhere else into db";
                        }
                    }
                } else {
                    $form['success'] = false;
                    $form['error'][] = "Sorry, there was an error uploading your file.";
                }
            }
        } else {
            $form['success'] = false;
            $form['error'][] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        }
    } else {
        $form['success'] = false;
        $form['error'][] =('file size is invalid ( ' . number_format($_FILES["fileToUpload"]["size"] / 1024 / 1024, 3) . ' MB)');
//        echo '<pre>';
//        var_dump($_FILES["fileToUpload"]["name"]);
//        echo '</pre>';
//        die($sql);
    }
}
if ($form['success'] == true) {
    header('Location: /admin/uploads/upload.php?id=' . "&message=" . $form['success']);
}
?>


<html>
<head>
    <title>Admin</title>
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
                            <li class="active"><a href="/admin/products/index.php"><span class="glyphicon glyphicon-dashboard"></span> Products</a>
                            </li>
                            <li><a href="/admin/products/index.php"><span class="glyphicon glyphicon-plane"></span> Images</a></li>
                            <li><a href="/admin/features/index.php"><span class="glyphicon glyphicon-cloud"></span> Features</a></li>
                            <li><a href="/admin/characteristics/index.php"><span class="glyphicon glyphicon-cloud"></span> Characteristics</a></li>
                            <li><a href="/admin/reservations/index.php"><span class="glyphicon glyphicon-cloud"></span> Reservations</a></li>
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

                            <li><a href="/admin/uploads/upload.php"><span class="glyphicon glyphicon-signal"></span> Link</a></li>

                        </ul>
                    </div><!-- /.navbar-collapse -->
                </nav>

            </div>
        </div>
    </div>
    <div class="col-xs-10 content">
        <div class="panel panel-default">
            <div class="panel-heading">
                Dashboard
            </div>
            <div class="panel-body">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
            </div>
        </div>
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

        <form action="../uploads/upload.php" method="post" enctype="multipart/form-data">
            Select image to upload:
            <br>
            <input type="file" name="fileToUpload" id="fileToUpload">
            <br>
            <input type="submit" value="Upload Image" name="submit">
        </form>
        <?php



        $sql = $DB->query("SELECT * FROM images  WHERE product_id=10");
        $result = $DB->query($sql[''], MYSQLI_ASSOC);
        $row[] = mysqli_fetch_array($result, MYSQLI_ASSOC);

        $image = $row['fileToUpload'];
        $image_src = "../uploads/trash/".$image;

?>
        <img src='<?php echo $image_src;  ?>' >



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




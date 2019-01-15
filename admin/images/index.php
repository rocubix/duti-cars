<?php
include "../../src/database.php";
$sql = $DB->query("SELECT * FROM images WHERE name!=''");
$images = $sql->fetch_all(MYSQLI_ASSOC);
$images = mysqli_num_rows($sql);
?>


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
<form action="../images/index.php" method="post" enctype="multipart/form-data">
    Select image to upload:<br>
    <input type="file" name="fileToUpload" id="fileToUpload"><br>
    <input type="submit" value="Upload Image" name="submit"><br>
</form>

</body>
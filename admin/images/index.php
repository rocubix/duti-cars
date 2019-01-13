<?php
include "../../src/database.php";
$length = 10;
$randomString = substr(str_shuffle(md5(time())), 0, $length);

$target_dir = "../uploads/trash/";
$form['success'] = true;
$form['error'] = null;
// Check if image file is a actual image or fake image
if (isset($_POST["submit"])) {
    $target_file = $target_dir . $randomString . ($_FILES["fileToUpload"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $imageSize = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if ($imageSize !== false) {


        $form['success'] = "File is an image - " . $imageSize["mime"] . ".";
        $form['success'] = false;


        if ($_FILES["fileToUpload"]["size"] > 1000000) {
            $form['error'][] = "Sorry, your file is too large.";
            $form['success'] = false;
        } else {
            $form['error'][] = "File is not an image.";
            $form['success'] = false;
        }
        if ($imageFileType != "jpg" || $imageFileType != "png" || $imageFileType != "jpeg"
            || $imageFileType != "gif") {

        } else {
            $form['error'][] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $form['success'] = false;
        }
        if ($form['success'] == false) {
            $form['error'][] = "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
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
                        $form['error'] = "Succesfuly insterted somewhere else into db";
                    }
                }
                if ($form['success'] == true) {
                    header('Location: /admin/images/index.php?id=' . "&message=" . $form['success']);
                }

            } else {
                $form['error'][] = "Sorry, there was an error uploading your file.";
            }
        }
    }

    echo " da ...";

}



// Check if file already exists
//    if (file_exists($target_file)) {
//        $form['error'][] = "Sorry, file already exists.";
//        $form['success'] = false;
//
//    }
// Check file size

// Allow certain file formats

// Check if $uploadOk is set to 0 by an error

// if everything is ok, try to upload file


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
</body>
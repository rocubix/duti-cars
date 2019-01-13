<?php
/**
 * Created by PhpStorm.
 * User: Andrei
 * Date: 11/23/2018
 * Time: 7:57 PM
 */
?>
<?php
//// Check if image file is a actual image or fake image
//if(isset($_POST["submit"])) {
//    $target_file = $target_dir . $randomString . ($_FILES["fileToUpload"]["name"]);
//    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
//    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
//
//    if( $imageFileType !=''){
//        if ( $_FILES["fileToUpload"]["size"] > 1024 * 1024 * 0.5  && $_FILES["fileToUpload"]["size"] < 1024 * 1024 * 20 ){
//            if ($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg" ||  $imageFileType == "gif"){
//               if(move_uploaded_file( $_FILES['fileToUpload']['tmp_name'], $target_dir)){
//                   die("succeeeeesssssss!!!");
//                }else{
//                   die("could not move file for some reason");
//               }
//            }else{
//                $form['succes'] = false;
//                $form['error'] = 'ce scrie mai jos';
//                die('file must be a valid image (jpg,png,jpeg,gif) '.$imageFileType." given");
//            }
//        }else{
//            die('file size is invalid ( '. number_format($_FILES["fileToUpload"]["size"] / 1024 / 1024,3) . ' MB)');
//        }
//    }else{
//        die('sorry not a valid image');
//    }
//
//    if($success){
//        //Upload I Ma Ge
//
//        //insert into DE BE
//    }
//
//    if ($imageFileType != true && $_FILES["fileToUpload"]["tmp_name"] > 2097152)
//        {
//            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
//            && $imageFileType != "gif") {
//                if ($check !== true) {
//                    echo "File is not an image. ";
?>


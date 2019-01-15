<?php
include "../../src/database.php";


if(isset($_POST['but_upload'])){

 $name = $_FILES['file']['name'];
 $target_dir = "../uploads/trash/";
 $target_file = $target_dir . basename($_FILES["file"]["name"]);

 // Select file type
 $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

 // Valid file extensions
 $extensions_arr = array("jpg","jpeg","png","gif");

 // Check extension
 if( in_array($imageFileType,$extensions_arr) ){

  // Insert record
     $sql = "insert into images(name) values('".$name."'";
     $DB->query($sql);

  // Upload file
  move_uploaded_file($_FILES['file']['tmp_name'],$target_dir.$name);

 }

}
?>

<form method="post" action="" enctype='multipart/form-data'>
  <input type='file' name='file' />
  <input type='submit' value='Save name' name='but_upload'>
</form>

<?php

$sql = "select product_id from images";
$result =  $sql;
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);

$image = $row['name'];
$image_src = "upload/".$image;

?>
<img src='<?php echo $image_src;  ?>' >
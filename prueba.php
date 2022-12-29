<?php 
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $_FILES["imagen"]["tmp_name"]);
var_dump($mime);

?>
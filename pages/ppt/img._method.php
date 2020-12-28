<?php require_once "/var/www/html/config.php";
$ID       = $_POST["id"];
$File     = $_FILES["img"]["tmp_name"];
$FilePath = PATH."/data/images/ppt/";
if(!file_exists($FilePath))
    mkdir($FilePath, 0777, true);
$Fitter = ["jpg", "jpeg", "png", "gif", "ico", "bmp", "JPG", "JPEG", "PNG", "GIF", "ICO", "BMP"];
foreach($Fitter as $FF)
{
    $F = $FilePath.$ID.".".$FF;
    if(file_exists($F))
    {
        chmod($F, 777);
        unlink($F);
    }
}
$FileName = $ID.".".pathinfo($_FILES["img"]["name"])["extension"];
if(move_uploaded_file($_FILES["img"]["tmp_name"], $FilePath."/".$FileName))
    exit(json_encode(['code' => 0]));
else exit(json_encode(["code" => 1]));

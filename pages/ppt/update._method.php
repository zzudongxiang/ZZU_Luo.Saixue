<?php require_once "/var/www/html/config.php";
if(!empty($_GET["type"]))
{
    $Type = $_GET["type"];
    if($Type == "title")
        $Key = "Title";
    else if($Type == "text")
        $Key = "Text";
    else exit(json_encode(["code" => 0]));
    $Value = $_POST["value"];
    $ID    = $_POST["id"];
    ExecuteNonQuery("UPDATE TIC_PPT SET $Key = '$Value' WHERE ID = $ID");
}
exit(json_encode(["code" => 0]));
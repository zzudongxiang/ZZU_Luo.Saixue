<?php require_once "/var/www/html/config.php";
$ID = $_POST["id"];
ExecuteNonQuery("DELETE FROM TIC_Judge WHERE ID = $ID");
exit(json_encode(["code" => 0]));
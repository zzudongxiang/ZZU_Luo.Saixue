<?php require_once "/var/www/html/config.php";
$Name = $_POST["name"];
ExecuteNonQuery("INSERT INTO TIC_Honor (Name, Remark) VALUES ('$Name', '啊哦～这个家伙太懒了，什么也没写～');");
exit(json_encode(["code" => 0]));
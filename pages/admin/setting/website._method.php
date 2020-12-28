<?php require_once "/var/www/html/config.php";
LoginChecked();
$Result      = false;
$QueryString = "UPDATE TIC_WebSite SET ConfigValue = :_Title WHERE ConfigKey LIKE 'WebSite_Title';";
$Result      |= ExecuteNonQuery($QueryString, [":_Title" => $_POST["title"]]);
$QueryString = "UPDATE TIC_WebSite SET ConfigValue = :_Footer WHERE ConfigKey LIKE 'WebSite_Footer';";
$Result      |= ExecuteNonQuery($QueryString, [":_Footer" => $_POST["footer"]]);
$QueryString = "UPDATE TIC_WebSite SET ConfigValue = :_LoginNotice WHERE ConfigKey LIKE 'Login_Notice';";
$Result      |= ExecuteNonQuery($QueryString, [":_LoginNotice" => $_POST["loginnotice"]]);
if($Result)
    RunJS(["back" => ""]);
else RunJS(["alert" => "网站信息修改失败, 信息未保存", "back" => ""]);

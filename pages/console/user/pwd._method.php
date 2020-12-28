<?php require_once "/var/www/html/config.php";
$UserData = LoginChecked();
$PWD      = trim($_POST["userpwd"]);
$RePWD    = trim($_POST["re-userpwd"]);
if(empty($PWD) || empty($RePWD))
{
    RunJS(array("alert" => "请输入有效的密码", "back" => ""));
    return;
}
if($PWD != $RePWD)
{
    RunJS(array("alert" => "两次输入的密码不一致, 请从新输入", "back" => ""));
    return;
}
$Result = ExecuteNonQuery("UPDATE TIC_UserInfo SET PassWord = :p_PWD WHERE GUID LIKE :p_GUID;", [":p_PWD" => $PWD, ":p_GUID" => trim($UserData["GUID"])]);
if($Result)
    RunJS(array("alert" => "修改密码成功", "top.jump" => "/pages/console/index.php"));
else  RunJS(array("alert" => "修改失败, 用户登陆密码未修改", "back" => ""));


<?php require_once "/var/www/html/config.php";
$LoginStatus = $_GET["login"];
if(!empty($LoginStatus) && $LoginStatus == "true")
{
    if(!empty($UserName = trim($_POST['username'])) && !empty($UserPwd = trim($_POST['userpwd'])))
    {
        $QueryString = "SELECT GUID FROM TIC_UserInfo WHERE UserName LIKE :_UserName AND PassWord LIKE :_PassWord";
        $Parameters  = [":_UserName" => $UserName, ":_PassWord" => $UserPwd];
        $UserData    = GetSingleResult($QueryString, $Parameters);
        if(!empty($UserData) && !empty($UserData["GUID"]))
        {
            setcookie("UserData", $UserData["GUID"], strtotime("+".LICENSE." second"), "/");
            RunJS(["top.jump" => "/pages/console/index.php"]);
        }
        else RunJS(["alert" => "用户名或密码错误, 请重新输入!", "back" => ""]);
    }
    else RunJS(["alert" => "输入的学号或密码为空, 请重新输入!", "back" => ""]);
}
else
{
    setcookie("UserData", "", time(), "/");
    RunJS(["top.jump" => "/"]);
}
exit();
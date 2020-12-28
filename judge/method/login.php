<?php require_once "/var/www/html/config.php";
$GUID = $_POST["guid"];
if(!empty($GUID))
{
    $ID = GetSingleResult("SELECT ID FROM TIC_Judge WHERE GUID LIKE '$GUID' AND OnlineStatus = 0;");
    if(!empty($ID))
    {
        $Result = ExecuteNonQuery("UPDATE TIC_Judge SET OnlineStatus = 1 WHERE GUID LIKE '$GUID';");
        if($Result)
        {
            setcookie("JudgeID", $GUID, time() + 18000, "/");
            exit(json_encode(['code' => 0]));
        }
        else exit(json_encode((["code" => 1, "info" => "无法登陆评委账户"])));
    }
    else exit(json_encode((["code" => 1, "info" => "无法登陆评委账户, 可能当前评委已登陆"])));
}
else exit(json_encode((["code" => 1, "info" => "请选择要登陆的评委"])));
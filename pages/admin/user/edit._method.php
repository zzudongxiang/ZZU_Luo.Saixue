<h1>Loading</h1>
<?php require_once "/var/www/html/config.php";

if(!empty($_POST["id"]) && !empty($_POST["method"]))
{
    if($_POST["method"] == "del")
    {
        $QueryString = "DELETE FROM TIC_UserInfo WHERE ID=:_ID";
        $Parameters  = [":_ID@d" => $_POST["id"]];
        $Result      = ExecuteNonQuery($QueryString, $Parameters);
    }
    else if($_POST["method"] == "reset")
    {
        $QueryString = "UPDATE TIC_UserInfo SET PassWord = UserName WHERE ID=:_ID";
        $Parameters  = [":_ID@d" => $_POST["id"]];
        $Result      = ExecuteNonQuery($QueryString, $Parameters);
    }
    else
    {
        $AdminRole = $_POST["adminrole"];
        if($_POST["method"] == "insert")
        {
            if(!empty(trim($_POST["username"])) && !empty(trim($_POST["nikename"])))
            {
                $QueryString = "
                INSERT INTO TIC_UserInfo (GUID, UserName, PassWord, NikeName, OtherInfo, AdminRole) 
                VALUES (:_GUID, :_UserName, :_PassWord, :_NikeName, :_OtherInfo, :_AdminRole)
                ON DUPLICATE KEY UPDATE 
                    OtherInfo = :_OtherInfo,
                    NikeName=:_NikeName,
                    OtherInfo=:_OtherInfo, 
                    AdminRole=:_AdminRole;";
                $Parameters  = [":_GUID" => GetGUID(), ":_UserName" => $_POST["username"], ":_PassWord" => $_POST["username"], ":_NikeName" => $_POST["nikename"], ":_OtherInfo" => $_POST["otherinfo"], ":_AdminRole" => $AdminRole];
                $Result      = ExecuteNonQuery($QueryString, $Parameters);
            }
            else $Result = false;
        }
        else if($_POST["method"] == "update")
        {
            $QueryString = "UPDATE TIC_UserInfo SET UserName=:_UserName, NikeName=:_NikeName, OtherInfo=:_OtherInfo, AdminRole=:_AdminRole WHERE ID=:_ID";
            $Parameters  = [":_ID@d" => $_POST["id"], ":_UserName" => $_POST["username"], ":_NikeName" => $_POST["nikename"], ":_OtherInfo" => $_POST["otherinfo"], ":_AdminRole" => $AdminRole];
            $Result      = ExecuteNonQuery($QueryString, $Parameters);
        }
    }
}
if(!$Result)
    RunJS(["alert" => "数据未保存"]);
if($_POST["method"] != "reset")
    RunJS(["js" => "parent.layer.closeAll();"]);

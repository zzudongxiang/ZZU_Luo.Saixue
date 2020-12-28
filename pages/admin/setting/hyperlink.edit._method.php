<?php require_once "/var/www/html/config.php";
$Result = false;
if(!empty($_POST["id"]) && !empty($_POST["method"]))
{
    if($_POST["method"] === "del")
    {
        $QueryString = "DELETE FROM TIC_Hyperlink WHERE ID=:_ID";
        $Parameters  = [":_ID@d" => $_POST["id"]];
        $Result      = ExecuteNonQuery($QueryString, $Parameters);
    }
    else
    {
        if(!empty($_POST["enabled"]) && $_POST["enabled"] == "on")
            $Enabled = 1;
        else $Enabled = 0;

        if(!empty($_POST["topmost"]) && $_POST["topmost"] == "on")
            $TopMost = 1;
        else  $TopMost = 0;

        if($_POST["method"] === "insert")
        {
            $QueryString = "INSERT INTO TIC_Hyperlink (Type, LinkName, LinkURL, ImageName, Enabled, TopMost, UpdateTime) VALUES (:_Type, :_LinkName, :_LinkURL, :_ImageName, :_Enabled, :_TopMost, :_UpdateTime)";
            $Parameters  = [":_Type" => $_POST["type"], ":_LinkName" => $_POST["linkname"], ":_LinkURL" => $_POST["linkurl"], ":_ImageName" => $_POST["imagename"], ":_Enabled@d" => $Enabled, ":_TopMost@d" => $TopMost, ":_UpdateTime" => date("Y-m-d H:i:s")];
            $Result      = ExecuteNonQuery($QueryString, $Parameters);
        }
        else if($_POST["method"] === "update")
        {
            $QueryString = "UPDATE TIC_Hyperlink SET Type=:_Type, LinkName=:_LinkName, LinkURL=:_LinkURL, Enabled=:_Enabled, TopMost=:_TopMost, UpdateTime=:_UpdateTime WHERE ID=:_ID";
            $Parameters  = [":_Type" => $_POST["type"], ":_LinkName" => $_POST["linkname"], ":_LinkURL" => $_POST["linkurl"], ":_Enabled@d" => $Enabled, ":_TopMost@d" => $TopMost, ":_UpdateTime" => date("Y-m-d H:i:s"), ":_ID@d" => $_POST["id"]];
            $Result      = ExecuteNonQuery($QueryString, $Parameters);
        }
    }
}
if(!$Result)
    RunJS(["alert" => "数据未保存"]);
RunJS(["js" => "parent.layer.closeAll();"]);
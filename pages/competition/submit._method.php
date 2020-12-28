<?php require_once "/var/www/html/config.php";
$UserData    = LoginChecked();
$CompMsg     = GetSingleResult("SELECT TIC_Competition.Filter, Comp_RegistrationInfo.UploadName, TIC_Competition.ID FROM Comp_RegistrationInfo JOIN TIC_Competition ON TIC_Competition.ID = Comp_RegistrationInfo.CompetitionID AND Comp_RegistrationInfo.ID = :_ID", [":_ID@d" => $_POST["rgsinfo"]]);
$FilePath    = PATH."/data/file/competition/".$CompMsg["ID"]."/".$CompMsg["UploadName"];
$FileChecked = false;
foreach(explode("|", $CompMsg["Filter"]) as $FF)
{
    if(file_exists($FilePath.".".$FF))
    {
        $FileChecked = true;
        break;
    }
}
if(!$FileChecked)
{
    RunJS(["alert" => "请上传作业后再尝试提交", "js" => "history.back(-1);"]);
    exit();
}
$Shown       = $_POST["show"] == "on" ? "1" : "0";
$QueryString = "UPDATE Comp_RegistrationInfo SET Remarks=:_Remarks, Shown=:_Shown, SubmitTime=:_SubmitTime, SubmitCount = SubmitCount + 1 WHERE ID = :_ID";
$Parameters  = [":_ID@d" => $_POST["rgsinfo"], ":_Remarks" => trim($_POST["remarks"]), ":_Shown@d" => $Shown, ":_SubmitTime" => date("Y-m-d H:i:s")];
$Result      = ExecuteNonQuery($QueryString, $Parameters);

foreach($_POST as $Item => $Value)
{
    if(preg_match("/CBX_\d+/", $Item))
    {
        $QueryString = "UPDATE Comp_Completion SET Status=".(float)$_POST[$Item]." WHERE TopicID=:_TopicID AND UserID=:_UserID";
        $Item        = str_replace("CBX_", "", $Item);
        $Parameters  = [":_TopicID@d" => $Item, ":_UserID@d" => $UserData["ID"]];
        $Result      = ExecuteNonQuery($QueryString, $Parameters);
    }
}
RunJS(["alert" => "提交成功", "top.jump" => "/pages/competition/user.php"]);
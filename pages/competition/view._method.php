<?php require_once "/var/www/html/config.php";
$UserData = LoginChecked();
if(!empty($_POST["id"]) && !empty($_POST["method"]))
{
    $UserID        = $UserData["ID"];
    $CompetitionID = $_POST["id"];
    $Parameters    = [":_UserID@d" => $UserID, ":_CompetitionID@d" => $CompetitionID, ":_UpdateTime" => date("Y-m-d H:i:s")];
    if(!empty($_POST["method"]))
    {
        if($_POST["method"] == "insert")
        {
            $Parameters[":_UploadPath"] = $UserData["UserName"]."(".$UserData["NikeName"].")";
            $QueryString                = "
                INSERT INTO Comp_RegistrationInfo 
                    (UserID, CompetitionID, UploadName, Remarks) 
                    VALUES (:_UserID, :_CompetitionID, :_UploadPath, '')
                    ON DUPLICATE KEY UPDATE 
                        Status = 1, 
                        UpdateTime = :_UpdateTime;";
        }
        else if($_POST["method"] == "update")
            $QueryString = "UPDATE Comp_RegistrationInfo SET Status = 1 - Status, UpdateTime = :_UpdateTime WHERE UserID = :_UserID AND CompetitionID = :_CompetitionID;";
        $Result      = ExecuteNonQuery($QueryString, $Parameters);
        $QueryString = "SELECT ID FROM Comp_Topic WHERE CompetitionID = :_CompetitionID;";
        $Result      = GetDataTable($QueryString, [":_CompetitionID@d" => $CompetitionID]);
        if(count($Result) > 0)
        {
            foreach($Result as $Row)
            {
                $TopicID     = $Row["ID"];
                $QueryString = "INSERT INTO Comp_Completion (UserID, TopicID) VALUES (:_UserID, :TopicID) ON DUPLICATE KEY UPDATE Status = Status;";
                $Parameters  = [":_UserID@d" => $UserID, ":TopicID@d" => $TopicID];
                $Result      = ExecuteNonQuery($QueryString, $Parameters);
            }
        }
        $QueryString = "SELECT Status FROM Comp_RegistrationInfo WHERE CompetitionID = :_CompetitionID AND UserID = :_UserID";
        $Parameters  = [":_UserID@d" => $UserID, ":_CompetitionID@d" => $CompetitionID];
        $Status      = GetSingleResult($QueryString, $Parameters)["Status"];
        if(!empty($Status) && $Status == "1")
            RunJS(["alert" => "报名成功", "top.jump" => "/pages/competition/submit.php?id=$CompetitionID"]);
        else RunJS(["alert" => "取消报名成功", "top.jump" => "/pages/competition/index.php"]);
    }
    else die("传入参数为非法值");
}
else die("未传入有效参数");

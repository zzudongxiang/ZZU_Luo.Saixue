<?php require_once "/var/www/html/config.php";
require_once PATH."/modules/xls/xlsExport.php";
$CompetitionID    = $_GET["id"];
$QueryString      = "
    SELECT User_Info.NikeName, User_Info.UserName, User_Info.OtherInfo, Reg_Info.UpdateTime, Reg_Info.SubmitTime, Reg_Info.Remarks, IF(Reg_Info.Shown > 0, '展示', '不展示') AS Shown, Reg_Info.SubmitCount, User_Info.Score, Reg_Info.Score AS RT_Score, Reg_Info.Sort FROM (
        SELECT tb_Info.UserInfoID AS ID, tb_Info.NikeName, tb_Info.UserName, tb_Info.OtherInfo, tb_Score.Score FROM (
            SELECT * FROM (     
                SELECT
                    tb_User.ID AS UserInfoID, tb_User.NikeName, tb_User.UserName, tb_User.OtherInfo
                FROM 
                    (SELECT * FROM TIC_UserInfo WHERE ID IN (SELECT UserID FROM Comp_RegistrationInfo WHERE CompetitionID=$CompetitionID AND Status = 1)) AS tb_User) AS tmp_User
        ) AS tb_Info               
        JOIN (
            SELECT SUM(Status * Score) AS Score, UserID FROM(
                SELECT Status, Comp_Topic.Score, UserID
                FROM Comp_Completion 
                JOIN Comp_Topic
                ON Comp_Topic.ID= Comp_Completion.TopicID
                AND Comp_Topic.CompetitionID = $CompetitionID
            ) AS tb_Score Group BY tb_Score.UserID 
        ) AS tb_Score   
        ON tb_Info.UserInfoID=tb_Score.UserID 
    ) AS User_Info JOIN (
        SELECT * FROM Comp_RegistrationInfo WHERE CompetitionID = $CompetitionID
    ) AS Reg_Info 
     ON Reg_Info.UserID = User_Info.ID 
     ORDER BY Reg_Info.Shown DESC, User_Info.Score DESC 
";
$Parameters       = [":_CompetitionID" => $CompetitionID];
$Result           = GetDataTable($QueryString, $Parameters);
$Titles["姓名"]     = ["width" => 10, "align" => "center"];
$Titles["学号"]     = ["width" => 14, "align" => "center"];
$Titles["年级/专业"]  = ["width" => 30, "align" => "left"];
$Titles["报名时间"]   = ["width" => 20, "align" => "center"];
$Titles["提交时间"]   = ["width" => 20, "align" => "center"];
$Titles["作品备注"]   = ["width" => 25, "align" => "left"];
$Titles["是否参加展示"] = ["width" => 15, "align" => "center"];
$Titles["作品提交次数"] = ["width" => 15, "align" => "center"];
$Titles["自评分"]    = ["width" => 8, "align" => "center"];
$Titles["现场分"]    = ["width" => 8, "align" => "center"];
$Titles["展示顺序"]   = ["width" => 10, "align" => "center"];
$xlsObject = GetExcelObj();
$FilePath  = PATH."/data/file/competition/xls/$CompetitionID.xls";
SetSheetsTitle($xlsObject, 0, "报名信息");
SetTitle($xlsObject, $Titles);
FillData($xlsObject, $Result);
SaveExcel($xlsObject, $FilePath);
$DownloadFileName = GetSingleResult("SELECT Title FROM TIC_Competition WHERE ID = $CompetitionID;")["Title"];
header("Cache-Control: public");
header("Content-Description: File Transfer");
header('Content-disposition: attachment; filename='.$DownloadFileName.".".pathinfo($FilePath)["extension"]);
header("Content-Type: application/zip");
header("Content-Transfer-Encoding: binary");
header('Content-Length: '.filesize($FilePath));
@readfile($FilePath);
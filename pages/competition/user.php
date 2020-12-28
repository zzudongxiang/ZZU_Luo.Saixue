<?php require_once "/var/www/html/config.php";
$UserData        = LoginChecked();
$PagesParameters = GetPagesParameters();
$QueryString     = "SELECT * FROM TIC_Competition WHERE Enabled = 1 AND Title LIKE :_SearchKey AND ID IN (SELECT CompetitionID FROM Comp_RegistrationInfo WHERE UserID=:_UserID AND Status = 1) ORDER BY EndTime DESC  LIMIT :_StartIndex, :_Limit;";
$Parameters      = [":_StartIndex@d" => $PagesParameters["StartIndex"], ":_Limit@d" => $PagesParameters["Limit"], ":_SearchKey" => "%".$PagesParameters["SearchKey"]."%", ":_UserID@d" => $UserData["ID"]];
$CountQuery      = "SELECT COUNT(*) FROM TIC_Competition WHERE Enabled = 1 AND Title LIKE :_SearchKey AND ID IN (SELECT CompetitionID FROM Comp_RegistrationInfo WHERE UserID=:_UserID AND Status = 1)";
$CountParameters = [":_SearchKey" => "%".$PagesParameters["SearchKey"]."%", ":_UserID@d" => $UserData["ID"]];
$PageTitle       = "我的赛事";
include_once PATH."/pages/competition/template.php";

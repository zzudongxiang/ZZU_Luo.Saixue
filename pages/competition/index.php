<?php require_once "/var/www/html/config.php";
$UserData        = LoginChecked();
$PagesParameters = GetPagesParameters();
$QueryString     = "SELECT * FROM TIC_Competition WHERE CURRENT_TIME() < EndTime AND Enabled = 1 AND Title LIKE :_SearchKey ORDER BY EndTime DESC LIMIT :_StartIndex, :_Limit;";
$Parameters      = [":_StartIndex@d" => $PagesParameters["StartIndex"], ":_Limit@d" => $PagesParameters["Limit"], ":_SearchKey" => "%".$PagesParameters["SearchKey"]."%"];
$CountQuery      = "SELECT COUNT(*) FROM TIC_Competition WHERE CURRENT_TIME() < EndTime AND Enabled = 1 AND Title LIKE :_SearchKey;";
$CountParameters = [":_SearchKey" => "%".$PagesParameters["SearchKey"]."%"];
$PageTitle       = "赛事报名";
include_once PATH."/pages/competition/template.php";

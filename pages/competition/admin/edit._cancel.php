<?php require_once "/var/www/html/config.php";
$CompetitionID = $_GET["compid"];
$UserID = $_GET["userid"];
$QueryString   = "UPDATE Comp_RegistrationInfo SET Status = 0, UpdateTime = :_UpdateTime WHERE UserID = :_UserID AND CompetitionID = :_CompetitionID";
$Parameters    = [":_UserID@d" => $UserID, ":_CompetitionID@d" => $CompetitionID, ":_UpdateTime" => date("Y-m-d H:i:s")];
$Result        = ExecuteNonQuery($QueryString, $Parameters);
RunJS(["back" => ""]);
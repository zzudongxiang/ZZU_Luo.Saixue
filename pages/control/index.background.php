<?php require_once "/var/www/html/config.php";
$Result = GetDataTable("SELECT * FROM TIC_WebSite WHERE ConfigKey LIKE 'RT_Timer' OR ConfigKey LIKE 'RT_Web' OR ConfigKey LIKE 'RT_Web_URL' OR ConfigKey LIKE 'RT_Speaker' OR ConfigKey LIKE 'RT_Music' OR ConfigKey LIKE 'RT_Music_Voice' OR ConfigKey LIKE 'RT_Volume' OR ConfigKey LIKE 'RT_Speaker_Msg';");
$RTN    = array();
foreach($Result as $Item)
    $RTN[$Item["ConfigKey"]] = $Item["ConfigValue"];
$CurrUser = GetSingleResult("SELECT IFNULL(NikeName, '暂无') AS NikeName FROM TIC_UserInfo WHERE ID = (SELECT ConfigValue FROM TIC_WebSite WHERE ConfigKey LIKE 'SpeakerID')");
$Sort     = GetSingleResult("SELECT Sort  FROM Comp_RegistrationInfo WHERE UserID = (SELECT ConfigValue FROM TIC_WebSite WHERE ConfigKey LIKE 'SpeakerID') AND CompetitionID = (SELECT ConfigValue FROM TIC_WebSite WHERE ConfigKey LIKE 'JudgeCompetition')")["Sort"];
if(empty($Sort))
    $Sort = 0;
$QueryString        = "
    SELECT IFNULL(ID, 0) AS ID, IFNULL(NikeName, '暂无') AS NikeName
    FROM TIC_UserInfo 
    WHERE ID = (
        SELECT UserID 
        FROM Comp_RegistrationInfo 
        WHERE Sort = 1 + $Sort
        AND CompetitionID = (SELECT ConfigValue FROM TIC_WebSite WHERE ConfigKey LIKE 'JudgeCompetition')
        )
";
$NextUser           = GetSingleResult($QueryString);
$RTN["RT_Next_Msg"] = "当前: <$Sort> ".$CurrUser["NikeName"]." | 下一位: ".$NextUser["NikeName"];
$RTN["RT_Next"]     = $NextUser["ID"];
exit(json_encode($RTN));
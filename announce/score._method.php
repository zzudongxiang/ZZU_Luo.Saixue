<?php require_once "/var/www/html/config.php";
$QueryString = "
    SELECT TIC_Judge.ID, IFNULL(Judge_ScoreDetail.Score, '暂无') AS Score  
    FROM (SELECT * FROM TIC_Judge WHERE TIC_Judge.Enabled = 1) AS TIC_Judge LEFT JOIN Judge_ScoreDetail 
    ON  Judge_ScoreDetail.CompetitionID = (SELECT ConfigValue FROM TIC_WebSite WHERE ConfigKey LIKE 'JudgeCompetition')
    AND Judge_ScoreDetail.UserID = (SELECT ConfigValue FROM TIC_WebSite WHERE ConfigKey LIKE 'SpeakerID')
    AND Judge_ScoreDetail.JudgeID = TIC_Judge.ID ;
";
$Result      = GetDataTable($QueryString);
$Data        = array();
foreach($Result as $Row)
    $Data["score_".$Row["ID"]] = $Row["Score"];
exit(json_encode($Data));




<?php require_once "/var/www/html/config.php";
$UserID        = GetSingleResult("SELECT ConfigValue FROM TIC_WebSite WHERE ConfigKey LIKE 'SpeakerID';")["ConfigValue"];
$CompetitionID = GetSingleResult("SELECT ConfigValue FROM TIC_WebSite WHERE ConfigKey LIKE 'JudgeCompetition';")["ConfigValue"];
$ScoreList     = GetDataTable("SELECT Judge_ScoreDetail.Score, TIC_Judge.Weight FROM Judge_ScoreDetail JOIN TIC_Judge ON TIC_Judge.ID = Judge_ScoreDetail.JudgeID AND Judge_ScoreDetail.CompetitionID = $CompetitionID AND UserID = $UserID;");
$Length        = 0;
$Score         = 0;
$tmpArray      = array();
foreach($ScoreList as $Row)
{
    for($i = 0; $i < $Row["Weight"]; $i++)
    {
        array_push($tmpArray, $Row["Score"]);
        $Length++;
    }
}
for($i = 0; $i < $Length; $i++)
{
    for($j = 0; $j < $Length - 1 - $i; $j++)
    {
        if($tmpArray[$j] > $tmpArray[$j + 1])
        {
            $tmp              = $tmpArray[$j];
            $tmpArray[$j]     = $tmpArray[$j + 1];
            $tmpArray[$j + 1] = $tmp;
        }
    }
}
for($i = 1; $i < $Length - 1; $i++)
    $Score += $tmpArray[$i];
$Min = $tmpArray[0];
$Max = $tmpArray[$Length - 1];

for($i = 0; $i < 60; $i++)
{
    $X     = ($Min + $Max) / 2.0;
    $Value = code_x($X, $Score, $Length, $tmpArray);
    if($Value > 0)
        $Min = $X;
    else
        $Max = $X;
}
$Score = ($Min + $Max) / 2.0;
if($Score > 100)
    $Score = round($Score, 1);
else if($Score > 10)
    $Score = round($Score, 2);
else $Score = round($Score, 3);
ExecuteNonQuery("UPDATE Comp_RegistrationInfo SET Score = $Score WHERE CompetitionID = $CompetitionID AND UserID = $UserID;");

function code_x($X, $Score, $Length, $Data)
{
    $Args = 10.0;
    return ($Score + 2 * $X + ($X - $Score[0]) * exp(-($X - $Score[0]) / $Args) + ($Data[$Length - 1] - $X) * exp(-($Data[$Length - 1] - $X) / $Args)) / $Length - $X;
}

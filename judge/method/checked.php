<?php require_once "/var/www/html/config.php";
if(empty($_COOKIE["JudgeID"]))
    $GUID = "";
else $GUID = $_COOKIE["JudgeID"];
if(!empty($GUID))
{
    $OnlineStatus = GetSingleResult("SELECT OnlineStatus FROM TIC_Judge WHERE GUID LIKE '$GUID';");
    if(!empty($OnlineStatus))
    {
        if($OnlineStatus["OnlineStatus"] == 1)
        {
            setcookie("JudgeID", $GUID, time() + 18000, "/");
            if(empty($_GET["wait"]))
                $Waiting = "wait";
            else $Waiting = $_GET["wait"];
            $QueryString = "
            SELECT Score
            FROM Judge_ScoreDetail 
            WHERE JudgeID = (SELECT ID FROM TIC_Judge WHERE GUID LIKE '$GUID') 
                AND UserID = (SELECT ConfigValue FROM TIC_WebSite WHERE ConfigKey LIKE 'SpeakerID') 
                AND CompetitionID = (SELECT ConfigValue FROM TIC_WebSite WHERE ConfigKey LIKE 'JudgeCompetition')
            ";
            $ScoreDone   = GetSingleResult($QueryString);
            if(!empty($ScoreDone) && !empty($ScoreDone["Score"]) && $ScoreDone["Score"] >= 0)
                exit(json_encode((["url" => $Waiting == "wait" ? '#' : '/judge/wait.php'])));
            else
            {
                $Tmp = GetSingleResult("SELECT UserID FROM Comp_RegistrationInfo WHERE UserID = (SELECT ConfigValue FROM TIC_WebSite WHERE ConfigKey LIKE 'SpeakerID') AND CompetitionID = (SELECT ConfigValue FROM TIC_WebSite WHERE ConfigKey LIKE 'JudgeCompetition');");
                if(!empty($Tmp))
                {
                    if(empty($_COOKIE["SpeakerID"]))
                        $SpeakerID = "";
                    else $SpeakerID = $_COOKIE["SpeakerID"];
                    if($SpeakerID != $Tmp["UserID"])
                        $ExitURL = "/judge/score.php";
                    else $ExitURL = $Waiting == "wait" ? "/judge/score.php" : "#";
                    setcookie("SpeakerID", $Tmp["UserID"], time() + 18000, "/");
                    exit(json_encode((["url" => $ExitURL])));
                }
                else exit(json_encode(["url" => "#"]));
            }
        }
        else goto clear;
    }
    else goto clear;
}
else goto clear;

clear:
setcookie("JudgeID", "", time(), "/");
setcookie("SpeakerID", "", time(), "/");
exit(json_encode((["url" => '/judge/index.php'])));

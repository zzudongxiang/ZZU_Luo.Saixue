<?php require_once "/var/www/html/config.php";
$UserData = LoginChecked();
if(empty($_POST["method"]))
    die("请指定操作方法");
$ID         = $_POST["id"];
$Title      = $_POST["title"];
$Text       = $_POST["text"];
$Filter     = $_POST["filetype"];
$FileSize   = $_POST["filesize"];
$TimeArray  = explode(" - ", $_POST["time"]);
$UploadTime = $TimeArray[0];
$EndTime    = end($TimeArray);
if(!empty($_POST["enabled"]) && $_POST["enabled"] == "on")
    $Enabled = 1;
else $Enabled = 0;

if(!empty($_POST["mustshown"]) && $_POST["mustshown"] == "on")
    $MustShown = 1;
else $MustShown = 0;
$Parameters = [":_Filter" => $Filter, ":_FileSize@d" => $FileSize, ":_Title" => $Title, ":_Text" => $Text, ":_UploadTime" => $UploadTime, ":_EndTime" => $EndTime, ":_Enabled@d" => $Enabled, ":_MustShown" => $MustShown];

if($_POST["method"] == "del")
{
    $QueryString = "DELETE FROM TIC_Competition WHERE ID=:_ID;";
    $Result      = ExecuteNonQuery($QueryString, [":_ID@d" => $ID]);
}
else
{
    if($_POST["method"] == "insert")
    {
        $QueryString = "INSERT INTO TIC_Competition (Title, Text, UploadTime, EndTime, Enabled, Filter, FileSize, MustShown) VALUES (:_Title, :_Text, :_UploadTime, :_EndTime, :_Enabled, :_Filter, :_FileSize, :_MustShown)";
        $Result      = ExecuteNonQuery($QueryString, $Parameters);
        $ID          = GetSingleResult("SELECT MAX(ID) FROM TIC_Competition;")["MAX(ID)"];
        $TopicTable  = GetKeyValue();
        foreach($TopicTable as $Item)
        {
            $Key         = $Item["Key"];
            $Value       = $Item["Value"];
            $QueryString = "INSERT INTO Comp_Topic (CompetitionID, Text, Score) VALUES (:_CompetitionID, :_Text, :_Score);";
            $Parameters  = [":_CompetitionID@d" => $ID, ":_Text" => $Key, ":_Score" => $Value];
            $TmpResult   = ExecuteNonQuery($QueryString, $Parameters);
        }
    }
    if($_POST["method"] == "update")
    {
        $QueryString          = "UPDATE TIC_Competition SET Title = :_Title, Text=:_Text, UploadTime=:_UploadTime, EndTime=:_EndTime, Enabled=:_Enabled, Filter=:_Filter, FileSize=:_FileSize, MustShown=:_MustShown WHERE ID=:_ID;";
        $Parameters[":_ID@d"] = $ID;
        $Result               = ExecuteNonQuery($QueryString, $Parameters);
        $TopicTable           = GetKeyValue();
        foreach($TopicTable as $Item => $ItemValue)
        {
            $ID          = str_replace("_", "", $Item);
            $QueryString = "UPDATE Comp_Topic SET Text=:_Text, Score=:_Score WHERE ID = :_ID;";
            $Parameters  = [":_ID@d" => $ID, ":_Text" => $ItemValue["Key"], ":_Score" => $ItemValue["Value"]];
            $TmpResult   = ExecuteNonQuery($QueryString, $Parameters);
        }
    }
}

if(!$Result)
    RunJS(["alert" => "数据未保存"]);
RunJS(["js" => "parent.layer.closeAll();"]);

function GetKeyValue()
{
    $Result = array();
    foreach($_POST as $Key => $Value)
    {
        if(preg_match("/TOPIC_TITLE_\d/", $Key))
        {
            $ItemKey                 = "_".str_replace("TOPIC_TITLE_", "", $Key);
            $Result[$ItemKey]["Key"] = $Value;
        }
        else if(preg_match("/TOPIC_SCORE_\d/", $Key))
        {
            $ItemKey                   = "_".str_replace("TOPIC_SCORE_", "", $Key);
            $Result[$ItemKey]["Value"] = $Value;
        }
    }
    return $Result;
}

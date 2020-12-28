<?php require_once "/var/www/html/config.php";
if(!empty($_GET["type"]))
{
    $Type = $_GET["type"];
    if($Type == "name")
        $Key = "Name";
    else if($Type == "honor")
        $Key = "Honor";
    else if($Type == "remark")
        $Key = "Remark";
    else if($Type == "enabled")
        $Key = "Enabled";
    else if($Type == "topmost")
        $Key = "TopMost";
    else if($Type == "sort")
        $Key = "sort";
    else if($Type == "class")
        $Key = "class";
    else exit(json_encode(["code" => 0]));
    $Value = $_POST["value"];
    if($Value == "true")
        $Value = 1;
    else if($Value == "false")
        $Value = 0;
    if($Type == "class")
    {
        if($Value == 1)
            $Value = "MAT";
        else $Value = "ELE";
    }
    $ID = $_POST["id"];
    ExecuteNonQuery("UPDATE TIC_Honor SET $Key = '$Value' WHERE ID = $ID");
}
exit(json_encode(["code" => 0]));
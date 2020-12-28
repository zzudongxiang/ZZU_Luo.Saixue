<?php require_once "/var/www/html/config.php";
$Result    = GetDataTable("SELECT * FROM TIC_Judge");
$FormValue = array();
foreach($Result as $Row)
{
    $ID                 = $Row["ID"];
    $FormValue["W_$ID"] = $Row["Weight"];
    $FormValue["O_$ID"] = $Row["OnlineStatus"] == 1 ? true : false;
    $FormValue["E_$ID"] = $Row["Enabled"] == 1 ? true : false;
}
exit(json_encode($FormValue));
<?php require_once "/var/www/html/config.php";
$QueryString = "SELECT * FROM TIC_Hyperlink WHERE Type LIKE '脚注超链接' AND Enabled = 1 ORDER BY TopMost DESC, UpdateTime DESC;";
$LinkedData  = GetDataTable($QueryString);
$LinkHTML    = "";
if(count($LinkedData) > 0)
{
    $Width  = 1150 / count($LinkedData);
    $Height = $Width * 0.35;
    foreach($LinkedData as $Row)
    {
        $Img      = ShowImage("/data/images/hyperlink/", $Row["ImageName"], $Width, $Height, ["alt" => $Row["LinkName"],
            "style" => "border-radius: 20px;"]);
        $LinkHTML .= "<a href='".$Row["LinkURL"]."'>".$Img."</a>";
    }
}
else return;
?>
<br>
<h1 class='site-h1'>
    <i class='layui-icon layui-icon-website' style='font-size: 30px; color: #74ACDF;'>
        <b> 相 关 网 站 </b>
    </i>
</h1>
<br>
<div class='layui-row' style='text-align: center'>
    <?php echo $LinkHTML ?>
</div>
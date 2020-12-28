<script type="text/javascript" src="/modules/sys/sys.js"></script>
<?php require_once "/var/www/html/config.php";
$UserData       = LoginChecked();
$PagesParameter = GetPagesParameters();
$Result         = GetDataTable($QueryString, $Parameters);
$ResultCount    = GetSingleResult($CountQuery, $CountParameters)["COUNT(*)"];

if(count($Result) > 0)
{
    $TableData = "";
    foreach($Result as $Row)
    {
        $Status = "<span class='layui-badge layui-bg-blue'>未开始</span>";
        if(strtotime(date("Y-m-d H:i:s")) > strtotime($Row["UploadTime"]))
            $Status = "<span class='layui-badge layui-bg-orange'>进行中</span>";
        if(strtotime(date("Y-m-d H:i:s")) > strtotime($Row["EndTime"]))
            $Status = "<span class='layui-badge layui-bg-cyan'>已结束</span>";

        $ButtonID  = $Row["ID"];
        $TableData .= "
        <tr>
            <td style='font-size: 20px'>
                <a href=javascript:ShowDialog('查看竞赛信息','/pages/competition/view.php?id=".$Row["ID"]."',0);>
                ".$Row["Title"]."
                </a>
            </td>  
            <td style='text-align: center;'>$Status</td>
            <td>
                <button class='layui-btn layui-btn-radius' 
                        onclick=ShowDialog('查看竞赛信息','/pages/competition/view.php?id=".$Row["ID"]."',0);>
                    <i class='layui-icon layui-icon-app'> 详细信息</i>
                </button>
            </td>    
        </tr>
        ";
    }
}
else $TableData = "<tr><td>未查询到比赛信息</td></tr>";
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <?php include_once PATH."/modules/body/head.php"; ?>
</head>
<body class="sys-body-style">
<?php include_once PATH."/modules/navigation/menu.php"; ?>
<div class="sys-console-body">
    <div class="sys-nav">
        <?php include_once PATH."/modules/navigation/nav.php"; ?>
    </div>
    <div class="sys-panel">
        <h1 class="site-h1"><?php echo $PageTitle; ?></h1>
        <hr class="layui-bg-blue">

        <table class="layui-table" style="text-align: left;" lay-skin="nob">
            <colgroup>
                <col>
                <col width="80">
                <col width="120">
            </colgroup>

            <?php echo $TableData ?>
        </table>
        <?php include_once PATH."/modules/pages.php"; ?>

    </div>
</div>
<?php include_once PATH."/modules/body/footer.php"; ?>
</body>
</html>
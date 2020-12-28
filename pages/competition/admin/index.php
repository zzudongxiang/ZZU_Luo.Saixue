<?php require_once "/var/www/html/config.php";
$UserData        = LoginChecked();
$ButtonStyle     = $UserData["AdminRole"] <= 1 ? ["disabled", "layui-btn-disabled"] : ["", ""];
$PagesParameters = GetPagesParameters();
$QueryString     = "
    SELECT TIC_Competition.ID, TIC_Competition.Title, TIC_Competition.UploadTime, TIC_Competition.EndTime, TIC_Competition.Enabled, IFNULL(tb_Count.Count, 0) AS Count
    FROM TIC_Competition LEFT JOIN 
        (SELECT CompetitionID, COUNT(*) AS Count FROM Comp_RegistrationInfo WHERE Status = 1 GROUP BY CompetitionID) AS tb_Count
    ON TIC_Competition.ID = tb_Count.CompetitionID
    AND Title LIKE :_SearchKey
    ORDER BY ID DESC LIMIT :_StartIndex, :_Limit;";
$Parameters      = [":_StartIndex@d" => $PagesParameters["StartIndex"], ":_Limit@d" => $PagesParameters["Limit"], ":_SearchKey" => "%".$PagesParameters["SearchKey"]."%"];
$Result          = GetDataTable($QueryString, $Parameters);
$ResultCount     = GetSingleResult("SELECT COUNT(*) FROM TIC_Competition WHERE Title LIKE :_SearchKey;", [":_SearchKey" => "%".$PagesParameters["SearchKey"]."%"])["COUNT(*)"];
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

        if($Row["Enabled"] == "0")
            $Status .= "<br><span class='layui-badge layui-bg-gray'>入口关闭</span>";

        $TableData .= "
    <tr>
        <td style='text-align: left'>[".$Row["ID"]."] ".$Row["Title"]."</td>
        <td>$Status</td>
        <td>".$Row["Count"]."</td>
        <td>
            <div class='layui-btn-group'>
                <button type='button' 
                        $ButtonStyle[0]
                        class='layui-btn layui-btn-warm $ButtonStyle[1]' 
                        onclick=ShowDialog('复制竞赛信息','/pages/competition/admin/edit.php?id=".$Row["ID"]."&mth=copy&tmp=','-1')>
                    复制
                </button>
                <button type='button' 
                        $ButtonStyle[0]
                        class='layui-btn layui-btn-normal $ButtonStyle[1]' 
                        onclick=ShowDialog('编辑竞赛信息','/pages/competition/admin/edit.php?id=','".$Row["ID"]."')>
                    编辑
                </button>
                <button type='button' 
                        class='layui-btn' 
                        onclick=top.location.href='/pages/competition/admin/view.php?id=".$Row["ID"]."'>
                    查看
                </button>
            </div>
        </td>
    </tr>
    ";
    }
}
else $TableData = "<tr><td colspan='4'>暂无相关数据</td></tr>";

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
        <h1 class="site-h1">竞赛管理</h1>
        <hr class="layui-bg-blue">
        <div style="display: block">
            <div style="float: left">
                <button <?php echo $ButtonStyle[0]; ?> class='layui-btn <?php echo $ButtonStyle[1]; ?>'
                                                       onclick=ShowDialog('添加新的竞赛','/pages/competition/admin/edit.php?id=','-1')>
                    <i class="layui-icon layui-icon-add-circle"> 添加</i>
                </button>
            </div>
            <div style="float: right">
                <button class="layui-btn layui-btn-normal" onclick="searching()" type="submit">
                    <i class="layui-icon layui-icon-search"> 查询</i>
                </button>
            </div>
            <div style="float: right; margin: 0 15px">
                <div class="layui-form">
                    <input id="search-key"
                           type="text"
                           class="layui-input"
                           placeholder="请输入搜索关键字"
                           value="<?php echo $PagesParameters["SearchKey"]; ?>">
                </div>
            </div>
        </div>
        <div style="clear: both;"></div>
        <table class="layui-table" style="text-align: center">
            <colgroup>
                <col>
                <col width="100">
                <col width="60">
                <col width="230">
            </colgroup>
            <thead>
            <tr>
                <th style="text-align: center">赛事名称</th>
                <th style="text-align: center">状态</th>
                <th style="text-align: center">报名</th>
                <th style="text-align: center">操作</th>
            </tr>
            </thead>
            <?php echo $TableData ?>
        </table>
        <?php include_once PATH."/modules/pages.php"; ?>
    </div>
</div>
<?php include_once PATH."/modules/body/footer.php"; ?>
</body>
</html>
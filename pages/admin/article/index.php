<?php require_once "/var/www/html/config.php";
LoginChecked();
$PagesConfig = GetPagesParameters();
$QueryString = "SELECT * FROM TIC_Article WHERE Title LIKE :_SearchKey ORDER BY TopMost Desc, UpdateTime DESC, ID DESC LIMIT :_StartIndex, :_Limit;";
$Parameters  = [":_StartIndex@d" => $PagesConfig["StartIndex"], ":_Limit@d" => $PagesConfig["Limit"], ":_SearchKey" => "%".$PagesConfig["SearchKey"]."%"];
$Result      = GetDataTable($QueryString, $Parameters);
$ResultCount = GetSingleResult("SELECT COUNT(*) FROM TIC_Article WHERE Title LIKE :_SearchKey", [":_SearchKey" => "%".$PagesConfig["SearchKey"]."%"])["COUNT(*)"];
$TableData   = "";
if(count($Result) > 0)
{
    foreach($Result as $Row)
    {
        if($Row["TopMost"] == "1")
            $TopMost = "<span style='float: left; margin: 2px 5px' class='layui-badge'>置顶</span>";
        else $TopMost = "";

        $Title = $TopMost.$Row["Title"]."　<i class='layui-icon layui-icon-read' style='color: #0086e6'>:".$Row["ViewCount"]."</i>";

        if($Row["Type"] == "新闻")
            $Type = "<span class='layui-badge layui-bg-orange'>".$Row["Type"]."</span>";
        else if($Row["Type"] == "通知")
            $Type = "<span class='layui-badge layui-bg-blue'>".$Row["Type"]."</span>";
        else $Type = "<span class='layui-badge layui-bg-green'>".$Row["Type"]."</span>";
        $TableData .= "
    <tr>
        <td>".$Row["ID"]."</td>
        <td>".$Row["UpdateTime"]."</td>
        <td>$Type</td>
        <td style='text-align: left'>".$Title."</td>
        <td>".$Row["Author"]."</td>
        <td>
            <div class='layui-btn-group'>
                <button class='layui-btn' 
                    title='编辑当前内容'
                    onclick=ShowDialog('编辑文章','/pages/admin/article/edit.php?id=','".$Row["ID"]."')>
                    <i class='layui-icon layui-icon-edit'></i>
                </button>
                <button class='layui-btn layui-btn-normal' 
                    title='复制当前内容'
                    onclick=ShowDialog('复制文章','/pages/admin/article/edit.php?type=copy&id=','".$Row["ID"]."')>
                    <i class='layui-icon layui-icon-file'></i>
                </button>
            </div>
            
        </td>
    </tr>
    ";
    }
}
else $TableData = "<tr><td colspan='6'>暂无相关文章内容</td></tr>"
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
        <h1 class="site-h1">文章管理</h1>
        <hr class="layui-bg-blue">
        <div style="display: block">
            <div style="float: left">
                <button class='layui-btn' onclick=ShowDialog('添加文章','/pages/admin/article/edit.php?type=copy&id=','-1')>
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
                           value="<?php echo $PagesConfig["SearchKey"]; ?>">
                </div>
            </div>
        </div>
        <div style="clear: both;"></div>
        <table class="layui-table" style="text-align: center">
            <colgroup>
                <col width="60">
                <col width="120">
                <col width="80">
                <col>
                <col width="80">
                <col width="150">
            </colgroup>
            <thead>
            <tr>
                <th style="text-align: center">序号</th>
                <th style="text-align: center">发布时间</th>
                <th style="text-align: center">类型</th>
                <th style="text-align: center">标题</th>
                <th style="text-align: center">作者</th>
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
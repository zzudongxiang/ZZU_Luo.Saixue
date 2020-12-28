<?php require_once "/var/www/html/config.php";
LoginChecked();
$PagesParameters = GetPagesParameters();
$ResultCount     = GetSingleResult("SELECT COUNT(*) FROM TIC_Hyperlink WHERE LinkName LIKE :_SearchKey OR Type LIKE :_SearchKey;", [":_SearchKey" => "%".$PagesParameters["SearchKey"]."%"])["COUNT(*)"];
$QueryString     = "SELECT * FROM TIC_Hyperlink WHERE LinkName LIKE :_SearchKey OR Type LIKE :_SearchKey ORDER BY TopMost Desc, Enabled DESC, UpdateTime DESC LIMIT :_StartIndex, :_Limit;";
$Parameters      = [":_StartIndex@d" => $PagesParameters["StartIndex"], ":_Limit@d" => $PagesParameters["Limit"], ":_SearchKey" => "%".$PagesParameters["SearchKey"]."%"];
$Result          = GetDataTable($QueryString, $Parameters);
$TableData       = "";
if(!empty($Result))
{
    foreach($Result as $Row)
    {
        $Shown     = $Row["Enabled"] == 1 ? "<b style='color: #0C0C0C'>显示</b>" : "<b style='color: #FF0000'>隐藏</b>";
        $TopMost   = $Row["TopMost"] == 1 ? "<span style='float: left; margin: 2px 5px' class='layui-badge'>置顶</span>" : "";
        $TableData .= "
        <tr>
            <td>".$Row["ID"]."</td>
            <td>".$Row["UpdateTime"]."</td>
            <td>".$Row["Type"]."</td>
            <td style='text-align: left'>".$Row["LinkName"]."$TopMost</td>
            <td>".$Shown."</td>
            <td>
                <button class='layui-btn' onclick=Dialog('编辑超链接数据','/pages/admin/setting/hyperlink.edit.php?id=','".$Row["ID"]."')>
                    <i class='layui-icon layui-icon-edit'> 编辑</i>
                </button>
            </td>
        </tr>
        ";
    }
}
else $TableData = "<tr><td colspan='6'>暂无数据</td></tr>";
?>
<div style="display: block">
    <div style="float: left">
        <button class='layui-btn' onclick=ShowDialog('添加超链接数据','/pages/admin/setting/hyperlink.edit.php?id=','-1')>
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
        <col width="60">
        <col width="120">
        <col width="120">
        <col>
        <col width="80">
        <col width="80">
    </colgroup>
    <thead>
    <tr>
        <th style="text-align: center">序号</th>
        <th style="text-align: center">更新时间</th>
        <th style="text-align: center">类型</th>
        <th style="text-align: center">名称</th>
        <th style="text-align: center">状态</th>
        <th style="text-align: center">操作</th>
    </tr>
    </thead>
    <?php echo $TableData ?>
</table>
<?php include_once PATH."/modules/pages.php"; ?>

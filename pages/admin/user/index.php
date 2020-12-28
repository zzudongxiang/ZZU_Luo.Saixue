<?php require_once "/var/www/html/config.php";
$UserData        = LoginChecked();
$PagesParameters = GetPagesParameters();
$SearchMessage   = "";
switch($PagesParameters["SearchType"])
{
    case "num":
        $PagesParameters["SearchType"] = "WHERE UserName LIKE :_Key";
        $SearchMessage                 = "Searching: 学号->".$PagesParameters["SearchKey"];
        break;
    case "name":
        $PagesParameters["SearchType"] = "WHERE NikeName LIKE :_Key";
        $SearchMessage                 = "Searching: 姓名->".$PagesParameters["SearchKey"];
        break;
    case "oth":
        $PagesParameters["SearchType"] = "WHERE OtherInfo LIKE :_Key";
        $SearchMessage                 = "Searching: 其他信息->".$PagesParameters["SearchKey"];
        break;
    default:
        $PagesParameters["SearchType"] = "";
        $SearchMessage                 = "";
        break;
}
$Parameters = [":_StartIndex@d" => $PagesParameters["StartIndex"], ":_Limit@d" => $PagesParameters["Limit"]];
if(!empty($PagesParameters["SearchType"]))
{
    $Parameters[":_Key"] = "%".$PagesParameters["SearchKey"]."%";
    $CountParameter      = [":_Key" => "%".$PagesParameters["SearchKey"]."%"];
}
else $CountParameter = null;
$QueryString = "SELECT * FROM TIC_UserInfo ".$PagesParameters["SearchType"]." ORDER BY AdminRole DESC, UserName Desc LIMIT :_StartIndex, :_Limit;";
$Result      = GetDataTable($QueryString, $Parameters);
$ResultCount = GetSingleResult("SELECT COUNT(*) FROM TIC_UserInfo ".$PagesParameters["SearchType"], $CountParameter)["COUNT(*)"];

$TableData = "";
if(count($Result) > 0)
{
    foreach($Result as $Row)
    {
        if($Row["AdminRole"] == 1)
            $AdminRole = "<i class='layui-icon layui-icon-username' style='color: #7ed33e'> </i>";
        else if($Row["AdminRole"] == 999)
            $AdminRole = "<i class='layui-icon layui-icon-friends' style='color: #1e9ff9'> </i>";
        else $AdminRole = "";

        $TableData .= "
        <tr>
            <td>".$Row["ID"]."</td>
            <td>$AdminRole".$Row["NikeName"]."</td>
            <td>".$Row["UserName"]."</td>
            <td style='text-align: left'>".$Row["OtherInfo"]."</td>
            <td>
                <button class='layui-btn' onclick=ShowDialog('编辑用户信息','/pages/admin/user/edit.php?id=','".$Row["ID"]."')>
                    <i class='layui-icon layui-icon-edit'> 编辑</i>
                </button>
            </td>
        </tr>
        ";
    }
}
else $TableData = "<tr><td colspan='5'>暂无相关用户信息</td></tr>"
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
        <h1 class="site-h1">用户管理</h1>
        <hr class="layui-bg-blue">
        <div style="display: inline-block">
            <div class="layui-btn-group" style="float:left;">
                <button type="button" class="layui-btn"
                        onclick="ShowDialog('添加用户信息','/pages/admin/user/edit.php?id=','-1')">
                    <i class="layui-icon layui-icon-friends"> 添加</i>
                </button>
                <button type="button" class="layui-btn" onclick="ShowDialog('导入用户数据','import.php?id=','0')">
                    <i class="layui-icon layui-icon-group"> 导入</i>
                </button>
                <button type="button" class="layui-btn"
                        onclick="ShowDialog('按条件搜索用户',
                                'search.php?type=<?php echo empty($_GET["type"]) ? "" : $_GET["type"]; ?>'+
                                '&key=<?php echo empty($_GET["key"]) ? "" : $_GET["key"]; ?>&id=','-1')">
                    <i class="layui-icon layui-icon-search"> 搜索</i>
                </button>
            </div>
            <div class="layui-form-mid layui-word-aux" style="margin: auto 10px"><?php echo $SearchMessage; ?></div>
        </div>
        <table class="layui-table" style="text-align: center">
            <colgroup>
                <col width="60">
                <col width="120">
                <col width="150">
                <col>
                <col width="100">
            </colgroup>
            <thead>
            <tr>
                <th style="text-align: center">序号</th>
                <th style="text-align: center">姓名</th>
                <th style="text-align: center">学号</th>
                <th style="text-align: center">其他信息</th>
                <th style="text-align: center">操作</th>
            </tr>
            </thead>
            <?php echo $TableData ?>
        </table>
        <?php include_once PATH."/modules/pages.php"; ?>
    </div>
</div>
<?php include_once PATH."/modules/body/footer.php"; ?>
<script>
    layui.use('form', function () {
        let form = layui.form;
    });
</script>
</body>
</html>
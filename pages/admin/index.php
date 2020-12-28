<?php require_once "/var/www/html/config.php";
LoginChecked();
$Page = ["website_title" => "", "website_tab" => "", "hyperlink_title" => "", "hyperlink_tab" => "", "honor_title" => "", "honor_tab" => ""];
$str  = "website";
if(!empty($_GET["tab"]) && ($_GET["tab"] === "hyperlink" || $_GET["tab"] === "honor"))
    $str = $_GET["tab"];
$Page [$str."_title"] = "layui-this";
$Page [$str."_tab"]   = "layui-show";
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
        <h1 class="site-h1">网站管理</h1>
        <hr class="layui-bg-blue">

        <div class="layui-tab layui-tab-card" style="box-shadow: none;" lay-filter="tab-panel">
            <ul class="layui-tab-title">
                <li id="website" class="<?php echo $Page["website_title"]; ?>">网站设置</li>
                <li id="hyperlink" class="<?php echo $Page["hyperlink_title"]; ?>">链接管理</li>
                <li id="honor" class="<?php echo $Page["honor_title"]; ?>">榜单管理</li>
            </ul>
            <div class="layui-tab-content">
                <div class="layui-tab-item <?php echo $Page["website_tab"]; ?>"><?php include_once PATH."/pages/admin/setting/website.php"; ?></div>
                <div class="layui-tab-item <?php echo $Page["hyperlink_tab"]; ?>"><?php include_once PATH."/pages/admin/setting/hyperlink.php"; ?></div>
                <div class="layui-tab-item <?php echo $Page["honor_tab"]; ?>"><?php include_once PATH."/pages/admin/setting/honor.php"; ?></div>
            </div>
        </div>
    </div>
</div>
<?php include_once PATH."/modules/body/footer.php"; ?>
<script>
    layui.use('element', function () {
        layui.element.on('tab(tab-panel)', function (data) {
            new SetParam("tab", this.id);
        });
    });
</script>
</body>
</html>
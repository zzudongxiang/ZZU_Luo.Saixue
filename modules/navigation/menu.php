<?php require_once "/var/www/html/config.php";;
if(!($UserData = LoginChecked(False)))
{
    $UserName = "登 录";
    $MenuList = "<dd><a href='/pages/login.php'>登　　录</a></dd>";
    $Click    = "/pages/login.php";
}
else
{
    $UserName = $UserData["NikeName"];
    $MenuList = "<dd><a href='/pages/console/index.php'>个人中心</a></dd>";
    $MenuList .= "<dd><a href='/pages/login._method.php?login=false'>退　　出</a></dd>";
    $Click    = "javascript:;";
}
?>
<img src="/images/banner_top.png" alt="">
<div class="layui-header">
    <ul class="layui-nav" style="background-color: #2257c9;">
        <li class="layui-nav-item"><a href="/index.php"><b style="font-size: 20px; color: #FFFFFF">首页</b></a></li>
        <li class="layui-nav-item">
            <a href="/pages/public/article.php?id=51" style="font-size: 20px; color: #FFFFFF">大赛简介</a>
        </li>
        <li class="layui-nav-item">
            <a href="/pages/public/article.php?id=50" style="font-size: 20px; color: #FFFFFF">赛学模式</a>
        </li>
        <li class="layui-nav-item">
            <a href="/pages/competition/index.php" style="font-size: 20px; color: #FF0000">赛事报名</a>
        </li>
        <li class="layui-nav-item">
            <a href="/judge/index.php" style="font-size: 20px; color: #FF0000">评委入口</a>
        </li>
<!--
        <li class="layui-nav-item">
            <a href="#" style="font-size: 20px; color: #FFFFFF">软件下载中心</a>
        </li>
        <li class="layui-nav-item">
            <a href="#" style="font-size: 20px; color: #FFFFFF">项目托管平台</a>
	</li>
        <li class="layui-nav-item"><a href="javascript:">基地概述</a>
            <dl class="layui-nav-child">
                <dd><a href="">基地概述</a></dd>
            </dl>
        </li>
        -->
        <li class="layui-nav-item layui-layout-right UserInfo" style="margin-right: 20px">
            <a href="<?php echo $Click; ?>">
                <img src="/images/user.jpg" class="layui-nav-img" alt="个人中心">
                <i class="layui-icon layui-icon-username" style="font-size: 14px;"><?php echo $UserName; ?></i>
            </a>
            <dl class="layui-nav-child"><?php echo $MenuList; ?></dl>
        </li>
    </ul>
</div>
<script>
    layui.use("element", function () {
        const element = layui.element;
        element.render();
    });
</script>

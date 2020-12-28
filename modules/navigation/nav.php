<?php require_once "/var/www/html/config.php";
if(!$UserData = LoginChecked(false))
    return;
?>
<ul class="layui-nav layui-nav-tree" layui-filter="nav" style="background-color: #3e6ccf;">
    <li class="layui-nav-item layui-nav-itemed">
        <a><i class="layui-icon layui-icon-form sys-nav-item-title" style="color: #FFFFFF">　个人中心</i></a>
        <dl class="layui-nav-child">
            <dd><a href="/pages/console/index.php">个人信息查看</a></dd>
            <dd><a href="/pages/console/user/pwd.php">修改登录密码</a></dd>
        </dl>
    </li>
    <?php
    if($UserData["AdminRole"] != 1)
        echo "
            <li class='layui-nav-item layui-nav-itemed'>
                <a><i class='layui-icon layui-icon-form sys-nav-item-title' style='color: #FFFFFF'>　赛事中心</i></a>
                <dl class='layui-nav-child'>
                    <dd><a href='/pages/competition/index.php'>赛事报名</a></dd>
                    <dd><a href='/pages/competition/user.php'>我的赛事</a></dd>
                </dl>
            </li>
            ";
    ?>

    <?php
    if($UserData["AdminRole"] > 0)
        include_once PATH."/modules/navigation/nav.admin.php";
    ?>
</ul>
<script>
    layui.use("element", function () {
        const element = layui.element;
    });
</script>
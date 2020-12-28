<li class="layui-nav-item layui-nav-itemed">
    <a><i class="layui-icon layui-icon-form sys-nav-item-title" style="color: #FFFFFF">　竞赛管理</i></a>
    <dl class="layui-nav-child">
        <dd><a href="/pages/competition/admin/index.php">竞赛管理</a></dd>
        <dd><a href="/pages/judge/judge/index.php">评委管理</a></dd>
        <dd><a href="/pages/judge/index.php">评分管理</a></dd>
    </dl>
</li>
<li class="layui-nav-item layui-nav-itemed">
    <a><i class="layui-icon layui-icon-form sys-nav-item-title" style="color: #FFFFFF">　现场管理</i></a>
    <dl class="layui-nav-child">
        <dd><a href="/pages/control/index.php">现场控制</a></dd>
        <dd><a href="/pages/ppt/index.php">PPT配置</a></dd>
    </dl>
</li>
<?php
if($UserData["AdminRole"] <= 1)
    return;
?>
<!-- 当前用户如果具有管理员权限, 则以下列表将会开放给当前用户使用 -->
<li class="layui-nav-item layui-nav-itemed">
    <a><i class="layui-icon layui-icon-form sys-nav-item-title" style="color: #FFFFFF">　网站管理</i></a>
    <dl class="layui-nav-child">
        <dd><a href="/pages/admin/index.php">网站管理</a></dd>
        <dd><a href="/pages/admin/article/index.php">文章管理</a></dd>
        <dd><a href="/pages/admin/user/index.php">用户管理</a></dd>
    </dl>
</li>

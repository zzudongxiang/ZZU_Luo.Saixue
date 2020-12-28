<?php require_once "/var/www/html/config.php";
$Data = GetSingleResult("SELECT * FROM TIC_Article WHERE ID = :_ID;", [":_ID" => $_GET["id"]]);
if(empty($Data))
{
    $Title = "您所查找的内容不存在";
    $Info  = "该文章可能已被管理员删除, 请返回首页查阅最新动态";
    $Text  = "
        <div style='text-align: center'>
            <img src='/images/404.png' style='margin: auto' alt='找不到内容'>
            <br><br>
            <a href='/' class='layui-btn layui-btn-normal'>点击返回首页 &gt;&gt;&gt;</a>
            <br><br>
        </div>";
}
else
{
    ExecuteNonQuery("UPDATE TIC_Article SET ViewCount = ViewCount + 1 WHERE ID = :_ID; ", [":_ID@d" => $_GET["id"]]);
    $Title = $Data["Title"];
    $Info  = "信息类型: <b>".$Data["Type"]."</b>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;"."发布时间: <b>".$Data["UpdateTime"]."</b>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;"."信息来源: <b>".$Data["Author"]."</b>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;"."阅读次数: <b>".$Data["ViewCount"]."</b>";
    $Text  = $Data["Text"];
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <?php include_once PATH."/modules/body/head.php"; ?>
</head>
<body class="sys-body-style">
<?php include_once PATH."/modules/navigation/menu.php"; ?>
<div style="padding: 10px; background-color: #FFFFFF">
    <?php
    if($Data["Type"] == "新闻")
        echo "<a href='/pages/public/all-article.php?type=news'><img src='/images/news_banner.png' alt='新闻中心'></a>";
    else if($Data["Type"] == "通知")
        echo "<a href='/pages/public/all-article.php?type=notice'><img src='/images/notice_banner.png' alt='通知公告'></a>";
    else
        echo "<a href='/'><img src='/images/web_banner.png' alt='网站简介'></a>";
    ?>
    <br>
    <hr>
    <br>
    <div style="width: 800px; margin: auto; padding: 20px 0 50px 0">
        <h1 style="text-align: center"><b><?php echo $Title ?></b></h1><br>
        <div style="text-align: center; background-color: #c1c1c1; padding: 5px; height: 28px; ">
            <h3><?php echo $Info ?></h3>
        </div>
        <br>
        <div style="font-size: 18px;"><?php echo $Text ?></div>
    </div>
</div>
<?php include_once PATH."/modules/body/footer.php"; ?>
</body>
</html>

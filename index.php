<?php require_once "/var/www/html/config.php"; 
#exit("Test");
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <?php include_once PATH."/modules/body/head.php"; ?>
</head>
<body class="sys-body-style">
<?php include_once PATH."/modules/navigation/menu.php"; ?>
<br>
<h1 class="site-h1">
    <i class="layui-icon layui-icon-read" style="font-size: 30px; color: #74ACDF;"><b> 新 闻 中 心 </b></i>
</h1>
<br>
<div style="width: 100%">
    <div style="margin: auto">
        <div style="width: 33.3%; float: left">
            <?php include_once PATH."/pages/modules/carousel.php" ?>
        </div>
        <div style="width: 66.6%; float: left">
            <?php
            $PublicNoticeConfig["Style"]     = "layui-bg-cyan";
            $PublicNoticeConfig["Title"]     = "新 闻 中 心";
            $PublicNoticeConfig["Href"]      = "/pages/public/all-article.php?type=news";
            $PublicNoticeConfig["TableData"] = GetDataTable("SELECT * FROM TIC_Article WHERE Type LIKE '新闻' order by TopMost DESC, UpdateTime DESC, ID DESC LIMIT 5;");
            include PATH."/pages/modules/home-article.php"
            ?>
        </div>
    </div>
</div>
<div style="clear: both"></div>
<br>
<h1 class="site-h1">
    <i class="layui-icon layui-icon-speaker" style="font-size: 30px; color: #74ACDF;"><b> 通 知 公 告 </b></i>
</h1>
<br>
<div style="width: 100%">
    <div style="margin: auto">
        <div style="width: 66.6%; float: left">
            <?php
            $PublicNoticeConfig["Style"]     = "layui-bg-green";
            $PublicNoticeConfig["Title"]     = "通 知 公 告";
            $PublicNoticeConfig["Href"]      = "/pages/public/all-article.php?type=notice";
            $PublicNoticeConfig["TableData"] = GetDataTable("SELECT * FROM TIC_Article WHERE Type LIKE '通知' order by TopMost DESC, UpdateTime DESC, ID DESC LIMIT 5;");
            include PATH."/pages/modules/home-article.php"
            ?>
        </div>
        <div style="width: 33.3%; float: left">
            <img src="/images/inner_notice.png">
        </div>
    </div>
</div>
<div style="clear: both"></div>
<br>
<img src="/images/banner_mid_2.png" alt="">
<br><br>
<h1 class='site-h1'>
    <a href="/pages/honor/index.php">
        <i class='layui-icon layui-icon-group' style='font-size: 30px; color: #74ACDF;'>
            <b> 荣 誉 榜 单 </b>
        </i>
    </a>
</h1>
<br>
<?php
$TYPE = "MAT";
include PATH."/pages/modules/honor.php";
?>
<br>
<?php
$TYPE = "ELE";
include PATH."/pages/modules/honor.php";
?>
<br>
<img src="/images/banner_mid_1.png" alt="">
<br>
<?php include_once PATH."/pages/modules/footerlink.php"; ?>
<br>
<?php include_once PATH."/modules/body/footer.php"; ?>
</body>
</html>

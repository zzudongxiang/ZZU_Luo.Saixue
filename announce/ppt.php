<?php require_once "/var/www/html/config.php";
$PPT_ID = GetSingleResult("SELECT ConfigValue FROM TIC_WebSite WHERE ConfigKey LIKE 'RT_PPT';")["ConfigValue"];
$Img    = ShowImage("/data/images/ppt/", $PPT_ID, 1880, 1080, ["id" => "imgs"]);
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="/modules/sys/sys.css">
    <link rel="stylesheet" href="/css/layui.css">
    <script src="/lay/jquery-1.3.2.min.js"></script>
</head>
<body style="background-color: #000000" onload="ppt_background()">
<div id="shows" style="text-align: center; width: 1920px; margin: auto;">
    <?php echo $Img ?>
</div>
<script>
    function ppt_background() {
        window.setInterval(function () {
            $.ajax({
                url: "/announce/ppt._method.php",
                dataType: 'json',
                type: 'post',
                success: function (data) {
                    $("#shows").html(data["html"]);
                }
            });
        }, 1000);
    }
</script>
</body>
</html>
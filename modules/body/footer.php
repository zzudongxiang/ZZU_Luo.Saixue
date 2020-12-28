<?php require_once "/var/www/html/config.php";
$QueryString = "SELECT ConfigValue FROM TIC_WebSite WHERE ConfigKey LIKE 'WebSite_Footer';";
$FooterData  = GetSingleResult($QueryString)["ConfigValue"];
?>
<hr class="layui-bg-blue">
<footer class='layui-footer' style="text-align: center;"><?php echo $FooterData; ?></footer>
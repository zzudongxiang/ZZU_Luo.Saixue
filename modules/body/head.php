<?php require_once "/var/www/html/config.php";
$QueryString = "SELECT ConfigValue FROM TIC_WebSite WHERE ConfigKey LIKE 'WebSite_Title';";
$WebTitle    = GetSingleResult($QueryString)["ConfigValue"];
?>
<meta charset="utf-8">

<title><?php echo $WebTitle; ?></title>
<link rel="icon" href="/images/icon.ico">

<link rel="stylesheet" href="/modules/sys/sys.css">

<link rel="stylesheet" href="/css/layui.css">

<script src="/lay/jquery-3.4.1.min.js"></script>
<script src="/layui.js"></script>

<script type="text/javascript" src="/modules/editor/wangEditor.min.js"></script>

<script type="text/javascript" src="/modules/sys/sys.js"></script>
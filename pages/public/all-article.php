<?php require_once "/var/www/html/config.php";
if(!empty($_GET["type"]))
{
    $TypeText  = $_GET["type"] == "news" ? "新闻" : "通知";
    $TypeTitle = $_GET["type"] == "news" ? "新闻中心" : "通知公告";
    $TypeImg   = $_GET["type"] == "news" ? "news_banner.png" : "notice_banner.png";
}
else return;

$PagesConfig = GetPagesParameters();
$ResultCount = GetSingleResult("SELECT COUNT(*) FROM TIC_Article WHERE Type LIKE '$TypeText'")["COUNT(*)"];
$QueryString = "SELECT * FROM TIC_Article  WHERE Type LIKE '$TypeText' ORDER BY UpdateTime DESC, ID DESC LIMIT :_StartIndex, :_Length;";
$Parameters  = [":_StartIndex@d" => ($PagesConfig["Current"] - 1) * $PagesConfig["Limit"], ":_Length@d" => $PagesConfig["Limit"]];
$Data        = GetDataTable($QueryString, $Parameters);
$TableData   = "";

foreach($Data as $Row)
{
    $TableData .= "
    <tr>
        <td><b style='width: 80px; font-size: 18px'>".$Row["UpdateTime"]."</b></td>
        <td style='text-align: left; font-size: 18px'>
            <a href='/pages/public/article.php?id=".$Row["ID"]."' target='_blank'><span >".$Row["Title"]."</span></a>
        </td>
    </tr>";
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
    <a href="/pages/public/all-article.php?type=<?php echo $_GET["type"]; ?>">
        <img src="/images/<?php echo $TypeImg; ?>" alt="<?php echo $TypeTitle; ?>">
    </a>
    <br>
    <hr>
    <div style="padding: 10px 30px;">
        <table lay-even class="layui-table" lay-skin="nob" style="text-align: center">
            <colgroup>
                <col width="150">
                <col>
            </colgroup>
            <?php echo $TableData ?>
        </table>
    </div>
    <hr>
    <?php include_once PATH."/modules/pages.php"; ?>
</div>
<?php include_once PATH."/modules/body/footer.php"; ?>
</body>
</html>
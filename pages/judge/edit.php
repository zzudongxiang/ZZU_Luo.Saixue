<?php require_once "/var/www/html/config.php";
$UserID   = $_GET["id"];
$UserInfo = GetSingleResult("SELECT * FROM TIC_UserInfo WHERE ID = $UserID");
if(empty($UserInfo))
    $Title = "暂无学生信息";
else $Title = $UserInfo["NikeName"]."(".$UserInfo["UserName"].") - ".$UserInfo["OtherInfo"];
$QueryString = "
    SELECT TIC_Judge.JudgeName, TIC_Judge.Weight,  Judge_ScoreDetail.Score, Judge_ScoreDetail.ID
    FROM Judge_ScoreDetail JOIN TIC_Judge
    ON UserID = $UserID 
        AND CompetitionID = (SELECT ConfigValue FROM TIC_WebSite WHERE ConfigKey LIKE 'JudgeCompetition')
        AND Judge_ScoreDetail.JudgeID = TIC_Judge.ID;
";
$Result      = GetDataTable($QueryString);
$ScoreID     = "";
if(count($Result) > 0)
{
    $TableData = "";
    foreach($Result as $Row)
    {
        $ScoreID   = $Row["ID"];
        $TableData .= "
        <tr>
            <td>".$Row["JudgeName"]."</td>
            <td>".$Row["Weight"]."</td>
            <td>".$Row["Score"]."</td>
            <td>
                <a class='layui-btn layui-btn-danger' href='/pages/judge/edit._method.php?id=$ScoreID'>删除</a>
            </td>
        </tr>
        ";
    }
}
else $TableData = "<tr><td colspan='4'>暂无评委打分信息</td></tr>"
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <?php include_once PATH."/modules/body/head.php"; ?>
</head>
<body style="width: 850px; overflow: hidden; margin: auto">
<div class="layui-form layui-field-box layui-form-pane">
    <h1><?php echo $Title; ?></h1>
    <br>
    <hr>
    <a class="layui-btn layui-btn-radius"
       style="float: right"
       href="/pages/judge/edit._method.php?id=<?php echo $UserID; ?>&mth=user">
        删除选手成绩
    </a>
    <div style="clear: both"></div>
    <hr>
    <div class="layui-form-item">
        <div style="height: 350px; overflow-x: hidden; overflow-y: scroll">
            <table class="layui-table" lay-skin="nob" style="text-align: center; width: 98%; margin: auto">
                <colgroup>
                    <col>
                    <col width="100">
                    <col width="100">
                    <col width="100">
                </colgroup>
                <thead>
                <tr>
                    <th style="text-align: center">评委</th>
                    <th style="text-align: center">权重</th>
                    <th style="text-align: center">打分</th>
                    <th style="text-align: center">操作</th>
                </tr>
                </thead>
                <?php echo $TableData ?>
            </table>
        </div>
    </div>
</div>
<script>
    layui.use('element', function () {
    });
</script>
</body>
</html>

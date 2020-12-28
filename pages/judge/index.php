<script type="text/javascript" src="/modules/sys/sys.js"></script>
<?php require_once "/var/www/html/config.php";
LoginChecked();
$PagesParameters = GetPagesParameters();
$CompetitionInfo = GetDataTable("SELECT * FROM TIC_Competition WHERE CURRENT_TIME() > EndTime ORDER BY ID DESC;");
$CompetitionID   = GetSingleResult("SELECT ConfigValue FROM TIC_WebSite WHERE ConfigKey LIKE 'JudgeCompetition';")["ConfigValue"];
if(!empty($CompetitionInfo))
{
    $Selected = "";
    foreach($CompetitionInfo as $Competition)
    {
        $ID           = $Competition["ID"];
        $Title        = $Competition["Title"];
        $SelectedItem = $CompetitionID == $ID ? "selected" : "";
        $Selected     .= "<option value='$ID' $SelectedItem>$Title</option>";
    }
}
$StartIndex  = $PagesParameters["StartIndex"];
$Limit       = $PagesParameters["Limit"];
$QueryString = "
    SELECT COUNT(*) FROM Comp_RegistrationInfo 
    WHERE CompetitionID = (SELECT TIC_WebSite.ConfigValue FROM TIC_WebSite WHERE ConfigKey LIKE 'JudgeCompetition') 
        AND Status = 1 AND Shown = 1;
";
$ResultCount = GetSingleResult($QueryString)["COUNT(*)"];
$QueryString = "
    SELECT IF(Comp_RegistrationInfo.Score >= 0, 1, -1) AS Status, TIC_UserInfo.ID, TIC_UserInfo.NikeName, TIC_UserInfo.UserName, TIC_UserInfo.OtherInfo, Comp_RegistrationInfo.Score, Comp_RegistrationInfo.Sort 
    FROM Comp_RegistrationInfo JOIN TIC_UserInfo
    ON CompetitionID = (SELECT TIC_WebSite.ConfigValue FROM TIC_WebSite WHERE ConfigKey LIKE 'JudgeCompetition') 
        AND Status = 1 AND Shown = 1 AND TIC_UserInfo.ID = Comp_RegistrationInfo.UserID 
    ORDER BY Status, Comp_RegistrationInfo.Sort
    LIMIT ".$PagesParameters["StartIndex"].", ".$PagesParameters["Limit"].";";
$Result      = GetDataTable($QueryString);
$RuningID    = GetSingleResult("SELECT ConfigValue FROM TIC_WebSite WHERE ConfigKey LIKE 'SpeakerID';")["ConfigValue"];
if(count($Result) > 0)
{
    $TableData = "";
    foreach($Result as $Row)
    {
        $SpeakerID = $Row["ID"];
        if($Row["Score"] >= 0 || $RuningID == $Row["ID"])
        {

            if($RuningID != $Row["ID"])
            {
                $TRStyle  = "style='background-color: #E0E0E0'";
                $BtnStyle = ["css" => "layui-btn-disabled", "js" => "disabled", "Text" => "发布"];
            }
            else
            {
                $TRStyle   = "style='background-color: #ffb300'";
                $BtnStyle  = ["css" => "layui-btn-danger", "js" => "", "Text" => "取消"];
                $SpeakerID = 0;
            }
        }
        else
        {
            $BtnStyle = ["css" => "", "js" => "", "Text" => "发布"];
            $TRStyle  = "";
        }
        $TableData .= "
        <tr $TRStyle id='tr_".$Row["ID"]."'>
            <td>".$Row["NikeName"]."</td>
            <td>".$Row["UserName"]."</td>
            <td style='text-align: left'>".$Row["OtherInfo"]."</td>
            <td id='score_".$Row["ID"]."'>".($Row["Score"] > 0 ? $Row["Score"] : "暂无")."</td>
            <td>".$Row["Sort"]."</td>
            <td>
            <div class='layui-btn-group'>
                <button id='btn_".$Row["ID"]."'
                    onclick=\"setSpeaker('$SpeakerID')\" 
                    class='layui-btn ".$BtnStyle["css"]."' ".$BtnStyle["js"].">".$BtnStyle["Text"]."</button>
                <button 
                    onclick=\"ShowDetailDialog('".$Row["ID"]."');\"  class='layui-btn layui-btn-normal'>详细</button></td>
            </td>
            </div>
        </tr>
        ";

    }
}
else $TableData = "<tr><td colspan='6'>暂无选手数据</td>";

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <?php include_once PATH."/modules/body/head.php"; ?>
</head>
<body class="sys-body-style" onload="backgroud()">
<?php include_once PATH."/modules/navigation/menu.php"; ?>
<div class="sys-console-body">
    <div class="sys-nav">
        <?php include_once PATH."/modules/navigation/nav.php"; ?>
    </div>
    <div class="sys-panel">
        <h1 class="site-h1">评分管理</h1>
        <hr class="layui-bg-blue">
        <div class="layui-form">
            <select lay-verify="" lay-filter="comp">
                <option value="-1">请选择正在进行的比赛</option>
                <?php echo $Selected; ?>
            </select>
        </div>
        <div style="clear: both;"></div>
        <table class="layui-table" style="text-align: center">
            <colgroup>
                <col width="120">
                <col width="120">
                <col>
                <col width="100">
                <col width="100">
                <col width="180">
            </colgroup>
            <thead>
            <tr>
                <th style="text-align: center">姓名</th>
                <th style="text-align: center">学号</th>
                <th style="text-align: center">年级/专业</th>
                <th style="text-align: center">得分</th>
                <th style="text-align: center">上场顺序</th>
                <th style="text-align: center">操作</th>
            </tr>
            </thead>
            <?php echo $TableData ?>
        </table>
        <?php include_once PATH."/modules/pages.php"; ?>
    </div>
</div>
<?php include_once PATH."/modules/body/footer.php"; ?>
<script>
    layui.use('form', function () {
        let form = layui.form;
        form.on('select(comp)', function (data) {
            if (confirm("确定要切换正在进行的比赛吗?")) {
                $.ajax({
                    url: "/pages/judge/index._method.php",
                    type: "post",
                    data: {id: data.value},
                    dataType: 'json',
                    success: function (data) {
                        top.location.href = UpdateParam(top.location.href, "reload", new Date().getTime());
                    }
                });
            } else top.location.href = UpdateParam(top.location.href, "reload", new Date().getTime());
        });
    });

    function setSpeaker(id) {
        $.ajax({
            url: "/pages/judge/index.set._method.php",
            type: "post",
            data: {id: id},
            dataType: 'json',
            success: function (data) {
                top.location.href = UpdateParam(top.location.href, "reload", new Date().getTime());
            }
        });
    }

    function backgroud() {
        window.setInterval(function () {
            $.ajax({
                url: "/pages/judge/index.background.php",
                dataType: 'json',
                success: function (data) {
                    if (data.score >= 0) {
                        let tr = $('#tr_' + data.id);
                        let score = $('#score_' + data.id);
                        let btn = $('#btn_' + data.id);
                        tr.attr('style', 'background-color: #E0E0E0');
                        score.text(data.score);
                        btn.text("发布");
                        btn.attr('class', 'layui-btn layui-btn-disabled');
                    }
                }
            });
        }, 1000);
    }

    function ShowDetailDialog(id) {
        layui.use('layer', function () {
            layui.layer.open({
                type: 2,
                offset: '50px',
                title: '评委打分详情',
                shadeClose: false,
                shade: 0.8,
                area: ['900px', '600px'],
                content: '/pages/judge/edit.php?id=' + id,
                scrollbar: false,
                resize: false,
                end: function (index, layero) {
                    layer.load(0);
                    top.location.href = UpdateParam(location.href, "reload", new Date().getTime());
                },
            });
        });
    }
</script>
</body>
</html>
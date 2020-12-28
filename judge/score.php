<?php require_once "/var/www/html/config.php";
$RegInfo   = GetSingleResult("SELECT * FROM Comp_RegistrationInfo WHERE UserID = (SELECT ConfigValue FROM TIC_WebSite WHERE ConfigKey LIKE 'SpeakerID') AND CompetitionID = (SELECT ConfigValue FROM TIC_WebSite WHERE ConfigKey LIKE 'JudgeCompetition');");
$JudgeInfo = GetSingleResult("SELECT * FROM TIC_Judge WHERE GUID LIKE '".$_COOKIE["JudgeID"]."'");
if(empty($RegInfo["Remarks"]))
    $Remarks = "无备注说明";
else $Remarks = $RegInfo["Remarks"];
$UserInfo = GetSingleResult("SELECT UserName, NikeName, OtherInfo FROM TIC_UserInfo WHERE ID = (SELECT ConfigValue FROM TIC_WebSite WHERE ConfigKey LIKE 'SpeakerID');");
if(empty($UserInfo) || empty($UserInfo["UserName"]))
    RunJS(["top.jump" => "/judge/wait.php"]);
else $UserInfoText = "[".$RegInfo["Sort"]."] ".$UserInfo["NikeName"]."(".$UserInfo["UserName"].") - ".$UserInfo["OtherInfo"];
$QueryString  = "
    SELECT Comp_Topic.Text, Comp_Topic.Score, Comp_Completion.Status 
    FROM Comp_Completion JOIN Comp_Topic 
        ON Comp_Completion.TopicID = Comp_Topic.ID 
               AND UserID = (SELECT ConfigValue FROM TIC_WebSite WHERE ConfigKey LIKE 'SpeakerID')  
               AND Comp_Topic.CompetitionID = (SELECT ConfigValue FROM TIC_WebSite WHERE ConfigKey LIKE 'JudgeCompetition');
";
$TopicResult  = GetDataTable($QueryString);
$TableData    = "";
$UserScoreSum = 0;
$MaxScoreSum  = 0;
if(count($TopicResult) > 0)
{
    $Index = 0;
    foreach($TopicResult as $Row)
    {
        $UserScoreSum += $UserScore = $Row["Status"] * $Row["Score"];
        $MaxScoreSum  += $Row["Score"];
        $TableData    .= "
            <tr>
                <td style='text-align: left'>".$Row["Text"]."</td>
                <td>"."<b style='color: #FF0000'>".$UserScore."</b> / ".$Row["Score"]."</td>
                

            ";
        if($JudgeInfo["JudgeRole"] == 1)
        {
            $TableData .= "
                <td>
                    <input type='number' name='sub'
                       id='input_".($Index++)."' required lay-verify='required'
                       placeholder='减分'
                       autocomplete='off'
                       min='0' opt='sub'
                       max='$UserScore'
                       class='layui-input'>
                </td>
                <td>
                    <input type='number' name='add'
                       id='input_".($Index++)."' required lay-verify='required'
                       placeholder='加分'
                       autocomplete='off'
                       min='0' opt='add'
                       max='".($Row["Score"] - $UserScore)."'
                       class='layui-input'>
                </td>";
        }
        else
        {
            if($Row["Status"] == 1)
                $DoneStatus = "<span style='color: #00A000'><b>完成全部</b></span>";
            else if($Row["Status"] == 0.5)
                $DoneStatus = "<span style='color: #ffb800'><b>完成过半</b></span>";
            else  $DoneStatus = "<span style='color: #FF0000'><b>没有完成</b></span>";
            $TableData .= "<td>$DoneStatus</td>";
        }
        $TableData .= "</tr>";
    }
}
else $TableData = "<tr><td colspan='3'>暂无任何完成情况数据</td></tr>";
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <?php include_once PATH."/judge/modules/body/head.php" ?>
</head>
<body class="judge-body" onload="checked('score')">
<?php include_once PATH."/judge/modules/navigation/menu.php"; ?>
<h3 style="text-align: left; margin: 5px">
    选手: <b style="color: #323639"> <?php echo $UserInfoText; ?> </b>
</h3>
<hr>
<table class="layui-table" lay-skin="nob" style="text-align: center; width: 98%; margin: auto">
    <colgroup>
        <col>
        <col width="120">
        <?php
        if($JudgeInfo["JudgeRole"] == 1)
            echo "<col width='80'><col width='80'>";
        else echo "<col width='100'>";
        ?>
    </colgroup>
    <thead>
    <tr>
        <th style="text-align: center">题目</th>
        <th style="text-align: center">参考分/分值</th>
        <?php
        if($JudgeInfo["JudgeRole"] == 1)
            echo "<th style='text-align: center'>减分</th><th style='text-align: center'>加分</th>";
        else echo "<th style='text-align: center'>完成情况</th>";
        ?>
    </tr>
    </thead>
    <?php echo $TableData ?>
    <?php
    if($JudgeInfo["JudgeRole"] == 1)
        echo "
        <tr style='text-align: left; border-bottom: 2px solid #000000'><td colspan='4'><b>选手备注说明</b></td></tr>
        <tr style='text-align: left;'><td colspan='4'>$Remarks</td></tr>
        ";
    ?>
</table>
<br>
<hr>
<br>
<?php
if(!($JudgeInfo["JudgeRole"] == 1))
    echo "
<table class='layui-table layui-form layui-form-pane' lay-skin='nob'
       style='text-align: center; width: 98%; margin: auto;'>
    <colgroup>
        <col>
        <col width='150'>
        <col width='100'>
    </colgroup>
    <tr>
        <td rowspan='4'>
            <div class='layui-form-item layui-form-text'>
                <label class='layui-form-label'>选手备注说明</label>
                <div class='layui-input-block'>
                    <textarea class='layui-textarea' disabled>$Remarks</textarea>
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <td style='text-align: right; font-size: 22px'><b>选手自评分:</b></td>
        <td>
            <label id='score' class='layui-form-label'
                   style='text-align: center; font-size: 22px'>
                $UserScoreSum
            </label>
        </td>
    </tr>
    <tr>
        <td style='text-align: right; font-size: 22px'><b>评委加分:</b></td>
        <td>
            <input type='number' name='add'
                   id='add' required lay-verify='required'
                   placeholder='请输入加分值'
                   autocomplete='off'
                   class='layui-input'>
        </td>
    </tr>
    <tr>
        <td style='text-align: right; font-size: 22px'><b>评委减分:</b></td>
        <td>
            <input type='number' name='sub'
                   id='sub' required lay-verify='required'
                   placeholder='请输入减分值'
                   autocomplete='off'
                   class='layui-input'>
        </td>
    </tr>
</table>
<hr>
";
?>

<div style="text-align:center; margin: 30px">
    <button id="submit" class="layui-btn layui-btn-radius layui-btn-warm" style="font-size: 24px; height:
    60px">　提　交　
    </button>
</div>
<?php include_once PATH."/modules/body/footer.php"; ?>
<script>
    layui.use('layer', function () {
        let MaxValue = <?php echo $MaxScoreSum; ?>;
        $("#submit").click(function () {
            let score = 0;
            <?php
            if($JudgeInfo["JudgeRole"] == 1)
            {
                echo "
                    score = $UserScoreSum;
                    let allInputs = document.getElementsByTagName('input');
                    let add = 0;
                    let sub = 0;
                    for (let i = 0; i < allInputs.length; i++) {
                        let tmp_input = $('#' + allInputs[i].id);
                        let max = parseFloat(tmp_input.attr('max'));
                        let min = parseFloat(tmp_input.attr('min'));
                        if (parseFloat(tmp_input.val()) > max || parseFloat(tmp_input.val()) < min) {
                            alert('请检查第' + i + '个输入窗口输入的数值是否正确');
                            tmp_input.focus();
                            return;
                        }
                        if (tmp_input.attr('opt') === 'add') {
                            if (tmp_input.val() === '')
                                add += 0;
                            else add += parseFloat(tmp_input.val());
                        } else if (tmp_input.attr('opt') === 'sub') {
                            if (tmp_input.val() === '')
                                sub += 0;
                            else sub += parseFloat(tmp_input.val());
                        }
                    }
                    score = score + add - sub;
                ";
            }
            else
            {
                echo "
                    score = $('#score').html().trim();
                    let add = $('#add').val();
                    let sub = $('#sub').val();
                    if (add === '') add = '0';
                    if (sub === '') sub = '0';
                    score = parseFloat(score) + parseFloat(add) - parseFloat(sub);
                ";
            }
            ?>
            if (score < 0 || score > MaxValue) {
                let msg = '您的总分值: <b style="font-size: 24px; color: #FF0000">' + score + '</b> ';
                msg += '超出了限定的最大: ' + MaxValue + '/最小值: 0, 请修改!';
                layui.layer.alert(msg, {icon: 2});
            } else {
                let msg = '您给该选手打: <b style="font-size: 36px; color: #FF0000">' + score + '</b> 分<br>请问您是否要提交该分数?';
                layer.confirm(msg, {icon: 3, title: '是否提交分数?'}, function (index) {
                    layer.load();
                    $.ajax({
                        url: "/judge/method/score.php",
                        dataType: 'json',
                        type: "post",
                        data: {score: score},
                        success: function (data) {
                            layer.close(index);
                            if (data.code === 0) {
                                top.location.href = '/judge/wait.php';
                            } else {
                                alert(data.msg);
                                top.location.href = '/judge/index.php';
                            }
                        }
                    });
                });
            }
        });
    });
</script>
</body>
</html>

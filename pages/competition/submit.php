<?php require_once "/var/www/html/config.php";
$UserData          = LoginChecked();
$QueryString       = "
    SELECT TIC_Competition.MustShown, Comp_RegistrationInfo.SubmitCount, Comp_RegistrationInfo.SubmitTime, Comp_RegistrationInfo.UploadName, Comp_RegistrationInfo.ID, Comp_RegistrationInfo.Remarks, Comp_RegistrationInfo.Shown, TIC_Competition.Title, TIC_Competition.UploadTime, TIC_Competition.EndTime, TIC_Competition.Filter, TIC_Competition.FileSize 
    FROM Comp_RegistrationInfo INNER JOIN TIC_Competition 
        ON Comp_RegistrationInfo.CompetitionID = TIC_Competition.ID 
               AND Comp_RegistrationInfo.CompetitionID = :_CompetitionID
               AND Comp_RegistrationInfo.UserID = :_UserID";
$Parameters        = [":_CompetitionID@d" => $_GET["id"], ":_UserID@d" => $UserData["ID"]];
$Result            = GetSingleResult($QueryString, $Parameters);
$CompetitionID     = $_GET["id"];
$UploadName        = $Result["UploadName"];
$Remarks           = $Result["Remarks"];
$Shown             = ["Show" => "", "Work" => ""];
$ArrayName         = $Result["Shown"] == "1" ? "Show" : "Work";
$Shown[$ArrayName] = "checked";
if($Result["MustShown"] == 1)
    $Shown = ["Show" => "checked", "Work" => ""];

$FileStatus = "";
foreach(explode("|", $Result["Filter"]) as $FF)
{
    if(file_exists(PATH."/data/file/competition/$CompetitionID/$UploadName.$FF"))
        $FileStatus = "<a download='$UploadName.$FF' href='/data/file/competition/$CompetitionID/$UploadName.$FF'><span class='layui-badge layui-bg-blue' style='margin-top: 10px'>文件已上传 点击下载</span></a>";
}

if(strtotime(date("Y-m-d H:i:s")) < strtotime($Result["UploadTime"]) || strtotime(date("Y-m-d H:i:s")) > strtotime($Result["EndTime"]))
    $ButtonStyle = ["class" => "layui-btn-disabled", "type" => "button", "text" => "当前不在提交作品时间之内", "upbtn" => "-1", "cbx" => "disabled"];
else $ButtonStyle = ["class" => "", "type" => "submit", "text" => "　提　　交　", "upbtn" => "1", "cbx" => ""];

$QueryString = "
    SELECT Comp_Topic.Text, Comp_Topic.Score,Comp_Topic.ID, Comp_Completion.Status
    FROM Comp_Topic JOIN Comp_Completion 
        ON Comp_Completion.TopicID = Comp_Topic.ID 
               AND  Comp_Topic.CompetitionID = :_CompetitionID 
               AND Comp_Completion.UserID = :_UserID";
$Parameters  = [":_CompetitionID@d" => $CompetitionID, ":_UserID@d" => $UserData["ID"]];
$TopicResult = GetDataTable($QueryString, $Parameters);
if(count($TopicResult) > 0)
{
    $TableData = "";
    foreach($TopicResult as $Row)
    {
        $Text                    = $Row["Text"];
        $Score                   = $Row["Score"];
        $RowID                   = $Row["ID"];
        $Checked                 = ["0" => "", "0.5" => "", "1" => ""];
        $Checked[$Row["Status"]] = "checked";
        $TableData               .= "
        <tr>
            <td style='text-align: left'>$Text</td>
            <td>$Score</td>
            <td>
            <input type='radio' name='CBX_$RowID' ".$ButtonStyle["cbx"]." ".$Checked["0"]." title='没有完成' value='0' lay-skin='primary'>
            <br>
            <input type='radio' name='CBX_$RowID' ".$ButtonStyle["cbx"]." ".$Checked["0.5"]." title='完成过半' value='0.5' lay-skin='primary'>
            <br>
            <input type='radio' name='CBX_$RowID' ".$ButtonStyle["cbx"]." ".$Checked["1"]." title='完成全部' value='1' lay-skin='primary'>            
            </td>
        </tr>";
    }
}
else $TableData = "<tr><td colspan='3'>当前竞赛无详细评分信息</td></tr>"
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <?php
    include_once PATH."/modules/body/head.php";
    ?>
</head>
<body class="sys-body-style">
<?php include_once PATH."/modules/navigation/menu.php"; ?>
<div class="sys-console-body">
    <div class="sys-nav">
        <?php include_once PATH."/modules/navigation/nav.php"; ?>
    </div>
    <div class="sys-panel">
        <h1 class="site-h1">提交作品</h1>
        <hr class="layui-bg-blue">
        <div style="width: 800px; margin: auto; padding: 20px 0 50px 0">
            <h1 style="text-align: center"><b><?php echo $Result["Title"] ?></b></h1><br>
            <hr>
            <div style="width: 600px; margin: auto">
                <div style="float: right; padding-right: 10px">
                    <?php echo $FileStatus; ?>
                </div>
                <div aria-disabled="true">
                    <?php
                    $UploadConfig["Extensions"] = $Result["Filter"];
                    $UploadConfig["Size"]       = $Result["FileSize"];
                    $UploadConfig["FilePath"]   = "/data/file/competition/$CompetitionID/";
                    $UploadConfig["FileName"]   = $UploadName;
                    $UploadConfig["Enabled"]    = $ButtonStyle["upbtn"];
                    $UploadConfig["Title"]      = "上传作品";
                    include PATH."/modules/upload/upload.standard.php";
                    ?>
                </div>
            </div>
            <hr>
            <form name="edit" action="submit._method.php" method="post">
                <div class="layui-form layui-field-box layui-form-pane">
                    <table class="layui-table" lay-even lay-skin="line" style="text-align: center">
                        <colgroup>
                            <col>
                            <col width="100">
                            <col width="150">
                        </colgroup>
                        <thead>
                        <tr>
                            <th style="text-align: center">题目</th>
                            <th style="text-align: center">分值</th>
                            <th style="text-align: center">完成情况</th>
                        </tr>
                        </thead>
                        <?php echo $TableData ?>
                    </table>
                    <!-- 比赛说明 -->
                    <div class="layui-form-item layui-form-text">
                        <label class="layui-form-label">备注说明</label>
                        <div class="layui-input-block">
                        <textarea name="remarks"
                                  class="layui-textarea"
                                  placeholder="请输入备注"
                                  style="height: 100px"
                                  <?php echo $ButtonStyle["cbx"] ?>><?php echo $Remarks; ?></textarea>
                        </div>
                    </div>
                    <!-- 是否参赛 -->
                    <div style="text-align: center; visibility: <?php echo $Result["MustShown"] == 1 ? "hidden" : "" ?>">
                        <input type="radio"
                               name="show"
                            <?php echo $ButtonStyle["cbx"]; ?>
                               title="我要参加展示赛"
                               value="on"
                               lay-skin="primary"
                            <?php echo $Shown["Show"]; ?>>
                        <input type="radio"
                               name="show"
                               value="off"
                            <?php echo $ButtonStyle["cbx"]; ?>
                               title="我不参加展示赛"
                               lay-skin="primary"
                            <?php echo $Shown["Work"]; ?>>
                    </div>
                    <div style="clear: both"></div>
                    <br>
                    <div style="text-align: center; width: 800px; margin: auto">
                        <button style="margin: auto"
                                class="layui-btn layui-btn-radius <?php echo $ButtonStyle["class"]; ?>"
                                type="<?php echo $ButtonStyle["type"]; ?>">
                            <?php echo $ButtonStyle["text"]; ?>
                        </button>
                    </div>
                    <input name="rgsinfo"
                           value="<?php echo $Result["ID"]; ?>"
                           style="visibility: hidden; height: 0; width: 0">
                </div>
            </form>
        </div>

    </div>
</div>
<?php include_once PATH."/modules/body/footer.php"; ?>
<script>
    layui.use('form', function () {
        let form = layui.form;
        form.render();
    });
</script>
<?php
if($Result["SubmitCount"] > 0)
{
    if($ButtonStyle["type"] == "submit")
    {
        $DateTime = empty($Result["SubmitTime"]) ? "" : $Result["SubmitTime"];
        $Msg      = "
                您已于<span style='color: #FF0000'><b> $DateTime </b></span>成功提交作业
                <br>
                <br>
                您已提交<span style='color: #FF0000'><b> ".$Result["SubmitCount"]." </b></span>次作业
                <br>
                <br>
                您可以在报名截止之前继续提交作业";
        $Msg      = str_replace("\n", "", $Msg);
        $Msg      = str_replace("\r", "", $Msg);
        RunJS(["JS" => "
        layui.use('layer', function () {
            layer.open({
            title: \"恭喜您\",
            content: \"$Msg\"
            });
        });     
        "]);
    }
}
?>
</body>
</html>

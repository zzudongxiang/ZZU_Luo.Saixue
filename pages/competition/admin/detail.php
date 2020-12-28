<?php require_once "/var/www/html/config.php";
$UserData      = LoginChecked();
$CompetitionID = $_GET["compid"];
$UserID        = $_GET["userid"];
$QueryString   = "SELECT UploadName, Remarks FROM Comp_RegistrationInfo WHERE UserID=:_UserID AND CompetitionID = :_CompetitionID";
$Parameters    = [":_UserID@d" => $UserID, ":_CompetitionID@d" => $CompetitionID];
$Result        = GetSingleResult($QueryString, $Parameters);
$UploadName    = $Result["UploadName"];
$Remarks       = $Result["Remarks"];
$FilePath      = PATH."/data/file/competition/$CompetitionID/$UploadName";
$Fitter        = "zip|rar|7z|jpg|png|jpeg|gif|m|c|cpp|doc|docx|ppt|pptx|xls|xlsx|txt|pdf";
$FileStatus    = "<span class='layui-badge' style='margin-top: 10px'>文件未上传</span>";
foreach(explode("|", $Fitter) as $FF)
{
    if(file_exists("$FilePath.$FF"))
    {
        $FileStatus = "<a download='$UploadName.$FF' href='/data/file/competition/$CompetitionID/$UploadName.$FF'><span class='layui-badge layui-bg-blue' style='margin-top: 10px'>文件已上传 点击下载</span></a>";
        break;
    }
}
$QueryString = "SELECT Comp_Topic.Text, Comp_Topic.Score, Comp_Completion.Status FROM Comp_Completion JOIN Comp_Topic ON Comp_Completion.TopicID = Comp_Topic.ID AND UserID=:_UserID AND Comp_Topic.CompetitionID = :_CompetitionID";
$TopicResult = GetDataTable($QueryString, $Parameters);
$TableData   = "";
if(count($TopicResult) > 0)
{
    foreach($TopicResult as $Row)
    {
        if($Row["Status"] == 1)
            $DoneStatus = "<span style='color: #00A000'><b>完成全部</b></span>";
        else if($Row["Status"] == 0.5)  $DoneStatus = "<span style='color: #ffb800'><b>完成过半</b></span>";
        else  $DoneStatus = "<span style='color: #FF0000'><b>没有完成</b></span>";

        $TableData .= "
            <tr>
                <td style='text-align: left'>".$Row["Text"]."</td>
                <td>".$Row["Score"]."</td>
                <td>$DoneStatus</td>
            </tr>";
    }
}
else $TableData = "<tr><td colspan='3'>暂无任何完成情况数据</td></tr>"
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <?php include_once PATH."/modules/body/head.php"; ?>
</head>
<body style="width: 850px; overflow: hidden; margin: auto">
<div class="layui-form layui-field-box layui-form-pane">
    <h1>作品提交详情</h1>
    <hr>
    <!-- 文件下载 -->
    <div class="layui-form-item">
        <?php
        echo $FileStatus;
        echo "<span style='margin-left: 15px'>$UploadName</span>";
        ?>
    </div>

    <div class="layui-tab layui-tab-card" style="box-shadow: none;" lay-filter="tab-panel">
        <ul class="layui-tab-title">
            <li id="website" class="layui-this">完成情况</li>
            <li id="hyperlink">提交备注</li>
        </ul>
        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">
                <!-- 勾选详情 -->
                <div class="layui-form-item">
                    <div style="height: 300px; overflow-x: hidden; overflow-y: scroll">
                        <table class="layui-table" lay-skin="nob" style="text-align: center; width: 98%; margin: auto">
                            <colgroup>
                                <col>
                                <col width="100">
                                <col width="100">
                            </colgroup>
                            <thead>
                            <tr>
                                <th style="text-align: center">题目</th>
                                <th style="text-align: center">总分值</th>
                                <th style="text-align: center">完成情况</th>
                            </tr>
                            </thead>
                            <?php echo $TableData ?>
                        </table>
                    </div>
                </div>
            </div>
            <div class="layui-tab-item">
                <!-- 提交备注 -->
                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label">提交备注</label>
                    <div class="layui-input-block">
                        <textarea class="layui-textarea" disabled style="height: 300px"><?php echo $Result["Remarks"];
                            ?></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    layui.use('element', function () {
    });
</script>
</body>
</html>
<?php require_once "/var/www/html/config.php";
$UserData        = LoginChecked();
$ButtonStyle     = $UserData["AdminRole"] <= 1 ? ["disabled", "layui-btn-disabled"] : ["", ""];
$PagesParameters = GetPagesParameters();
$CompetitionID   = $_GET["id"];
$Parameters      = [":_ID@d" => $CompetitionID];
$Title           = GetSingleResult("SELECT Title FROM TIC_Competition WHERE ID = :_ID", $Parameters)["Title"];
$QueryString     = "
SELECT User_Info.ID, User_Info.NikeName, User_Info.UserName, IFNULL(User_Info.Score, 0) AS Score, User_Info.OtherInfo, Reg_Info.Remarks, Reg_Info.Shown, Reg_Info.SubmitTime FROM (
    SELECT tb_Info.UserInfoID AS ID, tb_Info.NikeName, tb_Info.UserName, tb_Info.OtherInfo, tb_Score.Score FROM (
        SELECT * FROM (     
            SELECT
                tb_User.ID AS UserInfoID, tb_User.NikeName, tb_User.UserName, tb_User.OtherInfo
            FROM 
                (SELECT * FROM TIC_UserInfo WHERE ID IN (SELECT UserID FROM Comp_RegistrationInfo WHERE CompetitionID=$CompetitionID AND Status = 1)) AS tb_User) AS tmp_User
    ) AS tb_Info               
    LEFT JOIN (
        SELECT SUM(Status * Score) AS Score, UserID FROM(
            SELECT Status, Comp_Topic.Score, UserID
            FROM Comp_Completion 
            JOIN Comp_Topic
            ON Comp_Topic.ID= Comp_Completion.TopicID
            AND Comp_Topic.CompetitionID = $CompetitionID
        ) AS tb_Score Group BY tb_Score.UserID 
    ) AS tb_Score   
    ON tb_Info.UserInfoID=tb_Score.UserID 
) AS User_Info JOIN (
    SELECT * FROM Comp_RegistrationInfo WHERE CompetitionID = $CompetitionID
) AS Reg_Info 
 ON Reg_Info.UserID = User_Info.ID 
 ORDER BY Reg_Info.Shown DESC, User_Info.Score DESC 
 LIMIT ".$PagesParameters["StartIndex"].", ".$PagesParameters["Limit"].";
";
$QueryString     = str_replace("\r\n", "", $QueryString);
$Result          = GetDataTable($QueryString);
$QueryString     = "SELECT UploadName, UserID FROM Comp_RegistrationInfo WHERE CompetitionID = :_CompetitionID AND Status = 1";
$FileResult      = GetDataTable($QueryString, [":_CompetitionID@d" => $CompetitionID]);
$Files           = glob(PATH."/data/file/competition/$CompetitionID/*");
$FileCount       = 0;
$FileList        = [];
$UserList        = [];
$DoneList        = [];
foreach($FileResult as $Row)
{
    array_push($FileList, $Row["UploadName"]);
    $UserList[$Row["UploadName"]] = $Row["UserID"];
    $DoneList[$Row["UserID"]]     = "<span class='layui-badge layui-bg-orange' style='margin-left: 5px'>未提交</span>";
}
foreach($Files as $Row)
{
    $Row = pathinfo($Row)["filename"];
    if(in_array($Row, $FileList))
    {
        $FileCount++;
        $DoneList[$UserList[$Row]] = "<span class='layui-badge layui-bg-blue' style='margin-left: 5px'>已提交</span>";
    }
}

$TableData  = "";
$SUM        = 0;
$ShownCount = GetSingleResult("SELECT COUNT(*) FROM Comp_RegistrationInfo WHERE CompetitionID=$CompetitionID AND Shown=1 AND Status = 1;")["COUNT(*)"];
if(count($Result) > 0)
{
    foreach($Result as $Row)
    {
        $SUM += $Row["Score"];
        if($Row["Shown"] == "1")
            $Shown = "<span class='layui-badge layui-bg-green' style='margin-right: 5px'>展示</span>";
        else $Shown = "";
        $UID       = $Row["ID"];
        $TableData .= "
        <tr>
            <td>".$Row["NikeName"]."</td>
            <td>".$Row["UserName"]."</td>
            <td style='text-align: left'>".$Row["OtherInfo"].$DoneList[$Row["ID"]]."</td>
            <td>".$Row["SubmitTime"]."</td>
            <td>$Shown".$Row["Score"]." 分</td>
            <td>
                <div class='layui-btn-group'>
                    <button ".$ButtonStyle[0]." type='button' class='layui-btn layui-btn-danger ".$ButtonStyle[1]."' 
                    onclick=cancel('$CompetitionID','$UID');>取消</button>
                    <button type='button' class='layui-btn' onclick=ShowDialog('完成情况','detail.php?userid=".$Row["ID"]."&compid=$CompetitionID',0)>
                        <i class='layui-icon-list layui-icon'> 查看</i>
                    </button>
                </div>
            </td>
        </tr>
        ";
    }
}
else $TableData = "<tr><td colspan='6'>暂无报名数据</td></tr>";
$ResultCount = GetSingleResult("SELECT COUNT(*) FROM Comp_RegistrationInfo WHERE Status = 1 AND CompetitionID = $CompetitionID;")
["COUNT(*)"];
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <?php include_once PATH."/modules/body/head.php"; ?>
</head>
<body class="sys-body-style">
<?php include_once PATH."/modules/navigation/menu.php"; ?>
<div class="sys-console-body">
    <div class="sys-nav">
        <?php include_once PATH."/modules/navigation/nav.php"; ?>
    </div>
    <div class="sys-panel">
        <div>
            <a href="index.php"
               class="layui-btn layui-btn-radius layui-btn-normal"
               style="float: left;
                margin: 0px 15px">
                &lt;&lt;&lt;返回
            </a>
            <h1 class="site-h1"><?php echo $Title; ?></h1>
        </div>
        <hr class="layui-bg-blue">
        <div>
            <span style="margin: 5px 15px; font-size: 18px; color: #f89c0e">
                已报名: <?php echo $ResultCount; ?> 人
            </span>
            <span style="margin: 5px 15px; font-size: 18px; color: #ff5722">
                参加展示赛: <?php echo $ShownCount; ?> 人
            </span>
            <span style="margin: 5px 10px; font-size: 18px; color: #7ba0cc">
                上传文件个数: <?php echo $FileCount ?> 件
            </span>
            <a style="margin: 0 5px; float: right"
               class="layui-btn layui-btn-radius layui-btn-warm"
               href="zip._method.php?id=<?php echo $CompetitionID; ?>"
               target="_blank">
                下载文件
            </a>
            <a style="margin: 0 5px; float: right"
               class="layui-btn layui-btn-radius"
               href="xls._method.php?id=<?php echo $CompetitionID; ?>"
               target="_blank">
                导出数据
            </a>
        </div>
        <div style="clear: both"></div>
        <table class="layui-table" style="text-align: center">
            <colgroup>
                <col width="120">
                <col width="120">
                <col>
                <col width="120">
                <col width="120">
                <col width="200">
            </colgroup>
            <thead>
            <tr>
                <th style="text-align: center">姓名</th>
                <th style="text-align: center">学号</th>
                <th style="text-align: center">其他信息</th>
                <th style="text-align: center">最后提交时间</th>
                <th style="text-align: center">得分</th>
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
    function cancel(cid, uid) {
        if (confirm("确定要取消该选手的报名状态吗？")) {
            location.href = 'edit._cancel.php?compid=' + cid + '&userid=' + uid;
        }
    }
</script>
</body>
</html>
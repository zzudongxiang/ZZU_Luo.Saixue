<?php require_once "/var/www/html/config.php";
if(empty($PublicNoticeConfig))
    die("请传入参数初始化对象");
$DeafultConfig = ["Style" => "layui-bg-cyan", "Title" => "新闻中心", "Href" => "#", "TableData" => array()];
foreach($DeafultConfig as $ConfigKey => $ConfigValue)
{
    if(empty($PublicNoticeConfig[$ConfigKey]))
        $PublicNoticeConfig[$ConfigKey] = $ConfigValue;
}
$TitleWidth = 50;
$HTML       = "";
if(count($PublicNoticeConfig["TableData"]) > 0)
{
    foreach($PublicNoticeConfig["TableData"] as $Row)
    {
        # 处理是否时当天的通知新闻, 并给出样式
        $DateFormat = "[Y.m.d]";
        $PublicDate = date($DateFormat, strtotime($Row["UpdateTime"]));
        if($PublicDate == date($DateFormat, time()))
            $Today = "<span style='float: left;' class='layui-badge-dot'></span>";
        else $Today = "";
        # 给出通知新闻的时间样式
        $Date = "<b style='float: left; width: 100px'>$PublicDate</b>";
        # 给出通知新闻的置顶样式
        if($Row["TopMost"] == 1)
        {
            $TopMost       = "<span style='float: left; margin: 2px 5px' class='layui-badge'>置顶</span>";
            $TmpTitleWidth = $TitleWidth;

        }
        else
        {
            $TopMost       = "";
            $TmpTitleWidth = $TitleWidth + 3;
        }
        # 给出新闻的标题样式
        $Title = "
            <div style='max-width: ".$TmpTitleWidth."em; float: left; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;'>
                <a target='_blank' href='/pages/public/article.php?id=".$Row["ID"]."'>".$Row["Title"]."</a>
            </div>";
        # 给出通知新闻的完整样式
        $HTML .= "<div style='height: 20px'>$Date $Today $Title $TopMost</div><hr class='layui-bg-cyan'>";
    }
}
else $HTML = "暂无相关内容";
?>
<div class="layui-card" style="padding: 0 5px">
    <div class="layui-card-header <?php echo $PublicNoticeConfig["Style"]; ?>">
        <div class="layui-row">
            <div style="float: left"><h2><?php echo $PublicNoticeConfig["Title"]; ?></h2></div>
            <div style="text-align: right; float: right">
                <a target='_blank' href="<?php echo $PublicNoticeConfig["Href"] ?>" style="color: #FFFFFF">更多>>
                </a>
            </div>
        </div>
    </div>
    <div class="layui-card-body" style="height: 218px">
        <?php echo $HTML; ?>
    </div>
</div>


<?php require_once "/var/www/html/config.php";
LoginChecked();
$Result                      = GetDataTable("SELECT * FROM TIC_WebSite WHERE ConfigKey LIKE 'RT_Web' OR ConfigKey LIKE 'RT_Music';");
$RT_Web_Checked              = ["lottery.php" => "", "lottery_result.php" => "", "score.php" => "", "score_sum.php" => "", "score_sort.php" => ""];
$RT_Music_Checked            = ["bgm_old" => "", "bgm_new" => ""];
$RT_Web                      = GetSingleResult("SELECT ConfigValue FROM TIC_WebSite WHERE ConfigKey LIKE 'RT_Web_URL';")["ConfigValue"];
$RT_Music                    = GetSingleResult("SELECT ConfigValue FROM TIC_WebSite WHERE ConfigKey LIKE 'RT_Music_Voice';")["ConfigValue"];
$PageList                    = explode("/", $RT_Web);
$RT_Web                      = end($PageList);
$RT_Web_Checked[$RT_Web]     = "checked";
$RT_Music_Checked[$RT_Music] = "checked";
$PPTList                     = "";
$PPTResult                   = GetDataTable("SELECT * FROM TIC_PPT");
if(count($PPTResult) > 0)
{
    $SelectPPT = GetSingleResult("SELECT ConfigValue FROM TIC_WebSite WHERE ConfigKey LIKE 'RT_PPT';")["ConfigValue"];
    foreach($PPTResult as $Row)
    {
        if($Row["ID"] == $SelectPPT)
            $Checked = "checked";
        else $Checked = "";
        $PPTList .= "<input type='radio' name='RT_PPT' id='RT_PPT' lay-filter='RT_PPT' value='".$Row["ID"]."' title='".$Row["Title"]."' $Checked><br>";
    }
}

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
        <h1 class="site-h1">现场控制</h1>
        <hr class="layui-bg-blue">
        <table class="layui-form" style="width: 850px; margin: auto; text-align: center; font-size: 30px;
        padding-top: 20px">
            <colgroup>
                <col width="200">
                <col>
                <col width="120">
            </colgroup>

            <tr style=" height: 80px">
                <td>倒计时</td>
                <td style="text-align: left">
                    <button style="font-size: 20px" status="on" id="RT_Timer" class="layui-btn
                    layui-btn-radius">开始
                    </button>
                </td>
                <td>
                    <a href="/data/scoring.zip" class="layui-btn layui-btn-danger">客户端下载</a>
                </td>
            </tr>
            <tr style=" height: 80px">
                <td>选手发布</td>
                <td style="text-align: left">
                    <label style="font-size: 22px" id="RT_Next_Msg">当前: 暂无 | 下一位: 暂无</label>
                </td>
                <td>
                    <button style="font-size: 20px" id="RT_Next" args="0" class="layui-btn
                    layui-btn-danger">发布下一位选手
                    </button>
                </td>
            </tr>
            <tr style=" height: 80px">
                <td>页面显示</td>
                <td style="text-align: left">
                    <input type="radio" name="RT_Web_URL"
                           id="RT_Web_URL" lay-filter="RT_Web_URL"
                           value="/announce/ppt.php"
                        <?php echo $RT_Web_Checked["ppt.php"] ?>
                           title="0. 放映PPT">
                    <input type="radio" name="RT_Web_URL"
                           id="RT_Web_URL" lay-filter="RT_Web_URL"
                           value="/announce/lottery.php"
                        <?php echo $RT_Web_Checked["lottery.php"] ?>
                           title="1. 抽签准备">
                    <input type="radio" name="RT_Web_URL"
                           id="RT_Web_URL" lay-filter="RT_Web_URL"
                           value="/announce/lottery_result.php"
                        <?php echo $RT_Web_Checked["lottery_result.php"] ?>
                           title="2. 抽签结果">
                    <input type="radio" name="RT_Web_URL"
                           id="RT_Web_URL" lay-filter="RT_Web_URL"
                           value="/announce/score.php"
                        <?php echo $RT_Web_Checked["score.php"] ?>
                           title="3. 评委打分">
                    <input type="radio" name="RT_Web_URL"
                           id="RT_Web_URL" lay-filter="RT_Web_URL"
                           value="/announce/score_sum.php"
                        <?php echo $RT_Web_Checked["score_sum.php"] ?>
                           title="4. 学生成绩">
                    <input type="radio" name="RT_Web_URL"
                           id="RT_Web_URL" lay-filter="RT_Web_URL"
                           value="/announce/score_sort.php"
                        <?php echo $RT_Web_Checked["score_sort.php"] ?>
                           title="5. 最终排序">
                </td>
                <td>
                    <button style="font-size: 20px" status="on" id="RT_Web" class="layui-btn">显示</button>
                </td>
            </tr>
            <tr style=" height: 80px">
                <td>语音播报</td>
                <td>
                    <input style="font-size: 14px" id="RT_Speaker_Msg" type="text" class="layui-input"
                           oninput="RT_Speaker_Msg()">
                </td>
                <td>
                    <button style="font-size: 20px" status="on" id="RT_Speaker" class="layui-btn">播报</button>
                </td>
            </tr>
            <tr style=" height: 80px">
                <td>背景音乐</td>
                <td style="text-align: left">
                    <input type="radio" name="RT_Music_Voice"
                           id="RT_Music_Voice_old" lay-filter="RT_Music_Voice"
                           value="bgm_old"
                        <?php echo $RT_Music_Checked["bgm_old"] ?>
                           title="暖场背景音乐">
                    <input type="radio" name="RT_Music_Voice"
                           id="RT_Music_Voice_new" lay-filter="RT_Music_Voice"
                           value="bgm_new"
                        <?php echo $RT_Music_Checked["bgm_new"] ?>
                           title="颁奖背景音乐">
                </td>
                <td>
                    <button style="font-size: 20px" status="on" id="RT_Music" class="layui-btn">播放</button>
                </td>
            </tr>
            <tr style=" height: 80px">
                <td>系统音量</td>
                <td colspan="2" style="text-align: left">
                    <div class="layui-btn-group">
                        <button class="layui-btn" id="RT_Volume_0" onclick="setVolume(0)">0</button>
                        <button class="layui-btn" id="RT_Volume_10" onclick="setVolume(10)">10</button>
                        <button class="layui-btn" id="RT_Volume_20" onclick="setVolume(20)">20</button>
                        <button class="layui-btn" id="RT_Volume_30" onclick="setVolume(30)">30</button>
                        <button class="layui-btn" id="RT_Volume_40" onclick="setVolume(40)">40</button>
                        <button class="layui-btn" id="RT_Volume_50" onclick="setVolume(50)">50</button>
                        <button class="layui-btn" id="RT_Volume_60" onclick="setVolume(60)">60</button>
                        <button class="layui-btn" id="RT_Volume_70" onclick="setVolume(70)">70</button>
                        <button class="layui-btn" id="RT_Volume_80" onclick="setVolume(80)">80</button>
                        <button class="layui-btn" id="RT_Volume_90" onclick="setVolume(90)">90</button>
                        <button class="layui-btn" id="RT_Volume_100" onclick="setVolume(100)">100</button>
                    </div>
                    <div id="RT_Volume"></div>
                </td>
            </tr>
            <tr>
                <td style="vertical-align: top">幻灯片放映</td>
                <td colspan="2" style="text-align: left">
                    <?php echo $PPTList ?>
                </td>
            </tr>
        </table>
        <br>
        <hr>
        <br>
        <div style="width: 500px; margin: auto; text-align: center">
            <h1> - - - - - 数据同步 <label>0.00%</label> - - - - - </h1>
            <br>
            <button style="margin: 0 10px" class="layui-btn layui-btn-radius layui-btn-normal">从官网下载</button>
            <button style="margin: 0 10px" class="layui-btn layui-btn-radius layui-btn-normal">上传到官网</button>
        </div>
    </div>
</div>
<?php include_once PATH."/modules/body/footer.php"; ?>
<script>
    layui.use('form', function () {
        let form = layui.form;
        form.on('radio(RT_Web_URL)', function (data) {
            $.ajax({
                url: "/pages/control/index.web.url.php",
                dataType: 'json',
                type: "post",
                data: {RT_Web_URL: "http://" + window.location.host + data.value},
            });
        });
        form.on('radio(RT_Music_Voice)', function (data) {
            $.ajax({
                url: "/pages/control/index.music.voice.php",
                dataType: 'json',
                type: "post",
                data: {RT_Music_Voice: data.value},
            });
        });
        form.on('radio(RT_PPT)', function (data) {
            $.ajax({
                url: "/pages/control/index.ppt.php",
                dataType: 'json',
                type: "post",
                data: {RT_PPT: data.value},
            });
        });
    });

    function backgroud() {
        control_ajax();
        window.setInterval(function () {
            control_ajax();
        }, 1000);
    }

    function control_ajax() {
        $.ajax({
            url: "/pages/control/index.background.php",
            dataType: 'json',
            type: "post",
            success: function (data) {
                let RT_Timer = $("#RT_Timer");
                let RT_Web = $("#RT_Web");
                let RT_Speaker_Msg = $("#RT_Speaker_Msg");
                let RT_Speaker = $("#RT_Speaker");
                let RT_Music = $("#RT_Music");
                let RT_Next = $("#RT_Next");
                let RT_Next_Msg = $("#RT_Next_Msg");

                RT_Timer.attr("status", data.RT_Timer);
                RT_Timer.attr("class", data.RT_Timer === "on" ? "layui-btn layui-btn-radius layui-btn-warm" :
                    "layui-btn layui-btn-radius");
                RT_Timer.text(data.RT_Timer === "on" ? "　　停　　止　　" : "　　开　　始　　");

                RT_Web.attr("status", data.RT_Web);
                RT_Web.attr("class", data.RT_Web === "on" ? "layui-btn layui-btn-warm" : "layui-btn");
                RT_Web.text(data.RT_Web === "on" ? "关闭" : "显示");

                RT_Speaker.attr("status", data.RT_Speaker);
                RT_Speaker.attr("class", data.RT_Speaker === "on" ? "layui-btn layui-btn-warm" : "layui-btn");
                RT_Speaker.text(data.RT_Speaker === "on" ? "复位" : "播报");

                RT_Music.attr("status", data.RT_Music);
                RT_Music.attr("class", data.RT_Music === "on" ? "layui-btn layui-btn-warm" : "layui-btn");
                RT_Music.text(data.RT_Music === "on" ? "停止" : "播放");

                RT_Speaker_Msg.val(data.RT_Speaker_Msg);

                RT_Next.attr("args", data.RT_Next);

                RT_Next_Msg.text(data.RT_Next_Msg);

                for (let i = 0; i <= 10; i++)
                    $("#RT_Volume_" + (i * 10)).attr("class", "layui-btn");
                $("#RT_Volume_" + data.RT_Volume).attr("class", "layui-btn layui-btn-warm");
            },
        });
    }

    function setVolume(vol) {
        $.ajax({
            url: "/pages/control/index.volume.php",
            dataType: 'json',
            type: "post",
            data: {RT_Volume: vol},
        });
    }

    $("#RT_Timer").click(function () {
        $.ajax({
            url: "/pages/control/index.timer.php",
            dataType: 'json',
            type: "post",
            data: {RT_Timer: $("#RT_Timer").attr("status")},
        });
    });

    $("#RT_Web").click(function () {
        $.ajax({
            url: "/pages/control/index.web.php",
            dataType: 'json',
            type: "post",
            data: {RT_Web: $("#RT_Web").attr("status")},
        });
    });

    $("#RT_Speaker").click(function () {
        $.ajax({
            url: "/pages/control/index.speaker.php",
            dataType: 'json',
            type: "post",
            data: {RT_Speaker: $("#RT_Speaker").attr("status")},
        });
    });

    $("#RT_Music").click(function () {
        $.ajax({
            url: "/pages/control/index.music.php",
            dataType: 'json',
            type: "post",
            data: {RT_Music: $("#RT_Music").attr("status")},
        });
    });

    $("#RT_Next").click(function () {
        $.ajax({
            url: "/pages/control/index.next.php",
            dataType: 'json',
            type: "post",
            data: {id: $("#RT_Next").attr("args")},
        });
    });

    function RT_Speaker_Msg() {
        $.ajax({
            url: "/pages/control/index.speaker.msg.php",
            dataType: 'json',
            type: "post",
            data: {RT_Speaker_Msg: $("#RT_Speaker_Msg").val()},
        });
    }

</script>
</body>
</html>
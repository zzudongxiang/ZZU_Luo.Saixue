<?php require_once "/var/www/html/config.php";
$UserData = LoginChecked();
if(empty($_GET["id"]))
    die("请指定操作对象");
$TableData  = "";
$EditStatus = ["btnclass" => "layui-btn-disabled", "tbxclass" => "disabled", "js" => "return;"];
if($_GET["id"] > 0)
{
    $Method      = "update";
    $QueryString = "SELECT * FROM TIC_Competition WHERE ID = :_ID";
    $Parameters  = [":_ID@d" => $_GET["id"]];
    $Result      = GetSingleResult($QueryString, $Parameters);

    $QueryString = "SELECT * FROM Comp_Topic WHERE CompetitionID = :_ID";
    $TopicResult = GetDataTable($QueryString, $Parameters);
    if(count($TopicResult) > 0)
    {
        if(!empty($_GET["mth"]) && $_GET["mth"] == "copy")
            $EditStatus = ["btnclass" => "", "tbxclass" => "", "js" => ""];
        foreach($TopicResult as $Row)
        {
            $TableData .= "
            <tr>
                <td>
                    <input type='text'
                           name='TOPIC_TITLE_".$Row["ID"]."'
                           required
                           lay-verify='required'
                           placeholder='请输入题目'
                           class='layui-input'
                           value='".$Row["Text"]."'>
                </td>
                <td>
                    <input type='number'
                           name='TOPIC_SCORE_".$Row["ID"]."'
                           required
                           lay-verify='required'
                           placeholder='分值'
                           class='layui-input'
                           value='".$Row["Score"]."'>
                </td>
                <td>
                    <button onclick='del(this)' type='button' class='layui-btn layui-btn-warm ".$EditStatus["btnclass"]."'>
                        <i class='layui-icon layui-icon-delete'> 删除</i>
                    </button>
                </td>
            </tr>
        ";
        }
    }
    else $TableData = "";
}
else
{
    $Method     = "insert";
    $EditStatus = ["btnclass" => "", "tbxclass" => "", "js" => ""];
    $Result     = ["ID" => "系统自动分配", "Title" => "", "Text" => "", "UploadTime" => date("Y-m-d H:i:s"), "EndTime" => date("Y-m-d H:i:s"), "Enabled" => "0", "FileSize" => "102400", "MustShown" => 0];
}
if(!empty($_GET["mth"]) && $_GET["mth"] == "copy")
{
    $Result["ID"] = "系统自动分配";
    $Method       = "insert";
    $EditStatus   = ["btnclass" => "", "tbxclass" => "", "js" => ""];
}
$Filter = ["zip" => "", "doc" => "", "xls" => "", "jpg" => "", "ppt" => "", "cpp" => ""];
if(!empty($Result["Filter"]))
{
    switch($Result["Filter"])
    {
        case "doc|docx|pdf|txt";
            $Filter["doc"] = "checked";
            break;
        case "xls|xlsx";
            $Filter["xls"] = "checked";
            break;
        case "jpg|png|jpeg|bmp|gif";
            $Filter["jpg"] = "checked";
            break;
        case "ppt|pptx|pdf";
            $Filter["ppt"] = "checked";
            break;
        case "cpp|c|cs|m|py|ipy";
            $Filter["cpp"] = "checked";
            break;
        default:
            $Filter["zip"] = "checked";
            break;
    }
}
else $Filter["zip"] = "checked";
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <?php include_once PATH."/modules/body/head.php"; ?>
</head>
<body style="width: 850px; overflow: hidden; margin: auto">
<form name="edit" class="layui-form" method="post" action="/pages/competition/admin/edit._method.php">
    <!-- 给出一些隐藏对象, 用于提交表单时的数据提交 -->
    <div style="visibility: hidden; width: 0; height: 0;">
        <button id="btn-yes" name="btn-yes" type="button" onclick="process('<?php echo $Method ?>');">提交</button>
        <button id="btn-del" name="btn-del" type="button" onclick="process('del');">删除</button>
        <input name="method" id="method" value="del">
        <input name="id" value="<?php echo $Result["ID"]; ?>">
    </div>
    <div class="layui-tab">
        <ul class="layui-tab-title">
            <li class="layui-this">竞赛设置</li>
            <li>竞赛简介</li>
            <li>题目设置</li>
        </ul>
        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">
                <div class="layui-form-item">
                    <label class="layui-form-label">唯一标识</label>
                    <div class="layui-form-mid layui-word-aux">
                        <?php echo $Result["ID"]; ?>
                        <span>
                        <i style="color: #FF0000; padding-left: 10px" class="layui-icon layui-icon-about">
                            只有添加比赛信息时才可以改变题目数量
                        </i>
                    </span>
                    </div>
                </div>
                <!-- 竞赛的名称 -->
                <div class="layui-form-item">
                    <label class="layui-form-label">标题</label>
                    <div class="layui-input-block">
                        <label>
                            <input type="text"
                                   name="title"
                                   required
                                   lay-verify="required"
                                   placeholder="请输入标题"
                                   autocomplete="off"
                                   class="layui-input"
                                   value="<?php echo $Result["Title"] ?>">
                        </label>
                    </div>
                </div>
                <!-- 时间 -->
                <div class="layui-form-item">
                    <label class="layui-form-label">提交时间</label>
                    <div class="layui-input-block">
                        <input name="time"
                               lay-verify="required"
                               type="text"
                               class="layui-input"
                               id="datetime"
                               placeholder="选择时间范围"
                               required>
                    </div>
                </div>
                <!-- 允许上传的文件格式 -->
                <div class="layui-form-item">
                    <label class="layui-form-label">文件格式</label>
                    <div class="layui-input-block">
                        <input type="radio"
                               name="filetype"
                               title="压缩包:zip|rar|7z"
                               lay-skin="primary"
                               value="zip|rar|7z"
                            <?php echo $Filter["zip"] ?>>
                        <input type="radio"
                               name="filetype"
                               title="文档:doc|docx|pdf|txt"
                               lay-skin="primary"
                               value="doc|docx|pdf|txt"
                            <?php echo $Filter["doc"] ?>>
                        <input type="radio"
                               name="filetype"
                               title="表格:xls|xlsx"
                               lay-skin="primary"
                               value="xls|xlsx"
                            <?php echo $Filter["xls"] ?>>
                        <input type="radio"
                               name="filetype"
                               title="图片:jpg|png|jpeg|bmp|gif"
                               lay-skin="primary"
                               value="jpg|png|jpeg|bmp|gif"
                            <?php echo $Filter["jpg"] ?>>
                        <input type="radio"
                               name="filetype"
                               title="演示文档:ppt|pptx|pdf"
                               lay-skin="primary"
                               value="ppt|pptx|pdf"
                            <?php echo $Filter["ppt"] ?>>
                        <input type="radio"
                               name="filetype"
                               title="代码文件:cpp|c|cs|m|py|ipy"
                               lay-skin="primary"
                               value="cpp|c|cs|m|py|ipy"
                            <?php echo $Filter["cpp"] ?>>
                    </div>
                </div>
                <!-- 允许上传的文件大小 -->
                <div class="layui-form-item">
                    <label class="layui-form-label">文件大小</label>
                    <div class="layui-input-inline">
                        <label>
                            <input type="number"
                                   name="filesize"
                                   required
                                   lay-verify="required"
                                   placeholder="请输入文件大小"
                                   autocomplete="off"
                                   class="layui-input"
                                   value="<?php echo $Result["FileSize"] ?>">
                        </label>
                    </div>
                    <div class="layui-form-mid layui-word-aux">KB</div>
                </div>
                <!-- 是否强制参赛 -->
                <div class="layui-form-item">
                    <label class="layui-form-label">强制参赛</label>
                    <div class="layui-input-block">
                        <?php
                        if($Result["MustShown"] == "1")
                            $MustShown = "checked";
                        else $MustShown = "";
                        ?>
                        <input type="checkbox" name="mustshown" lay-skin="switch"
                               lay-text="ON|OFF" <?php echo $MustShown ?>>
                    </div>
                </div>
                <!-- 开放状态 -->
                <div class="layui-form-item">
                    <label class="layui-form-label">开放状态</label>
                    <div class="layui-input-block">
                        <?php
                        if($Result["Enabled"] == "1")
                            $TopMost = "checked";
                        else $TopMost = "";
                        ?>
                        <input type="checkbox" name="enabled" lay-skin="switch"
                               lay-text="ON|OFF" <?php echo $TopMost ?>>
                    </div>
                </div>
            </div>
            <div class="layui-tab-item layui-form">
                <!-- 竞赛内容 -->
                <fieldset class="layui-elem-field">
                    <legend>信息简介</legend>
                    <div class="layui-field-box">
                        <textarea name="text"
                                  class="layui-textarea"
                                  style="height: 380px"><?php echo $Result["Text"];
                            ?></textarea>
                    </div>
                </fieldset>
            </div>
            <div class="layui-tab-item">
                <!-- 赛题的修改 -->
                <div style="display: inline-block">
                    <button class='layui-btn <?php echo $EditStatus["btnclass"] ?>' type="button" onclick="addtopic()">
                        <i class="layui-icon layui-icon-add-circle"></i>添加
                    </button>
                    <span>
                        <i style="color: #FF0000; padding-left: 10px" class="layui-icon layui-icon-about">
                            比赛创建完成后不能修改题目数量
                        </i>
                    </span>
                </div>
                <div style="overflow-y: scroll; overflow-x: hidden; height: 380px; margin-top: 10px">
                    <table id="topic" class="layui-table" style="text-align: center; margin: auto">
                        <colgroup>
                            <col>
                            <col width="100">
                            <col width="100">
                        </colgroup>
                        <thead>
                        <tr>
                            <th style="text-align: center">内容</th>
                            <th style="text-align: center">分值</th>
                            <th style="text-align: center">操作</th>
                        </tr>
                        </thead>
                        <?php echo $TableData; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    layui.use('element', function () {
        let element = layui.element;
    });
    layui.use('form', function () {
        let form = layui.form;
    });
    layui.use('laydate', function () {
        let laydate = layui.laydate;
        laydate.render({
            elem: '#datetime'
            , type: 'datetime'
            , range: true
            , value: '<?php echo $Result["UploadTime"]?> - <?php echo $Result["EndTime"]?>'
        });
    });

    function addtopic() {
        <?php echo $EditStatus["js"]; ?>
        let tr = document.createElement("tr");
        let title = document.createElement("td");
        let score = document.createElement("td");
        let del = document.createElement("td");
        let id = new Date().getTime();
        title.innerHTML =
            "<input type='text' " +
            "name='TOPIC_TITLE_" + id + "' " +
            "required lay-verify='required' " +
            "placeholder='请输入题目' " +
            "class='layui-input'>";
        score.innerHTML =
            "<input type='number' " +
            "name='TOPIC_SCORE_" + id + "' " +
            "required lay-verify='required' " +
            "placeholder='分值' " +
            "class='layui-input'>";
        del.innerHTML =
            "<button onclick='del(this)' " +
            "type='button' " +
            "class='layui-btn layui-btn-warm'> " +
            "<i class='layui-icon layui-icon-delete'> 删除</i>" +
            "</button>";
        let topic = document.getElementById("topic");
        topic.appendChild(tr);
        tr.appendChild(title);
        tr.appendChild(score);
        tr.appendChild(del);
    }

    function del(obj) {
        <?php echo $EditStatus["js"]; ?>
        let tr = obj.parentNode.parentNode;
        tr.parentNode.removeChild(tr);
    }

    function updateedit(opt) {
        document.getElementById("method").value = opt;
        document.edit.submit();
    }
</script>
</body>
</html>
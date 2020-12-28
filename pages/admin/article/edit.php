<?php require_once "/var/www/html/config.php";
$UserData = LoginChecked();
if(!empty($_GET["id"]) && $_GET["id"] > 0)
{
    $QueryString = "SELECT * FROM TIC_Article WHERE ID=:_ID";
    $Parameters  = [":_ID@d" => $_GET["id"]];
    $Result      = GetSingleResult($QueryString, $Parameters);
    if(!empty($_GET["type"]) && $_GET["type"] == "copy")
    {
        $Method               = "insert";
        $Result["ID"]         = "系统自动分配";
        $Result["UpdateTime"] = date("Y-m-d");
    }
    else $Method = "update";
}
else
{
    $Result = ["ID" => "系统自动分配", "Type" => "通知", "UpdateTime" => date("Y-m-d"), "Title" => "", "Text" => "", "Author" => $UserData["NikeName"], "Enabled" => "1", "TopMost" => "0"];
    $Method = "insert";
}

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <?php include_once PATH."/modules/body/head.php"; ?>
</head>
<body style="width: 850px; overflow: hidden; margin: auto">
<form name="edit" class="layui-form" method="post" action="/pages/admin/article/edit._method.php">
    <!-- 给出一些隐藏对象, 用于提交表单时的数据提交 -->
    <div style="visibility: hidden; width: 0; height: 0;">
        <button id="btn-yes" name="btn-yes" type="button" onclick="process('<?php echo $Method ?>');">提交</button>
        <button id="btn-del" name="btn-del" type="button" onclick="process('del');">删除</button>
        <input name="method" id="method" value="">
        <input name="id" value="<?php echo $Result["ID"]; ?>">
    </div>
    <div class="layui-tab">
        <ul class="layui-tab-title">
            <li class="layui-this">属性设置</li>
            <li>内容编写</li>
        </ul>
        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">
                <div class="layui-form-item">
                    <label class="layui-form-label">唯一标识</label>
                    <div class="layui-form-mid layui-word-aux"><?php echo $Result["ID"]; ?></div>
                </div>
                <!-- 类型选择 -->
                <div class="layui-form-item">
                    <label class="layui-form-label">类型</label>
                    <div class="layui-input-block">
                        <?php $CheckedRadio[$Result["Type"]] = "checked"; ?>
                        <input type="radio" name="type" value="新闻"
                               title="新闻" <?php echo empty($CheckedRadio["新闻"]) ? "" : "checked"; ?>>
                        <input type="radio" name="type" value="通知"
                               title="通知" <?php echo empty($CheckedRadio["通知"]) ? "" : "checked"; ?>>
                        <input type="radio" name="type" value="概述"
                               title="概述" <?php echo empty($CheckedRadio["概述"]) ? "" : "checked"; ?>>
                    </div>
                </div>
                <!-- 发布时间 -->
                <div class="layui-form-item">
                    <label class="layui-form-label">发布时间</label>
                    <div class="layui-input-block">
                        <input type="text"
                               name="updatetime"
                               class="layui-input"
                               id="updatetime"
                               placeholder="yyyy-MM-dd"
                               lay-key="1">
                    </div>
                </div>
                <!-- 链接的名称 -->
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
                <!-- 链接的URL -->
                <div class="layui-form-item">
                    <label class="layui-form-label">作者</label>
                    <div class="layui-input-block">
                        <label>
                            <input type="text"
                                   name="author"
                                   required
                                   lay-verify="required"
                                   placeholder="请输入作者"
                                   autocomplete="off"
                                   class="layui-input"
                                   maxlength="5"
                                   value="<?php echo $Result["Author"]; ?>">
                        </label>
                    </div>
                </div>
                <!-- 置顶状态 -->
                <div class="layui-form-item">
                    <label class="layui-form-label">置顶状态</label>
                    <div class="layui-input-block">
                        <?php
                        if($Result["TopMost"] == "1")
                            $TopMost = "checked";
                        else $TopMost = "";
                        ?>
                        <input type="checkbox" name="topmost" lay-skin="switch"
                               lay-text="ON|OFF" <?php echo $TopMost ?>>
                    </div>
                </div>
            </div>
            <div class="layui-tab-item">
                <!-- 内容编写 -->
                <?php
                $EditorConfig["ID"]      = "article";
                $EditorConfig["Height"]  = "450";
                $EditorConfig["Text"]    = $Result["Text"];
                $EditorConfig["ImgPath"] = "/data/images/article/";
                include PATH."/modules/editor/editor.php";
                ?>
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
            elem: '#updatetime'
            , value: '<?php echo $Result["UpdateTime"] ?>'
        });
    });

    function updatenews(opt) {
        document.getElementById("method").value = opt;
        document.edit.submit();
    }
</script>
</body>
</html>



<?php require_once "/var/www/html/config.php";
LoginChecked();
if(!empty($_GET["id"]) && $_GET["id"] > 0)
{
    $QueryString = "SELECT * FROM TIC_Hyperlink WHERE ID=:_ID";
    $Parameters  = [":_ID@d" => $_GET["id"]];
    $Result      = GetSingleResult($QueryString, $Parameters);
    $Method      = "update";
}
else
{
    $Result = ["ID" => "系统自动分配", "Type" => "新闻轮播图", "LinkName" => "", "LinkURL" => "", "ImageName" => GetGUID(), "Enabled" => "1", "TopMost" => "0"];
    $Method = "insert";
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <?php include_once PATH."/modules/body/head.php"; ?>
</head>
<body style="width: 850px; overflow: hidden; margin: auto">

<form name="edit" class="layui-form" method="post" action="/pages/admin/setting/hyperlink.edit._method.php">
    <!-- 给出一些隐藏对象, 用于提交表单时的数据提交 -->
    <div style="visibility: hidden; width: 0; height: 0;">
        <button id="btn-yes" name="btn-yes" type="button" onclick="process('<?php echo $Method ?>');">提交</button>
        <button id="btn-del" name="btn-del" type="button" onclick="process('del');">删除</button>
        <input name="method" id="method" value="del">
        <input name="id" value="<?php echo $Result["ID"]; ?>">
    </div>
    <!-- 显示唯一标识 -->
    <div class="layui-form-item">
        <label class="layui-form-label">唯一标识</label>
        <div class="layui-form-mid layui-word-aux"><?php echo $Result["ID"]; ?></div>
    </div>
    <!-- 类型选择 -->
    <div class="layui-form-item">
        <label class="layui-form-label">链接类型</label>
        <div class="layui-input-block">
            <?php
            if(strtolower($Result["Type"]) === "脚注超链接")
                $CheckedRadio = ["carousel" => "", "link" => "checked"];
            else $CheckedRadio = ["carousel" => "checked", "link" => ""];
            ?>
            <input type="radio" name="type" value="新闻轮播图" title="新闻轮播图" <?php echo $CheckedRadio["carousel"] ?>>
            <input type="radio" name="type" value="脚注超链接" title="脚注超链接" <?php echo $CheckedRadio["link"] ?>>
        </div>
    </div>
    <!-- 链接的名称 -->
    <div class="layui-form-item">
        <label class="layui-form-label">链接标题</label>
        <div class="layui-input-block">
            <label>
                <input type="text"
                       name="linkname"
                       required
                       lay-verify="required"
                       placeholder="请输入标题"
                       autocomplete="off"
                       class="layui-input"
                       value="<?php echo str_replace("\"", "&quot;", $Result["LinkName"]); ?>">
            </label>
        </div>
    </div>
    <!-- 链接的URL -->
    <div class="layui-form-item">
        <label class="layui-form-label">链接地址</label>
        <div class="layui-input-block">
            <label>
                <input type="url"
                       name="linkurl"
                       required
                       lay-verify="required"
                       placeholder="请输入链接"
                       autocomplete="off"
                       class="layui-input"
                       value="<?php echo $Result["LinkURL"]; ?>">
            </label>
        </div>
    </div>
    <!-- 显示状态 -->
    <div class="layui-form-item">
        <label class="layui-form-label">展示状态</label>
        <div class="layui-input-block">
            <?php
            if($Result["Enabled"] == "1")
                $Enabled = "checked";
            else $Enabled = "";
            ?>
            <input type="checkbox" name="enabled" lay-skin="switch" lay-text="ON|OFF" <?php echo $Enabled; ?>>
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
            <input type="checkbox" name="topmost" lay-skin="switch" lay-text="ON|OFF" <?php echo $TopMost ?>>
        </div>
    </div>
    <!-- 上传图片 -->
    <div class="layui-form-item">
        <label class="layui-form-label">上传图片</label>
        <div class="layui-input-inline " style="width: 700px">
            <label>
                <input name="imagename" value="<?php echo $Result["ImageName"]; ?>" style="visibility: hidden">
            </label>
            <div style="float: left">
                <?php
                $UploadConfig["Extensions"] = "jpg|png|gif|jpeg|bmp";
                $UploadConfig["Size"]       = "4096";
                $UploadConfig["FilePath"]   = "/data/images/hyperlink/";
                $UploadConfig["FileName"]   = $Result["ImageName"];
                include PATH."/modules/upload/upload.standard.php";
                ?>
            </div>
        </div>
    </div>

</form>
<script>
    layui.use('form', function () {
        let form = layui.form;
    });
</script>
</body>
</html>



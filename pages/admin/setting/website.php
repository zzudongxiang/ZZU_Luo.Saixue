<?php require_once "/var/www/html/config.php";
LoginChecked();
$QueryString = "SELECT ConfigKey, ConfigValue FROM TIC_WebSite WHERE ConfigKey LIKE 'WebSite_Title' OR ConfigKey LIKE 'WebSite_Footer' OR ConfigKey LIKE 'Login_Notice';";
$WebSiteData = GetDataTable($QueryString);
$Setting     = array();
foreach($WebSiteData as $Row)
    $Setting[$Row["ConfigKey"]] = $Row["ConfigValue"];
?>

<form class="layui-form layui-form-pane" action="/pages/admin/setting/website._method.php" method="post">

    <div class="layui-form-item">
        <label class="layui-form-label">网站Logo</label>
        <div class="layui-input-inline " style="width: 700px">
            <div style="width: 30px; padding: 4px 10px; float: left">
                <?php echo ShowImage("/images/", "icon", 30, 30); ?>
            </div>
            <div style="float: left">
                <?php
                $UploadConfig["Extensions"] = "ico";
                $UploadConfig["Size"]       = "1024";
                $UploadConfig["FilePath"]   = "/images/";
                $UploadConfig["FileName"]   = "icon";
                include PATH."/modules/upload/upload.standard.php";
                ?>
            </div>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">网站标题</label>
        <div class="layui-input-inline " style="width: 500px">
            <input type="text" name="title" class="layui-input" value="<?php echo $Setting["WebSite_Title"]; ?>">
        </div>
        <div class="layui-form-mid layui-word-aux" style="cursor: default">网站的标签名字</div>
    </div>

    <div class="layui-form-item" pane="" style="margin-bottom: 0">
        <label class="layui-form-label">脚注信息</label>
        <div class="layui-input-block">

            <div class="layui-form-mid layui-word-aux"
                 style="margin-left: 20px; cursor: default">这里的信息将会显示到网页的最下方, 默认居中样式
            </div>
        </div>
    </div>
    <?php
    $EditorConfig["ID"]      = "footer";
    $EditorConfig["Height"]  = "150";
    $EditorConfig["Text"]    = $Setting["WebSite_Footer"];
    $EditorConfig["ImgPath"] = "/data/images/website/";
    include PATH."/modules/editor/editor.php";
    ?>
    <div class="layui-form-item" pane="" style="margin-top: 15px; margin-bottom: 0px">
        <label class="layui-form-label">登陆提示</label>
        <div class="layui-input-block">

            <div class="layui-form-mid layui-word-aux"
                 style="margin-left: 20px; cursor: default">这里的信息将在用户登陆时展示在登陆界面
            </div>
        </div>
    </div>
    <?php
    $EditorConfig["ID"]      = "loginnotice";
    $EditorConfig["Height"]  = "150";
    $EditorConfig["Text"]    = $Setting["Login_Notice"];
    $EditorConfig["ImgPath"] = "/data/images/website/";
    include PATH."/modules/editor/editor.php";
    ?>
    <div class="layui-form-item" style="margin-top: 15px">
        <button class="layui-btn" lay-submit="" type="submit">立即提交</button>
    </div>
</form>
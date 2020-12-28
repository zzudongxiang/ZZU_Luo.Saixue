<?php require_once "/var/www/html/config.php";
LoginChecked();
$PagesParameters = GetPagesParameters();
$Key             = empty($PagesParameters["SearchKey"]) ? "" : $PagesParameters["SearchKey"];
$Type            = empty($PagesParameters["SearchType"]) ? "name" : $PagesParameters["SearchType"];
$Selected[$Type] = "selected";
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <?php include_once PATH."/modules/body/head.php"; ?>
</head>
<body style="width: 850px; overflow: hidden; margin: auto">
<h1>搜索相关用户</h1>
<hr>
<form name="edit" class="layui-form" style="margin-top: 50px">
    <!-- 给出一些隐藏对象, 用于提交表单时的数据提交 -->
    <div style="visibility: hidden; width: 0; height: 0;">
        <button id="btn-yes" name="btn-yes" type="button" onclick="searching()">搜索</button>
    </div>
    <!-- 搜索范围 -->
    <div class="layui-form-item">
        <label class="layui-form-label">选择框</label>
        <div class="layui-input-block">
            <select lay-verify="required" lay-filter="search-type">
                <option value="name" <?php echo empty($Selected["name"]) ? "" : "selected"; ?>>姓名</option>
                <option value="num" <?php echo empty($Selected["num"]) ? "" : "selected"; ?>>学号</option>
                <option value="oth" <?php echo empty($Selected["oth"]) ? "" : "selected"; ?>>其他信息</option>
            </select>
        </div>
    </div>
    <input id="search-type" style="visibility: hidden; width: 0; height: 0;" value="<?php echo $Type; ?>">
    <!-- 搜索关键字 -->
    <div class="layui-form-item">
        <label class="layui-form-label">输入框</label>
        <div class="layui-input-block">
            <input id="search-key" type="text" class="layui-input" placeholder="请输入关键字"
                   value="<?php echo $Key; ?>">
        </div>
    </div>
</form>
<script>
    layui.use('form', function () {
        let form = layui.form;
        layui.form.on('select(search-type)', function (data) {
            document.getElementById("search-type").value = data.value;
        });
    });
</script>
</body>
</html>
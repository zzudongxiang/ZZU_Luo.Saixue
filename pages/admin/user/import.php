<?php require_once "/var/www/html/config.php";
LoginChecked();
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <?php include_once PATH."/modules/body/head.php"; ?>
</head>
<body style="width: 850px; overflow: hidden; margin: auto">
<!-- 导入用户按钮 -->
<fieldset class="layui-elem-field layui-field-title site-title">
    <legend><a>批量导入用户</a></legend>
</fieldset>
<button type="button" class="layui-btn" onclick="selectFile()">
    <i class="layui-icon layui-icon-search"> 选择文件导入</i>
</button>
<!-- 给定表格格式 -->
<fieldset class="layui-elem-field layui-field-title site-title">
    <legend><a>导入表格样式</a></legend>
</fieldset>
<table class="layui-table">
    <colgroup>
        <col width="150">
        <col width="200">
        <col>
    </colgroup>
    <thead>
    <tr>
        <th>姓名</th>
        <th>学号</th>
        <th>年级/专业</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>张三</td>
        <td>201900000000</td>
        <td>XXXX学院XXXX年级XXXX专业XX班级 学生/班长/宿舍长等</td>
    </tr>
    <tr>
        <td><i>NULL</i></td>
        <td><i>NULL</i></td>
        <td><i>NULL</i></td>
    </tr>
    </tbody>
</table>
<!-- 导入表格时的注意事项 -->
<fieldset class="layui-elem-field layui-field-title site-title">
    <legend><a name="attr">导入注意事项</a></legend>
</fieldset>
<blockquote class="layui-elem-quote" style="color: #FF0000">
    <p>1. 导入文件的格式为： *.xls 或 *.xlsx;</p>
    <p>2. Excel文件的第一行为标题行;</p>
    <p>3. Excel文件的第一个表格应为数据所在表格;</p>
    <p>4. Excel标题内容一定要与上表一致;</p>
    <p>5. Excel只能有3列数据, 且按照上表样式设定;</p>
</blockquote>
<!-- 提交的文件区域 -->
<form name="edit" action="/pages/admin/user/import._method.php" method="post" enctype="multipart/form-data">
    <input type="file" name="file" id="file" onchange="checkfile();" style="visibility: hidden;width: 0;height: 0;"/>
</form>
<script>
    layui.use('form', function () {
        let form = layui.form;
        layui.form.on('select(type)', function (data) {
            document.getElementById("type").value = data.value;
        });
    });

    function selectFile() {
        $("#file").trigger("click");
    }

    function checkfile() {
        let fileDir = document.getElementById("file").value;
        let suffix = fileDir.substr(fileDir.lastIndexOf("."));
        if ("" === fileDir) {
            alert("选择需要导入的Excel文件！");
            return;
        }
        if (".xls" !== suffix && ".xlsx" !== suffix) {
            alert("选择Excel格式的文件导入！");
            document.getElementById("file").value = "";
            return;
        }
        layui.use('layer', function () {
            layui.layer.load(0);
        });
        document.edit.submit();
    }
</script>
</body>
</html>
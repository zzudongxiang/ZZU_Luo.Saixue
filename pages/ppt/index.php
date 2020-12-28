<?php require_once "/var/www/html/config.php";
$UserData        = LoginChecked();
$PagesParameters = GetPagesParameters();
$ResultCount     = GetSingleResult("SELECT COUNT(*) FROM TIC_PPT WHERE Title LIKE '%".$PagesParameters["SearchKey"]."%'")["COUNT(*)"];
$Result          = GetDataTable("SELECT * FROM TIC_PPT WHERE Title LIKE '%".$PagesParameters["SearchKey"]."%' LIMIT ".$PagesParameters["StartIndex"].", ".$PagesParameters["Limit"].";");
$HiddenValue     = "";
if(count($Result) > 0)
{
    $TableData = "";
    foreach($Result as $Row)
    {
        $ID          = $Row["ID"];
        $TableData   .= "
        <tr>
            <td>
                <input type='text' name='title_$ID'
                        oninput='update_text(this,\"$ID\",\"title\")'
                        id='title_$ID' required lay-verify='required'
                        placeholder='请输入页面名称'
                        autocomplete='off'
                        class='layui-input'
                        value='".$Row["Title"]."'>
            </td>
            <td>
                <input type='text' name='text_$ID'
                        oninput='update_text(this,\"$ID\",\"text\")'
                        id='text_$ID' required lay-verify='required'
                        placeholder='请输入播报内容'
                        autocomplete='off'
                        class='layui-input'
                        value='".$Row["Text"]."'>    
            </td>
            <td>
                <a type='button' onclick='selectimg(\"$ID\");'
                    class='layui-btn layui-btn-radius' id='btn_img_$ID'>
                    上传文件
                </a> 
            </td>
            <td>
                <a class='layui-btn layui-btn-radius layui-btn-normal' href='javascript:shownimg($ID);'>查看</a>
            </td>
            <td><button class='layui-btn layui-btn-danger' onclick='del_one(\"$ID\")'>删除</button></td>      
        </tr>    
    ";
        $HiddenValue .= "<input id='file_$ID' type='file' style='width: 0; height: 0; visibility: hidden' onchange='uploadimg(\"$ID\")'>";
    }
}
else $TableData = "<tr><td colspan='5'>暂无相关数据</td></tr>";

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <?php include_once PATH."/modules/body/head.php"; ?>
</head>
<body class="sys-body-style"
">
<?php include_once PATH."/modules/navigation/menu.php"; ?>
<div class="sys-console-body">
    <div class="sys-nav">
        <?php include_once PATH."/modules/navigation/nav.php"; ?>
    </div>
    <div class="sys-panel">
        <h1 class="site-h1">PPT介绍</h1>
        <hr class="layui-bg-blue">
        <div style="display: block">
            <div style="float: left">
                <button class='layui-btn' onclick="add_one()">
                    <i class="layui-icon layui-icon-add-circle"> 添加</i>
                </button>
            </div>
            <div style="float: right">
                <button class="layui-btn layui-btn-normal" onclick="searching()" type="submit">
                    <i class="layui-icon layui-icon-search"> 查询</i>
                </button>
            </div>
            <div style="float: right; margin: 0 15px">
                <div class="layui-form">
                    <input id="search-key"
                           type="text"
                           class="layui-input"
                           placeholder="请输入搜索关键字"
                           value="<?php echo $PagesParameters["SearchKey"]; ?>">
                </div>
            </div>
        </div>
        <div style="clear: both;"></div>
        <form class="layui-form" lay-filter="judge" name="judge">
            <table class="layui-table layui-form" style="text-align: center">
                <colgroup>
                    <col width="150">
                    <col>
                    <col width="100">
                    <col width="100">
                    <col width="100">
                </colgroup>
                <thead>
                <tr>
                    <th style="text-align: center">页面名称</th>
                    <th style="text-align: center">播报内容</th>
                    <th style="text-align: center">上传图片</th>
                    <th style="text-align: center">查看图片</th>
                    <th style="text-align: center">操作</th>
                </tr>
                </thead>
                <?php echo $TableData ?>
            </table>
        </form>
        <?php include_once PATH."/modules/pages.php"; ?>
    </div>
    <?php echo $HiddenValue; ?>
</div>
<?php include_once PATH."/modules/body/footer.php"; ?>
<script>
    function update_text(obj, id, type) {
        $.ajax({
            url: "/pages/ppt/update._method.php?type=" + type,
            type: "post",
            data: {id: id, value: obj.value},
            dataType: 'json'
        });
    }

    function add_one() {
        layui.use('form', function () {
            layer.prompt(function (value, index, elem) {
                $.ajax({
                    url: "/pages/ppt/add._method.php",
                    dataType: 'json',
                    type: "post",
                    data: {name: value},
                    success: function (data) {
                        top.location.href = UpdateParam(top.location.href, "reload", new Date().getTime());
                    }
                });
            });
        });
    }

    function del_one(id) {
        if (confirm("确定要删除当前幻灯片吗？")) {
            $.ajax({
                url: "/pages/ppt/del._method.php",
                dataType: 'json',
                type: "post",
                data: {id: id},
                success: function (data) {
                    top.location.href = UpdateParam(top.location.href, "reload", new Date().getTime());
                }
            });
        }
    }

    function selectimg(ids) {
        $('#file_' + ids).click();
    }

    function uploadimg(ids) {
        let file = $("#file_" + ids);
        let formData = new FormData();
        formData.append('img', file[0].files[0]);
        formData.append('id', ids);
        layui.use('layer', function () {
            let index = layer.load();
            $.ajax({
                url: "/pages/ppt/img._method.php",
                type: "post",
                contentType: false,
                processData: false,
                data: formData,
                success: function (data) {
                    layer.close(index);
                }
            });
            file.val('');
            console.log(file.val())
        });
    }

    function shownimg(ids) {
        layui.use('layer', function () {
            let index = layer.load();
            $.ajax({
                url: "/pages/ppt/show._method.php",
                type: "post",
                data: {id: ids},
                dataType: 'json',
                success: function (data) {
                    layer.close(index);
                    layer.open({
                        type: 1,
                        title: '',
                        closeBtn: false,
                        shadeClose: true,
                        area: ['600px', '400px'],
                        content: data.img,
                    });
                }
            });

        });
    }
</script>
</body>
</html>
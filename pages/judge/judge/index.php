<?php require_once "/var/www/html/config.php";
$UserData        = LoginChecked();
$PagesParameters = GetPagesParameters();
$ResultCount     = GetSingleResult("SELECT COUNT(*) FROM TIC_Judge WHERE JudgeName LIKE '%".$PagesParameters["SearchKey"]."%'")["COUNT(*)"];
$Result          = GetDataTable("SELECT * FROM TIC_Judge WHERE JudgeName LIKE '%".$PagesParameters["SearchKey"]."%' ORDER BY OnlineStatus DESC, Enabled DESC, ID DESC LIMIT ".$PagesParameters["StartIndex"].", ".$PagesParameters["Limit"].";");
if(count($Result) > 0)
{
    $TableData = "";
    foreach($Result as $Row)
    {
        $OnlineStatus = $Row["OnlineStatus"] == 1 ? "checked" : "";
        $Enabled      = $Row["Enabled"] == 1 ? "checked" : "";
        $JudgeRole    = $Row["JudgeRole"] == 1 ? "checked" : "";
        $ID           = $Row["ID"];
        $TableData    .= "
        <tr>
            <td>
                <a href='javascript:shownimg($ID);'><label class='layui-form-label' style='text-align: left'> ".$Row["JudgeName"]." </label></a>
            </td>
            <td>
                <button type='button' onclick=\"selectimg($ID)\"
                    class='layui-btn layui-btn-radius' id='btn_img_$ID'>
                    上传文件
                </button>
                <input id='file_$ID' type='file' style='width: 0; height: 0; visibility: hidden' onchange='uploadimg($ID)'>
            </td>
            <td>
                <a class='layui-btn layui-btn-radius layui-btn-normal' href='javascript:shownimg($ID);'>查看</a>
            </td>
            <td>
                <input type='number' name='W_$ID'
                        oninput='update_weight(this,$ID)'
                        id='sub' required lay-verify='required'
                        placeholder='请输入权重'
                        autocomplete='off'
                        class='layui-input'
                        value='".$Row["Weight"]."'>
            </td>
            <td><input name='R_$ID' type='checkbox' lay-skin='switch' lay-filter='judgerole' lay-text='专业|嘉宾' value='$ID' $JudgeRole></td>
            <td><input name='O_$ID' type='checkbox' lay-skin='switch' lay-filter='online' lay-text='在线|离线' value='$ID' $OnlineStatus></td>
            <td><input name='E_$ID' type='checkbox' lay-skin='switch' lay-filter='enabled' lay-text='启用|禁用' value='$ID' $Enabled></td>
            <td><button class='layui-btn layui-btn-danger' onclick='del_one($ID)'>删除</button></td>      
        </tr>
    ";
    }
}
else $TableData = "<tr><td colspan='5'>暂无相关数据</td></tr>";

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
        <h1 class="site-h1">评委管理</h1>
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
                    <col>
                    <col width="150">
                    <col width="100">
                    <col width="100">
                    <col width="100">
                    <col width="100">
                    <col width="100">
                    <col width="100">
                </colgroup>
                <thead>
                <tr>
                    <th style="text-align: center">评委姓名</th>
                    <th style="text-align: center">上传图片</th>
                    <th style="text-align: center">查看图片</th>
                    <th style="text-align: center">评委权重</th>
                    <th style="text-align: center">专业评委</th>
                    <th style="text-align: center">在线状态</th>
                    <th style="text-align: center">是否启用</th>
                    <th style="text-align: center">操作</th>
                </tr>
                </thead>
                <?php echo $TableData ?>
            </table>
        </form>
        <?php include_once PATH."/modules/pages.php"; ?>
    </div>
</div>
<?php include_once PATH."/modules/body/footer.php"; ?>
<script>
    let form;
    layui.use('form', function () {
        form = layui.form;
        form.on('switch(enabled)', function (data) {
            $.ajax({
                url: "/pages/judge/judge/judge._method.php?type=enabled",
                type: "post",
                data: {id: data.value, value: data.elem.checked},
                dataType: 'json'
            });
        });
        form.on('switch(online)', function (data) {
            $.ajax({
                url: "/pages/judge/judge/judge._method.php?type=online",
                type: "post",
                data: {id: data.value, value: data.elem.checked},
                dataType: 'json'
            });
        });
        form.on('switch(judgerole)', function (data) {
            $.ajax({
                url: "/pages/judge/judge/judge._method.php?type=judgerole",
                type: "post",
                data: {id: data.value, value: data.elem.checked},
                dataType: 'json'
            });
        });
    });

    function update_weight(obj, id) {
        $.ajax({
            url: "/pages/judge/judge/judge._method.php?type=input",
            type: "post",
            data: {id: id, value: obj.value},
            dataType: 'json'
        });
    }

    function backgroud() {
        window.setInterval(function () {
            $.ajax({
                url: "/pages/judge/judge/get._method.php",
                dataType: 'json',
                success: function (data) {
                    form.val("judge", data);
                }
            });
        }, 3000)
    }

    function add_one() {
        layui.use('form', function () {
            layer.prompt(function (value, index, elem) {
                $.ajax({
                    url: "/pages/judge/judge/add._method.php",
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
        if (confirm("确定要删除当前评委吗？")) {
            $.ajax({
                url: "/pages/judge/judge/del._method.php",
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
                url: "/pages/judge/judge/img._method.php",
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
                url: "/pages/judge/judge/show._method.php",
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
                        area: ['180px', '280px'],
                        content: data.img,
                    });
                }
            });

        });
    }
</script>
</body>
</html>
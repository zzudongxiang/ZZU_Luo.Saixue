<?php require_once "/var/www/html/config.php";
$UserData        = LoginChecked();
$PagesParameters = GetPagesParameters();
$Result          = GetDataTable("SELECT * FROM TIC_Honor;");
$HiddenValue     = "";
if(count($Result) > 0)
{
    $TableData = "";
    $I         = 0;
    foreach($Result as $Row)
    {
        $ID      = $Row["ID"];
        $TopMost = "";
        if($Row["TopMost"] == 1)
            $TopMost = "checked";
        $Enabled = "";
        if($Row["Enabled"] == 1)
            $Enabled = "checked";
        $Type = "";
        if($Row["class"] == "MAT")
            $Type = "checked";
        $I++;
        $TableData   .= "
        <tr>
            <td>$I</td>
            <td>
                <input type='text' name='name_$ID'
                        oninput='update_text(this,\"$ID\",\"name\")'
                        id='name_$ID' required lay-verify='required'
                        placeholder='请输入名称'
                        autocomplete='off'
                        class='layui-input'
                        value='".$Row["Name"]."'>
                <br>
                <input type='text' name='honor_$ID'
                        oninput='update_text(this,\"$ID\",\"honor\")'
                        id='honor_$ID' required lay-verify='required'
                        placeholder='请输入荣誉'
                        autocomplete='off'
                        class='layui-input'
                        value='".$Row["Honor"]."'> 
                 <br>
                 <input type='number' name='sort_$ID'
                        oninput='update_text(this,\"$ID\",\"sort\")'
                        id='sort_$ID' required lay-verify='required'
                        placeholder='请输优先级'
                        autocomplete='off'
                        class='layui-input'
                        value='".$Row["sort"]."'>                    
            </td>
            <td>
                  <textarea name='honor_$ID'
                        oninput='update_text(this,\"$ID\",\"remark\")'
                        id='honor_$ID'
                        placeholder='请输入简介'
                        class='layui-input'
                        style='height: 180px'>".$Row["Remark"]."</textarea >   
            </td>
            <td>
                <input name='topmost_$ID' type='checkbox' lay-skin='switch' lay-filter='topmost' value='$ID' lay-text='置顶|正常' $TopMost> 
            <br>
            <br>
                <input name='enabled_$ID' type='checkbox' lay-skin='switch' lay-filter='enabled' value='$ID' lay-text='启用|禁用' $Enabled>
            <br>
            <br>
                <input name='type_$ID' type='checkbox' lay-skin='switch' lay-filter='type' value='$ID' lay-text='Matlab|电子设计' $Type> 
            </td> 
            <td>
                <a type='button' onclick='selectimg(\"$ID\");'
                    class='layui-btn' id='btn_img_$ID'>
                    上传图片
                </a> 
                <br>
                <br>
                <a class='layui-btn layui-btn-normal' href='javascript:shownimg($ID);'>查看图片</a>
                <br>
                <br>
                <button class='layui-btn layui-btn-danger' onclick='del_one(\"$ID\")'>删　　除</button>
            </td>
                 
        </tr>    
    ";
        $HiddenValue .= "<input id='file_$ID' type='file' style='width: 0; height: 0; visibility: hidden' onchange='uploadimg(\"$ID\")'>";
    }
}
else $TableData = "<tr><td colspan='7'>暂无相关数据</td></tr>";

?>

<div>
    <div style="display: block">
        <div style="float: left">
            <button class='layui-btn' onclick="add_one()">
                <i class="layui-icon layui-icon-add-circle"> 添加</i>
            </button>
        </div>
    </div>
    <div style="clear: both;"></div>
    <form class="layui-form" lay-filter="judge" name="judge">
        <table class="layui-table layui-form" lay-even style="text-align: center">
            <colgroup>
                <col width="20">
                <col width="180">
                <col>
                <col width="120">
                <col width="120">
            </colgroup>
            <thead>
            <tr>
                <th style="text-align: center"><b>序号</b></th>
                <th style="text-align: center"><b>姓名</b></th>
                <th style="text-align: center"><b>简介</b></th>
                <th style="text-align: center"><b>属性</b></th>
                <th style="text-align: center"><b>操作</b></th>
            </tr>
            </thead>
            <?php echo $TableData ?>
        </table>
    </form>
</div>
<?php echo $HiddenValue; ?>

<script>
    let form;
    layui.use('form', function () {
        form = layui.form;
        form.on('switch(enabled)', function (data) {
            $.ajax({
                url: "/pages/admin/setting/update._method.php?type=enabled",
                type: "post",
                data: {id: data.value, value: data.elem.checked},
                dataType: 'json'
            });
        });
        form.on('switch(topmost)', function (data) {
            $.ajax({
                url: "/pages/admin/setting/update._method.php?type=topmost",
                type: "post",
                data: {id: data.value, value: data.elem.checked},
                dataType: 'json'
            });
        });
        form.on('switch(type)', function (data) {
            $.ajax({
                url: "/pages/admin/setting/update._method.php?type=class",
                type: "post",
                data: {id: data.value, value: data.elem.checked},
                dataType: 'json'
            });
        });
    });

    function update_text(obj, id, type) {
        $.ajax({
            url: "/pages/admin/setting/update._method.php?type=" + type,
            type: "post",
            data: {id: id, value: obj.value},
            dataType: 'json'
        });
    }

    function add_one() {
        layui.use('form', function () {
            layer.prompt(function (value, index, elem) {
                $.ajax({
                    url: "/pages/admin/setting/add._method.php",
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
        if (confirm("确定要删除当前学生吗？")) {
            $.ajax({
                url: "/pages/admin/setting/del._method.php",
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
                url: "/pages/admin/setting/img._method.php",
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
                url: "/pages/admin/setting/show._method.php",
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
                        area: ['400px', '600px'],
                        content: data.img,
                    });
                }
            });

        });
    }
</script>
<?php require_once "/var/www/html/config.php";

# 对Upload按钮的相关参数进行初始化配置
$DefaultConfig = ["ID" => "", "Extensions" => "tmp", "Size" => "1024", "FilePath" => "/data/tmp/", "FileName" =>
    GetGUID(),"Title"=>"上传文件", "Enabled" => 1];
if(!empty($UploadConfig))
{
    foreach($DefaultConfig as $ConfigKey => $ConfigValue)
        if(empty($UploadConfig[$ConfigKey]))
            $UploadConfig[$ConfigKey] = $ConfigValue;
}
else $UploadConfig = $DefaultConfig;

# 限定上传文件大小最大不能超过1GB
if($UploadConfig["Size"] > 1024 * 1024 || $UploadConfig["Size"] <= 0)
    $UploadConfig["Size"] = 1024 * 1024;

?>

<script>
    layui.use('element', function () {
        const element = layui.element;
    });
    layui.use('upload', function () {
        const upload = layui.upload;
        <?php echo $UploadConfig["Enabled"] == 1 ? "" : "return;"; ?>
        upload.render({
            elem: "#<?php echo "btn".$UploadConfig["ID"]; ?>"
            , url: "/modules/upload/upload._method.php"
            , size: "<?php echo $UploadConfig["Size"]; ?>"
            , accept: "file"
            , exts: "<?php echo $UploadConfig["Extensions"]; ?>"
            , number: "1"
            , data: {
                FilePath: "<?php echo $UploadConfig["FilePath"]; ?>",
                FileName: "<?php echo $UploadConfig["FileName"]; ?>",
                Extensions: "<?php echo $UploadConfig["Extensions"]; ?>"
            }
            , before: function (obj) {
                let index = layer.open({
                    title: '上传文件中',
                    content: "<p style=text-align: center;'>正在上传文件, 请勿进行其他操作</p>",
                    btn: [],
                    closeBtn: 0,
                    move: false,
                    id: "layer"
                });
                document.getElementById("<?php echo "opt".$UploadConfig["ID"]; ?>").style.visibility = "hidden";
                document.getElementById("<?php echo "opt".$UploadConfig["ID"]; ?>").style.height = "0px";
                document.getElementById("<?php echo "wat".$UploadConfig["ID"]; ?>").style.visibility = "";
                document.getElementById("<?php echo "wat".$UploadConfig["ID"]; ?>").style.height = "38px";
            }
            , done: function (res) {
                ProcessDone(res.code);
            }
            , error: function () {
                alert(res.msg);
                ProcessDone(-1);
            }
            , progress: function (percent) {
                layui.element.progress('<?php echo "pro".$UploadConfig["ID"]; ?>', percent + '%');
            }
        });
    });

    function ProcessDone(ResultCode) {
        if (ResultCode === 0) {
            document.getElementById("<?php echo "res".$UploadConfig["ID"]; ?>").innerText = "上传成功!";
            document.getElementById("<?php echo "res".$UploadConfig["ID"]; ?>").style.color = "#4dc86f";
            document.getElementById("<?php echo "res".$UploadConfig["ID"]; ?>").className = "layui-icon layui-icon-ok";
        } else {
            document.getElementById("<?php echo "res".$UploadConfig["ID"]; ?>").innerText = "上传失败!";
            document.getElementById("<?php echo "res".$UploadConfig["ID"]; ?>").style.color = "#FF0000";
            document.getElementById("<?php echo "res".$UploadConfig["ID"]; ?>").className = "layui-icon layui-icon-close";
        }
        document.getElementById("<?php echo "rmk".$UploadConfig["ID"]; ?>").style.visibility = "hidden";
        document.getElementById("<?php echo "opt".$UploadConfig["ID"]; ?>").style.visibility = "";
        document.getElementById("<?php echo "opt".$UploadConfig["ID"]; ?>").style.height = "38px";
        document.getElementById("<?php echo "wat".$UploadConfig["ID"]; ?>").style.visibility = "hidden";
        document.getElementById("<?php echo "wat".$UploadConfig["ID"]; ?>").style.height = "0px";

        document.getElementById("<?php echo "res".$UploadConfig["ID"]; ?>").style.visibility = "";
        layer.closeAll();
        layui.element.progress('<?php echo "pro".$UploadConfig["ID"]; ?>', "0%");
    }
</script>
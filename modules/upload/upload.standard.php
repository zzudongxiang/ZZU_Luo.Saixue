<?php require_once "/var/www/html/config.php";
if(empty($UploadConfig["ID"]))
    $UploadConfig["ID"] = GetGUID();
include PATH."/modules/upload/upload.script.php";

# 根据Enabled属性, 确定按钮的样式
if($UploadConfig["Enabled"] == 1)
    $ButtonEnabled = ["class" => "", "js" => "", "style" => ""];
else $ButtonEnabled = ["class" => "layui-btn-disabled", "js" => "return;", "style" => "disabled"];

# 获取关于上传文件的一些要求
$Remark = "类型: ".str_replace("|", " | ", $UploadConfig["Extensions"])."　大小: ".GetFileSize($UploadConfig["Size"]);
?>

<div style="width: 100%; height: 38px">
    <div id="<?php echo "opt".$UploadConfig["ID"] ?>" style="height: 38px">
        <button data-method="notice" type="button"
                class="layui-btn layui-btn-normal layui-btn-radius <?php echo $ButtonEnabled["class"] ?>"
                id="<?php echo "btn".$UploadConfig["ID"]; ?>"><i
                    class="layui-icon"></i><?php echo $UploadConfig["Title"]; ?>
        </button>
        <i class="layui-icon" style="color: #FF0000; margin: 0 5px; visibility: hidden"
           id="<?php echo "res".$UploadConfig["ID"] ?>"></i>
        <div id="<?php echo "rmk".$UploadConfig["ID"] ?>" class="layui-inline layui-word-aux" style="cursor: default;">
            <?php echo $Remark ?>
        </div>
    </div>
    <div id="<?php echo "wat".$UploadConfig["ID"] ?>" style="visibility: hidden; height: 38px;">
        <div style="margin: 10px 0px" class="layui-progress layui-progress-big" lay-showpercent="true"
             lay-filter="<?php echo "pro".$UploadConfig["ID"]; ?>">
            <div class="layui-progress-bar layui-bg-red" lay-percent="0%"><span
                        class="layui-progress-text">0%</span>
            </div>
        </div>
    </div>
</div>
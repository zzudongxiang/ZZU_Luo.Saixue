<?php require_once "/var/www/html/config.php";

# 处理POST提交的数据是否正常
if(empty($_FILES["file"]))
    exit(json_encode(['code' => 1, "msg" => "未能读取到上传的文件"]));
if(empty($_POST["FilePath"]) || empty($_POST["FileName"]))
    exit(json_encode(['code' => 1, 'msg' => "未能指定上传到服务器的路径"]));
if(empty($_POST["Extensions"]))
    exit(json_encode(['code' => 1, 'msg' => "未指定允许的上传类型"]));

# 获取上传文件的信息
$Extension  = pathinfo($_FILES["file"]["name"])["extension"];
$Extensions = explode("|", $_POST["Extensions"]);
$FilePath   = $_POST["FilePath"];
$FileName   = $_POST["FileName"];

# 检查上传文件的类型是否是指定的类型
if(!in_array($Extension, $Extensions))
    exit(json_encode(['code' => 1, 'msg' => "上传文件的类型不在指定的类型中"]));

# 如果上传路径不存在, 则自动创建权限为777的文件路径
if(!file_exists(PATH."/".$FilePath))
    mkdir(PATH."/".$FilePath, 0777, true);
# 删除同目录下文件名相同, 但是文件格式不同的文件
if($DirHandler = opendir(PATH."/".$FilePath))
{
    while($FileItem = readdir($DirHandler))
    {
        if(is_dir(PATH."/$FilePath/$FileItem"))
            continue;
        if(pathinfo($FileItem)["filename"] == $FileName && in_array(pathinfo($FileItem)["extension"], $Extensions))
            unlink(PATH."/$FilePath/$FileItem");
    }
    @closedir($DirHandler);
}
else exit(json_encode(['code' => 1, 'msg' => "无法打开指定的文件夹"]));

# 处理上传的文件
if(move_uploaded_file($_FILES["file"]["tmp_name"], PATH."/$FilePath/$FileName.$Extension"))
    exit(json_encode(['code' => 0, "msg" => "上传成功!"]));
else exit(json_encode(['code' => 1, "msg" => "服务器无法接收文件, 可能指定路径无写入权限"]));


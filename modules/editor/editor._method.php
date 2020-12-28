<?php require_once "/var/www/html/config.php";
# 对提交的数据进行有效性判定
if($_FILES == null)
    exit(json_encode(array('errno' => 1, "data" => "未指定要上传的文件")));
if(empty($_POST["ImgPath"]))
    exit(json_encode(array('errno' => 1, "data" => "未指定上传路径")));
else $ImgPath = $_POST["ImgPath"];
# 初始化返回变量
$UrlArray = array();
$ErrNo    = 0;
# 处理上传的数据
foreach($_FILES as $FILE)
{
    # 获取文件的后缀 / 路径 / 新文件名等信息
    $Extension = pathinfo($FILE["name"])["extension"];
    $ImgPath   = str_replace("//", "/", "/$ImgPath/");
    $ImgName   = GetGUID().".$Extension";
    # 检查文件路径是否存在, 如果不存在, 则创建一个可写权限的路径文件夹
    if(!file_exists(PATH.$ImgPath))
        mkdir(PATH."/$ImgPath/", 0777, true);
    # 上传图片文件
    if(move_uploaded_file($FILE["tmp_name"], PATH."/$ImgPath/$ImgName"))
        array_push($UrlArray, str_replace("//", "/", "/$ImgPath/$ImgName"));
    else $ErrNo++;
}
# 返回上传结果
exit(json_encode(array('errno' => $ErrNo, "data" => $UrlArray)));


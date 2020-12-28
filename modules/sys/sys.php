<?php require_once "/var/www/html/config.php";

# 刷新Cookie中储存的登陆信息
if(!empty($_COOKIE["UserData"]))
    setcookie("UserData", $_COOKIE["UserData"], strtotime("+".LICENSE." second"), "/");

/*
 * 获取一个随即生成的GUID序号
 * 生成格式为: 46458a19-b568-9288-3ff3-35a139dc359d
 *
 * $SubPoint为指定分割的位置
 * $Hyphen为生成GUID之间的链接符
 * $Upper为指定生成的GUID是否强制大写状态
 *
 * @return string 返回生成的GUID序号
 */
function GetGUID($Upper = false, $SubPoint = [0, 8, 12, 16, 20, 32], $Hyphen = "-")
{
    $CharList = md5(uniqid(mt_rand(), true));
    if($Upper)
        $CharList = strtoupper($CharList);
    else $CharList = strtolower($CharList);
    $Result = "";
    for($i = 0; $i < count($SubPoint) - 1; $i++)
    {
        if($i !== 0)
            $Result .= $Hyphen;
        $Result .= substr($CharList, $SubPoint[$i], $SubPoint[$i + 1] - $SubPoint[$i]);
    }
    return $Result;
}

/*
 * 在网页中插入一个指定大小的图片
 *
 * @param string    $ImgPath        指定要插入的图片地址的路径
 * @param string    $ImgName        指定要插入的图片名称, 不带后缀
 * @param int       $Width          指定插入的宽度， 默认100
 * @param int       $Height         指定插入的高度， 默认100
 * @param array     $ImgAttributes  指定图片其他特性， 如id或class， 该特性将直接应用到img标签中， 例如： array("id"=>"divlabel")
 * @param array     $DivAttributes  指定Div其他特性， 如id或class， 该特性将直接应用到Div标签中， 例如： array("class"=>"imgclass")
 */
function ShowImage($ImgPath, $ImgName, $Width = 100, $Height = 100, $ImgAttributes = array(), $DivAttributes = array())
{
    $Fitter     = ["jpg", "jpeg", "png", "gif", "ico", "bmp", "JPG", "JPEG", "PNG", "GIF", "ICO", "BMP"];
    $Ori_Width  = 0;
    $Ori_Height = 0;
    foreach($Fitter as $Extension)
    {
        if(file_exists(PATH."/$ImgPath/$ImgName.$Extension"))
        {
            list($Ori_Width, $Ori_Height) = getimagesize(PATH."/$ImgPath/$ImgName.$Extension");
            $Fitter = $Extension;
            break;
        }
    }
    if($Ori_Width == 0 || $Ori_Height == 0)
        return "<p style='text-align: center'>未找到指定图片, 图片可能已损坏</p><br>";

    $WRate = $Width / $Ori_Width;
    $HRate = $Height / $Ori_Height;

    if($WRate >= $HRate)
        $Rate = $HRate;
    else $Rate = $WRate;
    $ImageWidth  = $Ori_Width * $Rate;
    $ImageHeight = $Ori_Height * $Rate;

    $Width  .= "px";
    $Height .= "px";

    $ImgAttr = "";
    if(is_array($ImgAttributes))
        foreach($ImgAttributes as $Key => $Val)
            $ImgAttr .= $Key."='$Val' ";
    $DivAttr = "";
    if(is_array($DivAttributes))
        foreach($DivAttributes as $Key => $Val)
            $DivAttr .= $Key."='$Val' ";

    $ImgHTML = "<img $ImgAttr src='$ImgPath/$ImgName.$Fitter' width='$ImageWidth' height='$ImageHeight' style='margin: auto;'>";
    return "<div $DivAttr style='display: table-cell; vertical-align: middle; text-align: center; width: $Width; height: $Height;'>$ImgHTML</div>";
}

/*
 * 运行一段JS语句, 内置一部分确定的语句内容
 *
 * @prama array $CodeArray 指定要运行的JS内容序列
 *
 * $CodeKey 可选: jump, top.jump, alert, back, reload, dialog
 */
function RunJS($CodeArray)
{
    $JSCode = "<script>";
    if(is_array($CodeArray))
    {
        foreach($CodeArray as $Key => $Value)
        {
            switch(strtolower($Key))
            {
                case "jump":
                    $JSCode .= "location.href = '$Value';";
                    break;
                case "top.jump":
                    $JSCode .= "top.location.href = '$Value';";
                    break;
                case "alert":
                    $JSCode .= "alert('$Value');";
                    break;
                case "back":
                    $JSCode .= "self.location=document.referrer";
                    break;
                case "reload":
                    $JSCode .= "location.reload();";
                    break;
                case "top.reload":
                    $JSCode .= "top.location.href = UpdateParam(top.location.href, 'reload', new Date().getTime());";
                    break;
                case "dialog":
                    $JSCode .= "layer.open({content: '$Value'});";
                    break;
                default:
                    $JSCode .= $Value.";";
            }
        }
    }
    else $JSCode .= $CodeArray;
    echo $JSCode."</script>";
}

/*
 * 检查是否有用户已经登陆, 返回检查结果
 *
 * $Jump    参数指示, 如果用户未登陆, 是否跳转到登陆界面, 默认为跳转
 * @return  如果账户已登陆, 则从数据库中获取用户信息, 并返回获取结果
 *
 */
function LoginChecked($Jump = true)
{
    if(empty($_COOKIE["UserData"]))
    {
        if($Jump)
        {
            RunJS(array("top.jump" => "/pages/login.php"));
            return false;
        }
        else return false;
    }
    else
    {
        $QueryString = "SELECT * FROM TIC_UserInfo WHERE GUID = :_GUID;";
        $Parameters  = [":_GUID" => $_COOKIE["UserData"]];
        return GetSingleResult($QueryString, $Parameters);
    }
}

/*
 * 根据文件的KB大小换算为常用的文件字节大小
 *
 * 输入以KB为单位的字节大小
 * 输出带有单位的字符串对象
 */
function GetFileSize($KBSize)
{
    $AllFormat  = ["KB", "MB", "GB", "TB"];
    $TransPoint = 0;
    while($KBSize >= 1024)
    {
        $TransPoint++;
        $KBSize /= 1024;
    }
    return number_format($KBSize, 2).$AllFormat[$TransPoint];
}

/*
 * 获取带有分页表格的参数值
 */
function GetPagesParameters()
{
    # 初始化返回值
    $Result = ["Current" => 1, "Limit" => 10, "SearchType" => "", "SearchKey" => ""];

    # 获取GET中的当前页面参数, 如果没有指定, 则默认为第一页
    if(!empty($_GET["curr"]))
        $Result["Current"] = (int)$_GET["curr"];

    # 获取页面中GET参数中设定的每页数据个数参数, 如果没有指定, 则默认每页10条数据
    if(!empty($_GET["limit"]))
        $Result["Limit"] = (int)$_GET["limit"];

    # 获取页面中GET参数中设定的Search类型
    if(!empty($_GET["search-type"]))
        $Result["SearchType"] = $_GET["search-type"];

    # 获取页面中GET参数中设定的Search关键值
    if(!empty($_GET["search-key"]))
        $Result["SearchKey"] = $_GET["search-key"];

    # 计算数据库查询起始索引
    $Result["StartIndex"] = $Result["Limit"] * ($Result["Current"] - 1);

    # 返回查询结果
    return $Result;
}


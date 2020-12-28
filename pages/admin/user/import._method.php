<?php require_once "/var/www/html/config.php";
$ResultData = ["SuccessCount" => 0, "ErrorCount" => 0, "ErrorInfo" => array()];
if(!empty($_FILES['file']['name']))
{
    require_once PATH."/modules/xls/PHPExcel.php";
    require_once PATH."/modules/xls/PHPExcel/IOFactory.php";
    require_once PATH."/modules/xls/PHPExcel/Reader/Excel5.php";
    # 处理文件上传部分功能
    $tmp_file   = $_FILES['file']['tmp_name'];
    $file_types = explode(".", $_FILES ['file']['name']);
    $file_type  = $file_types[count($file_types) - 1];//文件类型
    if(strtolower($file_type) != "xls" && strtolower($file_type) != "xlsx")
    {
        $ResultData["ErrorCount"]++;
        array_push($ResultData["ErrorInfo"], "不是Excel文件，重新上传");
        goto end;
    }
    $savePath = PATH."/data/tmp/";
    if(!file_exists($savePath))
        mkdir($savePath, 0777, true);
    $file_name = GetGUID().".".$file_type;
    $filename  = $savePath.'/'.$file_name;
    if(!move_uploaded_file($tmp_file, $filename))
    {
        $ResultData["ErrorCount"]++;
        array_push($ResultData["ErrorInfo"], "文件上传失败");
        goto end;
    }
    # 处理插入数据库的内容
    try
    {
        if(strtolower($file_type) == 'xlsx')
        {
            $objReader   = new PHPExcel_Reader_Excel2007();
            $objPHPExcel = $objReader -> load($filename);
        }
        else
        {
            $objReader   = PHPExcel_IOFactory ::createReader('Excel5');
            $objPHPExcel = $objReader -> load($filename);
        }
        $sheet      = $objPHPExcel -> getSheet(0);
        $highestRow = $sheet -> getHighestRow();
        $NikeName   = $objPHPExcel -> getActiveSheet() -> getCell("A1") -> getValue();
        $UserName   = $objPHPExcel -> getActiveSheet() -> getCell("B1") -> getValue();
        $OtherInfo  = $objPHPExcel -> getActiveSheet() -> getCell("C1") -> getValue();
        if($NikeName == "姓名" && $UserName == "学号" && $OtherInfo == "年级/专业")
        {
            for($j = 2; $j <= $highestRow; $j++)
            {
                $NikeName    = $objPHPExcel -> getActiveSheet() -> getCell("A".$j) -> getValue();
                $UserName    = $objPHPExcel -> getActiveSheet() -> getCell("B".$j) -> getValue();
                $OtherInfo   = $objPHPExcel -> getActiveSheet() -> getCell("C".$j) -> getValue();
                $QueryString = "
                    INSERT INTO TIC_UserInfo (GUID, UserName, PassWord, NikeName, OtherInfo) 
                    VALUES (:_GUID, :_UserName, :_PassWord, :_NikeName, :_OtherInfo) 
                    ON DUPLICATE KEY UPDATE OtherInfo = :_OtherInfo";
                $Parameters  = [":_GUID" => GetGUID(), ":_UserName" => $UserName, ":_PassWord" => $UserName, ":_NikeName" => $NikeName, ":_OtherInfo" => $OtherInfo];
                $Result      = ExecuteNonQuery($QueryString, $Parameters);
                if(!$Result)
                {
                    $ResultData["ErrorCount"]++;
                    array_push($ResultData["ErrorInfo"], "$NikeName($UserName)信息更新失败 -> 数据未变化");
                }
                else $ResultData["SuccessCount"]++;
            }
        }
        else
        {
            $ResultData["ErrorCount"]++;
            array_push($ResultData["ErrorInfo"], "表头与规定不符");
            goto end;
        }
    }
    catch(PHPExcel_Exception $e)
    {
        $ResultData["ErrorCount"]++;
        array_push($ResultData["ErrorInfo"], "无法读取表格: ".$e -> getMessage());
        goto end;
    }
}

end:
$ErrorInfo = "";
foreach($ResultData["ErrorInfo"] as $Row)
    $ErrorInfo .= "$Row\r\n\r\n";

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <?php include_once PATH."/modules/body/head.php"; ?>
</head>
<body style="width: 850px; overflow: hidden; margin: auto">
<h1>执行结果</h1>
<hr>
<form name="edit" class="layui-form" style="margin-top: 50px">
    <!-- 成功结果 -->
    <div class="layui-form-item">
        <label class="layui-form-label">执行成功</label>
        <div class="layui-form-mid layui-word-aux">
            <span style="color: #00A000"><?php echo $ResultData["SuccessCount"]; ?></span>
        </div>
    </div>
    <!-- 失败结果 -->
    <div class="layui-form-item">
        <label class="layui-form-label">执行失败</label>
        <div class="layui-form-mid layui-word-aux">
            <span style="color: #FF0000"><?php echo $ResultData["ErrorCount"]; ?></span>
        </div>
    </div>
    <!-- 失败详细 -->
    <div class="layui-form-item layui-form-text">
        <label class="layui-form-label">失败信息</label>
        <div class="layui-input-block">
            <textarea class="layui-textarea" style="height: 200px"><?php echo $ErrorInfo; ?></textarea>
        </div>
    </div>
</form>
<script>
    layui.use('form', function () {
        let form = layui.form;
    });

    function Done() {
        parent.layer.closeAll();
    }
</script>
</body>
</html>
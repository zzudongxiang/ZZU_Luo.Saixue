<?php require_once "/var/www/html/config.php";

require_once PATH.'/modules/xls/PHPExcel.php';

function GetExcelObj()
{
    return new PHPExcel();
}

function SetSheetsTitle(PHPExcel &$xlsObject, $Index, $Title)
{
    try
    {
        $xlsObject -> setActiveSheetIndex($Index);
        $xlsObject -> getActiveSheet() -> setTitle($Title);
    }
    catch(PHPExcel_Exception $e)
    {
        die($e -> getMessage());
    }
}

function SetTitle(PHPExcel &$xlsObject, array $Titles)
{
    $Index = 0;
    foreach($Titles as $TitleName => $TitleConfig)
    {
        $ColTitle = GetColName($Index);
        try
        {
            if(!preg_match("/^\d+$/", $TitleConfig["width"]))
                $TitleWidth = 20;
            else $TitleWidth = $TitleConfig["width"];
            $xlsObject -> getActiveSheet() -> getColumnDimension($ColTitle) -> setWidth($TitleWidth);
            $xlsObject -> getActiveSheet() -> setCellValue($ColTitle."1", $TitleName);
            if(empty($TitleConfig["align"]))
                $TitleAlign = "left";
            else $TitleAlign = $TitleConfig["align"];
            if($TitleAlign == "center")
            {
                $xlsObject -> getActiveSheet() -> getStyle($ColTitle.":".$ColTitle) -> getAlignment()
                           -> setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            }
            else if($TitleAlign == "left")
            {
                $xlsObject -> getActiveSheet() -> getStyle($ColTitle.":".$ColTitle) -> getAlignment()
                           -> setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            }
            else if($TitleAlign == "right")
            {
                $xlsObject -> getActiveSheet() -> getStyle($ColTitle.":".$ColTitle) -> getAlignment()
                           -> setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            }
            $xlsObject -> getActiveSheet() -> getStyle($ColTitle."1") -> getAlignment()
                       -> setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        }
        catch(PHPExcel_Exception $e)
        {
            die($e -> getMessage());
        }
        $Index++;
    }
}

function FillData(PHPExcel &$xlsObject, array $Data)
{
    $RowNumber = 2;
    foreach($Data as $Row)
    {
        $Index = 0;
        foreach($Row as $Col)
        {
            $ColTitle = GetColName($Index);
            try
            {
                $xlsObject -> getActiveSheet() -> setCellValue($ColTitle.$RowNumber, $Col);
                if(preg_match("/^\d+$/", $Col))
                {
                    $xlsObject -> getActiveSheet() -> getStyle($ColTitle.$RowNumber) -> getNumberFormat()
                               -> setFormatCode("0");
                }
                else if(preg_match("/^\d+\.\d+$/", $Col))
                {
                    $xlsObject -> getActiveSheet() -> getStyle($ColTitle.$RowNumber) -> getNumberFormat()
                               -> setFormatCode("0.00");
                }
            }
            catch(PHPExcel_Exception $e)
            {
                die($e -> getMessage());
            }
            $Index++;
        }
        $RowNumber++;
    }
}

function GetColName($Index)
{
    $ColList  = ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z"];
    $Length   = ceil(($Index + 1) / 26);
    $ColTitle = "";
    for($i = 0; $i < $Length; $i++)
        $ColTitle .= $ColList[$Index % 26];
    return $ColTitle;
}

function SaveExcel(PHPExcel &$xlsObject, $FilePath)
{
    try
    {
        if(!file_exists(pathinfo($FilePath)["dirname"]))
            mkdir(pathinfo($FilePath)["dirname"], 0777, true);
        $xlsWriter = new PHPExcel_Writer_Excel5($xlsObject);
        $xlsWriter -> save($FilePath);
    }
    catch(PHPExcel_Writer_Exception $e)
    {
        die($e -> getMessage());
    }
}

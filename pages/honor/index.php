<?php require_once "/var/www/html/config.php";
$Type      = $_GET["type"];
$Result    = GetDataTable("SELECT * FROM TIC_Honor WHERE class LIKE '$Type' AND Enabled = 1 ORDER BY TopMost DESC, sort;");
$TableData = "";
if(count($Result) > 0)
{
    $Index   = 0;
    $LastRow = "";
    foreach($Result as $Row)
    {
        $Index++;
        if($Index % 2 == 0)
        {
            $TableData .= "
            <tr>
                <td rowspan='3' style='height:280px; width:180px; text-align: center; vertical-align: center; padding: 10px'>
                    ".ShowImage("/data/images/honor/", $LastRow["ID"], 180, 280, ["style" => "border-radius: 20px;"])."
                </td>
                <td style='height: 50px; width: 350px; text-align: left; padding-left: 10px'><b style='font-size: 35px; font-family: \"LiSu\"'>".$LastRow["Name"]."</b></td>
                <td rowspan='3' style='width: 40px'></td>
                <td rowspan='3' style='height:280px; width:180px; text-align: center; vertical-align: center; padding: 10px'>
                    ".ShowImage("/data/images/honor/", $Row["ID"], 180, 280, ["style" => "border-radius: 20px;"])."
                </td>
                <td style='height: 50px; width: 350px; text-align: left; padding-left: 10px'><b style='font-size: 35px; font-family: \"LiSu\"'>".$Row["Name"]."</b></td>
            </tr>
            <tr>
                <td style='height: 25px; text-align: left; padding-left: 10px'><span class='layui-badge'>".$LastRow["Honor"]."</span></td>
                <td style='height: 25px; text-align: left; padding-left: 10px'><span class='layui-badge'>".$Row["Honor"]."</span></td>
            </tr>
            <tr>
                <td style='height: 200px; text-align: left; vertical-align: top; padding: 10px'>".$LastRow["Remark"]."</td>
                <td style='height: 200px; text-align: left; vertical-align: top; padding: 10px'>".$Row["Remark"]."</td>
            </tr>
        ";
        }
        else
        {
            $LastRow = $Row;
        }
    }
    if(count($Result) % 2 == 1)
    {
        $Row       = $Result[count($Result) - 1];
        $TableData .= "
            <tr>
                <td rowspan='3' style='height:280px; width:180px; text-align: center; vertical-align: center; padding: 10px'>
                    ".ShowImage("/data/images/honor/", $Row["ID"], 180, 280)."
                </td>
                <td style='height: 50px; width: 350px; text-align: left; padding-left: 10px'><b style='font-size: 35px; font-family: \"LiSu\"'>".$Row["Name"]."</b></td>
                <td rowspan='3' style='width: 40px'></td>
                <td rowspan='3' colspan='2' style='height:280px; width:180px; text-align: center; vertical-align: center; padding: 10px'>
                </td>
            </tr>
            <tr>
                <td style='height: 25px; text-align: left; padding-left: 10px'><span class='layui-badge'>".$Row["Honor"]."</span></td>
            </tr>s
            <tr>
                <td style='height: 200px; text-align: left; vertical-align: top; padding: 10px'>".$Row["Remark"]."</td>
            </tr>
            ";
    }
}
else $TableData = "<tr><td colspan='5'>暂无相关数据</td></tr>"

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <?php include_once PATH."/modules/body/head.php"; ?>

    <style type="text/css">
        table {
            width: 1180px;
            border: 1px solid gray;
            border-collapse: collapse;
            position: relative;
            margin: 0 auto;
        }

        #box {
            width: 1180px;
            overflow: hidden;
            border: 1px solid gray;
            position: relative;
            margin: 0 auto;
        }

        th, td {
            line-height: 35px;
            border: 1px solid gray;
            text-align: center;
        }
    </style>
</head>
<body class="sys-body-style">
<?php include_once PATH."/modules/navigation/menu.php"; ?>
<div style="padding: 10px; background-color: #FFFFFF">
    <a href='/'><img src='/images/honor_banner.png' alt='荣誉榜单'></a>
    <br><br>
    <h1 style="text-align: center; font-size: 50px">
        <b style="color: #fe0119">
            <?php
            if($Type == "MAT")
                echo "MATLAB与建模";
            else if($Type == "ELE")
                echo "单片机与电子设计";
            else echo "ERROR!!!"
            ?>
        </b>
    </h1>
    <hr>
    <div id="box">
        <table id="con1">
            <?php echo $TableData; ?>
        </table>
    </div>
</div>
<?php include_once PATH."/modules/body/footer.php"; ?>
</body>
</html>

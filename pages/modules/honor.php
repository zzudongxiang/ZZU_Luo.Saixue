<?php require_once "/var/www/html/config.php";
if(empty($TYPE))
    $TYPE = "MAT";
$Result    = GetDataTable("SELECT * FROM TIC_Honor WHERE class LIKE '$TYPE' AND Enabled = 1 ORDER BY TopMost DESC, sort;");
$TableData = "";
$COL       = 5;
if(count($Result) > 0)
{
    for($i = 0; $i < (count($Result) / $COL); $i++)
    {
        $td_img = "<tr>";
        $td_oth = "<tr>";
        $td_spa = "<tr>";
        for($j = 0; $j < $COL; $j++)
        {
            if($i * $COL + $j >= count($Result))
            {
                $td_img .= "<td height='200px'></td>";
                $td_oth .= "<td height='50px'></td>";
            }
            else
            {
                $ID     = $Result[$i * $COL + $j]["ID"];
                $Oth    = "<span class='layui-badge'>".$Result[$i * $COL + $j]["Honor"]."</span><br><b style='font-size: 25px; font-family: \"LiSu\"'>".$Result[$i * $COL + $j]["Name"]."</b>";
                $td_img .= "
                    <td height='200px' align='center' valign='middle'>
                        <a href='/pages/honor/index.php?type=$TYPE'>".ShowImage("/data/images/honor/", $ID, 200, 200, ["style" => "border-radius: 20px;"])."</a>
                    </td> ";
                $td_oth .= "<td height='50px'>$Oth</td>";
                $td_spa .= "<td style='height: 20px; background-color: #EEEEEE'></td>";
            }
        }
        $td_img    .= "</tr>";
        $td_oth    .= "</tr>";
        $td_spa    .= "</tr>";
        $TableData .= $td_img.$td_oth.$td_spa;
    }

}
else return;

?>
<style type="text/css">
    table {
        width: 1200px;
        border: 0 solid gray;
        border-collapse: collapse;
        position: relative;
        margin: 0 auto;
    }

    #<?php echo $TYPE; ?>_box {
        width: 1200px;
        /*height: 500px;*/
        overflow: hidden;
        border: 0 solid gray;
        position: relative;
        margin: 0 auto;
    }

    th, td {
        width: 200px;
        line-height: 35px;
        border: 0 solid gray;
        text-align: center;
    }
</style>
<br>
<h1 style="text-align: center; font-size: 30px">
    <b style="color: #fe0119">
        <?php
        if($TYPE == "MAT")
            echo "MATLAB与建模";
        else if($TYPE == "ELE")
            echo "单片机与电子设计";
        else echo "ERROR!!!"
        ?>
    </b>
</h1>
<hr>
<div class='layui-row' style='text-align: center; background-color: #dddddd'>
    <div id="<?php echo $TYPE; ?>_box">
        <div id="<?php echo $TYPE; ?>_con1">
            <table>
                <?php echo $TableData; ?>
            </table>
        </div>
        <table id="<?php echo $TYPE; ?>_con2"></table>
    </div>
</div>
<script type="text/javascript">
    let <?php echo $TYPE; ?>_box = document.getElementById("<?php echo $TYPE; ?>_box");
    let <?php echo $TYPE; ?>_con1 = document.getElementById("<?php echo $TYPE; ?>_con1");
    let <?php echo $TYPE; ?>_con2 = document.getElementById("<?php echo $TYPE; ?>_con2");
    //<?php echo $TYPE; ?>_con2.innerHTML = <?php echo $TYPE; ?>_con1.innerHTML;
    setInterval(function () {
        if (<?php echo $TYPE; ?>_box.scrollTop >= <?php echo $TYPE; ?>_con1.scrollHeight) {
            <?php echo $TYPE; ?>_box.scrollTop = 0;
        } else {
            <?php echo $TYPE; ?>_box.scrollTop++;
        }
    }, 30);
</script>

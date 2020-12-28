<?php require_once "/var/www/html/config.php";
$QueryString  = "SELECT * FROM TIC_Hyperlink WHERE Type LIKE '新闻轮播图' AND Enabled = 1 ORDER BY TopMost DESC, UpdateTime DESC;";
$CarouselData = GetDataTable($QueryString);
$CarouselHTML = "";
$Width        = 400;
$Height       = 300;
foreach($CarouselData as $Row)
{
    $ImgHTML      = ShowImage("/data/images/hyperlink/", $Row["ImageName"], $Width, $Height, ["alt" =>
        $Row["LinkName"]]);
    $CarouselHTML .= "<div><a href='".$Row["LinkURL"]."' target='_blank'>".$ImgHTML."</a></div>";
}
?>
<div class="layui-carousel" id="carousel">
    <div carousel-item style="text-align: center">
        <?php echo $CarouselHTML; ?>
    </div>
</div>

<script>
    layui.use("carousel", function () {
        const carousel = layui.carousel;
        carousel.render({
            elem: "#carousel"
            , width: "100%"
            , arrow: "always"
        });
    });
</script>
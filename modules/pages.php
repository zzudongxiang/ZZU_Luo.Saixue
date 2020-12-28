<?php require_once "/var/www/html/config.php";
if(empty($ResultCount))
    $ResultCount = 0;
$PagesConfig = GetPagesParameters();
?>
<div id="pages" style="text-align: center;">
    <div class="layui-box layui-laypage layui-laypage-default">
        <!-- 其中内容由LayUI进行补充 -->
    </div>
</div>
<script>
    layui.use('laypage', function () {
        layui.laypage.render({
            elem: 'pages',
            layout: ['count', 'prev', 'page', 'next', 'limit', 'skip'],
            limits: [10, 20, 30, 40, 50, 100],
            count: <?php echo $ResultCount; ?>,
            limit: <?php echo $PagesConfig["Limit"];?>,
            curr: <?php echo $PagesConfig["Current"];?>,
            jump: function (obj, first) {
                if (!first) {
                    let url = location.href;
                    url = UpdateParam(url, "curr", obj.curr);
                    url = UpdateParam(url, "limit", obj.limit);
                    location.href = url;
                }
            }
        });
    });
</script>

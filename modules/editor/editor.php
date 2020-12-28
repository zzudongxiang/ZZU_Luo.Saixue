<?php require_once "/var/www/html/config.php";
$DefaultConfig = ["ID" => "", "Height" => 200, "Text" => "", "ImgPath" => "/data/tmp/"];
if(!empty($EditorConfig))
{
    foreach($DefaultConfig as $ConfigKey => $ConfigValue)
        if(empty($EditorConfig[$ConfigKey]))
            $EditorConfig[$ConfigKey] = $ConfigValue;
}
else $EditorConfig = $DefaultConfig;
?>

<div style="height: <?php echo $EditorConfig["Height"]."px"; ?> ">
    <div id="toolbar<?php echo $EditorConfig["ID"]; ?>" style="border: 1px solid #CCCCCC; height: 30px"></div>
    <div id="text<?php echo $EditorConfig["ID"]; ?>"
         style="border: 1px solid #CCCCCC; height: <?php echo ($EditorConfig["Height"] - 30)."px"; ?>">
        <?php echo $EditorConfig["Text"]; ?>
    </div>
</div>
<textarea id="txt<?php echo $EditorConfig["ID"]; ?>"
          name="<?php echo $EditorConfig["ID"]; ?>"
          style="width: 0; height: 0; visibility: hidden; ">
        <?php echo $EditorConfig["Text"]; ?>
    </textarea>
<script type="text/javascript">
    let $input<?php echo $EditorConfig["ID"]; ?> = $('#txt<?php echo $EditorConfig["ID"]; ?>');
    let edit<?php echo $EditorConfig["ID"]; ?> = new window.wangEditor('#toolbar<?php echo $EditorConfig["ID"]; ?>',
        '#text<?php echo $EditorConfig["ID"]; ?>');
    edit<?php echo $EditorConfig["ID"]; ?>.customConfig.menus = [
        'head',
        'bold',
        'fontSize',
        'fontName',
        'italic',
        'underline',
        'strikeThrough',
        'foreColor',
        'backColor',
        'link',
        'list',
        'justify',
        'quote',
        'emoticon',
        'image',
        'table',
        'code',
    ];
    edit<?php echo $EditorConfig["ID"]; ?>.customConfig.colors = [
        '#ff0000',
        '#000000',
        '#eeece0',
        '#1c487f',
        '#4d80bf',
        '#c24f4a',
        '#8baa4a',
        '#7b5ba1',
        '#46acc8',
        '#f9963b',
        '#ffffff'
    ];
    edit<?php echo $EditorConfig["ID"]; ?>.customConfig.uploadImgServer = '/modules/editor/editor._method.php';
    edit<?php echo $EditorConfig["ID"]; ?>.customConfig.uploadImgTimeout = 60000;
    edit<?php echo $EditorConfig["ID"]; ?>.customConfig.uploadImgMaxSize = 3 * 1024 * 1024;
    edit<?php echo $EditorConfig["ID"]; ?>.customConfig.uploadImgParams = {
        ImgPath: "<?php echo $EditorConfig["ImgPath"]; ?>"
    };
    edit<?php echo $EditorConfig["ID"]; ?>.customConfig.onchange = function (html) {
        $input<?php echo $EditorConfig["ID"]; ?>.val(html);
    };
    edit<?php echo $EditorConfig["ID"]; ?>.create();
</script>

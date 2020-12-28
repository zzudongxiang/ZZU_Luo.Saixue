<?php require_once "/var/www/html/config.php";
$ID = $_GET["id"];

if(!empty($ID) && $ID > 0)
{
    $FilePath = PATH."/data/file/competition/zip/$ID.zip";
    $DirPath  = PATH."/data/file/competition/$ID/";
    if(!file_exists($FilePath))
        mkdir(PATH."/data/file/competition/zip/", 0777, true);
    if(!file_exists($DirPath))
        mkdir(PATH."/data/file/competition/$ID/", 0777, true);
    else unlink($FilePath);
    fclose(fopen($FilePath, 'w'));
    $zip = new \ZipArchive();
    if($zip -> open($FilePath, \ZipArchive::OVERWRITE) === true)
    {
        $DownloadFileName = GetSingleResult("SELECT Title FROM TIC_Competition WHERE ID = $ID;")["Title"];
        $Handler = opendir($DirPath);
        while(($FileName = readdir($Handler)) !== false)
        {
            if($FileName != '.' && $FileName != '..')
                $zip -> addFile($DirPath.'/'.$FileName, $FileName);
        }
        $zip -> close();
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header('Content-disposition: attachment; filename='.$DownloadFileName.".".pathinfo($FilePath)["extension"]);
        header("Content-Type: application/zip");
        header("Content-Transfer-Encoding: binary");
        header('Content-Length: '.filesize($FilePath));
        @readfile($FilePath);
        @closedir($Handler);
    }
}
exit();
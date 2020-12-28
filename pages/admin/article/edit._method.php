<?php require_once "/var/www/html/config.php";
$Result = false;
if(!empty($_POST["id"]) && !empty($_POST["method"]))
{
    if($_POST["method"] === "del")
    {
        $QueryString = "DELETE FROM TIC_Article WHERE ID=:_ID";
        $Parameters  = [":_ID@d" => $_POST["id"]];
        $Result      = ExecuteNonQuery($QueryString, $Parameters);
    }
    else
    {
        if($_POST["topmost"] == "on")
            $TopMost = 1;
        else  $TopMost = 0;

        if($_POST["method"] == "insert")
        {
            $QueryString = "INSERT INTO TIC_Article (Type, Title, Text, UpdateTime, Author, TopMost) VALUES (:_Type, :_Title, :_Text, :_UpdateTime, :_Author, :_TopMost)";
            $Parameters  = [":_Type" => $_POST["type"], ":_Title" => $_POST["title"], ":_Text" => $_POST["article"], ":_UpdateTime" => $_POST["updatetime"], ":_Author" => $_POST["author"], ":_TopMost@d" => $TopMost];
            $Result      = ExecuteNonQuery($QueryString, $Parameters);
        }
        else if($_POST["method"] == "update")
        {
            $QueryString = "UPDATE TIC_Article SET Type=:_Type, Title=:_Title, Text=:_Text, Author=:_Author, TopMost=:_TopMost, UpdateTime=:_UpdateTime WHERE ID=:_ID";
            $Parameters  = [":_Type" => $_POST["type"], ":_Title" => $_POST["title"], ":_Text" => $_POST["article"], ":_Author" => $_POST["author"], ":_TopMost@d" => $TopMost, ":_ID@d" => $_POST["id"], ":_UpdateTime" => $_POST["updatetime"]];
            $Result      = ExecuteNonQuery($QueryString, $Parameters);
        }
    }
}
if(!$Result)
    RunJS(["alert" => "数据未保存"]);
RunJS(["js" => "parent.layer.closeAll();"]);
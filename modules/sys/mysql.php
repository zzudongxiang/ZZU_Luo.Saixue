<?php require_once "/var/www/html/config.php";

/*
 * 如果没有配置网站数据库的连接信息,
 * 则使用以下信息作为默认信息尝试连接数据库
 *
 * 建议在 /var/www/html/config.php中修改对应数据
 */

# 指定数据库连接地址
if(!defined("DB_HOST"))
    define("DB_HOST", "localhost");

# 指定数据库登陆用户名
if(!defined("DB_UNAME"))
    define("DB_UNAME", "root");

# 指定数据库登陆密码
if(!defined("DB_UPWD"))
    define("DB_UPWD", "admin");

# 指定要访问的数据库对象
if(!defined("DB_DBNAME"))
    define("DB_DBNAME", "mysql");

/*
 * 传入一个PDO的地址, 将参数部分内容添加到PDO的执行参数中, 便于执行
 * 参数格式为： :ParameterName@ParameterType
 * 参数类型一栏可以省略, 当类型省略时, 将按照字符串格式解析
 */
function LoadParameters(PDOStatement &$Query, array $Parameters)
{
    foreach($Parameters as $ParameterKey => &$ParameterValue)
    {
        # 去除参数名中所有的空格, 参数名中不允许有空格
        $ParameterKey = str_replace(" ", "", $ParameterKey);
        # 检查参数名格式是否正确
        if(preg_match("/^:[a-zA-Z_]+(@[dbsDBS])?$/", $ParameterKey))
        {
            # 按照指定格式解析字符串对象
            $ParamList = explode("@", $ParameterKey);
            $ParamKey  = $ParamList[0];
            if(count($ParamList) > 1)
                $ParamType = $ParamList[1];
            else $ParamType = "s";
            # 根据参数类型指定PDO传参类型
            switch(strtolower($ParamType))
            {
                case "d":
                    $ParamType = PDO::PARAM_INT;
                    break;
                case "b":
                    $ParamType = PDO::PARAM_BOOL;
                    break;
                default :
                    $ParamType = PDO::PARAM_STR;
                    break;
            }
            # 在SQL执行对象中绑定参数对象与值
            $Query -> bindParam($ParamKey, $ParameterValue, $ParamType);
        }
        else die("编程错误: 不是有效的参数类型");
    }
}

/*
 * 获取一个查询结果, 以数据表方式返回
 *
 * @param   string  $QueryString    要执行的sql语句, 例如: "SELECT * FROM TABLE WHERE COL > :ids"
 * @param   array   $Parameters     对应的数据库语句中的参数列表, 例如: array(":ids" => "1")
 * @return  mixed   $Result         sql语句执行结果
 */
function ExecuteQuery($QueryString, array $Parameters = null)
{
    # 创建Mysql的PDO链接, 并将返回值进行初始化操作
    $HOST       = "mysql:host=".DB_HOST.";dbname=".DB_DBNAME;
    $Connection = new PDO($HOST, DB_UNAME, DB_UPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8';"));
    $Result     = ["ResultData" => array(), "AffectRowCount" => 0];
    # 将sql语句返回到服务器, 准备进行查询工作
    if($Query = $Connection -> prepare($QueryString))
    {
        # 如果参数列表不为空, 则将参数装载进查询对象
        if(!empty($Parameters))
            LoadParameters($Query, $Parameters);
        # 执行查询, 并将查询结果置于返回值中
        if($Query -> execute())
        {
            while($Row = $Query -> fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))
                array_push($Result["ResultData"], $Row);
            $Result["AffectRowCount"] = $Query -> rowCount();
        }
        else die($Query -> errorInfo()[2].", 请检查: $QueryString");
    }
    else die($Query -> errorInfo()[2].", 请检查: $HOST");
    return $Result;
}

/*
 * 执行一个sql语句, 返回一个数据表
 */
function GetDataTable($QueryString, array $Parameters = null)
{
    return ExecuteQuery($QueryString, $Parameters)["ResultData"];
}

/*
 * 执行一个sql语句, 返回一行数据
 * 如果查询结果具有多行, 则只返回第一行数据
 */
function GetSingleResult($QueryString, array $Parameters = null)
{
    $Result = ExecuteQuery($QueryString, $Parameters)["ResultData"];
    if(count($Result) > 0)
        return $Result[0];
    else return array();
}

/*
 * 执行一个UPDATE、INSERT或DELECT语句, 返回执行结果
 * 注意: 返回的是执行结果, 而非受影响的行数
 * 当函数成功执行, 但并未影响到任意一条数据时, 将返回False
 */
function ExecuteNonQuery($QueryString, array $Parameters = null)
{
    $Result = ExecuteQuery($QueryString, $Parameters)["AffectRowCount"];
    if($Result > 0)
        return true;
    else return false;
}





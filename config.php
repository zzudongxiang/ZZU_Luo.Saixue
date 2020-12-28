<?php require_once "/var/www/html/config.php";
/*
 * 该文件用于引导程序正确访问网站文件
 * 在部署网站时, 按照需要可修改以下网站参数
 */

# 配置网站根目录位置, 指定PATH到文件的根目录
if(!defined("PATH"))
    define("PATH", "/var/www/html/");

# 配置网站数据库地址信息
if(!defined("DB_HOST"))
    define("DB_HOST", "localhost");

# 配置网站数据库登录所需用户名
if(!defined("DB_UNAME"))
    define("DB_UNAME", "root");

# 配置网站数据库登录所需密码
if(!defined("DB_UPWD"))
    define("DB_UPWD", "raspberry");

# 配置网站数据库对应数据库名称
if(!defined("DB_DBNAME"))
    define("DB_DBNAME", "TIC_Competition");

# 配置网站的服务角色， 如果是公网服务， 则有些页面不提供接口(可选：WebSite、Raspberry、Debuger)
if(!defined("Role"))
    define("Role", "WebSite");

# 配置网站登录时, 浏览器记住登录信息的时长(单位: 秒)
if(!defined("LICENSE"))
{
    if(Role == "WebSite")
        define("LICENSE", "1800");
    else if(Role == "Raspberry")
        define("LICENSE", "18000");
    else if(Role == "Debuger")
        define("LICENSE", "604800");
    else define("LICENSE", "3600");
}

# 导入网站必备数据库访问方法
require_once PATH."/modules/sys/mysql.php";

# 导入网站必备基础访问方法
require_once PATH."/modules/sys/sys.php";

## 注意事项：

1.  需要将config.php文件置于 /var/www/html/ 目录下
    <font color="FF0000">如果该目录不存在， 应新建相关文件</font>

2. 系统需要安装apache2, sudo apt install apache2

3.  修改apache2关于php的配置文件，
    <font color="FF0000">将php.ini.bak文件复制到 /etc/php/7.2/apache2/ 目录下 替换原有php.ini文件（将该php.ini.bak更名为php.ini）</font>
    
4. 系统需要安装和配置 php-common php-mysql php-mbstring php-zip php-xml

5. 系统数据库使用Mysql, 请在 config.php 文件中进行配置

6. 系统运行前需要保证对目录下的 ./data/ 目录具有666以上权限
   #### 如果需要用phpmyadmin:
   1). 需要对 ./phpmyadmin/tmp/ 目录赋予读写权限
   2). 如果 ./phpmyadmin/ 目录下无 ./tmp/ 目录, 请创建
   3). 至少保证程序对 ./tmp 目录可读可写(666以上权限)
   4). 赋予文件路径权限时必须添加-R对其子目录也赋予权限

## 树梅派配置流程
1. 烧系统
    百度一堆教程, 不再赘述, 需要注意的是, 烧录好镜像后需要在 /boot 下新建空白文件 ssh

2. 更新源:
    bash>> sudo apt update
    
3. 更新自带软件(可选可不选):
    bash>> sudo apt upgrade
    
4. 安装create_ap
    basg>> sudo apt install util-linux procps hostapd iproute2 iw haveged dnsmasq
    
    bash>> sudo git clone https://github.com/oblique/create_ap.git
    
    bash>> cd create_ap
    
    bash>> sudo make install
    
    bash>> sudo systemctl enable create_ap
    
    bash>> sudo systemctl start create_ap
    
5. 安装php
    bash>> sudo apt install php php-common php-mysql php-zip php-xml php-mbstring
    
6. 安装apache2
    bash>> sudo apt install apache2
    
7. 安装mysql(mariadb)
    bash>> sudo apt install mariadb-server
    
8. 修改mysql密码
    bash>> sudo mysql -u root
    
    mysql>> use mysql;
    
    mysql>> select host, user from user;
    
    mysql>> update user set host = '%', authentication_string = password('raspberry'), plugin='mysql_native_password' where user like 'root';
    
    mysql>> flush privileges;
    
    mysql>> exit;
    
    登录测试:
    
    bash>> mysql -u root -p
    
    password: raspberry
    
9. 开启mysql远程登录
    bash>> sudo nano /ect/mysql/mariadb.conf.d/50-sever.cnf
    
    nano>> "bind-address = 127.0.0.1" --> "#bind-address = 127.0.0.1"
    
10. 重启系统
    bash>> sudo reboot
    
11. 测试系统环境
#### apache2:  浏览器地址栏输入Raspberry IP

#### MYSQL: 使用Naivcat软件进行连接测试

#### php:
> bash>>cd /var/www/html
> bash>>sudo nano index.php
> nano>> <？php echo phpinfo();"
> 浏览器地址栏输入 Raspberry IP/index.php

#### 测试结束：
> bash>>sudo rm -rf /var/www/html/*

12. 导入导出数据
#### A. 导出数据
        IP:     47.95.12.167
        
        UName:  root
        
        UPwd:   zzudongxiang@163.com
        
        打开 Navicat 创建MySQL连接
        
        数据表双击打开, 右键选择"储存SQL文件" -> "结构和数据"
        
        选择文件位置, 保存*.储存SQL文件


#### B. 导入数据
        IP:     Raspberry IP地址
        
        UName:  root
        
        UPwd:   raspberry
        
        打开 Navicat 创建MariaDB连接
        
        如果目标数据库不存在, 右键数据库链接新建数据库
        
        双击打开数据库, 右键选择"运行SQL文件"
        
        选择*.sql文件位置, 导入数据

13. 导入网站数据
    bash>> sudo chmod -R 777 /var/www/html
    
    打开 FileZilla 软件
    
    IP:     Raspberry IP
    
    UName:  pi
    
    UPwd:   raspberry
    
    Port:   22
    
    创建与Raspberry的链接
    
    在 "远程站点" 选择路径 "/var/www/html"
    
    将本地文件拖入 远程站点的 "/var/www/html/" 下
    
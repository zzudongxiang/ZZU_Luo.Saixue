-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2020-12-29 07:44:19
-- 服务器版本： 5.7.29-0ubuntu0.18.04.1
-- PHP 版本： 7.2.24-0ubuntu0.18.04.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `TIC_Competition`
--

-- --------------------------------------------------------

--
-- 表的结构 `Comp_Completion`
--

CREATE TABLE `Comp_Completion` (
  `ID` int(10) UNSIGNED NOT NULL,
  `UserID` int(10) UNSIGNED NOT NULL COMMENT '学生对应ID',
  `TopicID` int(10) UNSIGNED NOT NULL COMMENT '对应问题ID',
  `Status` float NOT NULL DEFAULT '0' COMMENT '是否已完成'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户勾选的完成情况';

-- --------------------------------------------------------

--
-- 表的结构 `Comp_RegistrationInfo`
--

CREATE TABLE `Comp_RegistrationInfo` (
  `ID` int(10) UNSIGNED NOT NULL,
  `UserID` int(10) UNSIGNED NOT NULL COMMENT '关联的报名学生ID',
  `CompetitionID` int(10) UNSIGNED NOT NULL COMMENT '关联的比赛ID',
  `UpdateTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '报名的时间',
  `SubmitTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '最后提交的时间',
  `Status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '报名的状态',
  `UploadName` varchar(255) NOT NULL DEFAULT 'tmp' COMMENT '提交文件的文件名',
  `Remarks` text NOT NULL COMMENT '对作品提交的注释内容',
  `Shown` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否参加展示赛的选项',
  `SubmitCount` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '提交的次数',
  `Score` float NOT NULL DEFAULT '-1' COMMENT '选手的得分',
  `Sort` int(10) NOT NULL DEFAULT '0' COMMENT '选手抽签排序'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `Comp_Topic`
--

CREATE TABLE `Comp_Topic` (
  `ID` int(10) UNSIGNED NOT NULL,
  `CompetitionID` int(10) UNSIGNED NOT NULL COMMENT '关联的赛题标号',
  `Text` text NOT NULL COMMENT '赛题的名称',
  `Score` float NOT NULL COMMENT '该题目的得分'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `Judge_ScoreDetail`
--

CREATE TABLE `Judge_ScoreDetail` (
  `ID` int(10) UNSIGNED NOT NULL,
  `JudgeID` int(10) UNSIGNED NOT NULL COMMENT '关联的评委ID',
  `UserID` int(10) UNSIGNED NOT NULL COMMENT '关联的学生ID',
  `CompetitionID` int(10) UNSIGNED NOT NULL COMMENT '比赛的ID',
  `Score` float NOT NULL COMMENT '评委打分细节'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `TIC_Article`
--

CREATE TABLE `TIC_Article` (
  `ID` int(10) UNSIGNED NOT NULL,
  `Type` varchar(255) NOT NULL DEFAULT '新闻通知' COMMENT '显示当前消息的类型是新闻还是通知',
  `Title` varchar(255) NOT NULL DEFAULT '新闻通知的标题' COMMENT '当前新闻通知的标题内容',
  `Text` longtext NOT NULL COMMENT '当前新闻通知的内容',
  `UpdateTime` date NOT NULL COMMENT '当前新闻发布的时间/ 最后修改的时间',
  `Author` varchar(255) NOT NULL DEFAULT '管理员' COMMENT '当前新闻发布的作者',
  `TopMost` tinyint(1) NOT NULL DEFAULT '0' COMMENT '指示当前新闻是否置顶显示',
  `ViewCount` int(11) NOT NULL DEFAULT '1' COMMENT '当前新闻的阅读数'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='创新创业大赛发布的文章/新闻';

-- --------------------------------------------------------

--
-- 表的结构 `TIC_Competition`
--

CREATE TABLE `TIC_Competition` (
  `ID` int(10) UNSIGNED NOT NULL,
  `Title` varchar(255) NOT NULL DEFAULT '竞赛的标题' COMMENT '竞赛的标题',
  `Text` longtext NOT NULL COMMENT '竞赛的内容',
  `UploadTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '提交作品的时间',
  `EndTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '提交截至的时间',
  `Enabled` tinyint(1) NOT NULL DEFAULT '0' COMMENT '当前比赛是否显示给用户',
  `Filter` varchar(255) NOT NULL DEFAULT 'zip|rar|7z',
  `FileSize` int(10) NOT NULL DEFAULT '102400',
  `MustShown` tinyint(1) NOT NULL DEFAULT '0' COMMENT '指示该比赛是否强制要求参加'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `TIC_Honor`
--

CREATE TABLE `TIC_Honor` (
  `ID` int(10) UNSIGNED NOT NULL,
  `Name` varchar(255) NOT NULL DEFAULT 'Name' COMMENT '荣誉榜的学生姓名',
  `Honor` varchar(255) NOT NULL DEFAULT '参赛选手' COMMENT '荣誉榜的学生荣誉类型',
  `Remark` text NOT NULL COMMENT '学生自己添加的简介',
  `TopMost` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否置顶当前学生',
  `Enabled` tinyint(1) NOT NULL DEFAULT '1' COMMENT '当前学生是否显示到页面上',
  `class` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT 'MAT',
  `sort` int(10) NOT NULL DEFAULT '999'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `TIC_Hyperlink`
--

CREATE TABLE `TIC_Hyperlink` (
  `ID` int(10) UNSIGNED NOT NULL,
  `Type` varchar(255) NOT NULL DEFAULT 'Carousel' COMMENT '指示当前连接显示的方式, 可选Carousel或者FooterLink',
  `LinkName` varchar(255) NOT NULL COMMENT '指示当前连接的名称',
  `LinkURL` varchar(255) NOT NULL DEFAULT '/' COMMENT '指示当前超链接对应的链接地址',
  `ImageName` varchar(255) NOT NULL DEFAULT '/images/error.jpg' COMMENT '图片位置对应的超链接',
  `Enabled` tinyint(1) NOT NULL DEFAULT '1' COMMENT '指示当前超链接是否显示在首页上',
  `TopMost` tinyint(1) NOT NULL DEFAULT '0' COMMENT '指示是否将该链接置顶显示',
  `UpdateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '指示最后更新的时间, 最终也是以此作为排序依据的'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='网站的静态链接和动态连接都将储存到该表中';

--
-- 转存表中的数据 `TIC_Hyperlink`
--

INSERT INTO `TIC_Hyperlink` (`ID`, `Type`, `LinkName`, `LinkURL`, `ImageName`, `Enabled`, `TopMost`, `UpdateTime`) VALUES
(21, '新闻轮播图', '我院召开第五届科技创新大赛启动动员会', '/pages/public/article.php?id=34', '5a64775b-bf8c-bbfe-3ab5-9901808489d6', 1, 1, '2019-10-27 11:30:49'),
(22, '新闻轮播图', '我院学生荣获第十四届全国大学生智能汽车竞赛华北区二等奖', '/pages/public/article.php?id=36', '958b09a7-a003-2f35-7a08-3a184b293609', 1, 0, '2019-10-27 11:31:24'),
(23, '新闻轮播图', '我院学子荣获第12届中国大学生计算机设计大赛最高奖', '/pages/public/article.php?id=37', 'b16fcafc-f544-4455-5743-8cb8bdc66c29', 1, 0, '2019-10-27 11:32:11'),
(24, '脚注超链接', '中国大学生数学建模竞赛', 'http://www.mcm.edu.cn', 'deaa0021-36f5-61d1-0b80-0a89b15c06fe', 1, 0, '2019-10-27 11:34:40'),
(25, '脚注超链接', '郑州大学', 'http://www15.zzu.edu.cn/', '29e69b3f-d657-ba1b-ee4b-e0e39a26205c', 0, 0, '2019-10-27 11:46:03'),
(26, '脚注超链接', '全国大学恩\"智浦杯\"智能汽车竞赛', 'https://smartcar.cdstm.cn/index', 'B6E4089B-7D8F-3610-3AE1-4DFE96D0E6D3', 1, 0, '2019-10-27 11:45:52'),
(27, '脚注超链接', '全国大学生电子设计竞赛', 'https://www.nuedc-training.com.cn/', 'E6C8447A-7C28-ACBE-CD40-B6AC6BB97D36', 1, 0, '2019-10-27 11:45:47'),
(28, '脚注超链接', '美国大学生数学建模竞赛', 'https://www.comap.com/', '751a35c6-7b68-cca3-5811-e1afbc857c8a', 1, 0, '2019-10-27 11:45:40'),
(29, '新闻轮播图', '我院第五届科技创新大赛圆满结束', 'http://www5.zzu.edu.cn/wuli/info/1011/2666.htm', 'd7c6bed5-b2aa-2516-cfc7-1c8e7a9feb7c', 1, 1, '2020-01-08 14:02:00'),
(30, '新闻轮播图', '第六届赛学创新活动-Matlab与建模第1轮作业答辩赛圆满完成', '', 'fbb65de9-ac15-96d9-2300-53446b6234da', 1, 0, '2020-11-16 04:21:20'),
(31, '新闻轮播图', '李苏贵书记、潘志峰教授给宿舍男女团体冠军授旗', 'http://zzu-saixue.com/pages/public/article.php?id=70', 'daa2a0c2-5ddf-1c71-60da-45c6167e6c1a', 1, 0, '2020-12-04 13:02:04');

-- --------------------------------------------------------

--
-- 表的结构 `TIC_Judge`
--

CREATE TABLE `TIC_Judge` (
  `ID` int(10) UNSIGNED NOT NULL,
  `GUID` varchar(40) NOT NULL DEFAULT '0000-0000-0000-00000000' COMMENT '评委的GUID',
  `JudgeName` varchar(255) NOT NULL DEFAULT '嘉宾评委' COMMENT '评委的名字',
  `Weight` float NOT NULL DEFAULT '1' COMMENT '评委的权重',
  `Enabled` tinyint(1) NOT NULL DEFAULT '1' COMMENT '当前评委是否可登录',
  `OnlineStatus` tinyint(1) NOT NULL DEFAULT '0' COMMENT '当前评委的在线状态',
  `JudgeRole` tinyint(1) NOT NULL DEFAULT '0' COMMENT '指示是否为专业评委'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `TIC_PPT`
--

CREATE TABLE `TIC_PPT` (
  `ID` int(10) UNSIGNED NOT NULL,
  `Title` varchar(255) NOT NULL DEFAULT '幻灯片名称' COMMENT '幻灯片页面的标题, 仅用作显示',
  `Text` text COMMENT '幻灯片的内容播报,不需要播报则为空'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `TIC_PPT`
--

INSERT INTO `TIC_PPT` (`ID`, `Title`, `Text`) VALUES
(2, '001首页', '大家好, 我是小赛。很高兴主持今天的第六届,, Matlab与建模   第3轮作业答辩赛。决赛。'),
(6, '002欢迎各位嘉宾莅临', '欢迎各位嘉宾评委莅临指导！请允许我隆重介绍他们.'),
(9, '003首席评委   田勇志', '首席评委：田勇志老师。第六届,, 赛学创新活动,组委会副主任，博士，毕业于中国科学院安徽光机所，光电信息科学研究所教师。《matlab与数学建模》主讲老师。欢迎您'),
(10, '首席评委  马 冰', '嘉宾评委：马冰老师。第六届赛学创新活动,组委会副主任，从事电子信息科学与技术学科，主讲《matlab与数学建模》 、《微机原理及实验》、《误差理论与数据处理》、《网站建设与网页制作》等。欢迎您'),
(15, '评委   张训宇', '评委：张训宇学长。物理学院（微电子学院）第五届科技创新大赛,技术委员，2018级电子科学与技术专业学生。第四届科技创新大赛matlab与数学建模答辩赛10强。欢迎您'),
(16, '06技术支持  杨奥申', '技术支持：杨奥申。物理学院（微电子学院）第六届 赛学创新活动秘书长，2019级电子科学与技术专业，副年级长。第五届赛学创新活动Matlab与建模作业答辩赛30强。欢迎您'),
(19, '010电脑随机抽签排序', '现在进行电脑随机抽签排序，请课程负责人罗荣辉老师主持抽签仪式。'),
(21, '特别提示', '以上为选手现场得分，经由作业重复率等事项审核后再行公布最终成绩'),
(23, '004嘉宾评委  闫磊磊', '嘉宾评委：闫磊磊老师。第六届 ,,赛学创新活动,组委会副主任，博士，毕业于中科院武汉物数所，光电信息科学研究所教师，主讲，MATLAB与建模，等。郑州大学青年拔尖人才，荣获中科院院长特别奖，目前发表S/C/I论文20余篇，其中包括中科院一区4篇。欢迎您'),
(24, '011结束 ', '谢谢各位嘉宾、评委、同学，下次再见。'),
(25, '单崇新005', '嘉宾：单崇新老师。郑州大学物理学院（微电子学院）院长、教授、博士生导师，，曾获得长江学者特聘教授、国家杰出青年基金、中国青年科技奖、中组部万人计划、“青年拔尖人才”、国家有突出贡献中青年专家、中科院“百人计划”以及中原学者等多项荣誉称号。第六届，，赛学创新大赛组委会主任，，.欢迎您。'),
(27, '潘志峰', '嘉宾：潘志峰老师。郑州大学教务处副处长、教授、硕士生导师。物理学院（微电子学院）大学物理教学中心主任，高等学校大学物理课程教学指导委员会医药类工作委员会副主任委员。获得省“五一劳动奖章”、“教学标兵”、“教学名师”、“优秀青年知识分子”、“优秀青年科技人才”等荣誉称号。欢迎您。'),
(28, '王杰芳007', '嘉宾:王杰芳老师..。物理学院（微电子学院）副院长，教授。中国物理学会（物理教学委员会委员，教育部大学物理课程教学指导委员会中南地区工作委员会秘书长，曾获河南省职业道德建设先进个人，河南省教学标兵，郑州大学首届“十大巾帼标兵”，郑州大学学生“我最喜爱的老师”等荣誉称号。，，欢迎您'),
(29, '其他嘉宾010', '嘉  宾 。。。。王千茹老师 。欢迎您。。。。。田勇志老师 。 欢迎您。。。。。张艺博老师。欢迎您。。。。。马 冰老师。 欢迎您。。。。。闫磊磊老师 。欢迎您。。。。。贺菁老师。欢迎您。。。。。'),
(30, '二等奖012-2', '获得二等奖的选手，他们是，，范岩，，历婉琪，，范森等一九级同学，，刘天赐，，马隆景瑞，，刘涛涛等2零级同学。。 有请以上同学领奖。有请。王千茹老师，罗荣辉老师，王晓川老师颁奖合影'),
(31, '一等奖013-2', '获得一等奖的选手，他们是。一九级陈信生,,别江山,,2零级陈荣瑞,,陈翔,,张世川,,唐绍博,,李畅,,王盟,,廉鸿甫。有请以上9名同学上台领奖。。。有请王永刚副书记，，王杰芳副院长颁奖合影'),
(32, '特等奖014-2', ' 获得特等奖的选手，他们是.一九级严肃,,2零级秦昊,,董宇,,武一航 。 请以上4名同学上台领奖。有请，，单崇新院长颁奖合影。'),
(34, '嘉宾2讲话015-2', '有请物理学院（微电子学院）院长、第六届赛学创新活动组委会主任单崇新教授上台作总结讲话'),
(35, '发布荣誉榜单016', '下面发布第六届赛学创新活动 官网荣誉榜单'),
(36, '011宣布结束', '有请宣布第六届 赛学创新活动结束'),
(40, '008技术支持 马隆景瑞', '技术支持：马隆景瑞同学。物理学院（微电子学院）第六届,, 赛学创新活动组委会技术委员，2020级物理学类专业学生。Matlab与建模第1轮作业答辩赛亚军。欢迎您'),
(41, '005学生评委 严肃', '学生评委：严肃学长。物理学院（微电子学院） 第六届,, 赛学创新活动技术部长，2019级电子科学与技术专业学生。获2020年美国大学生数学建模竞赛二等奖，第五届,,赛学创新活动Matlab与建模答辩赛20强。欢迎您'),
(42, '007学生评委 陈信生', '学生评委：陈信生学长。物理学院（微电子学院）第六届,, 赛学创新活动技术委员，2019级电子科学与技术专业学生。获2020年美国大学生数学建模竞赛三等奖，第五届,,赛学创新活动Matlab与建模答辩赛10强。欢迎您'),
(43, '006学生评委 范岩', '学生评委：范岩学长。物理学院（微电子学院） 第六届,, 赛学创新活动技术委员，2019级电子科学与技术专业学生。获2020年美国大学生数学建模竞赛三等奖，第五届,,赛学创新活动Matlab与建模答辩赛10强。欢迎您'),
(44, '学生评委 徐媛媛', '学生评委：徐媛媛。物理学院（微电子学院）第六届 赛学创新活动技术委员，2019级电子科学与技术专业学生。第五届赛学创新活动Matlab与建模答辩赛冠军。欢迎您'),
(48, '嘉宾 王永刚006', ' 嘉宾 。 王永刚老师。。   物理学院（微电子学院）党委副书记。曾获第七届全国“挑战杯”铜奖指导教师，第八届河南省“挑战杯”特等奖优秀指导教师，曾获得，河南省高等学校优秀共产党员等荣誉称号。，，欢迎您'),
(49, '嘉宾  张斌', '嘉宾  。张 斌老师 。。   物理学院（微电子学院）电子科学与测控系副主任、副教授，硕士生导师。'),
(51, '其他嘉宾009-1', '嘉  宾 。。。王千茹老师.。。。。.。。。秦二强老师。欢迎您。。。。。。。 田勇志老师。欢迎您。。。。。。。 马 冰老师。欢迎您。。。。。。。。闫磊磊老师 。欢迎您。。。。。。。贺菁老师 。欢迎您。。。。。。。张艺博老师。欢迎您。。。。。。。'),
(55, '流动红旗010', '下面，，颁授大一决赛（男、女优胜团体锦旗'),
(56, '团体颁奖011', '男团。冠军 。。。。  男菊1东1二2宿舍。。。。 女团。冠军。。。。松16 6一9宿舍，，.。男团亚军，，，，，男菊1东4零1。，男团季军  。。。。男菊1东1二0宿舍。（（下面，有请田勇志老师 。。马冰老师 。。闫磊磊老师 。。贺菁老师。。 张艺博老师。。，上台颁奖。'),
(62, '再见017', '第六届赛学创新活动,到此圆满结束,谢谢大家.各位领导,,嘉宾,,同学们..再见'),
(67, '智能车寻迹首页', '大家好, 我是小赛。很高兴主持今天的第六届赛学 创新大赛《创新创业教育学习与实践》。。。第一轮寻迹智能车设计答辩赛'),
(68, '02欢迎', '欢迎各位嘉宾评委莅临指导！。请允许我隆重介绍他们'),
(72, '08电脑抽签', '下面，由电脑随机抽签来决定出场顺序。。比赛现在开始'),
(73, '智能车  再见', '第1轮循迹智能车作业答辩赛圆满结束。同学们，让我们下次再见。'),
(75, '学生评委   厉婉琪', '物理学院（微电子学院）第六届赛学创新活动技术委员，2019级电子科学与技术专业学生。获2020年美国大学生数学建模竞赛三等奖，第五届赛学创新活动Matlab与建模答辩赛20强。欢迎您'),
(76, '07技术支持   董 宇', '技术支持 ：董 宇。物理学院（微电子学院）第六届赛学创新活动组委会技术委员，2020级物理学类专业学生。Matlab与建模第1轮作业答辩赛季军。欢迎您'),
(78, '首页2', '大家好, 我是小赛。很高兴主持今天的第六届Matlab与建模   第2轮作业答辩赛。甲级扩大赛'),
(79, '学生评委  秦 昊', '学生评委 ：秦 昊。物理学院（微电子学院）第六届赛学创新活动组委会技术委员，2020级物理学类专业学生。Matlab与建模第1，2，3轮作业答辩赛冠军，欢迎您。'),
(80, '学生评委 马隆景瑞', '学生评委：马隆景瑞。物理学院（微电子学院）第六届赛学创新活动组委会技术委员，2020级物理学类专业学生。Matlab与建模第1轮作业答辩赛亚军。欢迎您。'),
(81, '学生评委  董 宇', '学生评委 ：董 宇。物理学院（微电子学院）第六届赛学创新活动组委会技术委员，2020级物理学类专业学生。Matlab与建模第1轮作业答辩赛季军，欢迎您。'),
(82, '首页', '大家好, 我是小赛。很高兴主持今天的第六届Matlab与建模   第2轮作业答辩赛表彰会'),
(96, '009技术支持 杨典衡', '技术支持：杨典衡同学,物理学院（微电子学院） 第六届,, 赛学创新活动组委会 委员，2020级物理学类专业学生。Matlab与建模第2轮作业答辩赛30强。欢迎您！'),
(99, '01单片机与电子设计首页', '大家好, 我是小赛。很高兴主持今天的第六届单片机与电子设计第1、2轮作业答辩赛。'),
(101, '03首席评委  路书祥', '首席评委  路书祥老师。 第六届赛学创新活动组委会副主任，电子科学与测控系高级工程师，北京理工大学物理电子学专业博士。，在MEMS传感、光纤水听器和FPGA信号处理等方面有多年研究开发经验。欢迎您。'),
(102, '04评委  杨浩', '评委：杨浩学长。物理学院（微电子学院）物理电子学专业研究生，参加全国大学生机器人竞赛取得优良成绩。毕业设计《基于单片机的恒温箱控制系统设计》受到好评。欢迎您。'),
(103, '05评委  邵永威', '评委： 邵永威学长。物理学院（微电子学院）电子信息专业专业研究生。获郑州大学2020届优秀本科毕业生，在校园航模大赛-飞行组以及智能循迹小车等大赛中取得优良成绩。欢迎您。'),
(104, '09再见', '单片机与电子设计 第1、2轮作业答辩赛圆满结束，再见。'),
(105, '表彰会002', '以 赛 导 学,,, 以 赛 促 教,,, 以 赛 督 创,,, 以 赛 正 风,,'),
(106, '表彰会003', '授人以“鱼” ,,不如授人以“渔”,,, 更不如启人以“欲”.'),
(107, '二等奖012-1', '下面宣布二等奖获得者名单并颁发证书'),
(108, '一等奖013-1', '下面宣布一等奖获得者名单并颁发证书'),
(109, '特等奖014-1', '最后隆重宣布特等奖获得者名单并颁发证书'),
(110, '美赛参赛', NULL),
(111, '嘉宾王晓川008', '嘉宾：王晓川老师。郑州大学教务处教学研究管理科科长，赛学育人模式（早期参与者。曾主讲《数据结构》等课程，担任《Matlab与建模》课程教学督导，，欢迎您'),
(112, '罗老师009', '嘉宾：罗荣辉老师。物理学院（微电子学院）第六届科技创新大赛组委会执行主任，电子科学与测控系副主任，副教授，硕士生导师。。欢迎您'),
(113, '首页表彰会001', '大家好, 我是小赛。很高兴主持今天的第六届，，赛学创新活动总结表彰会。'),
(114, '欢迎嘉宾004', '欢迎各位嘉宾莅临出席！请允许我隆重介绍他们'),
(115, '祁秀香 005-1', '嘉宾：祁秀香老师，，郑州大学教务处副处长。欢迎您'),
(116, '圆满结束017-1', '下面有请，单崇新院长，宣布第六届，，赛学创新活动，，圆满结束');

-- --------------------------------------------------------

--
-- 表的结构 `TIC_UserInfo`
--

CREATE TABLE `TIC_UserInfo` (
  `ID` int(10) UNSIGNED NOT NULL,
  `GUID` varchar(40) NOT NULL DEFAULT '0000000000' COMMENT '用于在浏览器中储存对应的Cookie, 该Cookie对应指定的用户信息',
  `UserName` varchar(255) NOT NULL DEFAULT '00000000000' COMMENT '指示用户的学号, 一般为11位纯数字',
  `PassWord` varchar(255) NOT NULL DEFAULT '000000' COMMENT '指示当前用户的登录密码, 初始密码为6个0, 用户登录之后可以修改自己的密码',
  `NikeName` varchar(255) NOT NULL DEFAULT '学生姓名' COMMENT '指示当前学生的真实姓名',
  `OtherInfo` varchar(255) NOT NULL DEFAULT '年级/专业/班级' COMMENT '指示当前学生的年级/专业/班级以及住宿信息',
  `Room` varchar(200) DEFAULT '暂无',
  `AdminRole` int(10) NOT NULL DEFAULT '0' COMMENT '指示当用户的角色是否是管理员'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `TIC_WebSite`
--

CREATE TABLE `TIC_WebSite` (
  `ID` int(10) UNSIGNED NOT NULL,
  `ConfigKey` varchar(255) NOT NULL DEFAULT 'ConfigKey' COMMENT '指示当前配置的键值',
  `ConfigValue` longtext NOT NULL COMMENT '指示配置文件的键对应的值, 便于直接在页面中显示'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `TIC_WebSite`
--

INSERT INTO `TIC_WebSite` (`ID`, `ConfigKey`, `ConfigValue`) VALUES
(1, 'WebSite_Title', '第六届赛学创新活动'),
(2, 'WebSite_Footer', '                                                        <h3 style=\"text-align: center;\"><span style=\"font-size: small;\">地址: 中国河南省郑州市科学大道100号　　<span style=\"text-align: left;\">郑州大学</span>物理<span style=\"text-align: left;\">学院</span>（微电子<span style=\"text-align: left;\">学院</span>）大学生创新创业教育实践基地 All Right Reserved.</span></h3><p style=\"text-align: center;\"><span style=\"text-align: left; font-size: small;\">兼容: FireFox 18+、Chrome 55+、Microsoft Edge 浏览器</span></p><p style=\"text-align: center;\"><span style=\"text-align: left; font-size: small;\"><br></span></p><p style=\"text-align: center;\"><a href=\"http://www.beian.miit.gov.cn/\" target=\"_blank\">豫ICP备19040596号</a><br></p><p style=\"text-align: center;\"><br></p><p style=\"text-align: center;\"><br></p>                            '),
(3, 'Login_Notice', '                                                                                        <p><span style=\"font-family: 宋体; font-size: medium;\">初始密码为<span style=\"color: rgb(255, 0, 0); font-weight: bold;\">个人学号</span>, 登录后请修改密码</span></p><p><span style=\"font-family: 宋体; font-size: medium;\">※:个人账号无法登录请联系管理员</span></p><p><br></p>                                            '),
(4, 'JudgeCompetition', '52'),
(5, 'SpeakerID', '3562'),
(6, 'RT_Timer', 'off'),
(7, 'RT_Web', 'on'),
(8, 'RT_Web_URL', 'http://47.97.231.76/announce/ppt.php'),
(9, 'RT_Speaker', 'on'),
(10, 'RT_Speaker_Msg', ''),
(11, 'RT_Music', 'off'),
(12, 'RT_Music_Voice', 'bgm_new'),
(13, 'RT_Volume', '40'),
(14, 'RT_PPT', '110');

--
-- 转储表的索引
--

--
-- 表的索引 `Comp_Completion`
--
ALTER TABLE `Comp_Completion`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `PK_UT` (`UserID`,`TopicID`),
  ADD KEY `FK_T_CPT` (`TopicID`);

--
-- 表的索引 `Comp_RegistrationInfo`
--
ALTER TABLE `Comp_RegistrationInfo`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `PK_UC` (`UserID`,`CompetitionID`),
  ADD KEY `FK_Comp` (`CompetitionID`);

--
-- 表的索引 `Comp_Topic`
--
ALTER TABLE `Comp_Topic`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `FK_Topic_Com` (`CompetitionID`);

--
-- 表的索引 `Judge_ScoreDetail`
--
ALTER TABLE `Judge_ScoreDetail`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `UderID_JudgeID` (`UserID`,`JudgeID`,`CompetitionID`) USING BTREE,
  ADD KEY `FK_Judge_JudgeID` (`JudgeID`),
  ADD KEY `FK_Judge_Comp` (`CompetitionID`);

--
-- 表的索引 `TIC_Article`
--
ALTER TABLE `TIC_Article`
  ADD PRIMARY KEY (`ID`);

--
-- 表的索引 `TIC_Competition`
--
ALTER TABLE `TIC_Competition`
  ADD PRIMARY KEY (`ID`);

--
-- 表的索引 `TIC_Honor`
--
ALTER TABLE `TIC_Honor`
  ADD PRIMARY KEY (`ID`);

--
-- 表的索引 `TIC_Hyperlink`
--
ALTER TABLE `TIC_Hyperlink`
  ADD PRIMARY KEY (`ID`);

--
-- 表的索引 `TIC_Judge`
--
ALTER TABLE `TIC_Judge`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `PK_JudgeName` (`JudgeName`),
  ADD KEY `Judge_GUID` (`GUID`);

--
-- 表的索引 `TIC_PPT`
--
ALTER TABLE `TIC_PPT`
  ADD PRIMARY KEY (`ID`);

--
-- 表的索引 `TIC_UserInfo`
--
ALTER TABLE `TIC_UserInfo`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `GUID` (`GUID`),
  ADD UNIQUE KEY `UserName` (`UserName`);

--
-- 表的索引 `TIC_WebSite`
--
ALTER TABLE `TIC_WebSite`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `ConfigKey` (`ConfigKey`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `Comp_Completion`
--
ALTER TABLE `Comp_Completion`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `Comp_RegistrationInfo`
--
ALTER TABLE `Comp_RegistrationInfo`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `Comp_Topic`
--
ALTER TABLE `Comp_Topic`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `Judge_ScoreDetail`
--
ALTER TABLE `Judge_ScoreDetail`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `TIC_Article`
--
ALTER TABLE `TIC_Article`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `TIC_Competition`
--
ALTER TABLE `TIC_Competition`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `TIC_Honor`
--
ALTER TABLE `TIC_Honor`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `TIC_Hyperlink`
--
ALTER TABLE `TIC_Hyperlink`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- 使用表AUTO_INCREMENT `TIC_Judge`
--
ALTER TABLE `TIC_Judge`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `TIC_PPT`
--
ALTER TABLE `TIC_PPT`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- 使用表AUTO_INCREMENT `TIC_UserInfo`
--
ALTER TABLE `TIC_UserInfo`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `TIC_WebSite`
--
ALTER TABLE `TIC_WebSite`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- 限制导出的表
--

--
-- 限制表 `Comp_Completion`
--
ALTER TABLE `Comp_Completion`
  ADD CONSTRAINT `FK_T_CPT` FOREIGN KEY (`TopicID`) REFERENCES `Comp_Topic` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_U_CPT` FOREIGN KEY (`UserID`) REFERENCES `TIC_UserInfo` (`ID`) ON DELETE CASCADE;

--
-- 限制表 `Comp_RegistrationInfo`
--
ALTER TABLE `Comp_RegistrationInfo`
  ADD CONSTRAINT `FK_Comp` FOREIGN KEY (`CompetitionID`) REFERENCES `TIC_Competition` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_User` FOREIGN KEY (`UserID`) REFERENCES `TIC_UserInfo` (`ID`) ON DELETE CASCADE;

--
-- 限制表 `Comp_Topic`
--
ALTER TABLE `Comp_Topic`
  ADD CONSTRAINT `FK_Topic_Com` FOREIGN KEY (`CompetitionID`) REFERENCES `TIC_Competition` (`ID`) ON DELETE CASCADE;

--
-- 限制表 `Judge_ScoreDetail`
--
ALTER TABLE `Judge_ScoreDetail`
  ADD CONSTRAINT `FK_Judge_Comp` FOREIGN KEY (`CompetitionID`) REFERENCES `TIC_Competition` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_Judge_JudgeID` FOREIGN KEY (`JudgeID`) REFERENCES `TIC_Judge` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_Judge_UserID` FOREIGN KEY (`UserID`) REFERENCES `TIC_UserInfo` (`ID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

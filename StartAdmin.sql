-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: 2020-04-03 03:38:46
-- 服务器版本： 5.7.28
-- PHP Version: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `startadmin`
--

-- --------------------------------------------------------

--
-- 表的结构 `sa_access`
--

CREATE TABLE `sa_access` (
  `access_id` int(9) NOT NULL,
  `access_user` int(9) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `access_token` varchar(255) NOT NULL DEFAULT '' COMMENT 'AccessToken',
  `access_plat` varchar(255) NOT NULL DEFAULT 'all' COMMENT '登录平台',
  `access_ip` varchar(255) NOT NULL DEFAULT '' COMMENT 'IP',
  `access_status` int(9) NOT NULL DEFAULT '0' COMMENT '状态',
  `access_createtime` int(9) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `access_updatetime` int(9) NOT NULL DEFAULT '0' COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='授权信息表';

-- --------------------------------------------------------

--
-- 表的结构 `sa_app`
--

CREATE TABLE `sa_app` (
  `app_id` int(9) NOT NULL COMMENT 'APPID',
  `app_secret` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '密钥',
  `app_name` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '应用名称',
  `app_status` int(9) NOT NULL DEFAULT '0' COMMENT '1被禁用',
  `app_createtime` int(9) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `app_updatetime` int(9) NOT NULL DEFAULT '0' COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='应用表';

--
-- 转存表中的数据 `sa_app`
--

INSERT INTO `sa_app` (`app_id`, `app_secret`, `app_name`, `app_status`, `app_createtime`, `app_updatetime`) VALUES
(1, '123456', 'Test', 0, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `sa_attach`
--

CREATE TABLE `sa_attach` (
  `attach_id` int(9) NOT NULL,
  `attach_path` varchar(255) NOT NULL DEFAULT '' COMMENT '路径',
  `attach_type` varchar(255) NOT NULL DEFAULT '' COMMENT '类型',
  `attach_size` int(11) NOT NULL DEFAULT '0' COMMENT '大小',
  `attach_user` int(11) NOT NULL DEFAULT '0' COMMENT '用户',
  `attach_status` int(9) NOT NULL DEFAULT '0' COMMENT '状态',
  `attach_createtime` int(9) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `attach_updatetime` int(9) NOT NULL DEFAULT '0' COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='附件表';

-- --------------------------------------------------------

--
-- 表的结构 `sa_auth`
--

CREATE TABLE `sa_auth` (
  `auth_id` bigint(20) NOT NULL COMMENT '权限ID',
  `auth_group` int(11) NOT NULL DEFAULT '0' COMMENT '权限管理组',
  `auth_node` int(11) NOT NULL DEFAULT '0' COMMENT '功能ID',
  `auth_status` int(11) NOT NULL DEFAULT '0' COMMENT '1被禁用',
  `auth_createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `auth_updatetime` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='权限表';

-- --------------------------------------------------------

--
-- 表的结构 `sa_code`
--

CREATE TABLE `sa_code` (
  `code_id` int(9) NOT NULL,
  `code_user` int(9) NOT NULL DEFAULT '0' COMMENT 'user',
  `code_code` varchar(255) NOT NULL DEFAULT '' COMMENT 'code',
  `code_status` int(9) NOT NULL DEFAULT '0' COMMENT '状态',
  `code_createtime` int(9) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `code_updatetime` int(9) NOT NULL DEFAULT '0' COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='临时凭证表';

-- --------------------------------------------------------

--
-- 表的结构 `sa_conf`
--

CREATE TABLE `sa_conf` (
  `conf_id` int(11) NOT NULL,
  `conf_key` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '参数名',
  `conf_value` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '参数值',
  `conf_readonly` int(11) NOT NULL DEFAULT '0' COMMENT '是否只读',
  `conf_desc` varchar(255) CHARACTER SET utf8 DEFAULT '' COMMENT '参数描述',
  `conf_int` int(11) NOT NULL DEFAULT '0' COMMENT '参数到期',
  `conf_system` int(11) NOT NULL DEFAULT '0' COMMENT '系统级配置',
  `conf_status` int(11) NOT NULL DEFAULT '0',
  `conf_createtime` int(11) NOT NULL DEFAULT '0',
  `conf_updatetime` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='配置表';

--
-- 转存表中的数据 `sa_conf`
--

INSERT INTO `sa_conf` (`conf_id`, `conf_key`, `conf_value`, `conf_readonly`, `conf_desc`, `conf_int`, `conf_system`, `conf_status`, `conf_createtime`, `conf_updatetime`) VALUES
(1, 'wechat_appid', '', 0, '微信ID', 0, 1, 0, 0, 0),
(2, 'wechat_appkey', '', 0, '微信密钥', 0, 1, 0, 0, 0),
(3, 'wechat_token', 'StartAdmin', 0, '微信TOKEN', 0, 1, 0, 0, 1585844226),
(4, 'wechat_aes_key', 'StartAdmin', 0, '微信AES密钥', 0, 1, 0, 0, 1585844226),
(11, 'weapp_appid', '', 0, '小程序APPID', 0, 1, 0, 0, 0),
(12, 'weapp_appkey', '', 0, '小程序SECRET', 0, 1, 0, 0, 0),
(36, 'app_name', 'StartAdmin', 0, '产品名称', 0, 1, 0, 0, 0),
(37, 'iconfont', '//at.alicdn.com/t/font_666204_u6x6ssnn9sh.css', 0, '阿里图标', 0, 1, 0, 0, 0),
(39, 'upload_max_file', '2097152', 0, '最大文件上传限制', 0, 1, 0, 0, 0),
(40, 'upload_file_type', 'jpg,png,gif,jpeg,bmp,txt,pdf,mp3,mp4,amr,m4a,xls,xlsx,ppt,pptx,doc,docx', 0, '允许文件上传类型', 0, 1, 0, 0, 0),
(41, 'upload_max_image', '2097152', 0, '最大图片上传限制', 0, 1, 0, 0, 0),
(42, 'upload_image_type', 'jpg,png,gif,jpeg,bmp', 0, '允许上传图片类型', 0, 1, 0, 0, 0),
(43, 'alisms_appkey', '', 0, '阿里云短信key', 0, 1, 0, 0, 0),
(44, 'alisms_appid', '', 0, '阿里云短信ID', 0, 1, 0, 0, 0),
(45, 'alisms_sign', '', 0, '阿里云短信签名', 0, 1, 0, 0, 0),
(46, 'alisms_template', '', 0, '阿里云短信模板', 0, 1, 0, 0, 0),
(47, 'default_group', '3', 0, '注册默认用户组', 0, 1, 0, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `sa_group`
--

CREATE TABLE `sa_group` (
  `group_id` int(11) NOT NULL,
  `group_name` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '管理组名称',
  `group_system` int(11) NOT NULL DEFAULT '0' COMMENT '是否系统组',
  `group_desc` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '管理组描述',
  `group_status` int(11) NOT NULL DEFAULT '0' COMMENT '1被禁用',
  `group_createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `group_updatetime` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='管理组表';

--
-- 转存表中的数据 `sa_group`
--

INSERT INTO `sa_group` (`group_id`, `group_name`, `group_system`, `group_desc`, `group_status`, `group_createtime`, `group_updatetime`) VALUES
(1, '超级管理员', 1, '不允许删除', 0, 0, 1575903468),
(2, '管理员', 1, '不允许删除', 0, 0, 1575903468),
(3, '普通用户', 1, '默认用户组', 0, 1571924836, 1575905137);

-- --------------------------------------------------------

--
-- 表的结构 `sa_log`
--

CREATE TABLE `sa_log` (
  `log_id` int(9) NOT NULL COMMENT '操作ID',
  `log_user` int(11) NOT NULL COMMENT '用户UID',
  `log_gets` text CHARACTER SET utf8 COMMENT 'GET参数',
  `log_posts` text CHARACTER SET utf8 COMMENT 'POST参数',
  `log_cookies` text CHARACTER SET utf8 COMMENT 'Cookies数据',
  `log_node` int(9) NOT NULL COMMENT '节点ID',
  `log_ip` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT 'IP地址',
  `log_os` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '操作系统',
  `log_browser` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '浏览器',
  `log_status` int(9) NOT NULL DEFAULT '0' COMMENT '1被禁用',
  `log_createtime` int(9) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `log_updatetime` int(9) NOT NULL DEFAULT '0' COMMENT '修改时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='访问记录表';

-- --------------------------------------------------------

--
-- 表的结构 `sa_node`
--

CREATE TABLE `sa_node` (
  `node_id` int(11) NOT NULL COMMENT '功能ID',
  `node_system` int(11) NOT NULL DEFAULT '0' COMMENT '系统节点',
  `node_title` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '功能名称',
  `node_desc` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '功能描述',
  `node_module` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT 'api' COMMENT '模块',
  `node_controller` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '控制器',
  `node_action` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '方法',
  `node_pid` int(11) NOT NULL DEFAULT '0' COMMENT '父ID',
  `node_order` int(11) NOT NULL DEFAULT '0' COMMENT '排序ID',
  `node_show` int(11) NOT NULL DEFAULT '1' COMMENT '1显示到菜单',
  `node_icon` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '图标',
  `node_extend` text CHARACTER SET utf8 COMMENT '扩展数据',
  `node_status` int(11) NOT NULL DEFAULT '0' COMMENT '1被禁用',
  `node_createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `node_updatetime` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='功能节点表';

--
-- 转存表中的数据 `sa_node`
--

INSERT INTO `sa_node` (`node_id`, `node_system`, `node_title`, `node_desc`, `node_module`, `node_controller`, `node_action`, `node_pid`, `node_order`, `node_show`, `node_icon`, `node_extend`, `node_status`, `node_createtime`, `node_updatetime`) VALUES
(1, 1, '管理首页', '', 'admin', 'index', 'index', 0, 0, 1, 'shouye', NULL, 0, 0, 1585131318),
(2, 1, '用户管理', '', 'admin', '', '', 0, 0, 1, 'haoyouliebiao', NULL, 0, 0, 1575948484),
(3, 1, '系统设置', '', 'admin', '', '', 0, 0, 1, 'shezhi', NULL, 0, 0, 1575948484),
(4, 1, '接口列表', '', 'api', '', '', 0, 0, 0, '', NULL, 0, 0, 1576045995),
(5, 1, '数据日志', '', 'admin', '', '', 0, 0, 1, 'book', NULL, 0, 0, 1575948636),
(6, 1, '微信管理', '', 'admin', '', '', 0, 0, 1, 'wechat', NULL, 0, 1585323009, 1585323009),
(100, 1, '用户管理', '', 'admin', 'user', 'index', 2, 0, 1, '', '', 0, 0, 1575948484),
(101, 1, '用户组管理', '', 'admin', 'group', 'index', 2, 0, 1, '', NULL, 0, 0, 1575948484),
(102, 1, '系统配置', '', 'admin', 'conf', 'index', 3, 0, 1, '', '', 0, 0, 1575960614),
(103, 1, '验证码管理', '', 'admin', 'sms', 'index', 3, 0, 1, '', NULL, 0, 0, 1575948484),
(104, 1, '节点管理', '', 'admin', 'node', 'index', 3, 0, 1, '', '', 0, 0, 1575948484),
(105, 1, '附件管理', '', 'admin', 'attach', 'index', 3, 0, 1, '', '', 0, 0, 1575948484),
(106, 1, '清理数据', '', 'admin', 'system', 'clean', 5, 0, 1, '', '', 0, 0, 1575984190),
(107, 1, '代码生成', '', 'admin', 'system', 'build', 3, 0, 1, '', '', 0, 0, 1575948484),
(108, 1, '基础设置', '', 'admin', 'conf', 'base', 3, 0, 1, '', '', 0, 0, 1575948484),
(109, 1, '访问日志', '', 'admin', 'log', 'index', 5, 0, 1, '', '', 0, 0, 1575984177),
(110, 1, '访问统计', '', 'admin', 'log', 'state', 5, 0, 1, '', '', 0, 0, 1575984183),
(111, 0, '微信菜单管理', '', 'admin', 'wemenu', 'index', 6, 0, 1, '', NULL, 0, 1585323009, 1585323009),
(112, 0, '临时凭证管理', '', 'admin', 'code', 'index', 3, 0, 1, '', '', 0, 1581836878, 1581839323),
(113, 0, '微信粉丝管理', '', 'admin', 'wechat', 'index', 6, 0, 1, '', NULL, 0, 1585323009, 1585323009),
(1000, 1, '获取用户列表接口', '', 'api', 'user', 'getList', 4, 0, 1, '', NULL, 0, 0, 1575948484),
(1001, 1, '获取用户组列表接口', '', 'api', 'group', 'getList', 4, 0, 1, '', NULL, 0, 0, 1575948484),
(1003, 1, '获取所有配置列表接口', '', 'api', 'conf', 'getList', 4, 0, 1, '', NULL, 0, 0, 1575948484),
(1004, 1, '获取短信验证码列表接口', '', 'api', 'sms', 'getList', 4, 0, 1, '', NULL, 0, 0, 1575948484),
(1005, 1, '获取节点列表接口', '', 'api', 'node', 'getList', 4, 0, 1, '', NULL, 0, 0, 1575948484),
(1006, 1, '获取用户详细信息接口', '', 'api', 'user', 'detail', 4, 0, 1, '', NULL, 0, 0, 1575948484),
(1007, 1, '添加用户接口', '', 'api', 'user', 'add', 4, 0, 1, '', NULL, 0, 0, 1575948484),
(1008, 1, '修改用户接口', '', 'api', 'user', 'update', 4, 0, 1, '', NULL, 0, 0, 1575948484),
(1009, 1, '禁用用户接口', '', 'api', 'user', 'disable', 4, 0, 1, '', NULL, 0, 0, 1575948484),
(1010, 1, '启用用户接口', '', 'api', 'user', 'enable', 4, 0, 1, '', NULL, 0, 0, 1575948484),
(1011, 1, '删除用户接口', '', 'api', 'user', 'delete', 4, 0, 1, '', NULL, 0, 0, 1575948484),
(1012, 1, '获取我的资料接口', '', 'api', 'user', 'getmyinfo', 4, 0, 1, '', NULL, 0, 0, 1575948484),
(1013, 1, '修改我的资料接口', '', 'api', 'user', 'updatemyinfo', 4, 0, 1, '', NULL, 0, 0, 1575948484),
(1014, 1, '添加用户组接口', '', 'api', 'group', 'add', 4, 0, 1, '', NULL, 0, 0, 1575948484),
(1015, 1, '获取用户组信息接口', '', 'api', 'group', 'detail', 4, 0, 1, '', NULL, 0, 0, 1575948484),
(1016, 1, '修改用户组信息接口', '', 'api', 'group', 'update', 4, 0, 1, '', NULL, 0, 0, 1575948484),
(1017, 1, '禁用用户组接口', '', 'api', 'group', 'disable', 4, 0, 1, '', NULL, 0, 0, 1575948484),
(1018, 1, '启用用户组接口', '', 'api', 'group', 'enable', 4, 0, 1, '', NULL, 0, 0, 1575948484),
(1019, 1, '删除用户组接口', '', 'api', 'group', 'delete', 4, 0, 1, '', NULL, 0, 0, 1575948484),
(1020, 1, '设置用户组的权限', '', 'api', 'group', 'authorize', 4, 0, 1, '', NULL, 0, 0, 1575948484),
(1021, 1, '获取用户组的权限', '', 'api', 'group', 'getauthorize', 4, 0, 1, '', NULL, 0, 0, 1575948484),
(1022, 1, '禁用节点接口', '', 'api', 'node', 'disable', 4, 0, 1, '', NULL, 0, 0, 1575948484),
(1023, 1, '启用节点接口', '', 'api', 'node', 'enable', 4, 0, 1, '', NULL, 0, 0, 1575948484),
(1024, 1, '删除节点接口', '', 'api', 'node', 'delete', 4, 0, 1, '', NULL, 0, 0, 1575948484),
(1026, 1, '显示节点到菜单接口', '', 'api', 'node', 'show_menu', 4, 0, 1, '', NULL, 0, 0, 1575948484),
(1027, 1, '隐藏节点到菜单接口', '', 'api', 'node', 'hide_menu', 4, 0, 1, '', NULL, 0, 0, 1575948484),
(1028, 1, '获取节点信息接口', '', 'api', 'node', 'detail', 4, 0, 1, '', NULL, 0, 0, 1575948484),
(1029, 1, '修改节点信息接口', '', 'api', 'node', 'update', 4, 0, 1, '', NULL, 0, 0, 1575948484),
(1030, 1, '添加节点信息接口', '', 'api', 'node', 'add', 4, 0, 1, '', NULL, 0, 0, 1575948484),
(1031, 1, '节点导入接口', '', 'api', 'node', 'import', 4, 0, 1, '', NULL, 0, 0, 1575948484),
(1032, 1, 'API接口首页', '', 'api', 'index', 'index', 4, 0, 1, '', NULL, 0, 1575948342, 1575948484),
(1033, 1, '发送短信接口', '', 'api', 'sms', 'send', 4, 0, 1, '', NULL, 0, 1575948342, 1575948484),
(1034, 1, '获取系统错误信息接口', '', 'api', 'system', 'errors', 4, 0, 1, '', NULL, 0, 1575948342, 1575948484),
(1035, 1, '获取验证码接口', '', 'api', 'system', 'getcaptcha', 4, 0, 1, '', NULL, 0, 1575948342, 1575948484),
(1036, 1, '用户登录接口', '', 'api', 'user', 'login', 4, 0, 1, '', NULL, 0, 1575948342, 1575948484),
(1037, 1, '用户注册接口', '', 'api', 'user', 'reg', 4, 0, 1, '', NULL, 0, 1575948342, 1575948484),
(1038, 1, '修改我的密码接口', '', 'api', 'user', 'motifypassword', 4, 0, 1, '', NULL, 0, 1575948342, 1575948484),
(1039, 1, '找回密码接口', '', 'api', 'user', 'resetpassword', 4, 0, 1, '', NULL, 0, 1575948342, 1575948484),
(1040, 1, '微信小程序登录接口', '', 'api', 'user', 'wxapplogin', 4, 0, 1, '', NULL, 0, 1575948342, 1575948484),
(1041, 1, '微信手机号解密接口', '', 'api', 'user', 'wxphonedecodelogin', 4, 0, 1, '', NULL, 0, 1575948342, 1575948484),
(1042, 1, '添加配置接口', '', 'api', 'conf', 'add', 4, 0, 1, '', NULL, 0, 1575948342, 1575948484),
(1043, 1, '修改配置接口', '', 'api', 'conf', 'update', 4, 0, 1, '', NULL, 0, 1575948342, 1575948484),
(1044, 1, '获取配置信息接口', '', 'api', 'conf', 'detail', 4, 0, 1, '', NULL, 0, 1575948342, 1575948484),
(1045, 1, '删除配置信息接口', '', 'api', 'conf', 'delete', 4, 0, 1, '', NULL, 0, 1575948342, 1575948484),
(1046, 1, '获取附件列表接口', '', 'api', 'attach', 'getList', 4, 0, 1, '', NULL, 0, 1575948342, 1575948484),
(1047, 1, '上传文件接口', '', 'api', 'attach', 'uploadfile', 4, 0, 1, '', NULL, 0, 1575948342, 1575948484),
(1048, 1, '删除附件接口', '', 'api', 'attach', 'delete', 4, 0, 1, '', NULL, 0, 1575948342, 1575948484),
(1049, 1, '清空授权信息接口', '', 'api', 'auth', 'clean', 4, 0, 1, '', NULL, 0, 1575948342, 1575948484),
(1050, 1, '清空访问日志接口', '', 'api', 'log', 'clean', 4, 0, 1, '', NULL, 0, 1575948342, 1575948484),
(1051, 1, '删除短信接口', '', 'api', 'sms', 'delete', 4, 0, 1, '', NULL, 0, 1575948342, 1575948484),
(1052, 1, '代码生成接口', '', 'api', 'system', 'build', 4, 0, 1, '', '', 0, 0, 1575948484),
(1074, 1, '获取基础设置接口', '', 'api', 'conf', 'getBaseConfig', 4, 0, 1, '', NULL, 0, 0, 1575948484),
(1075, 1, '修改基础设置接口', '', 'api', 'conf', 'updateBaseConfig', 4, 0, 1, '', NULL, 0, 0, 1575948484),
(1077, 1, '上传图片接口', '', 'api', 'attach', 'uploadimage', 4, 0, 1, '', '', 0, 1575981672, 1575981701),
(1078, 1, '获取访问统计数据接口', '', 'api', 'log', 'state', 4, 0, 1, '', NULL, 0, 1575981672, 1575981672),
(1079, 1, '获取日志列表接口', '', 'api', 'log', 'getList', 4, 0, 1, '', NULL, 0, 1575981672, 1575981672),
(1080, 1, '删除日志接口', '', 'api', 'log', 'delete', 4, 0, 1, '', NULL, 0, 1575981672, 1575981672),
(1081, 1, '导出节点接口', '', 'api', 'node', 'excel', 4, 0, 1, '', NULL, 0, 1575981672, 1575981672),
(1082, 1, '导出日志接口', '', 'api', 'log', 'excel', 4, 0, 1, '', NULL, 0, 1575981672, 1575981672),
(1083, 0, '获取临时凭证详情接口', '', 'api', 'code', 'detail', 4, 0, 1, '', NULL, 0, 1581836878, 1581836878),
(1084, 0, '添加临时凭证接口', '', 'api', 'code', 'add', 4, 0, 1, '', NULL, 0, 1581836878, 1581836878),
(1085, 0, '修改临时凭证接口', '', 'api', 'code', 'update', 4, 0, 1, '', NULL, 0, 1581836878, 1581836878),
(1086, 0, '删除临时凭证接口', '', 'api', 'code', 'delete', 4, 0, 1, '', NULL, 0, 1581836878, 1581836878),
(1087, 0, '禁用临时凭证接口', '', 'api', 'code', 'disable', 4, 0, 1, '', NULL, 0, 1581836878, 1581836878),
(1088, 0, '启用临时凭证接口', '', 'api', 'code', 'enable', 4, 0, 1, '', NULL, 0, 1581836878, 1581836878),
(1089, 0, '获取临时凭证列表接口', '', 'api', 'code', 'getList', 4, 0, 1, '', NULL, 0, 1581836878, 1581836878),
(1091, 0, '获取微信菜单详情接口', '', 'api', 'wemenu', 'detail', 4, 0, 1, '', NULL, 0, 1585323009, 1585323009),
(1092, 0, '添加微信菜单接口', '', 'api', 'wemenu', 'add', 4, 0, 1, '', NULL, 0, 1585323009, 1585323009),
(1093, 0, '修改微信菜单接口', '', 'api', 'wemenu', 'update', 4, 0, 1, '', NULL, 0, 1585323009, 1585323009),
(1094, 0, '删除微信菜单接口', '', 'api', 'wemenu', 'delete', 4, 0, 1, '', NULL, 0, 1585323009, 1585323009),
(1095, 0, '禁用微信菜单接口', '', 'api', 'wemenu', 'disable', 4, 0, 1, '', NULL, 0, 1585323009, 1585323009),
(1096, 0, '启用微信菜单接口', '', 'api', 'wemenu', 'enable', 4, 0, 1, '', NULL, 0, 1585323009, 1585323009),
(1097, 0, '获取微信菜单列表接口', '', 'api', 'wemenu', 'getList', 4, 0, 1, '', NULL, 0, 1585323009, 1585323009),
(1100, 0, '微信发布自定义菜单接口', '', 'api', 'wemenu', 'publish', 4, 0, 1, '', NULL, 0, 1585323009, 1585323009),
(1101, 1, '获取微信粉丝列表接口', '', 'api', 'wechat', 'getList', 4, 0, 1, '', NULL, 0, 0, 1575948484),
(1102, 1, '禁用微信粉丝接口', '', 'api', 'wechat', 'disable', 4, 0, 1, '', NULL, 0, 0, 1575948484),
(1103, 1, '启用微信粉丝接口', '', 'api', 'wechat', 'enable', 4, 0, 1, '', NULL, 0, 0, 1575948484),
(1104, 1, '用户导出Excel接口', '', 'api', 'user', 'excel', 4, 0, 1, '', NULL, 0, 0, 1575948484),
(1113, 0, '获取小程序用户详情接口', '', 'api', 'weapp', 'detail', 4, 0, 1, '', NULL, 0, 1585854558, 1585854558),
(1114, 0, '添加小程序用户接口', '', 'api', 'weapp', 'add', 4, 0, 1, '', NULL, 0, 1585854558, 1585854558),
(1115, 0, '修改小程序用户接口', '', 'api', 'weapp', 'update', 4, 0, 1, '', NULL, 0, 1585854558, 1585854558),
(1116, 0, '删除小程序用户接口', '', 'api', 'weapp', 'delete', 4, 0, 1, '', NULL, 0, 1585854558, 1585854558),
(1117, 0, '禁用小程序用户接口', '', 'api', 'weapp', 'disable', 4, 0, 1, '', NULL, 0, 1585854558, 1585854558),
(1118, 0, '启用小程序用户接口', '', 'api', 'weapp', 'enable', 4, 0, 1, '', NULL, 0, 1585854558, 1585854558),
(1119, 0, '获取小程序用户列表接口', '', 'api', 'weapp', 'getList', 4, 0, 1, '', NULL, 0, 1585854558, 1585854558),
(1120, 0, '小程序用户管理', '', 'admin', 'weapp', 'index', 0, 0, 1, '', NULL, 0, 1585854558, 1585854558);

-- --------------------------------------------------------

--
-- 表的结构 `sa_sms`
--

CREATE TABLE `sa_sms` (
  `sms_id` int(9) NOT NULL,
  `sms_phone` varchar(255) NOT NULL DEFAULT '' COMMENT '手机号',
  `sms_code` varchar(10) NOT NULL DEFAULT '' COMMENT '验证码',
  `sms_timeout` int(11) NOT NULL DEFAULT '0' COMMENT '超时时间戳',
  `sms_callback` varchar(255) NOT NULL DEFAULT '' COMMENT '发送短信结果',
  `sms_status` int(9) NOT NULL DEFAULT '0' COMMENT '状态',
  `sms_createtime` int(9) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `sms_updatetime` int(9) NOT NULL DEFAULT '0' COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='短信验证码表';

-- --------------------------------------------------------

--
-- 表的结构 `sa_user`
--

CREATE TABLE `sa_user` (
  `user_id` int(11) NOT NULL COMMENT 'UID',
  `user_account` varchar(64) CHARACTER SET utf8 NOT NULL COMMENT '帐号',
  `user_password` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '密码',
  `user_salt` varchar(4) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '密码盐',
  `user_name` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '用户昵称',
  `user_idcard` varchar(18) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '身份证',
  `user_truename` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '真实姓名',
  `user_email` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '邮箱',
  `user_money` decimal(9,2) NOT NULL DEFAULT '0.00' COMMENT '余额',
  `user_group` int(11) NOT NULL DEFAULT '0' COMMENT '用户组',
  `user_wechat` int(11) NOT NULL DEFAULT '0' COMMENT '绑定的公众号',
  `user_wxapp` int(11) NOT NULL DEFAULT '0' COMMENT '绑定的小程序',
  `user_qq` int(11) NOT NULL DEFAULT '0' COMMENT '绑定的QQ',
  `user_ipreg` varchar(255) NOT NULL COMMENT '注册IP',
  `user_status` int(11) NOT NULL DEFAULT '0' COMMENT '1被禁用',
  `user_createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `user_updatetime` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户表';

--
-- 转存表中的数据 `sa_user`
--

INSERT INTO `sa_user` (`user_id`, `user_account`, `user_password`, `user_salt`, `user_name`, `user_idcard`, `user_truename`, `user_email`, `user_money`, `user_group`, `user_wechat`, `user_wxapp`, `user_qq`, `user_ipreg`, `user_status`, `user_createtime`, `user_updatetime`) VALUES
(1, 'root', 'bb0ae052a4967ddc3bc18721bd971e093a2226bd', 'meDn', '超级管理员', '500000000000000000', '超级管理员', 'admin@hamm.cn', '12346.58', 1, 0, 0, 0, '127.0.0.1', 0, 0, 1575901928);

-- --------------------------------------------------------

--
-- 表的结构 `sa_weapp`
--

CREATE TABLE `sa_weapp` (
  `weapp_id` int(9) NOT NULL,
  `weapp_openid` varchar(255) NOT NULL DEFAULT '' COMMENT 'OPENID',
  `weapp_status` int(9) NOT NULL DEFAULT '0' COMMENT '状态',
  `weapp_createtime` int(9) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `weapp_updatetime` int(9) NOT NULL DEFAULT '0' COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='小程序用户表';

-- --------------------------------------------------------

--
-- 表的结构 `sa_wechat`
--

CREATE TABLE `sa_wechat` (
  `wechat_id` int(11) NOT NULL COMMENT 'ID',
  `wechat_openid` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT 'openid',
  `wechat_nick` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '昵称',
  `wechat_head` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '头像',
  `wechat_sex` int(11) NOT NULL DEFAULT '0' COMMENT '性别',
  `wechat_province` varchar(255) NOT NULL DEFAULT '' COMMENT '省份',
  `wechat_city` varchar(255) NOT NULL DEFAULT '' COMMENT '城市',
  `wechat_country` varchar(255) NOT NULL DEFAULT '' COMMENT '国家',
  `wechat_status` int(11) NOT NULL DEFAULT '0',
  `wechat_createtime` int(11) NOT NULL DEFAULT '0',
  `wechat_updatetime` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='微信粉丝表';

-- --------------------------------------------------------

--
-- 表的结构 `sa_wemenu`
--

CREATE TABLE `sa_wemenu` (
  `wemenu_id` int(9) NOT NULL,
  `wemenu_name` varchar(255) NOT NULL DEFAULT '' COMMENT '菜单名称',
  `wemenu_type` varchar(255) NOT NULL DEFAULT '0' COMMENT '类型',
  `wemenu_url` varchar(255) NOT NULL DEFAULT '' COMMENT '菜单链接',
  `wemenu_appid` varchar(255) NOT NULL DEFAULT '' COMMENT '小程序ID',
  `wemenu_key` varchar(255) NOT NULL DEFAULT '' COMMENT '点击参数',
  `wemenu_page` varchar(255) NOT NULL DEFAULT '' COMMENT '小程序页面',
  `wemenu_pid` int(9) NOT NULL DEFAULT '0' COMMENT '父菜单',
  `wemenu_status` int(9) NOT NULL DEFAULT '0' COMMENT '状态',
  `wemenu_createtime` int(9) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `wemenu_updatetime` int(9) NOT NULL DEFAULT '0' COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='微信菜单表';

--
-- 转存表中的数据 `sa_wemenu`
--

INSERT INTO `sa_wemenu` (`wemenu_id`, `wemenu_name`, `wemenu_type`, `wemenu_url`, `wemenu_appid`, `wemenu_key`, `wemenu_page`, `wemenu_pid`, `wemenu_status`, `wemenu_createtime`, `wemenu_updatetime`) VALUES
(5, '普通菜单', 'click', '', '', 'normal_menu', '', 0, 0, 1585325798, 1585567064),
(6, '富媒体', 'click', '', '', 'media', '', 0, 0, 1585325806, 1585327224),
(7, '高级菜单', 'click', '', '', 'system', '', 0, 0, 1585325814, 1585327395),
(8, '点击菜单', 'click', '', '', 'click', '', 5, 0, 1585325831, 1585327202),
(9, '拍照发图', 'pic_sysphoto', '', '', 'camera', '', 6, 0, 1585325860, 1585327286),
(10, '微信扫码推', 'scancode_push', '', '', 'wechatScanner', '', 7, 0, 1585325867, 1585327464),
(11, '打开小程序', 'click', '', '', 'forTest', '', 7, 0, 1585327128, 1585328317),
(12, '打开网页', 'view', 'https://hamm.cn', '', '', '', 5, 0, 1585327252, 1585327252),
(13, '相册发图', 'pic_weixin', '', '', 'photo', '', 6, 0, 1585327311, 1585327311),
(14, '随便发图', 'pic_photo_or_album', '', '', 'all', '', 6, 0, 1585327330, 1585327330),
(15, '自定义扫码', 'scancode_waitmsg', '', '', 'customScaner', '', 7, 0, 1585327447, 1585327447),
(16, '发送位置', 'location_select', '', '', 'location', '', 7, 0, 1585327482, 1585327482);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sa_access`
--
ALTER TABLE `sa_access`
  ADD PRIMARY KEY (`access_id`) USING BTREE;

--
-- Indexes for table `sa_app`
--
ALTER TABLE `sa_app`
  ADD PRIMARY KEY (`app_id`) USING BTREE,
  ADD KEY `app_secret` (`app_secret`) USING BTREE;

--
-- Indexes for table `sa_attach`
--
ALTER TABLE `sa_attach`
  ADD PRIMARY KEY (`attach_id`) USING BTREE;

--
-- Indexes for table `sa_auth`
--
ALTER TABLE `sa_auth`
  ADD PRIMARY KEY (`auth_id`) USING BTREE,
  ADD KEY `role_group` (`auth_group`) USING BTREE,
  ADD KEY `role_auth` (`auth_node`) USING BTREE;

--
-- Indexes for table `sa_code`
--
ALTER TABLE `sa_code`
  ADD PRIMARY KEY (`code_id`) USING BTREE;

--
-- Indexes for table `sa_conf`
--
ALTER TABLE `sa_conf`
  ADD PRIMARY KEY (`conf_id`) USING BTREE,
  ADD KEY `conf_key` (`conf_key`) USING BTREE;

--
-- Indexes for table `sa_group`
--
ALTER TABLE `sa_group`
  ADD PRIMARY KEY (`group_id`) USING BTREE;

--
-- Indexes for table `sa_log`
--
ALTER TABLE `sa_log`
  ADD PRIMARY KEY (`log_id`) USING BTREE,
  ADD KEY `log_user` (`log_user`) USING BTREE,
  ADD KEY `log_node` (`log_node`) USING BTREE;

--
-- Indexes for table `sa_node`
--
ALTER TABLE `sa_node`
  ADD PRIMARY KEY (`node_id`) USING BTREE,
  ADD KEY `auth_pid` (`node_pid`) USING BTREE,
  ADD KEY `node_module` (`node_module`) USING BTREE,
  ADD KEY `node_controller` (`node_controller`) USING BTREE,
  ADD KEY `node_action` (`node_action`) USING BTREE;

--
-- Indexes for table `sa_sms`
--
ALTER TABLE `sa_sms`
  ADD PRIMARY KEY (`sms_id`) USING BTREE,
  ADD KEY `sms_phone` (`sms_phone`(191)) USING BTREE,
  ADD KEY `sms_code` (`sms_code`) USING BTREE;

--
-- Indexes for table `sa_user`
--
ALTER TABLE `sa_user`
  ADD PRIMARY KEY (`user_id`) USING BTREE,
  ADD KEY `admin_group` (`user_group`) USING BTREE,
  ADD KEY `admin_name` (`user_name`) USING BTREE,
  ADD KEY `admin_password` (`user_password`) USING BTREE,
  ADD KEY `admin_account` (`user_account`) USING BTREE;

--
-- Indexes for table `sa_weapp`
--
ALTER TABLE `sa_weapp`
  ADD PRIMARY KEY (`weapp_id`);

--
-- Indexes for table `sa_wechat`
--
ALTER TABLE `sa_wechat`
  ADD PRIMARY KEY (`wechat_id`) USING BTREE,
  ADD KEY `wechat_openid` (`wechat_openid`) USING BTREE;

--
-- Indexes for table `sa_wemenu`
--
ALTER TABLE `sa_wemenu`
  ADD PRIMARY KEY (`wemenu_id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `sa_access`
--
ALTER TABLE `sa_access`
  MODIFY `access_id` int(9) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `sa_app`
--
ALTER TABLE `sa_app`
  MODIFY `app_id` int(9) NOT NULL AUTO_INCREMENT COMMENT 'APPID', AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `sa_attach`
--
ALTER TABLE `sa_attach`
  MODIFY `attach_id` int(9) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `sa_auth`
--
ALTER TABLE `sa_auth`
  MODIFY `auth_id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '权限ID';
--
-- 使用表AUTO_INCREMENT `sa_code`
--
ALTER TABLE `sa_code`
  MODIFY `code_id` int(9) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `sa_conf`
--
ALTER TABLE `sa_conf`
  MODIFY `conf_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;
--
-- 使用表AUTO_INCREMENT `sa_group`
--
ALTER TABLE `sa_group`
  MODIFY `group_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- 使用表AUTO_INCREMENT `sa_log`
--
ALTER TABLE `sa_log`
  MODIFY `log_id` int(9) NOT NULL AUTO_INCREMENT COMMENT '操作ID';
--
-- 使用表AUTO_INCREMENT `sa_node`
--
ALTER TABLE `sa_node`
  MODIFY `node_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '功能ID', AUTO_INCREMENT=1121;
--
-- 使用表AUTO_INCREMENT `sa_sms`
--
ALTER TABLE `sa_sms`
  MODIFY `sms_id` int(9) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `sa_user`
--
ALTER TABLE `sa_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'UID', AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `sa_weapp`
--
ALTER TABLE `sa_weapp`
  MODIFY `weapp_id` int(9) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `sa_wechat`
--
ALTER TABLE `sa_wechat`
  MODIFY `wechat_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID';
--
-- 使用表AUTO_INCREMENT `sa_wemenu`
--
ALTER TABLE `sa_wemenu`
  MODIFY `wemenu_id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

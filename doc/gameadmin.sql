/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50725
Source Host           : localhost:3306
Source Database       : gameadmin

Target Server Type    : MYSQL
Target Server Version : 50725
File Encoding         : 65001

Date: 2019-04-11 16:09:36
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for game_api
-- ----------------------------
DROP TABLE IF EXISTS `game_api`;
CREATE TABLE `game_api` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `base_url` varchar(255) DEFAULT NULL,
  `hash` varchar(50) DEFAULT NULL,
  `method` varchar(10) DEFAULT NULL,
  `is_token` varchar(255) DEFAULT '0',
  `app_id` int(11) DEFAULT NULL,
  `param` varchar(255) DEFAULT NULL,
  `key` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of game_api
-- ----------------------------
INSERT INTO `game_api` VALUES ('1', 'test', 'https://www.gameadmin.top/index/index/testPost.html', 'NGPJPXYFLBFCTASBXYMYJ97R5', 'no_limit', '0', null, 'id:2|name:999', null, null, '1511938273', '1516843876');
INSERT INTO `game_api` VALUES ('45', '轮播图', 'https://www.gameadmin.top/index/index/testPost.html', 'INW2BZIXLBZJXHZ4QD4QGBLQ2', 'post', '0', null, 'id:1|name:活动1|image:http://www.baidu.com|cd:22', null, null, '1516413527', '1541690386');

-- ----------------------------
-- Table structure for game_app
-- ----------------------------
DROP TABLE IF EXISTS `game_app`;
CREATE TABLE `game_app` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `app_id` int(11) NOT NULL,
  `secret` varchar(255) NOT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `app_id` (`app_id`)
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of game_app
-- ----------------------------
INSERT INTO `game_app` VALUES ('48', '67890', '83315454', 'T5TIKY52PXW92KI8T47YNCPY2', '1512900450', '1514554017');
INSERT INTO `game_app` VALUES ('49', 'jjj', '89701605', 'CSOS6G1EX3PBE5QLWVOKJ4S4N', '1513262603', '1516368208');
INSERT INTO `game_app` VALUES ('51', '33213213', '36632265', 'SJXMOASBN1F7ZD85V44FPFZD5', '1513749512', '1513749512');
INSERT INTO `game_app` VALUES ('52', '66', '41119516', 'VG57ZSSDC1PN66QWHTQ167BXW', '1514170106', '1514170106');
INSERT INTO `game_app` VALUES ('54', 'fff', '35381515', 'FW8R3HD2DK7UVSK1UNPWUX3VR', '1514337763', '1514337763');
INSERT INTO `game_app` VALUES ('55', 'gggg', '17816116', 'V42T5EVD39Y19PHVQVKAP6AAM', '1514337920', '1514337920');
INSERT INTO `game_app` VALUES ('61', '888', '82758191', '2584LUAXGDEF2OFJESDQKPAVC', '1515790384', '1515790384');
INSERT INTO `game_app` VALUES ('62', '首页测试数据', '85221630', 'B3IXA9U7PRKQPBIJPJWBI6OVX', '1516412439', '1516412439');
INSERT INTO `game_app` VALUES ('63', 'frsfsd', '62661704', 'LZ8UTZOZO6QFB8EY6HIX2UJC2', '1516606772', '1516606772');
INSERT INTO `game_app` VALUES ('66', 'test11111', '42597693', 'JGBRGA7EXDY8T5INDBJO6PHCE', '1516756425', '1554970036');
INSERT INTO `game_app` VALUES ('67', 'ssdfgsdf', '62128950', 'IG4NT2KGVA72E1VRDEDFZBZUL', '1516760748', '1516760748');
INSERT INTO `game_app` VALUES ('68', '11111111', '82120651', 'OPYRD3BIOUXV75556X34DAYCR', '1516760764', '1516760764');

-- ----------------------------
-- Table structure for game_article
-- ----------------------------
DROP TABLE IF EXISTS `game_article`;
CREATE TABLE `game_article` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文章ID',
  `cid` smallint(5) unsigned NOT NULL COMMENT '分类ID',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `introduction` varchar(255) DEFAULT '' COMMENT '简介',
  `content` longtext COMMENT '内容',
  `author` varchar(20) DEFAULT '' COMMENT '作者',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态 0 待审核  1 审核',
  `reading` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '阅读量',
  `thumb` varchar(255) DEFAULT '' COMMENT '缩略图',
  `photo` text COMMENT '图集',
  `is_top` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否置顶  0 不置顶  1 置顶',
  `is_recommend` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否推荐  0 不推荐  1 推荐',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `publish_time` datetime NOT NULL COMMENT '发布时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='文章表';

-- ----------------------------
-- Records of game_article
-- ----------------------------
INSERT INTO `game_article` VALUES ('1', '1', '测试文章一', '', '<p>测试内容</p>', 'admin', '1', '0', '', null, '0', '0', '0', '2017-04-11 14:10:10', '2017-04-11 14:09:45');

-- ----------------------------
-- Table structure for game_auth_group
-- ----------------------------
DROP TABLE IF EXISTS `game_auth_group`;
CREATE TABLE `game_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(100) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `rules` varchar(255) NOT NULL COMMENT '权限规则ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=192 DEFAULT CHARSET=utf8 COMMENT='权限组表';

-- ----------------------------
-- Records of game_auth_group
-- ----------------------------
INSERT INTO `game_auth_group` VALUES ('1', '超级管理组', '1', '1,2,3,103');

-- ----------------------------
-- Table structure for game_auth_group_access
-- ----------------------------
DROP TABLE IF EXISTS `game_auth_group_access`;
CREATE TABLE `game_auth_group_access` (
  `uid` mediumint(8) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='权限组规则表';

-- ----------------------------
-- Records of game_auth_group_access
-- ----------------------------
INSERT INTO `game_auth_group_access` VALUES ('1', '1');
INSERT INTO `game_auth_group_access` VALUES ('27', '2');
INSERT INTO `game_auth_group_access` VALUES ('28', '2');
INSERT INTO `game_auth_group_access` VALUES ('29', '50');
INSERT INTO `game_auth_group_access` VALUES ('30', '52');
INSERT INTO `game_auth_group_access` VALUES ('31', '54');
INSERT INTO `game_auth_group_access` VALUES ('32', '2');
INSERT INTO `game_auth_group_access` VALUES ('33', '64');
INSERT INTO `game_auth_group_access` VALUES ('34', '1');
INSERT INTO `game_auth_group_access` VALUES ('35', '52');
INSERT INTO `game_auth_group_access` VALUES ('36', '35');
INSERT INTO `game_auth_group_access` VALUES ('37', '52');
INSERT INTO `game_auth_group_access` VALUES ('38', '65');
INSERT INTO `game_auth_group_access` VALUES ('39', '59');
INSERT INTO `game_auth_group_access` VALUES ('40', '69');
INSERT INTO `game_auth_group_access` VALUES ('41', '69');
INSERT INTO `game_auth_group_access` VALUES ('42', '69');
INSERT INTO `game_auth_group_access` VALUES ('43', '61');
INSERT INTO `game_auth_group_access` VALUES ('44', '62');
INSERT INTO `game_auth_group_access` VALUES ('45', '64');
INSERT INTO `game_auth_group_access` VALUES ('46', '69');
INSERT INTO `game_auth_group_access` VALUES ('47', '70');
INSERT INTO `game_auth_group_access` VALUES ('48', '70');
INSERT INTO `game_auth_group_access` VALUES ('49', '70');
INSERT INTO `game_auth_group_access` VALUES ('50', '69');
INSERT INTO `game_auth_group_access` VALUES ('51', '70');
INSERT INTO `game_auth_group_access` VALUES ('52', '70');
INSERT INTO `game_auth_group_access` VALUES ('53', '70');
INSERT INTO `game_auth_group_access` VALUES ('54', '70');
INSERT INTO `game_auth_group_access` VALUES ('55', '1');
INSERT INTO `game_auth_group_access` VALUES ('56', '1');
INSERT INTO `game_auth_group_access` VALUES ('57', '72');
INSERT INTO `game_auth_group_access` VALUES ('58', '72');
INSERT INTO `game_auth_group_access` VALUES ('59', '72');
INSERT INTO `game_auth_group_access` VALUES ('60', '72');
INSERT INTO `game_auth_group_access` VALUES ('61', '1');
INSERT INTO `game_auth_group_access` VALUES ('62', '78');
INSERT INTO `game_auth_group_access` VALUES ('63', '78');
INSERT INTO `game_auth_group_access` VALUES ('64', '1');
INSERT INTO `game_auth_group_access` VALUES ('65', '117');
INSERT INTO `game_auth_group_access` VALUES ('66', '90');
INSERT INTO `game_auth_group_access` VALUES ('67', '90');
INSERT INTO `game_auth_group_access` VALUES ('68', '102');
INSERT INTO `game_auth_group_access` VALUES ('69', '102');
INSERT INTO `game_auth_group_access` VALUES ('70', '102');
INSERT INTO `game_auth_group_access` VALUES ('71', '106');
INSERT INTO `game_auth_group_access` VALUES ('72', '106');
INSERT INTO `game_auth_group_access` VALUES ('73', '112');
INSERT INTO `game_auth_group_access` VALUES ('74', '111');
INSERT INTO `game_auth_group_access` VALUES ('75', '78');
INSERT INTO `game_auth_group_access` VALUES ('76', '126');
INSERT INTO `game_auth_group_access` VALUES ('77', '1');
INSERT INTO `game_auth_group_access` VALUES ('78', '123');
INSERT INTO `game_auth_group_access` VALUES ('79', '1');
INSERT INTO `game_auth_group_access` VALUES ('80', '123');
INSERT INTO `game_auth_group_access` VALUES ('81', '129');
INSERT INTO `game_auth_group_access` VALUES ('82', '135');
INSERT INTO `game_auth_group_access` VALUES ('83', '129');
INSERT INTO `game_auth_group_access` VALUES ('84', '138');
INSERT INTO `game_auth_group_access` VALUES ('85', '137');
INSERT INTO `game_auth_group_access` VALUES ('86', '1');
INSERT INTO `game_auth_group_access` VALUES ('87', '138');
INSERT INTO `game_auth_group_access` VALUES ('88', '138');
INSERT INTO `game_auth_group_access` VALUES ('89', '138');
INSERT INTO `game_auth_group_access` VALUES ('90', '138');
INSERT INTO `game_auth_group_access` VALUES ('91', '117');
INSERT INTO `game_auth_group_access` VALUES ('92', '156');
INSERT INTO `game_auth_group_access` VALUES ('93', '156');
INSERT INTO `game_auth_group_access` VALUES ('94', '163');
INSERT INTO `game_auth_group_access` VALUES ('95', '164');
INSERT INTO `game_auth_group_access` VALUES ('96', '145');
INSERT INTO `game_auth_group_access` VALUES ('97', '172');
INSERT INTO `game_auth_group_access` VALUES ('98', '175');
INSERT INTO `game_auth_group_access` VALUES ('99', '1');
INSERT INTO `game_auth_group_access` VALUES ('100', '173');
INSERT INTO `game_auth_group_access` VALUES ('101', '180');
INSERT INTO `game_auth_group_access` VALUES ('102', '181');
INSERT INTO `game_auth_group_access` VALUES ('103', '185');
INSERT INTO `game_auth_group_access` VALUES ('104', '187');
INSERT INTO `game_auth_group_access` VALUES ('105', '187');
INSERT INTO `game_auth_group_access` VALUES ('106', '187');
INSERT INTO `game_auth_group_access` VALUES ('107', '1');
INSERT INTO `game_auth_group_access` VALUES ('108', '1');
INSERT INTO `game_auth_group_access` VALUES ('109', '1');
INSERT INTO `game_auth_group_access` VALUES ('110', '1');

-- ----------------------------
-- Table structure for game_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `game_auth_rule`;
CREATE TABLE `game_auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL DEFAULT '' COMMENT '规则名称',
  `title` varchar(20) NOT NULL,
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `pid` smallint(5) unsigned NOT NULL COMMENT '父级ID',
  `icon` varchar(50) DEFAULT '' COMMENT '图标',
  `sort` int(50) unsigned NOT NULL COMMENT '排序',
  `condition` char(100) DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=219 DEFAULT CHARSET=utf8 COMMENT='规则表';

-- ----------------------------
-- Records of game_auth_rule
-- ----------------------------
INSERT INTO `game_auth_rule` VALUES ('1', 'admin/user/default', '用户管理', '1', '1', '0', 'xe66f', '1', '');
INSERT INTO `game_auth_rule` VALUES ('2', 'admin/user/userlist', '用户列表', '1', '1', '1', '', '0', '');
INSERT INTO `game_auth_rule` VALUES ('3', 'admin/auth/index', '权限管理', '1', '1', '1', '', '0', '');
INSERT INTO `game_auth_rule` VALUES ('4', 'admin/auth/showRole', '角色列表', '1', '1', '1', '', '0', '');
INSERT INTO `game_auth_rule` VALUES ('8', 'admin/api/app_list', '接口仓库', '1', '1', '7', '', '0', '');
INSERT INTO `game_auth_rule` VALUES ('5', 'admin/DbManage/default', '数据库', '1', '1', '0', 'xe631', '201', '');
INSERT INTO `game_auth_rule` VALUES ('6', 'admin/DbManage/index', '备份', '1', '1', '5', '', '0', '');
INSERT INTO `game_auth_rule` VALUES ('7', 'admin/api/default', '接口管理', '1', '1', '0', 'xe857', '3', '');
INSERT INTO `game_auth_rule` VALUES ('202', 'admin/file_system/index', '文件管理', '1', '1', '0', 'xe61d', '200', '');
INSERT INTO `game_auth_rule` VALUES ('203', 'admin/gameCfg', '系统配置', '1', '1', '0', 'xe609', '5', '');
INSERT INTO `game_auth_rule` VALUES ('209', 'admin/gameCfg/verifycode', '系统变量配置', '1', '1', '203', 'xe660', '0', '');
INSERT INTO `game_auth_rule` VALUES ('217', 'admin/Test/index', '测试', '1', '1', '5', 'xe67b', '0', '');
INSERT INTO `game_auth_rule` VALUES ('218', 'admin/Log/index', '操作日志', '1', '1', '0', 'xe6b2', '202', '');

-- ----------------------------
-- Table structure for game_category
-- ----------------------------
DROP TABLE IF EXISTS `game_category`;
CREATE TABLE `game_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类ID',
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `alias` varchar(50) DEFAULT '' COMMENT '导航别名',
  `content` longtext COMMENT '分类内容',
  `thumb` varchar(255) DEFAULT '' COMMENT '缩略图',
  `icon` varchar(20) DEFAULT '' COMMENT '分类图标',
  `list_template` varchar(50) DEFAULT '' COMMENT '分类列表模板',
  `detail_template` varchar(50) DEFAULT '' COMMENT '分类详情模板',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '分类类型  1  列表  2 单页',
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID',
  `path` varchar(255) DEFAULT '' COMMENT '路径',
  `create_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='分类表';

-- ----------------------------
-- Records of game_category
-- ----------------------------
INSERT INTO `game_category` VALUES ('1', '分类一', '', '', '', '', '', '', '1', '0', '0', '0,', '2016-12-22 18:22:24');

-- ----------------------------
-- Table structure for game_config
-- ----------------------------
DROP TABLE IF EXISTS `game_config`;
CREATE TABLE `game_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '变量名',
  `group` varchar(30) NOT NULL DEFAULT '' COMMENT '分组',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '变量标题',
  `tip` varchar(100) NOT NULL DEFAULT '' COMMENT '变量描述',
  `type` varchar(30) NOT NULL DEFAULT '' COMMENT '类型:string,text,int,bool,array,datetime,date,file',
  `value` text NOT NULL COMMENT '变量值',
  `content` text COMMENT '变量字典数据',
  `rule` varchar(100) NOT NULL DEFAULT '' COMMENT '验证规则',
  `extend` varchar(255) DEFAULT '' COMMENT '扩展属性',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='系统配置';

-- ----------------------------
-- Records of game_config
-- ----------------------------
INSERT INTO `game_config` VALUES ('1', 'codeid', 'verify_code', '验证码ID', '请填写极验验证码ID', 'string', '48a6ebac4ebc6642d68c217fca33eb4d', null, 'required', '');
INSERT INTO `game_config` VALUES ('2', 'codekey', 'verify_code', '验证码Key', '请填写极验验证码Key', 'string', '4f1c085290bec5afdc54df73535fc361', null, 'required', '');
INSERT INTO `game_config` VALUES ('3', 'url', 'game', '游戏url', '请填写游戏url', 'string', 'http://api.game2019.net', null, 'required', '');
INSERT INTO `game_config` VALUES ('4', 'appkey', 'game', '游戏key', '请填写游戏key', 'string', '221223333221', null, 'required', '');
INSERT INTO `game_config` VALUES ('5', 'appsecret', 'game', '游戏secret', '请填写游戏secret', 'string', '2121212111', null, 'required', '');
INSERT INTO `game_config` VALUES ('6', 'mail_type', 'email', '发送方式', '选择邮件发送方式', 'select', '0', '[\"SMTP\",\"MAIL\"]', '', '');
INSERT INTO `game_config` VALUES ('7', 'mail_smtp_host', 'email', '邮件host', '错误的配置发送邮件会导致服务器超时', 'string', 'smtp.qq.com', '', '', '');
INSERT INTO `game_config` VALUES ('8', 'mail_smtp_port', 'email', '邮件port', '(不加密默认25,SSL默认465,TLS默认587)', 'string', '465', '', '', '');
INSERT INTO `game_config` VALUES ('9', 'mail_smtp_user', 'email', '用户名', '（填写完整用户名）', 'string', '1000011122', '', '', '');
INSERT INTO `game_config` VALUES ('10', 'mail_smtp_pass', 'email', '密码', '（填写您的密码）', 'string', 'password', '', '', '');
INSERT INTO `game_config` VALUES ('11', 'mail_verify_type', 'email', '验证方式', '（SMTP验证方式[推荐SSL]）', 'select', '0', '[\"TLS\",\"SSL\"]', '', '');
INSERT INTO `game_config` VALUES ('12', 'mail_from', 'email', '发件人', '', 'string', '10000@qq.com', '', '', '');

-- ----------------------------
-- Table structure for game_file
-- ----------------------------
DROP TABLE IF EXISTS `game_file`;
CREATE TABLE `game_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) DEFAULT NULL,
  `size` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of game_file
-- ----------------------------
INSERT INTO `game_file` VALUES ('82', '\\public\\uploads\\20181108\\01e8d6bb590532f8c36a8c3d7c85eb8f.jpg', '241k');
INSERT INTO `game_file` VALUES ('83', '\\public\\uploads\\20181108\\d475149f27b1e15c3734b61ada8aa0bc.jpg', '396k');

-- ----------------------------
-- Table structure for game_link
-- ----------------------------
DROP TABLE IF EXISTS `game_link`;
CREATE TABLE `game_link` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '链接名称',
  `link` varchar(255) DEFAULT '' COMMENT '链接地址',
  `image` varchar(255) DEFAULT '' COMMENT '链接图片',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态 1 显示  2 隐藏',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='友情链接表';

-- ----------------------------
-- Records of game_link
-- ----------------------------

-- ----------------------------
-- Table structure for game_log
-- ----------------------------
DROP TABLE IF EXISTS `game_log`;
CREATE TABLE `game_log` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `userid` int(4) DEFAULT '0',
  `username` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '',
  `action` varchar(255) DEFAULT '',
  `request` text,
  `logday` int(4) DEFAULT NULL,
  `response` text,
  `recordtime` datetime DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0' COMMENT '1、成功log',
  PRIMARY KEY (`id`),
  KEY `l_i` (`logday`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of game_log
-- ----------------------------
INSERT INTO `game_log` VALUES ('1', '1', 'admin', 'app\\admin\\controller\\GameCfg::verifycode', '', '20190411', null, null, '1');
INSERT INTO `game_log` VALUES ('2', '1', 'admin', 'app\\admin\\controller\\GameCfg::verifycode', '', '20190411', null, null, '1');
INSERT INTO `game_log` VALUES ('3', '1', 'admin', 'app\\admin\\controller\\GameCfg::verifycode', '', '20190411', null, '2019-04-11 15:11:28', '1');
INSERT INTO `game_log` VALUES ('4', '1', 'admin', 'app\\admin\\controller\\GameCfg::verifycode', '', '20190411', null, '2019-04-11 15:17:41', '1');
INSERT INTO `game_log` VALUES ('5', '1', 'admin', 'app\\admin\\controller\\GameCfg::verifycode', '', '20190411', null, '2019-04-11 15:29:11', '1');
INSERT INTO `game_log` VALUES ('6', '1', 'admin', 'app\\admin\\controller\\GameCfg::editCode', '{\"codeid\":\"48a6ebac4ebc6642d68c217fca33eb4d\",\"codekey\":\"4f1c085290bec5afdc54df73535fc3612\"}', '20190411', '{\"code\":0,\"msg\":\"\\u66f4\\u65b0\\u6210\\u529f\"}', '2019-04-11 15:29:15', '1');
INSERT INTO `game_log` VALUES ('7', '1', 'admin', 'app\\admin\\controller\\GameCfg::editCode', '{\"codeid\":\"48a6ebac4ebc6642d68c217fca33eb4d\",\"codekey\":\"4f1c085290bec5afdc54df73535fc361\"}', '20190411', '{\"code\":0,\"msg\":\"\\u66f4\\u65b0\\u6210\\u529f\"}', '2019-04-11 15:29:19', '1');
INSERT INTO `game_log` VALUES ('8', '1', 'admin', 'app\\admin\\controller\\GameCfg::verifycode', '', '20190411', null, '2019-04-11 15:36:06', '1');
INSERT INTO `game_log` VALUES ('9', '1', 'admin', 'app\\admin\\controller\\GameCfg::editGame', '{\"url\":\"http:\\/\\/api.game2019.net\",\"appkey\":\"221223333221\",\"appsecret\":\"2121212111\"}', '20190411', '{\"code\":0,\"msg\":\"\\u66f4\\u65b0\\u6210\\u529f\"}', '2019-04-11 15:36:12', '1');
INSERT INTO `game_log` VALUES ('10', '1', 'admin', 'app\\admin\\controller\\GameCfg::editEmail', '{\"mail_type\":\"0\",\"mail_smtp_host\":\"smtp.qq.com\",\"mail_smtp_port\":\"465\",\"mail_smtp_user\":\"1000011122\",\"mail_smtp_pass\":\"password\",\"mail_verify_type\":\"0\",\"mail_from\":\"10000@qq.com\"}', '20190411', '{\"code\":0,\"msg\":\"\\u66f4\\u65b0\\u6210\\u529f\"}', '2019-04-11 15:36:18', '1');
INSERT INTO `game_log` VALUES ('11', '1', 'admin', 'app\\admin\\controller\\User::addUser', '{\"username\":\"tttesteststt\",\"email\":\"1112333@qq.com\",\"password\":\"96e79218965eb72c92a549dd5a330112\",\"last_login_ip\":\"0.0.0.0\",\"create_time\":\"2019-04-11 03:43:23\"}', '20190411', null, '2019-04-11 15:43:23', '1');
INSERT INTO `game_log` VALUES ('12', '1', 'admin', 'app\\admin\\controller\\User::editUser', '{\"id\":\"107\",\"username\":\"test1abc\",\"email\":\"1031158073@qq.com\"}', '20190411', null, '2019-04-11 15:45:51', '1');
INSERT INTO `game_log` VALUES ('13', '1', 'admin', 'app\\admin\\controller\\User::editUser', '{\"id\":\"107\",\"username\":\"test1abc\",\"email\":\"1031158073@qq.com\",\"password\":\"111111\",\"check_password\":\"111111\"}', '20190411', null, '2019-04-11 15:46:11', '1');
INSERT INTO `game_log` VALUES ('14', '1', 'admin', 'app\\admin\\controller\\User::deleteUser', '109', '20190411', null, '2019-04-11 15:47:32', '1');
INSERT INTO `game_log` VALUES ('15', '1', 'admin', 'app\\admin\\controller\\User::deleteUser', '{\"id\":\"110\"}', '20190411', null, '2019-04-11 15:48:07', '1');
INSERT INTO `game_log` VALUES ('16', '1', 'admin', 'app\\admin\\controller\\auth::edit', '{\"id\":\"6\",\"title\":\"\\u5907\\u4efd1\",\"name\":\"admin\\/DbManage\\/index\",\"icon\":\"\",\"status\":\"1\",\"sort\":\"0\"}', '20190411', null, '2019-04-11 15:56:44', '1');
INSERT INTO `game_log` VALUES ('17', '1', 'admin', 'app\\admin\\controller\\auth::edit', '{\"id\":\"6\",\"title\":\"\\u5907\\u4efd\",\"name\":\"admin\\/DbManage\\/index\",\"icon\":\"\",\"status\":\"1\",\"sort\":\"0\"}', '20190411', null, '2019-04-11 15:57:06', '1');
INSERT INTO `game_log` VALUES ('18', '1', 'admin', 'app\\admin\\controller\\auth::add', '{\"pid\":\"5\",\"title\":\"\\u6d4b\\u8bd5\",\"name\":\"admin\\/Test\\/index\",\"icon\":\"xe67b;\",\"status\":\"1\",\"sort\":\"0\"}', '20190411', null, '2019-04-11 15:58:28', '1');
INSERT INTO `game_log` VALUES ('19', '1', 'admin', 'app\\admin\\controller\\auth::edit', '{\"id\":\"217\",\"title\":\"\\u6d4b\\u8bd5\",\"name\":\"admin\\/Test\\/index\",\"icon\":\"xe67b\",\"status\":\"1\",\"sort\":\"0\"}', '20190411', null, '2019-04-11 15:58:35', '1');
INSERT INTO `game_log` VALUES ('20', '1', 'admin', 'app\\admin\\controller\\auth::add', '{\"pid\":\"0\",\"title\":\"\\u64cd\\u4f5c\\u65e5\\u5fd7\",\"name\":\"admin\\/Log\\/index\",\"icon\":\"xe6b2\",\"status\":\"1\",\"sort\":\"0\"}', '20190411', null, '2019-04-11 16:00:35', '1');
INSERT INTO `game_log` VALUES ('21', '1', 'admin', 'app\\admin\\controller\\api::edit_app', '{\"name\":\"test11111\",\"app_id\":\"42597693\",\"secret\":\"JGBRGA7EXDY8T5INDBJO6PHCE\"}', '20190411', null, '2019-04-11 16:07:16', '1');

-- ----------------------------
-- Table structure for game_nav
-- ----------------------------
DROP TABLE IF EXISTS `game_nav`;
CREATE TABLE `game_nav` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL COMMENT '父ID',
  `name` varchar(20) NOT NULL COMMENT '导航名称',
  `alias` varchar(20) DEFAULT '' COMMENT '导航别称',
  `link` varchar(255) DEFAULT '' COMMENT '导航链接',
  `icon` varchar(255) DEFAULT '' COMMENT '导航图标',
  `target` varchar(10) DEFAULT '' COMMENT '打开方式',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态  0 隐藏  1 显示',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='导航表';

-- ----------------------------
-- Records of game_nav
-- ----------------------------

-- ----------------------------
-- Table structure for game_options
-- ----------------------------
DROP TABLE IF EXISTS `game_options`;
CREATE TABLE `game_options` (
  `option_value` varchar(255) NOT NULL,
  `option_name` int(255) DEFAULT NULL,
  PRIMARY KEY (`option_value`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of game_options
-- ----------------------------

-- ----------------------------
-- Table structure for game_slide_category
-- ----------------------------
DROP TABLE IF EXISTS `game_slide_category`;
CREATE TABLE `game_slide_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '轮播图分类',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='轮播图分类表';

-- ----------------------------
-- Records of game_slide_category
-- ----------------------------
INSERT INTO `game_slide_category` VALUES ('1', '首页轮播');

-- ----------------------------
-- Table structure for game_system
-- ----------------------------
DROP TABLE IF EXISTS `game_system`;
CREATE TABLE `game_system` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '配置项名称',
  `value` text NOT NULL COMMENT '配置项值',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='系统配置表';

-- ----------------------------
-- Records of game_system
-- ----------------------------
INSERT INTO `game_system` VALUES ('1', 'site_config', 'a:7:{s:10:\"site_title\";s:30:\"Think Admin 后台管理系统\";s:9:\"seo_title\";s:0:\"\";s:11:\"seo_keyword\";s:0:\"\";s:15:\"seo_description\";s:0:\"\";s:14:\"site_copyright\";s:0:\"\";s:8:\"site_icp\";s:0:\"\";s:11:\"site_tongji\";s:0:\"\";}');

-- ----------------------------
-- Table structure for game_user
-- ----------------------------
DROP TABLE IF EXISTS `game_user`;
CREATE TABLE `game_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL COMMENT '用户名',
  `password` varchar(50) NOT NULL COMMENT '密码',
  `mobile` varchar(11) DEFAULT '' COMMENT '手机',
  `email` varchar(50) DEFAULT '' COMMENT '邮箱',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '用户状态  1 正常  2 禁止',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `last_login_time` datetime DEFAULT NULL COMMENT '最后登陆时间',
  `last_login_ip` varchar(50) DEFAULT '' COMMENT '最后登录IP',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=111 DEFAULT CHARSET=utf8 COMMENT='用户表';

-- ----------------------------
-- Records of game_user
-- ----------------------------
INSERT INTO `game_user` VALUES ('1', 'admin', 'e10adc3949ba59abbe56e057f20f883e', '', 'test@qq.com', '1', '2018-11-08 10:27:39', '2019-04-11 11:23:01', '127.0.0.1');
INSERT INTO `game_user` VALUES ('107', 'test1abc', '96e79218965eb72c92a549dd5a330112', '', '1031158073@qq.com', '1', '2019-04-02 09:48:39', null, '0.0.0.0');
INSERT INTO `game_user` VALUES ('108', 'test222bd', '1bbd886460827015e5d605ed44252251', '', '1137788015@qq.com', '1', '2019-04-10 02:00:26', null, '0.0.0.0');

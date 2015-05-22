/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1
Source Server Version : 50520
Source Host           : localhost:3306
Source Database       : dishes

Target Server Type    : MYSQL
Target Server Version : 50520
File Encoding         : 65001

Date: 2014-03-14 12:06:14
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `mx_admin`
-- ----------------------------
DROP TABLE IF EXISTS `mx_admin`;
CREATE TABLE `mx_admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `adminuser` char(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '管理员帐号',
  `password` char(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '密码',
  `nickname` varchar(10) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '昵称',
  `groupid` tinyint(3) unsigned NOT NULL DEFAULT '2' COMMENT '管理员类型，1：超级管理员，2：普通管理员',
  `permission` varchar(150) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '可操作权限',
  `floor_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT '0' COMMENT '管理员可管理的区域表id集合，如：1,2,3,4,5',
  `email` varchar(50) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '管理员邮箱',
  `phone` char(11) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '手机号',
  `tel` char(13) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '座机',
  `createtime` int(11) unsigned DEFAULT '0' COMMENT '创建时间',
  `updatetime` int(11) unsigned DEFAULT '0' COMMENT '修改时间',
  `roleid` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态，1：可用，0：禁止',
  `logintime` int(11) unsigned DEFAULT '0' COMMENT '登录时间',
  `lasttime` int(11) unsigned DEFAULT '0' COMMENT '上次登录时间',
  `logincount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '登录次数',
  `loginip` char(20) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '登录ip',
  PRIMARY KEY (`id`,`adminuser`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='系统管理员表';

-- ----------------------------
-- Records of mx_admin
-- ----------------------------
INSERT INTO `mx_admin` VALUES ('1', 'admin', '4f0eb195353edae8138280262c9b0126', '超级管理员12', '1', '', '3,7', '1212334', '88888812', '8888888124', '1231', null, '1', '1394769315', '1394767377', '113', '127.0.0.1');
INSERT INTO `mx_admin` VALUES ('45', 'cesfa', '41432f1cb4ba8532107162d4e4514b40', '方', '1', '2,12,13,14,15,16,17,5,24,25,26,6,27,28,29,30', '1,3', null, '', '', '1393834665', '1393905466', '1', '1393835183', '0', '1', '127.0.0.1');
INSERT INTO `mx_admin` VALUES ('46', 'test', '4f0eb195353edae8138280262c9b0126', '测试帐号', '1', '', '8', null, '12312312312', '3123123123', '1393924980', '1394082830', '1', '1394615362', '1394081591', '10', '127.0.0.1');
INSERT INTO `mx_admin` VALUES ('47', '123123', '4f0eb195353edae8138280262c9b0126', '123', '2', '2,12,13,14,15,16,17', '3', null, '123', '123', '1394084741', '1394158944', '1', '1394181890', '1394158934', '2', '127.0.0.1');
INSERT INTO `mx_admin` VALUES ('48', '我试试\'', '4f0eb195353edae8138280262c9b0126', '123', '1', '', null, null, '123', '123', '1394607396', '0', '1', '1394607556', '1394607488', '3', '127.0.0.1');

-- ----------------------------
-- Table structure for `mx_floor`
-- ----------------------------
DROP TABLE IF EXISTS `mx_floor`;
CREATE TABLE `mx_floor` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '楼层/区域名称',
  `adminid` varchar(255) COLLATE utf8_unicode_ci DEFAULT '0' COMMENT '操作人id集合；如：1,2,3,4',
  `parentid` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '父类id，无父类为0；',
  `createtime` int(11) unsigned DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='楼层/区域表';

-- ----------------------------
-- Records of mx_floor
-- ----------------------------
INSERT INTO `mx_floor` VALUES ('1', '一楼', '0', '0', '1394697490');
INSERT INTO `mx_floor` VALUES ('2', '包厢', '1', '1', '0');
INSERT INTO `mx_floor` VALUES ('3', '大厅', '0,47,1,45', '1', '1394703067');
INSERT INTO `mx_floor` VALUES ('4', '二楼1111', '1', '0', '1394533637');
INSERT INTO `mx_floor` VALUES ('5', '大厅', '1', '4', '0');
INSERT INTO `mx_floor` VALUES ('6', '包厢', '0', '4', '1394078140');
INSERT INTO `mx_floor` VALUES ('7', 'ces', '0,1', '1', '1394703139');
INSERT INTO `mx_floor` VALUES ('8', '22', '0', '4', '1394703158');

-- ----------------------------
-- Table structure for `mx_logs`
-- ----------------------------
DROP TABLE IF EXISTS `mx_logs`;
CREATE TABLE `mx_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `adminid` int(10) unsigned NOT NULL COMMENT '管理员id',
  `loginip` char(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '登录ip',
  `logintime` int(11) unsigned DEFAULT NULL COMMENT '登录时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of mx_logs
-- ----------------------------
INSERT INTO `mx_logs` VALUES ('1', '1', '127.0.0.1', '1393815220');
INSERT INTO `mx_logs` VALUES ('2', '1', '127.0.0.1', '1393816660');
INSERT INTO `mx_logs` VALUES ('3', '1', '127.0.0.1', '1393816790');
INSERT INTO `mx_logs` VALUES ('4', '35', '127.0.0.1', '1393832581');
INSERT INTO `mx_logs` VALUES ('5', '43', '127.0.0.1', '1393834738');
INSERT INTO `mx_logs` VALUES ('6', '45', '127.0.0.1', '1393835183');
INSERT INTO `mx_logs` VALUES ('7', '1', '127.0.0.1', '1393839352');
INSERT INTO `mx_logs` VALUES ('8', '1', '127.0.0.1', '1393839365');
INSERT INTO `mx_logs` VALUES ('9', '1', '127.0.0.1', '1393892892');
INSERT INTO `mx_logs` VALUES ('10', '1', '127.0.0.1', '1393892909');
INSERT INTO `mx_logs` VALUES ('11', '1', '127.0.0.1', '1393918939');
INSERT INTO `mx_logs` VALUES ('12', '46', '127.0.0.1', '1393925321');
INSERT INTO `mx_logs` VALUES ('13', '46', '127.0.0.1', '1393927193');
INSERT INTO `mx_logs` VALUES ('14', '46', '127.0.0.1', '1393980417');
INSERT INTO `mx_logs` VALUES ('15', '46', '127.0.0.1', '1393981971');
INSERT INTO `mx_logs` VALUES ('16', '46', '127.0.0.1', '1394014345');
INSERT INTO `mx_logs` VALUES ('17', '46', '127.0.0.1', '1394014523');
INSERT INTO `mx_logs` VALUES ('18', '46', '127.0.0.1', '1394014535');
INSERT INTO `mx_logs` VALUES ('19', '46', '127.0.0.1', '1394068093');
INSERT INTO `mx_logs` VALUES ('20', '1', '127.0.0.1', '1394069897');
INSERT INTO `mx_logs` VALUES ('21', '1', '127.0.0.1', '1394081215');
INSERT INTO `mx_logs` VALUES ('22', '46', '127.0.0.1', '1394081591');
INSERT INTO `mx_logs` VALUES ('23', '1', '127.0.0.1', '1394081639');
INSERT INTO `mx_logs` VALUES ('24', '1', '127.0.0.1', '1394152328');
INSERT INTO `mx_logs` VALUES ('25', '1', '127.0.0.1', '1394158724');
INSERT INTO `mx_logs` VALUES ('26', '1', '127.0.0.1', '1394158791');
INSERT INTO `mx_logs` VALUES ('27', '47', '127.0.0.1', '1394158934');
INSERT INTO `mx_logs` VALUES ('28', '47', '127.0.0.1', '1394181890');
INSERT INTO `mx_logs` VALUES ('29', '1', '127.0.0.1', '1394188062');
INSERT INTO `mx_logs` VALUES ('30', '1', '127.0.0.1', '1394239071');
INSERT INTO `mx_logs` VALUES ('31', '1', '127.0.0.1', '1394417220');
INSERT INTO `mx_logs` VALUES ('32', '1', '127.0.0.1', '1394422686');
INSERT INTO `mx_logs` VALUES ('33', '1', '127.0.0.1', '1394441018');
INSERT INTO `mx_logs` VALUES ('34', '1', '127.0.0.1', '1394443568');
INSERT INTO `mx_logs` VALUES ('35', '1', '127.0.0.1', '1394500605');
INSERT INTO `mx_logs` VALUES ('36', '1', '127.0.0.1', '1394501710');
INSERT INTO `mx_logs` VALUES ('37', '1', '127.0.0.1', '1394502771');
INSERT INTO `mx_logs` VALUES ('38', '1', '127.0.0.1', '1394584511');
INSERT INTO `mx_logs` VALUES ('39', '1', '127.0.0.1', '1394594022');
INSERT INTO `mx_logs` VALUES ('40', '1', '127.0.0.1', '1394603194');
INSERT INTO `mx_logs` VALUES ('41', '1', '127.0.0.1', '1394603280');
INSERT INTO `mx_logs` VALUES ('42', '1', '127.0.0.1', '1394607413');
INSERT INTO `mx_logs` VALUES ('43', '48', '127.0.0.1', '1394607450');
INSERT INTO `mx_logs` VALUES ('44', '48', '127.0.0.1', '1394607488');
INSERT INTO `mx_logs` VALUES ('45', '48', '127.0.0.1', '1394607555');
INSERT INTO `mx_logs` VALUES ('46', '1', '127.0.0.1', '1394615018');
INSERT INTO `mx_logs` VALUES ('47', '1', '127.0.0.1', '1394615038');
INSERT INTO `mx_logs` VALUES ('48', '1', '127.0.0.1', '1394615356');
INSERT INTO `mx_logs` VALUES ('49', '46', '127.0.0.1', '1394615362');
INSERT INTO `mx_logs` VALUES ('50', '1', '127.0.0.1', '1394615459');
INSERT INTO `mx_logs` VALUES ('51', '1', '127.0.0.1', '1394673619');
INSERT INTO `mx_logs` VALUES ('52', '1', '127.0.0.1', '1394679190');
INSERT INTO `mx_logs` VALUES ('53', '1', '127.0.0.1', '1394688804');
INSERT INTO `mx_logs` VALUES ('54', '1', '127.0.0.1', '1394690387');
INSERT INTO `mx_logs` VALUES ('55', '1', '127.0.0.1', '1394690824');
INSERT INTO `mx_logs` VALUES ('56', '1', '127.0.0.1', '1394690985');
INSERT INTO `mx_logs` VALUES ('57', '1', '127.0.0.1', '1394691050');
INSERT INTO `mx_logs` VALUES ('58', '1', '127.0.0.1', '1394691629');
INSERT INTO `mx_logs` VALUES ('59', '1', '127.0.0.1', '1394695052');
INSERT INTO `mx_logs` VALUES ('60', '1', '127.0.0.1', '1394696644');
INSERT INTO `mx_logs` VALUES ('61', '1', '127.0.0.1', '1394697694');
INSERT INTO `mx_logs` VALUES ('62', '1', '127.0.0.1', '1394697733');
INSERT INTO `mx_logs` VALUES ('63', '1', '127.0.0.1', '1394697881');
INSERT INTO `mx_logs` VALUES ('64', '1', '127.0.0.1', '1394698197');
INSERT INTO `mx_logs` VALUES ('65', '1', '127.0.0.1', '1394698232');
INSERT INTO `mx_logs` VALUES ('66', '1', '127.0.0.1', '1394698366');
INSERT INTO `mx_logs` VALUES ('67', '1', '127.0.0.1', '1394698764');
INSERT INTO `mx_logs` VALUES ('68', '1', '127.0.0.1', '1394704978');
INSERT INTO `mx_logs` VALUES ('69', '1', '127.0.0.1', '1394757135');
INSERT INTO `mx_logs` VALUES ('70', '1', '127.0.0.1', '1394764078');
INSERT INTO `mx_logs` VALUES ('71', '1', '127.0.0.1', '1394767105');
INSERT INTO `mx_logs` VALUES ('72', '1', '127.0.0.1', '1394767377');
INSERT INTO `mx_logs` VALUES ('73', '1', '127.0.0.1', '1394769315');

-- ----------------------------
-- Table structure for `mx_material`
-- ----------------------------
DROP TABLE IF EXISTS `mx_material`;
CREATE TABLE `mx_material` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `img` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='素材表';

-- ----------------------------
-- Records of mx_material
-- ----------------------------
INSERT INTO `mx_material` VALUES ('1', '222', './Public/upload/39314913938961912.jpg|./Public/upload/39314913938961912.jpg|./Public/upload/39314913938961912.jpg|./Public/upload/39314913938961912.jpg');
INSERT INTO `mx_material` VALUES ('2', '红烧肉', './upload/13938953202.jpg');
INSERT INTO `mx_material` VALUES ('3', '2222', './Public/upload/39314913938961912.jpg');
INSERT INTO `mx_material` VALUES ('4', '222222', './Public/upload/39314913938961912.jpg|./Public/upload/39314913938961912.jpg|./Public/upload/39314913938961912.jpg|./Public/upload/39314913938961912.jpg');

-- ----------------------------
-- Table structure for `mx_menu`
-- ----------------------------
DROP TABLE IF EXISTS `mx_menu`;
CREATE TABLE `mx_menu` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `name` varchar(20) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '菜品名称',
  `menutypeid` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '菜品类型；如：1为热菜',
  `content` text COLLATE utf8_unicode_ci COMMENT '菜品介绍',
  `img` varchar(255) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '源文件路径',
  `file_small` char(100) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '菜品小图',
  `file_big` char(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '菜品大图',
  `price` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '价格',
  `unit` varchar(5) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '计数单位',
  `recommend` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否为推荐，0:不推荐，1:推荐',
  `createtime` int(11) unsigned DEFAULT '0' COMMENT '创建时间',
  `updatetime` int(11) unsigned DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='菜谱表';

-- ----------------------------
-- Records of mx_menu
-- ----------------------------
INSERT INTO `mx_menu` VALUES ('1', '水煮鱼', '3', '新鲜活鱼', null, '', '', '454.00', '份', '0', '1354351567', '1354351567');
INSERT INTO `mx_menu` VALUES ('2', '12312', '7', '123', '/Public/upload/887164-1394679216.png', '', null, '31.00', '撒旦法', '1', '1394679216', '0');

-- ----------------------------
-- Table structure for `mx_menustype`
-- ----------------------------
DROP TABLE IF EXISTS `mx_menustype`;
CREATE TABLE `mx_menustype` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `name` varchar(10) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '分类名称',
  `content` text COLLATE utf8_unicode_ci COMMENT '分类介绍',
  `img` varchar(255) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '分类图片',
  `parentid` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '父级id',
  `createtime` int(11) unsigned DEFAULT '0' COMMENT '创建时间',
  `updatetime` int(11) unsigned DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='菜谱分类表';

-- ----------------------------
-- Records of mx_menustype
-- ----------------------------
INSERT INTO `mx_menustype` VALUES ('1', '家常菜谱', null, '/Public/upload/714895-1394606413.jpg', '0', '1394606413', '0');
INSERT INTO `mx_menustype` VALUES ('2', '中华菜系', null, '/Public/upload/944771-1394606481.jpg', '0', '1394606481', '0');
INSERT INTO `mx_menustype` VALUES ('3', '川菜', null, '/Public/upload/906875-1394606659.png', '2', '1394606659', '0');
INSERT INTO `mx_menustype` VALUES ('4', '外国菜谱', null, '/Public/upload/240125-1394606851.jpg', '0', '1394606851', '0');
INSERT INTO `mx_menustype` VALUES ('5', '各地小吃', null, '/Public/upload/395744-1394606887.jpg', '0', '1394606887', '0');
INSERT INTO `mx_menustype` VALUES ('6', '烘焙', null, '/Public/upload/281085-1394606913.jpg', '0', '1394606913', '0');
INSERT INTO `mx_menustype` VALUES ('7', '家常菜', null, '/Public/upload/779386-1394606970.jpg', '1', '1394606970', '0');
INSERT INTO `mx_menustype` VALUES ('8', '私家菜', null, '/Public/upload/723380-1394606994.jpg', '1', '1394606994', '0');
INSERT INTO `mx_menustype` VALUES ('9', '凉菜', null, '/Public/upload/739083-1394607016.jpg', '1', '1394607016', '0');
INSERT INTO `mx_menustype` VALUES ('10', '海鲜', null, '/Public/upload/927320-1394607039.jpg', '1', '1394607039', '0');
INSERT INTO `mx_menustype` VALUES ('11', '热菜', null, '/Public/upload/104556-1394607066.jpg', '1', '1394607066', '0');
INSERT INTO `mx_menustype` VALUES ('12', '汤粥', null, '/Public/upload/110748-1394607111.jpg', '1', '1394607111', '0');
INSERT INTO `mx_menustype` VALUES ('13', '素食', null, '/Public/upload/118980-1394607134.jpg', '1', '1394607134', '0');
INSERT INTO `mx_menustype` VALUES ('14', '酱料蘸料', null, '/Public/upload/591035-1394607173.jpg', '1', '1394607173', '0');
INSERT INTO `mx_menustype` VALUES ('15', '简易菜（微波炉）', null, '/Public/upload/812890-1394607243.jpg', '1', '1394607243', '0');
INSERT INTO `mx_menustype` VALUES ('16', '火锅底料', null, '/Public/upload/748046-1394607271.jpg', '1', '1394607271', '0');
INSERT INTO `mx_menustype` VALUES ('17', '甜品点心', null, '/Public/upload/208458-1394607297.jpg', '1', '1394607297', '0');
INSERT INTO `mx_menustype` VALUES ('18', '糕点主食', null, '/Public/upload/615122-1394607319.jpg', '1', '1394607319', '0');
INSERT INTO `mx_menustype` VALUES ('19', '干果菜谱', null, '/Public/upload/378039-1394607360.jpg', '1', '1394607360', '0');
INSERT INTO `mx_menustype` VALUES ('20', '卤酱', null, '/Public/upload/559221-1394607406.jpg', '1', '1394607406', '0');
INSERT INTO `mx_menustype` VALUES ('21', '时尚饮品', null, '/Public/upload/649172-1394607435.jpg', '1', '1394607435', '0');
INSERT INTO `mx_menustype` VALUES ('22', '湘菜', null, '/Public/upload/343149-1394607469.jpg', '2', '1394607469', '0');
INSERT INTO `mx_menustype` VALUES ('23', '粤菜', null, '/Public/upload/863754-1394607496.jpg', '2', '1394607496', '0');
INSERT INTO `mx_menustype` VALUES ('24', '湘菜', null, '/Public/upload/151259-1394607568.jpg', '2', '1394607568', '0');
INSERT INTO `mx_menustype` VALUES ('25', '湘菜', null, '/Public/upload/401068-1394607622.jpg', '2', '1394607622', '0');

-- ----------------------------
-- Table structure for `mx_nav`
-- ----------------------------
DROP TABLE IF EXISTS `mx_nav`;
CREATE TABLE `mx_nav` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '导航id',
  `val` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '导航Action',
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '导航名字',
  `show` tinyint(3) unsigned NOT NULL COMMENT '是否显示,0:需要权限过滤，1:无需过滤,直接显示',
  `tplshow` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '模版中是否显示，0:不现实，1:默认显示',
  `parentid` int(10) unsigned NOT NULL COMMENT '父id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='导航以及权限控制类';

-- ----------------------------
-- Records of mx_nav
-- ----------------------------
INSERT INTO `mx_nav` VALUES ('1', 'Index', '系统首页', '1', '1', '0');
INSERT INTO `mx_nav` VALUES ('2', 'Menu', '菜谱管理', '0', '1', '0');
INSERT INTO `mx_nav` VALUES ('3', 'Stream', '流水统计', '0', '1', '0');
INSERT INTO `mx_nav` VALUES ('4', 'Personal', '个人信息', '1', '1', '0');
INSERT INTO `mx_nav` VALUES ('5', 'System', '系统设置', '0', '1', '0');
INSERT INTO `mx_nav` VALUES ('6', 'Table', '餐桌设置', '0', '1', '0');
INSERT INTO `mx_nav` VALUES ('7', 'tabList', '桌号列表', '1', '1', '1');
INSERT INTO `mx_nav` VALUES ('8', 'free', '空闲中桌位', '1', '1', '1');
INSERT INTO `mx_nav` VALUES ('9', 'employ', '使用中桌位', '1', '1', '1');
INSERT INTO `mx_nav` VALUES ('11', 'invoicing', '已结账订单', '1', '1', '1');
INSERT INTO `mx_nav` VALUES ('12', 'typeList', '分类列表', '0', '1', '2');
INSERT INTO `mx_nav` VALUES ('13', 'addType', '添加分类', '0', '1', '2');
INSERT INTO `mx_nav` VALUES ('14', 'menuList', '菜谱列表', '0', '1', '2');
INSERT INTO `mx_nav` VALUES ('15', 'editMenu', '添加菜谱', '0', '1', '2');
INSERT INTO `mx_nav` VALUES ('16', 'hotMenu', '热卖菜', '0', '1', '2');
INSERT INTO `mx_nav` VALUES ('17', 'material', '素材列表', '0', '1', '2');
INSERT INTO `mx_nav` VALUES ('18', 'day', '今日流水', '0', '1', '3');
INSERT INTO `mx_nav` VALUES ('19', 'week', '周流水', '0', '1', '3');
INSERT INTO `mx_nav` VALUES ('20', 'month', '月流水', '0', '1', '3');
INSERT INTO `mx_nav` VALUES ('21', 'all', '总流水', '0', '1', '3');
INSERT INTO `mx_nav` VALUES ('22', 'password', '修改密码', '1', '1', '4');
INSERT INTO `mx_nav` VALUES ('23', 'info', '修改个人信息', '1', '1', '4');
INSERT INTO `mx_nav` VALUES ('24', 'adminList', '操作员', '0', '1', '5');
INSERT INTO `mx_nav` VALUES ('25', 'editAdmin', '添加操作员', '0', '1', '5');
INSERT INTO `mx_nav` VALUES ('26', 'logs', '日志', '0', '1', '5');
INSERT INTO `mx_nav` VALUES ('27', 'tableList', '餐桌列表', '0', '1', '6');
INSERT INTO `mx_nav` VALUES ('28', 'areaList', '区域列表', '0', '1', '6');
INSERT INTO `mx_nav` VALUES ('29', 'editArea', '添加区域', '0', '1', '6');
INSERT INTO `mx_nav` VALUES ('30', 'editTable', '添加餐桌', '0', '1', '6');
INSERT INTO `mx_nav` VALUES ('31', 'reserve', '已预定桌位', '1', '1', '1');
INSERT INTO `mx_nav` VALUES ('32', 'nowStore', '本店设置', '0', '1', '5');
INSERT INTO `mx_nav` VALUES ('33', 'addMaterial', '添加素材', '0', '1', '2');
INSERT INTO `mx_nav` VALUES ('34', 'modifStatus', '更改餐桌状态', '0', '0', '6');

-- ----------------------------
-- Table structure for `mx_options`
-- ----------------------------
DROP TABLE IF EXISTS `mx_options`;
CREATE TABLE `mx_options` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `optionname` varchar(255) DEFAULT NULL COMMENT '配置名',
  `optionvalue` text COMMENT '值,数组序列化',
  `optiondesc` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='系统配置表';

-- ----------------------------
-- Records of mx_options
-- ----------------------------
INSERT INTO `mx_options` VALUES ('1', 'now_store', 'a:4:{s:4:\"name\";s:9:\"大酒店\";s:7:\"content\";s:15:\"详细介绍；\";s:4:\"logo\";s:37:\"./Public/upload/736967-1394098024.png\";s:6:\"imgTag\";i:2;}', '本店设置');

-- ----------------------------
-- Table structure for `mx_orderform`
-- ----------------------------
DROP TABLE IF EXISTS `mx_orderform`;
CREATE TABLE `mx_orderform` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `t_id` int(10) unsigned NOT NULL COMMENT '桌号自增id',
  `price` decimal(10,2) unsigned NOT NULL COMMENT '总计',
  `starttime` int(11) unsigned DEFAULT '0' COMMENT '点餐开始时间',
  `endtime` int(11) unsigned DEFAULT '0' COMMENT '订单结账时间',
  `createtime` int(11) unsigned DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='流水统计表';

-- ----------------------------
-- Records of mx_orderform
-- ----------------------------
INSERT INTO `mx_orderform` VALUES ('1', '1', '300.00', '1393576487', '1393576487', '1394076573');
INSERT INTO `mx_orderform` VALUES ('2', '2', '123.00', '0', '0', '1394076573');
INSERT INTO `mx_orderform` VALUES ('3', '2', '321.00', '0', '0', '1394076573');
INSERT INTO `mx_orderform` VALUES ('4', '1', '13.00', '0', '0', '1394076573');
INSERT INTO `mx_orderform` VALUES ('5', '1', '321.00', '0', '0', '1394076573');
INSERT INTO `mx_orderform` VALUES ('6', '1', '23.00', '0', '0', '1394076573');
INSERT INTO `mx_orderform` VALUES ('7', '5', '323.00', '0', '0', '1394076573');
INSERT INTO `mx_orderform` VALUES ('8', '1', '23.00', '0', '0', '1394076573');
INSERT INTO `mx_orderform` VALUES ('9', '1', '3.00', '0', '0', '1394076573');
INSERT INTO `mx_orderform` VALUES ('10', '2', '0.00', '0', '0', '1394076573');
INSERT INTO `mx_orderform` VALUES ('11', '3', '3.00', '0', '0', '1394076573');
INSERT INTO `mx_orderform` VALUES ('12', '1', '2.00', '0', '0', '1394076573');
INSERT INTO `mx_orderform` VALUES ('13', '2', '123.00', '0', '0', '1394076573');
INSERT INTO `mx_orderform` VALUES ('14', '1', '32.00', '0', '0', '1394076573');
INSERT INTO `mx_orderform` VALUES ('15', '2', '31.00', '0', '0', '1394076573');
INSERT INTO `mx_orderform` VALUES ('17', '12', '7718.00', '1394414689', '1394445539', '1394445539');
INSERT INTO `mx_orderform` VALUES ('19', '12', '7718.00', '1394414689', '1394445581', '1394445581');
INSERT INTO `mx_orderform` VALUES ('20', '12', '7718.00', '1394414689', '1394445614', '1394445614');
INSERT INTO `mx_orderform` VALUES ('21', '12', '7718.00', '1394414689', '1394445620', '1394445620');
INSERT INTO `mx_orderform` VALUES ('22', '12', '7718.00', '1394414689', '1394445640', '1394445640');
INSERT INTO `mx_orderform` VALUES ('23', '12', '7718.00', '1394414689', '1394445723', '1394445723');
INSERT INTO `mx_orderform` VALUES ('24', '16', '454.00', '1394274244', '1394445907', '1394445907');
INSERT INTO `mx_orderform` VALUES ('25', '16', '454.00', '1394274244', '1394446832', '1394446832');
INSERT INTO `mx_orderform` VALUES ('26', '10', '2724.00', '1394433053', '1394446961', '1394446961');
INSERT INTO `mx_orderform` VALUES ('27', '15', '908.00', '1394436641', '1394502037', '1394502037');
INSERT INTO `mx_orderform` VALUES ('28', '15', '1962.00', '0', '1394517915', '1394517915');
INSERT INTO `mx_orderform` VALUES ('29', '17', '3632.00', '1394440048', '1394519423', '1394519423');
INSERT INTO `mx_orderform` VALUES ('30', '17', '908.00', '1394519435', '1394532685', '1394532685');
INSERT INTO `mx_orderform` VALUES ('31', '5', '908.00', '1394528606', '1394532688', '1394532688');
INSERT INTO `mx_orderform` VALUES ('32', '17', '908.00', '1394519435', '1394532759', '1394532759');
INSERT INTO `mx_orderform` VALUES ('33', '17', '908.00', '1394519435', '1394532779', '1394532779');
INSERT INTO `mx_orderform` VALUES ('34', '17', '908.00', '1394519435', '1394532802', '1394532802');
INSERT INTO `mx_orderform` VALUES ('35', '5', '908.00', '1394528606', '1394532809', '1394532809');
INSERT INTO `mx_orderform` VALUES ('36', '5', '2270.00', '1394532819', '1394533168', '1394533168');
INSERT INTO `mx_orderform` VALUES ('37', '5', '2270.00', '1394532819', '1394533205', '1394533205');
INSERT INTO `mx_orderform` VALUES ('38', '5', '2270.00', '1394532819', '1394533221', '1394533221');
INSERT INTO `mx_orderform` VALUES ('39', '5', '2270.00', '1394532819', '1394533244', '1394533244');
INSERT INTO `mx_orderform` VALUES ('40', '5', '2270.00', '1394532819', '1394533292', '1394533292');
INSERT INTO `mx_orderform` VALUES ('41', '5', '2393.00', '1394532819', '1394533314', '1394533314');
INSERT INTO `mx_orderform` VALUES ('42', '5', '2393.00', '1394532819', '1394533405', '1394533405');
INSERT INTO `mx_orderform` VALUES ('43', '5', '2393.00', '1394532819', '1394533414', '1394533414');
INSERT INTO `mx_orderform` VALUES ('44', '17', '454.00', '1394590301', '1394590434', '1394590434');
INSERT INTO `mx_orderform` VALUES ('45', '17', '3178.00', '1394590451', '1394595313', '1394595313');
INSERT INTO `mx_orderform` VALUES ('46', '20', '31.00', '1394704966', '1394705017', '1394705017');

-- ----------------------------
-- Table structure for `mx_reserve`
-- ----------------------------
DROP TABLE IF EXISTS `mx_reserve`;
CREATE TABLE `mx_reserve` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '预定时间',
  `code` char(5) NOT NULL DEFAULT '' COMMENT '验证码',
  `tab_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '桌子的id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='预定表';

-- ----------------------------
-- Records of mx_reserve
-- ----------------------------

-- ----------------------------
-- Table structure for `mx_settle`
-- ----------------------------
DROP TABLE IF EXISTS `mx_settle`;
CREATE TABLE `mx_settle` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `t_id` int(10) unsigned NOT NULL COMMENT '桌号自增id',
  `num` int(3) unsigned NOT NULL COMMENT '桌位号',
  `floorname` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '桌位所属楼层/区域名称',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '请求类型；1：下单请求，2：结账请求',
  `createtime` int(11) unsigned DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='下单/结账请求表';

-- ----------------------------
-- Records of mx_settle
-- ----------------------------
INSERT INTO `mx_settle` VALUES ('1', '12', '8', '一楼&nbsp;>>大厅', '1', '1394761323');
INSERT INTO `mx_settle` VALUES ('2', '15', '11', '一楼&nbsp;>>大厅', '1', '1394761369');

-- ----------------------------
-- Table structure for `mx_stream`
-- ----------------------------
DROP TABLE IF EXISTS `mx_stream`;
CREATE TABLE `mx_stream` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '价格',
  `amount` tinyint(3) unsigned DEFAULT '1' COMMENT '数量',
  `u_id` int(10) unsigned NOT NULL COMMENT '菜谱id',
  `menutypeid` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '菜谱分类id',
  `uname` varchar(20) NOT NULL COMMENT '菜名',
  `o_id` int(10) unsigned NOT NULL COMMENT '流水统计表id',
  `t_id` int(10) unsigned NOT NULL COMMENT '桌号表id',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否为追加；0：不追加，1：追加',
  `createtime` int(11) unsigned DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8 COMMENT='流水详细表';

-- ----------------------------
-- Records of mx_stream
-- ----------------------------
INSERT INTO `mx_stream` VALUES ('1', '15.00', '1', '3', '0', '辣子鸡', '1', '1', '0', '1393576487');
INSERT INTO `mx_stream` VALUES ('2', '50.00', '2', '2', '0', '辣子鸭', '1', '1', '0', '1393576487');
INSERT INTO `mx_stream` VALUES ('3', '454.00', '8', '1', '0', '水煮鱼', '0', '12', '0', '1394445581');
INSERT INTO `mx_stream` VALUES ('4', '454.00', '1', '1', '0', '水煮鱼', '0', '12', '0', '1394445581');
INSERT INTO `mx_stream` VALUES ('5', '454.00', '1', '1', '0', '水煮鱼', '0', '12', '0', '1394445581');
INSERT INTO `mx_stream` VALUES ('6', '454.00', '1', '1', '0', '水煮鱼', '0', '12', '0', '1394445581');
INSERT INTO `mx_stream` VALUES ('7', '454.00', '1', '1', '0', '水煮鱼', '0', '12', '0', '1394445581');
INSERT INTO `mx_stream` VALUES ('8', '454.00', '1', '1', '0', '水煮鱼', '0', '12', '0', '1394445581');
INSERT INTO `mx_stream` VALUES ('9', '454.00', '1', '1', '0', '水煮鱼', '0', '12', '0', '1394445581');
INSERT INTO `mx_stream` VALUES ('10', '454.00', '1', '1', '0', '水煮鱼', '0', '12', '0', '1394445581');
INSERT INTO `mx_stream` VALUES ('11', '454.00', '1', '1', '0', '水煮鱼', '0', '12', '0', '1394445581');
INSERT INTO `mx_stream` VALUES ('12', '454.00', '1', '1', '0', '水煮鱼', '0', '12', '1', '1394445581');
INSERT INTO `mx_stream` VALUES ('13', '454.00', '8', '1', '0', '水煮鱼', '20', '12', '0', '1394445614');
INSERT INTO `mx_stream` VALUES ('14', '454.00', '1', '1', '0', '水煮鱼', '20', '12', '0', '1394445614');
INSERT INTO `mx_stream` VALUES ('15', '454.00', '1', '1', '0', '水煮鱼', '20', '12', '0', '1394445614');
INSERT INTO `mx_stream` VALUES ('16', '454.00', '1', '1', '0', '水煮鱼', '20', '12', '0', '1394445614');
INSERT INTO `mx_stream` VALUES ('17', '454.00', '1', '1', '0', '水煮鱼', '20', '12', '0', '1394445614');
INSERT INTO `mx_stream` VALUES ('18', '454.00', '1', '1', '0', '水煮鱼', '20', '12', '0', '1394445614');
INSERT INTO `mx_stream` VALUES ('19', '454.00', '1', '1', '0', '水煮鱼', '20', '12', '0', '1394445614');
INSERT INTO `mx_stream` VALUES ('20', '454.00', '1', '1', '0', '水煮鱼', '20', '12', '0', '1394445614');
INSERT INTO `mx_stream` VALUES ('21', '454.00', '1', '1', '0', '水煮鱼', '20', '12', '0', '1394445614');
INSERT INTO `mx_stream` VALUES ('22', '454.00', '1', '1', '0', '水煮鱼', '20', '12', '1', '1394445614');
INSERT INTO `mx_stream` VALUES ('23', '454.00', '8', '1', '0', '水煮鱼', '21', '12', '0', '1394445620');
INSERT INTO `mx_stream` VALUES ('24', '454.00', '1', '1', '0', '水煮鱼', '21', '12', '0', '1394445620');
INSERT INTO `mx_stream` VALUES ('25', '454.00', '1', '1', '0', '水煮鱼', '21', '12', '0', '1394445620');
INSERT INTO `mx_stream` VALUES ('26', '454.00', '1', '1', '0', '水煮鱼', '21', '12', '0', '1394445620');
INSERT INTO `mx_stream` VALUES ('27', '454.00', '1', '1', '0', '水煮鱼', '21', '12', '0', '1394445620');
INSERT INTO `mx_stream` VALUES ('28', '454.00', '1', '1', '0', '水煮鱼', '21', '12', '0', '1394445620');
INSERT INTO `mx_stream` VALUES ('29', '454.00', '1', '1', '0', '水煮鱼', '21', '12', '0', '1394445620');
INSERT INTO `mx_stream` VALUES ('30', '454.00', '1', '1', '0', '水煮鱼', '21', '12', '0', '1394445620');
INSERT INTO `mx_stream` VALUES ('31', '454.00', '1', '1', '0', '水煮鱼', '21', '12', '0', '1394445620');
INSERT INTO `mx_stream` VALUES ('32', '454.00', '1', '1', '0', '水煮鱼', '21', '12', '1', '1394445620');
INSERT INTO `mx_stream` VALUES ('33', '454.00', '8', '1', '0', '水煮鱼', '22', '12', '0', '1394445640');
INSERT INTO `mx_stream` VALUES ('34', '454.00', '1', '1', '0', '水煮鱼', '22', '12', '0', '1394445640');
INSERT INTO `mx_stream` VALUES ('35', '454.00', '1', '1', '0', '水煮鱼', '22', '12', '0', '1394445640');
INSERT INTO `mx_stream` VALUES ('36', '454.00', '1', '1', '0', '水煮鱼', '22', '12', '0', '1394445640');
INSERT INTO `mx_stream` VALUES ('37', '454.00', '1', '1', '0', '水煮鱼', '22', '12', '0', '1394445640');
INSERT INTO `mx_stream` VALUES ('38', '454.00', '1', '1', '0', '水煮鱼', '22', '12', '0', '1394445640');
INSERT INTO `mx_stream` VALUES ('39', '454.00', '1', '1', '0', '水煮鱼', '22', '12', '0', '1394445640');
INSERT INTO `mx_stream` VALUES ('40', '454.00', '1', '1', '0', '水煮鱼', '22', '12', '0', '1394445640');
INSERT INTO `mx_stream` VALUES ('41', '454.00', '1', '1', '0', '水煮鱼', '22', '12', '0', '1394445640');
INSERT INTO `mx_stream` VALUES ('42', '454.00', '1', '1', '0', '水煮鱼', '22', '12', '1', '1394445640');
INSERT INTO `mx_stream` VALUES ('43', '454.00', '8', '1', '0', '水煮鱼', '23', '12', '0', '1394445723');
INSERT INTO `mx_stream` VALUES ('44', '454.00', '1', '1', '0', '水煮鱼', '23', '12', '0', '1394445723');
INSERT INTO `mx_stream` VALUES ('45', '454.00', '1', '1', '0', '水煮鱼', '23', '12', '0', '1394445723');
INSERT INTO `mx_stream` VALUES ('46', '454.00', '1', '1', '0', '水煮鱼', '23', '12', '0', '1394445723');
INSERT INTO `mx_stream` VALUES ('47', '454.00', '1', '1', '0', '水煮鱼', '23', '12', '0', '1394445723');
INSERT INTO `mx_stream` VALUES ('48', '454.00', '1', '1', '0', '水煮鱼', '23', '12', '0', '1394445723');
INSERT INTO `mx_stream` VALUES ('49', '454.00', '1', '1', '0', '水煮鱼', '23', '12', '0', '1394445723');
INSERT INTO `mx_stream` VALUES ('50', '454.00', '1', '1', '0', '水煮鱼', '23', '12', '0', '1394445723');
INSERT INTO `mx_stream` VALUES ('51', '454.00', '1', '1', '0', '水煮鱼', '23', '12', '0', '1394445723');
INSERT INTO `mx_stream` VALUES ('52', '454.00', '1', '1', '0', '水煮鱼', '23', '12', '1', '1394445723');
INSERT INTO `mx_stream` VALUES ('53', '454.00', '1', '1', '0', '水煮鱼', '25', '16', '1', '1394446832');
INSERT INTO `mx_stream` VALUES ('54', '454.00', '3', '1', '0', '水煮鱼', '26', '10', '0', '1394446961');
INSERT INTO `mx_stream` VALUES ('55', '454.00', '2', '1', '0', '水煮鱼', '26', '10', '0', '1394446961');
INSERT INTO `mx_stream` VALUES ('56', '454.00', '1', '1', '0', '水煮鱼', '26', '10', '1', '1394446961');
INSERT INTO `mx_stream` VALUES ('57', '454.00', '1', '1', '0', '水煮鱼', '27', '15', '1', '1394502037');
INSERT INTO `mx_stream` VALUES ('58', '454.00', '2', '1', '0', '水煮鱼', '28', '15', '0', '1394517915');
INSERT INTO `mx_stream` VALUES ('59', '454.00', '2', '1', '0', '水煮鱼', '28', '15', '0', '1394517915');
INSERT INTO `mx_stream` VALUES ('60', '454.00', '1', '1', '0', '水煮鱼', '28', '15', '1', '1394517915');
INSERT INTO `mx_stream` VALUES ('61', '454.00', '6', '1', '0', '水煮鱼', '29', '17', '1', '1394519423');
INSERT INTO `mx_stream` VALUES ('62', '454.00', '2', '1', '0', '水煮鱼', '29', '17', '0', '1394519423');
INSERT INTO `mx_stream` VALUES ('63', '454.00', '2', '1', '0', '水煮鱼', '34', '17', '0', '1394532802');
INSERT INTO `mx_stream` VALUES ('64', '454.00', '1', '1', '0', '水煮鱼', '35', '5', '1', '1394532809');
INSERT INTO `mx_stream` VALUES ('65', '454.00', '1', '1', '0', '水煮鱼', '35', '5', '1', '1394532809');
INSERT INTO `mx_stream` VALUES ('66', '454.00', '4', '1', '3', '水煮鱼', '43', '5', '0', '1394533414');
INSERT INTO `mx_stream` VALUES ('67', '454.00', '1', '1', '3', '水煮鱼', '43', '5', '0', '1394533414');
INSERT INTO `mx_stream` VALUES ('68', '454.00', '1', '1', '3', '水煮鱼', '43', '5', '1', '1394533414');
INSERT INTO `mx_stream` VALUES ('69', '454.00', '1', '1', '3', '水煮鱼', '44', '17', '0', '1394590434');
INSERT INTO `mx_stream` VALUES ('70', '454.00', '4', '1', '3', '水煮鱼', '45', '17', '0', '1394595313');
INSERT INTO `mx_stream` VALUES ('71', '454.00', '1', '1', '3', '水煮鱼', '45', '17', '0', '1394595313');
INSERT INTO `mx_stream` VALUES ('72', '454.00', '2', '1', '3', '水煮鱼', '45', '17', '0', '1394595313');
INSERT INTO `mx_stream` VALUES ('73', '31.00', '1', '2', '7', '12312', '46', '20', '0', '1394705017');

-- ----------------------------
-- Table structure for `mx_tab`
-- ----------------------------
DROP TABLE IF EXISTS `mx_tab`;
CREATE TABLE `mx_tab` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `tabname` varchar(30) DEFAULT '' COMMENT '餐桌名称',
  `num` smallint(5) unsigned NOT NULL DEFAULT '1' COMMENT '每个桌的号码',
  `floor_num` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '所属楼层/区域',
  `chair` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '椅子数量',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '桌子的状态  1代表空闲 2预定 3使用中 4整理中',
  `createtime` int(11) unsigned DEFAULT '0' COMMENT '创建时间',
  `updatetime` int(11) unsigned DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COMMENT='桌子表';

-- ----------------------------
-- Records of mx_tab
-- ----------------------------
INSERT INTO `mx_tab` VALUES ('1', '阿萨德发1', '1', '1', '0', '1', '0', null);
INSERT INTO `mx_tab` VALUES ('2', '阿萨德发2', '2', '1', '0', '2', '0', null);
INSERT INTO `mx_tab` VALUES ('3', '阿萨德发3', '3', '1', '0', '1', '0', null);
INSERT INTO `mx_tab` VALUES ('4', '阿萨德发4', '5', '6', '0', '2', '0', '1393903741');
INSERT INTO `mx_tab` VALUES ('5', '123123', '6', '5', '0', '1', '1393901091', '0');
INSERT INTO `mx_tab` VALUES ('10', '123123', '7', '3', '123', '1', '1393903525', '1394617161');
INSERT INTO `mx_tab` VALUES ('11', '123123', '7', '2', '0', '1', '1393903555', '1393903639');
INSERT INTO `mx_tab` VALUES ('12', '123123', '8', '3', '12', '3', '1393903629', '1394617151');
INSERT INTO `mx_tab` VALUES ('13', '123123', '9', '4', '0', '1', '1393903658', '0');
INSERT INTO `mx_tab` VALUES ('14', '123123', '10', '2', '0', '2', '1393903664', '0');
INSERT INTO `mx_tab` VALUES ('15', '123123', '11', '3', '0', '3', '1393903668', '0');
INSERT INTO `mx_tab` VALUES ('16', '123123', '12', '1', '0', '1', '1393903671', '0');
INSERT INTO `mx_tab` VALUES ('17', '34123', '13', '3', '12', '1', '1393903722', '0');
INSERT INTO `mx_tab` VALUES ('18', '飞黄腾达', '14', '6', '0', '1', '1393925307', '0');
INSERT INTO `mx_tab` VALUES ('19', 'ceshi', '15', '3', '4', '1', '1394585276', '0');
INSERT INTO `mx_tab` VALUES ('20', '321', '16', '2', '12', '4', '1394585783', '1394586072');

-- ----------------------------
-- Table structure for `mx_temp`
-- ----------------------------
DROP TABLE IF EXISTS `mx_temp`;
CREATE TABLE `mx_temp` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `t_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '桌号自增id',
  `u_id` int(10) unsigned NOT NULL,
  `uname` varchar(20) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '用户的名字',
  `name` varchar(20) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '菜的名字',
  `price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `starttime` int(10) unsigned DEFAULT '0' COMMENT '开始点菜的时间',
  `amount` tinyint(4) DEFAULT '1' COMMENT '菜的数量',
  `menustypeid` int(10) unsigned NOT NULL COMMENT '菜谱分类id',
  `status` tinyint(3) unsigned DEFAULT '0' COMMENT '是否为追加；0：不追加，1：追加',
  `check` tinyint(3) unsigned DEFAULT '0' COMMENT '是否下过订单,0表示没有下订单，1表示已下过，2表示后台确认下单',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=130 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='点临时表';

-- ----------------------------
-- Records of mx_temp
-- ----------------------------
INSERT INTO `mx_temp` VALUES ('127', '12', '2', '用户1', '12312', '31.00', '1394704276', '1', '7', '0', '1');
INSERT INTO `mx_temp` VALUES ('128', '15', '2', '用户1', '12312', '31.00', '1394761357', '2', '7', '0', '1');
INSERT INTO `mx_temp` VALUES ('129', '15', '2', '用户1', '12312', '31.00', '1394761776', '1', '7', '1', '0');

-- ----------------------------
-- Table structure for `mx_user`
-- ----------------------------
DROP TABLE IF EXISTS `mx_user`;
CREATE TABLE `mx_user` (
  `num` smallint(6) unsigned NOT NULL COMMENT '临时用户id',
  `t_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '桌号',
  `uname` varchar(20) NOT NULL DEFAULT '' COMMENT '临时客户的名字'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户id表';

-- ----------------------------
-- Records of mx_user
-- ----------------------------
INSERT INTO `mx_user` VALUES ('1', '17', '用户1');
INSERT INTO `mx_user` VALUES ('1', '12', '用户1');
INSERT INTO `mx_user` VALUES ('2', '12', '用户2');
INSERT INTO `mx_user` VALUES ('1', '15', '用户1');
INSERT INTO `mx_user` VALUES ('1', '10', '用户1');
INSERT INTO `mx_user` VALUES ('1', '11', '用户1');

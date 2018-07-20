CREATE DATABASE /*!32312 IF NOT EXISTS*/`api_scaffolding_v1_0_0` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `api_scaffolding_v1_0_0`;

CREATE TABLE `tbl_user` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '用户编号',
  `account` varchar(128) NOT NULL DEFAULT '' COMMENT '账号',
  `auth_key` varchar(32) NOT NULL DEFAULT '' COMMENT '记住我的认证',
  `password` varchar(128) NOT NULL DEFAULT '' COMMENT '密码',
  `name` varchar(64) NOT NULL DEFAULT '' COMMENT '姓名',
  `sex` tinyint(4) NOT NULL DEFAULT '0' COMMENT '性别(1:男,2:女)',
  `avatar` varchar(128) NOT NULL DEFAULT '' COMMENT '头像',
  `phone` varchar(16) NOT NULL DEFAULT '' COMMENT '手机号码',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态(0:未认证,1:审核通过,2:审核失败)',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `last_login` int(10) NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `is_deleted` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否删除(0:否,1:是)',
  `token` varchar(32) NOT NULL DEFAULT '' COMMENT '令牌',
  `token_expired_time` int(10) NOT NULL DEFAULT '0' COMMENT '令牌过期时间',
  `allowance` int(10) NOT NULL DEFAULT '0' COMMENT '速率限制数量',
  `allowance_updated_at` int(10) NOT NULL DEFAULT '0' COMMENT '速率限制更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_phone` (`phone`) USING BTREE COMMENT '手机号码索引'
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='用户表';

INSERT INTO `tbl_user` (`id`, `account`, `auth_key`, `password`, `name`, `sex`, `avatar`, `phone`, `status`, `create_time`, `update_time`, `last_login`, `is_deleted`, `token`, `token_expired_time`, `allowance`, `allowance_updated_at`) VALUES ('1', '15850243619', '', '$2y$13$mlB2Iqe1GNHFaEvNBxxvBu.OKaQy3FyiSXERPuShzTM1jAS5ZWwbS', 'name123', '2', '123123', '15850243619', '1', '0', '0', '1523946877', '0', '722bd25f87c80de4', '1523947477', '0', '1523946876');

CREATE TABLE `tbl_article` (
	`id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '文章编号',
	`title` varchar(64) NOT NULL DEFAULT '' COMMENT '文章标题',
	`content` text NOT NULL DEFAULT '' COMMENT '文章内容',
	`status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '0：未发布 1：已发布',
	`create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `is_deleted` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否删除(0:否,1:是)',
	PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='文章信息表';

CREATE TABLE `tbl_article_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `article_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '文章编号',
  `title` varchar(64) NOT NULL DEFAULT '' COMMENT '日志标题',
  `content` varchar(500) NOT NULL DEFAULT '' COMMENT '日志内容',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文章日志表';

CREATE TABLE `tbl_admin_user` (
  `user_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '用户编号',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='后台用户扩展表';

/*RBAC*/
CREATE TABLE `auth_rule`
(
   `name`                 varchar(64) NOT NULL,
   `data`                 blob,
   `created_at`           integer,
   `updated_at`           integer,
    PRIMARY KEY (`name`)
) ENGINE InnoDB;

CREATE TABLE `auth_item`
(
   `name`                 varchar(64) NOT NULL,
   `type`                 smallint NOT NULL,
   `description`          text,
   `rule_name`            varchar(64),
   `data`                 blob,
   `created_at`           integer,
   `updated_at`           integer,
   PRIMARY KEY (`name`),
   FOREIGN KEY (`rule_name`) references `auth_rule` (`name`) on delete set null on update cascade,
   key `type` (`type`)
) ENGINE InnoDB;

CREATE TABLE `auth_item_child`
(
   `parent`               varchar(64) NOT NULL,
   `child`                varchar(64) NOT NULL,
   PRIMARY KEY (`parent`, `child`),
   FOREIGN KEY (`parent`) references `auth_item` (`name`) on delete cascade on update cascade,
   FOREIGN KEY (`child`) references `auth_item` (`name`) on delete cascade on update cascade
) ENGINE InnoDB;

CREATE TABLE `auth_assignment`
(
   `item_name`            varchar(64) NOT NULL,
   `user_id`              varchar(64) NOT NULL,
   `created_at`           integer,
   PRIMARY KEY (`item_name`, `user_id`),
   FOREIGN KEY (`item_name`) references `auth_item` (`name`) on delete cascade on update cascade,
   key `auth_assignment_user_id_idx` (`user_id`)
) ENGINE InnoDB;

INSERT INTO `tbl_admin_user` VALUES ('1');

INSERT INTO `auth_assignment` VALUES ('管理员', '1', '1532066539');

INSERT INTO `auth_item` VALUES ('/v1/article/*', '2', null, null, null, '1532066500', '1532066500');
INSERT INTO `auth_item` VALUES ('/v1/user/*', '2', null, null, null, '1532075412', '1532075412');
INSERT INTO `auth_item` VALUES ('admin', '1', null, null, null, '1532066529', '1532066529');
INSERT INTO `auth_item` VALUES ('文章管理', '2', null, null, null, '1532066512', '1532066512');
INSERT INTO `auth_item` VALUES ('用户登录', '2', null, null, null, '1532075431', '1532075431');

INSERT INTO `auth_item_child` VALUES ('admin', '文章管理');
INSERT INTO `auth_item_child` VALUES ('admin', '用户登录');
INSERT INTO `auth_item_child` VALUES ('文章管理', '/v1/article/*');
INSERT INTO `auth_item_child` VALUES ('用户登录', '/v1/user/*');

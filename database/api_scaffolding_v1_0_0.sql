CREATE DATABASE /*!32312 IF NOT EXISTS*/`api_scaffolding_v1_0_0` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `api_scaffolding_v1_0_0`;

CREATE TABLE `tbl_user` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '用户编号',
  `account` varchar(128) NOT NULL DEFAULT '' COMMENT '账号',
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='用户表';

CREATE TABLE `tbl_article` (
	`id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '文章编号',
	`title` varchar(64) DEFAULT '' COMMENT '文章标题',
	`content` text NOT NULL DEFAULT '' COMMENT '文章内容',
	`status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '0：未发布 1：已发布',
	`create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `is_deleted` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否删除(0:否,1:是)',
	PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='文章信息表';

CREATE TABLE `tbl_article_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `article_id` bigint(20) DEFAULT '0' COMMENT '文章编号',
  `title` varchar(64) NOT NULL DEFAULT '' COMMENT '日志标题',
  `content` varchar(500) NOT NULL DEFAULT '' COMMENT '日志内容',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文章日志表';
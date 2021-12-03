CREATE TABLE IF NOT EXISTS `__PREFIX__suisunwechat_article`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '名称',
  `media_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '公众号媒体ID',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) NULL DEFAULT NULL COMMENT '更新时间',
  `deletetime` int(10) NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE = InnoDB CHARACTER SET = utf8 COMMENT = '文章';

CREATE TABLE IF NOT EXISTS `__PREFIX__suisunwechat_article_detail`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `article_id` int(10) NOT NULL DEFAULT 0 COMMENT '文章ID',
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '标题',
  `image` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '封面图',
  `thumb_media_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '封面图ID',
  `author` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '作者',
  `show_cover` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '是否在文章内容显示封面图片',
  `digest` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '摘要',
  `content` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '内容',
  `source_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '原文链接',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) NULL DEFAULT NULL COMMENT '更新时间',
  `deletetime` int(10) NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE = InnoDB CHARACTER SET = utf8 COMMENT = '文章内容';

CREATE TABLE IF NOT EXISTS `__PREFIX__suisunwechat_auto_reply`  (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `keywordcontent` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '关键词',
  `eventkey` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '响应事件',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE = InnoDB CHARACTER SET = utf8 COMMENT = '自动回复';

CREATE TABLE IF NOT EXISTS `__PREFIX__suisunwechat_broadcasting`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `msgid` int(10) NULL DEFAULT 0 COMMENT '信息id',
  `type` enum('text','textandphoto','image','video','voice','article') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'text' COMMENT '消息类型:text=文本,textandphoto=图文,image=图片,video=视频,voice=音频',
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '消息内容',
  `openids` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '接收人',
  `tags` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '接收组',
  `status` enum('1','0') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '1' COMMENT '状态:0=失败,1=成功',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) NULL DEFAULT NULL COMMENT '更新时间',
  `deletetime` int(10) NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE = InnoDB CHARACTER SET = utf8 COMMENT = '消息群发';

CREATE TABLE IF NOT EXISTS `__PREFIX__suisunwechat_image`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `media_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '公众号媒体ID',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '名称',
  `image` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '图片',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) NULL DEFAULT NULL COMMENT '更新时间',
  `deletetime` int(10) NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE = InnoDB CHARACTER SET = utf8 COMMENT = '图片消息';

CREATE TABLE IF NOT EXISTS `__PREFIX__suisunwechat_imagetext`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `desc` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '摘要',
  `image` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '封面',
  `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '链接',
  `items` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '图文列表',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) NULL DEFAULT NULL COMMENT '更新时间',
  `deletetime` int(10) NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE = InnoDB CHARACTER SET = utf8 COMMENT = '图文消息';

CREATE TABLE IF NOT EXISTS `__PREFIX__suisunwechat_lang`  (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `lang` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '简称',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '国家',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE = InnoDB CHARACTER SET = utf8 COMMENT = '语言简称';

CREATE TABLE IF NOT EXISTS `__PREFIX__suisunwechat_service_admin`  (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `openid` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '客服openid',
  `currentid` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '当前服务人员openid',
  `nickname` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '客服姓名',
  `status` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0' COMMENT '状态,0:空闲,1:服务中',
  `createtime` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updatetime` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE = InnoDB CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `__PREFIX__suisunwechat_service_history`  (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '发送内容',
  `aopenid` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '客服openid',
  `uopenid` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '客户openid',
  `is_admin` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0' COMMENT '是否管理员',
  `createtime` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updatetime` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE = InnoDB CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `__PREFIX__suisunwechat_service_log`  (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `reply` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '回复内容',
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '发送内容',
  `aopenid` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '客服openid',
  `uopenid` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '客户openid',
  `status` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0' COMMENT '状态,0:等待中,1:已回复',
  `createtime` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updatetime` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE = InnoDB CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `__PREFIX__suisunwechat_video`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `media_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '公众号媒体ID',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '名称',
  `desc` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '描述',
  `image` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '封面',
  `thumb_media_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '封面图媒体ID',
  `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '视频',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) NULL DEFAULT NULL COMMENT '更新时间',
  `deletetime` int(10) NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE = InnoDB CHARACTER SET = utf8 COMMENT = '视频消息';

CREATE TABLE IF NOT EXISTS `__PREFIX__suisunwechat_voice`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `media_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '公众号媒体ID',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '名称',
  `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '音频',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) NULL DEFAULT NULL COMMENT '更新时间',
  `deletetime` int(10) NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE = InnoDB CHARACTER SET = utf8 COMMENT = '音频消息';

CREATE TABLE IF NOT EXISTS `__PREFIX__suisunwechat_wechat`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `type` enum('conditional','base') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'base' COMMENT '菜单类型:base=基本菜单,conditional=个性化菜单',
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '名称',
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '内容',
  `matchrule` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '个性化信息',
  `menuid` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '微信菜单ID',
  `status` enum('1','0') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '0' COMMENT '发布状态:0=未发布,1=已发布',
  `createtime` int(11) NOT NULL COMMENT '创建时间',
  `updatetime` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE = InnoDB CHARACTER SET = utf8 COMMENT = '微信管理';

CREATE TABLE IF NOT EXISTS `__PREFIX__suisunwechat_wechat_config`  (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `type` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '配置类型',
  `config` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '配置内容',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE = MyISAM CHARACTER SET = utf8 COMMENT = '公众号配置';

CREATE TABLE IF NOT EXISTS `__PREFIX__suisunwechat_wechat_fans`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `nickname` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '粉丝昵称',
  `headimgurl` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '粉丝头像',
  `openid` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT 'openid',
  `sex` int(11) NOT NULL DEFAULT 0 COMMENT '性别',
  `country` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '国家',
  `province` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '省',
  `city` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '市',
  `tagids` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '粉丝标签',
  `subscribe` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '关注状态:0=取消关注,1=已关注',
  `subscribe_time` int(11) NULL COMMENT '关注时间',
  `createtime` int(11) NOT NULL COMMENT '创建时间',
  `updatetime` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE = InnoDB CHARACTER SET = utf8 COMMENT = '粉丝管理';

CREATE TABLE IF NOT EXISTS `__PREFIX__suisunwechat_wechat_fans_tag`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `tag_id` int(10) NULL DEFAULT NULL COMMENT '标签ID',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '名称',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) NULL DEFAULT NULL COMMENT '更新时间',
  `deletetime` int(10) NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE = InnoDB CHARACTER SET = utf8 COMMENT = '标签管理';

CREATE TABLE IF NOT EXISTS `__PREFIX__suisunwechat_wechat_source`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `eventkey` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '事件标识',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '素材名称',
  `type` enum('text','video','image','audio','textandphoto') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'text' COMMENT '消息类型:text=文本,textandphoto=图文,image=图文,video=视频,audio=音频',
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '消息内容',
  `status` enum('1','0') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '1' COMMENT '是否启用:0=不启用,1=启用',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) NULL DEFAULT NULL COMMENT '更新时间',
  `deletetime` int(10) NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE = InnoDB CHARACTER SET = utf8 COMMENT = '响应资源';

CREATE TABLE IF NOT EXISTS `__PREFIX__suisunwechat_wechat_usersummary`  (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `time` int(10) NOT NULL COMMENT '统计时间',
  `user_source` int(10) NOT NULL COMMENT '用户渠道',
  `new_user` int(10) NOT NULL COMMENT '新增用户',
  `cancel_user` int(10) NOT NULL COMMENT '取消关注',
  `createtime` int(10) NOT NULL COMMENT '创建时间',
  `updatetime` int(10) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE = InnoDB CHARACTER SET = utf8 COMMENT = '微信用户数据分析';

ALTER TABLE __PREFIX__suisunwechat_wechat_fans MODIFY headimgurl varchar(255) null comment '粉丝头像';
ALTER TABLE __PREFIX__suisunwechat_wechat_fans MODIFY openid varchar(255) null comment 'openid';
ALTER TABLE __PREFIX__suisunwechat_wechat_fans MODIFY country varchar(255) null comment '国家';
ALTER TABLE __PREFIX__suisunwechat_wechat_fans MODIFY province varchar(255) null comment '省';
ALTER TABLE __PREFIX__suisunwechat_wechat_fans MODIFY city varchar(255) null comment '市';
ALTER TABLE __PREFIX__suisunwechat_wechat_fans MODIFY tagids varchar(255) null comment '粉丝标签';
ALTER TABLE __PREFIX__suisunwechat_wechat_fans MODIFY subscribe_time int(11) null comment '关注时间';
ALTER TABLE __PREFIX__suisunwechat_wechat_fans MODIFY subscribe enum('0','1') null comment '关注状态:0=取消关注,1=已关注';
ALTER TABLE __PREFIX__suisunwechat_wechat MODIFY content text null comment '内容';
ALTER TABLE __PREFIX__suisunwechat_service_history MODIFY content text null comment '发送内容';
ALTER TABLE __PREFIX__suisunwechat_service_log MODIFY reply text null comment '回复内容';
ALTER TABLE __PREFIX__suisunwechat_service_log MODIFY content text null comment '发送内容';


COMMIT;

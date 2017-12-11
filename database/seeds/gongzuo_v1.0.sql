/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50540
Source Host           : localhost:3306
Source Database       : gongzuo

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2016-08-22 10:26:11
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for answers
-- ----------------------------
DROP TABLE IF EXISTS `answers`;
CREATE TABLE `answers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户 UID',
  `question_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '问题 UID',
  `detail` text NOT NULL COMMENT '回答详情',
  `vote_up` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '赞同数',
  `commented` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '评论数',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `answers_uid_index` (`uid`),
  KEY `answers_question_id_index` (`question_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for answer_comments
-- ----------------------------
DROP TABLE IF EXISTS `answer_comments`;
CREATE TABLE `answer_comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户 UID',
  `answer_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '回答 UID',
  `content` text NOT NULL COMMENT '评论详情',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `answer_comments_uid_index` (`uid`),
  KEY `answer_comments_answer_id_index` (`answer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for answer_votes
-- ----------------------------
DROP TABLE IF EXISTS `answer_votes`;
CREATE TABLE `answer_votes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '点赞人 UID',
  `answers_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '回答 ID',
  `up_down` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '1:赞成；2:反对',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `answer_votes_uid_index` (`uid`),
  KEY `answer_votes_answers_id_index` (`answers_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for articles
-- ----------------------------
DROP TABLE IF EXISTS `articles`;
CREATE TABLE `articles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'UID',
  `subject` varchar(255) NOT NULL DEFAULT '' COMMENT '文章标题',
  `detail` text NOT NULL COMMENT '文章内容',
  `thumbnails` varchar(125) NOT NULL DEFAULT '' COMMENT '缩略图',
  `standard` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '文章审核状态 0审核中 1审核通过 2审核不通过 3已删除',
  `viewed` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '浏览数',
  `stared` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '关注数',
  `shared` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享数',
  `vote_up` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '赞同数',
  `vote_down` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '反对数',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `articles_uid_index` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for article_comments
-- ----------------------------
DROP TABLE IF EXISTS `article_comments`;
CREATE TABLE `article_comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户 UID',
  `article_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文章 ID',
  `content` text NOT NULL COMMENT '评论详情',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `article_comments_uid_index` (`uid`),
  KEY `article_comments_article_id_index` (`article_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for article_history
-- ----------------------------
DROP TABLE IF EXISTS `article_history`;
CREATE TABLE `article_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `article_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文章 ID',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态 1审核通过 2审核不通过 3已删除',
  `reason` text NOT NULL COMMENT '原因',
  `adminid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '管理员id',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for article_stars
-- ----------------------------
DROP TABLE IF EXISTS `article_stars`;
CREATE TABLE `article_stars` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '收藏人 UID',
  `article_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文章 ID',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `article_stars_uid_index` (`uid`),
  KEY `article_stars_article_id_index` (`article_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for article_tags
-- ----------------------------
DROP TABLE IF EXISTS `article_tags`;
CREATE TABLE `article_tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `article_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文章 ID',
  `tag_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '标签 ID',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `article_tags_article_id_index` (`article_id`),
  KEY `article_tags_tag_id_index` (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for article_votes
-- ----------------------------
DROP TABLE IF EXISTS `article_votes`;
CREATE TABLE `article_votes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户 ID',
  `article_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文章 ID',
  `up_down` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Up or Down',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `article_votes_article_id_index` (`article_id`),
  KEY `article_votes_uid_index` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for categories
-- ----------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `entity` varchar(128) NOT NULL DEFAULT '' COMMENT '分类名称',
  `order` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `pic` varchar(128) NOT NULL COMMENT '图标（点击前）',
  `pic_hide` varchar(128) NOT NULL COMMENT '图标（点击后）',
  `is_display` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否显示 1-显示 0-不显示',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for categories_tags
-- ----------------------------
DROP TABLE IF EXISTS `categories_tags`;
CREATE TABLE `categories_tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tag_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '标签 ID',
  `category_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分类 ID',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `categories_tags_tag_id_index` (`tag_id`),
  KEY `categories_tags_category_id_index` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for competence
-- ----------------------------
DROP TABLE IF EXISTS `competence`;
CREATE TABLE `competence` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL DEFAULT '' COMMENT '权限名称',
  `level` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '级别',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级id',
  `con` varchar(128) NOT NULL DEFAULT '' COMMENT '权限地址',
  `url_name` varchar(128) NOT NULL DEFAULT '' COMMENT '路由别名',
  `breadcrumbs` varchar(128) NOT NULL DEFAULT '' COMMENT '面包屑',
  `order` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `is_default` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否为上级默认页 0否',
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建人id',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for competence_group
-- ----------------------------
DROP TABLE IF EXISTS `competence_group`;
CREATE TABLE `competence_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `competence_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '权限id',
  `group_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '工作组id',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for contents
-- ----------------------------
DROP TABLE IF EXISTS `contents`;
CREATE TABLE `contents` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL COMMENT '通知内容',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dialogs
-- ----------------------------
DROP TABLE IF EXISTS `dialogs`;
CREATE TABLE `dialogs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sender` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '发信人 UID',
  `recipient` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '收信人 UID',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dialogs_sender_index` (`sender`),
  KEY `dialogs_recipient_index` (`recipient`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dialog_messages
-- ----------------------------
DROP TABLE IF EXISTS `dialog_messages`;
CREATE TABLE `dialog_messages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dialog_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '对话 UID',
  `sender` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '发信人 UID',
  `recipient` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '收信人 UID',
  `content` text NOT NULL COMMENT '信息内容',
  `operator` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '删除 uid',
  `read` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否已读 0-未读 1-已读',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dialog_messages_dialog_id_index` (`dialog_id`),
  KEY `dialog_messages_sender_index` (`sender`),
  KEY `dialog_messages_recipient_index` (`recipient`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for notifications
-- ----------------------------
DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `from` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '发送人id',
  `recipient` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '指定收信人id',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '通知类型 2-文章信息通知 3-问答信息通知',
  `content` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '系统通知',
  `show_type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '模板显示类型',
  `by` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '通知方式',
  `associate_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '关联 ID',
  `read` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否已读',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_from_index` (`from`),
  KEY `notifications_type_index` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for content_user
-- ----------------------------
DROP TABLE IF EXISTS `content_user`;
CREATE TABLE `content_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户 UID',
  `content_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '系统通知 ID',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `content_user_uid_index` (`uid`),
  KEY `content_user_content_id_index` (`content_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for questions
-- ----------------------------
DROP TABLE IF EXISTS `questions`;
CREATE TABLE `questions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户 UID',
  `subject` varchar(255) NOT NULL DEFAULT '' COMMENT '提问主题',
  `detail` text NOT NULL COMMENT '提问详情',
  `answered` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '回答数',
  `viewed` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '浏览数',
  `stared` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '关注数',
  `shared` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享数',
  `vote_up` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '赞同数',
  `is_hot` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态 0否 1是',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态 0已发布 1已删除',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `questions_uid_index` (`uid`),
  KEY `questions_answered_index` (`answered`) USING BTREE,
  KEY `questions_created_at_index` (`created_at`),
  KEY `questions_vote_up_index` (`vote_up`),
  KEY `questions_is_hot_index` (`is_hot`),
  KEY `questions_status_index` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for questions_history
-- ----------------------------
DROP TABLE IF EXISTS `questions_history`;
CREATE TABLE `questions_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `questions_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '问题ID',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态 1编辑 1删除',
  `adminid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '管理员id',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for question_invitations
-- ----------------------------
DROP TABLE IF EXISTS `question_invitations`;
CREATE TABLE `question_invitations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户 UID',
  `question_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '问题 UID',
  `invited` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '被邀请人 UID',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `question_invitations_uid_index` (`uid`),
  KEY `question_invitations_question_id_index` (`question_id`),
  KEY `question_invitations_invited_index` (`invited`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for question_stars
-- ----------------------------
DROP TABLE IF EXISTS `question_stars`;
CREATE TABLE `question_stars` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '关注人 UID',
  `question_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '问题 ID',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `question_stars_uid_index` (`uid`),
  KEY `question_stars_question_id_index` (`question_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for question_tags
-- ----------------------------
DROP TABLE IF EXISTS `question_tags`;
CREATE TABLE `question_tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `question_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '问题 ID',
  `tag_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '标签 ID',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `question_tags_question_id_index` (`question_id`),
  KEY `question_tags_tag_id_index` (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for sessions
-- ----------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sid` varchar(255) NOT NULL DEFAULT '' COMMENT 'Session ID',
  `value` text NOT NULL COMMENT 'Session Value',
  `activity` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后活跃时间',
  `ip` varchar(64) NOT NULL DEFAULT '' COMMENT '请求 IP',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_sid_index` (`sid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for sms_code
-- ----------------------------
DROP TABLE IF EXISTS `sms_code`;
CREATE TABLE `sms_code` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mobile` char(11) NOT NULL DEFAULT '' COMMENT '手机',
  `type` enum('updatepass','binding','retrieve','registered') NOT NULL COMMENT '类型',
  `code` char(11) NOT NULL DEFAULT '' COMMENT '验证码',
  `send_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '发送时间',
  `back_data` text NOT NULL COMMENT '短信接口返回数据',
  `ip` char(20) NOT NULL DEFAULT '' COMMENT 'ip',
  `return_status` char(10) NOT NULL DEFAULT '' COMMENT '短信接口端返回的状态码',
  `status` enum('1','2') NOT NULL COMMENT '状态码 1可用2不可用',
  `error_num` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '错误次数',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tags
-- ----------------------------
DROP TABLE IF EXISTS `tags`;
CREATE TABLE `tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `name` varchar(64) NOT NULL DEFAULT '' COMMENT '标签名',
  `tagged_answers` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '相关问题数量',
  `tagged_articles` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '相关文章数量',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tags_name_index` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tags_history
-- ----------------------------
DROP TABLE IF EXISTS `tags_history`;
CREATE TABLE `tags_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tag_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '标签 ID',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态 1编辑 2添加 3删除 4添加到领域',
  `category_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领域id',
  `adminid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '管理员id',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `display_name` varchar(128) NOT NULL DEFAULT '' COMMENT '昵称',
  `mobile` char(11) NOT NULL DEFAULT '' COMMENT '手机',
  `email` varchar(255) NOT NULL DEFAULT '' COMMENT 'email',
  `passcode` varchar(64) NOT NULL DEFAULT '' COMMENT '密码',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '头像',
  `gender` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '性别 1男2女3保密',
  `birthday` date NOT NULL DEFAULT '1000-01-01' COMMENT '生日',
  `province` varchar(64) NOT NULL DEFAULT '' COMMENT '省份',
  `city` varchar(64) NOT NULL DEFAULT '' COMMENT '城市',
  `occupation` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '1在校学生 2职业人士',
  `registered_ip` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '注册 IP',
  `disabled` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否禁用',
  `email_verified` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '邮箱是否验证',
  `mobile_verified` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '手机是否验证',
  `customized_uri` varchar(32) NOT NULL DEFAULT '' COMMENT '自定义 URI',
  `group_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '权限组 0为普通用户',
  `slogan` varchar(128) NOT NULL DEFAULT '' COMMENT '个性签名',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `users_mobile_index` (`mobile`),
  KEY `users_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for users_qq
-- ----------------------------
DROP TABLE IF EXISTS `users_qq`;
CREATE TABLE `users_qq` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `nickname` varchar(64) NOT NULL DEFAULT '' COMMENT '用户昵称',
  `openid` varchar(128) NOT NULL DEFAULT '' COMMENT 'QQ唯一标识',
  `gender` varchar(8) NOT NULL DEFAULT '' COMMENT '性别',
  `access_token` varchar(64) NOT NULL DEFAULT '' COMMENT '授权令牌',
  `refresh_token` varchar(64) NOT NULL DEFAULT '' COMMENT '获取access_token',
  `avatar` varchar(128) NOT NULL DEFAULT '' COMMENT 'QQ头像',
  `figureurl` varchar(64) NOT NULL DEFAULT '' COMMENT 'QQ空间头像',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `users_qq_uid_index` (`uid`),
  KEY `users_qq_openid_index` (`openid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for users_sina
-- ----------------------------
DROP TABLE IF EXISTS `users_sina`;
CREATE TABLE `users_sina` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `expires_in` int(11) NOT NULL DEFAULT '0' COMMENT 'access_token接口调用凭证超时时间',
  `screen_name` varchar(64) NOT NULL DEFAULT '' COMMENT '微博昵称',
  `sinaid` varchar(128) NOT NULL DEFAULT '' COMMENT '唯一标识',
  `gender` varchar(8) NOT NULL DEFAULT '' COMMENT '性别',
  `location` varchar(64) NOT NULL DEFAULT '' COMMENT '地址',
  `description` text NOT NULL COMMENT '个人描述',
  `profile_image_url` varchar(64) NOT NULL DEFAULT '' COMMENT '微博',
  `blog_url` varchar(255) NOT NULL DEFAULT '' COMMENT '博客地址',
  `access_token` varchar(32) NOT NULL DEFAULT '' COMMENT '用户授权的作用域',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `users_sina_uid_index` (`uid`),
  KEY `users_sina_sinaid_index` (`sinaid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for users_weixin
-- ----------------------------
DROP TABLE IF EXISTS `users_weixin`;
CREATE TABLE `users_weixin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `expires_in` int(11) NOT NULL DEFAULT '0' COMMENT 'access_token接口调用凭证超时时间',
  `nickname` varchar(64) NOT NULL DEFAULT '' COMMENT '微信昵称',
  `openid` varchar(128) NOT NULL DEFAULT '' COMMENT '唯一标识',
  `sex` varchar(8) NOT NULL DEFAULT '' COMMENT '性别',
  `access_token` varchar(64) NOT NULL DEFAULT '' COMMENT '用户授权的作用域',
  `refresh_token` varchar(64) NOT NULL DEFAULT '' COMMENT '用户刷新access_token',
  `headimgurl` varchar(255) NOT NULL DEFAULT '' COMMENT '微信头像',
  `province` varchar(32) NOT NULL DEFAULT '' COMMENT '省份',
  `city` varchar(32) NOT NULL DEFAULT '' COMMENT '地区',
  `country` varchar(32) NOT NULL DEFAULT '' COMMENT '国家',
  `unionid` varchar(128) NOT NULL DEFAULT '' COMMENT '用户统一标识',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `users_weixin_uid_index` (`uid`),
  KEY `users_weixin_openid_index` (`openid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for user_analysis
-- ----------------------------
DROP TABLE IF EXISTS `user_analysis`;
CREATE TABLE `user_analysis` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户 UID',
  `follower` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '粉丝数',
  `following` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '关注数',
  `invitation` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '被邀请数',
  `question` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '问题数',
  `answer` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '回答数',
  `profile_viewed` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '个人中心被浏览',
  `reputation` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '声望值',
  `online` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '在线时长',
  `last_login` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `last_logout` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登出时间',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_analysis_uid_unique` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for user_educations
-- ----------------------------
DROP TABLE IF EXISTS `user_educations`;
CREATE TABLE `user_educations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户 UID',
  `enrolled` date NOT NULL DEFAULT '1000-01-01' COMMENT '入学年份',
  `graduated` date NOT NULL DEFAULT '1000-01-01' COMMENT '毕业年份',
  `school` varchar(128) NOT NULL DEFAULT '' COMMENT '学校名称',
  `department` varchar(128) NOT NULL DEFAULT '' COMMENT '院系',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_educations_uid_unique` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for user_following
-- ----------------------------
DROP TABLE IF EXISTS `user_following`;
CREATE TABLE `user_following` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '关注人 UID',
  `following` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '被关注人 UID',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_following_uid_index` (`uid`),
  KEY `user_following_following_index` (`following`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for user_groups
-- ----------------------------
DROP TABLE IF EXISTS `user_groups`;
CREATE TABLE `user_groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL DEFAULT '' COMMENT '工作组名称',
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建人id',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for user_proficiencies
-- ----------------------------
DROP TABLE IF EXISTS `user_proficiencies`;
CREATE TABLE `user_proficiencies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户 UID',
  `tag_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '标签 ID',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_proficiencies_uid_index` (`uid`),
  KEY `user_proficiencies_tag_id_index` (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for user_sessions
-- ----------------------------
DROP TABLE IF EXISTS `user_sessions`;
CREATE TABLE `user_sessions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sid` varchar(128) NOT NULL DEFAULT '' COMMENT 'Session ID',
  `value` text NOT NULL COMMENT 'Session Value',
  `activity` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后活跃时间',
  `logged_in` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '登录时间',
  `remember_me` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '记住登录',
  `ip` varchar(64) NOT NULL DEFAULT '' COMMENT '请求 IP',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_sessions_sid_index` (`sid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for user_works
-- ----------------------------
DROP TABLE IF EXISTS `user_works`;
CREATE TABLE `user_works` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户 UID',
  `from` date NOT NULL DEFAULT '1000-01-01' COMMENT '起始年份',
  `to` date NOT NULL DEFAULT '1000-01-01' COMMENT '结束年份',
  `company` varchar(128) NOT NULL DEFAULT '' COMMENT '公司名称',
  `position` varchar(128) NOT NULL DEFAULT '' COMMENT '职位',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_works_uid_unique` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for cv_persons
-- ----------------------------
DROP TABLE IF EXISTS `cv_persons`;
CREATE TABLE `cv_persons` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户 UID',
  `name` varchar(128) NOT NULL DEFAULT '' COMMENT '姓名',
  `mobile` char(11) NOT NULL DEFAULT '' COMMENT '手机',
  `email` varchar(128) NOT NULL DEFAULT '' COMMENT 'email',
  `gender` varchar(64) NOT NULL DEFAULT '' COMMENT '性别',
  `birthday` date NOT NULL DEFAULT '1000-01-01' COMMENT '生日',
  `province` varchar(64) NOT NULL DEFAULT '' COMMENT '省份',
  `city` varchar(64) NOT NULL DEFAULT '' COMMENT '城市',
  `resumeavatar` varchar(128) NOT NULL DEFAULT '' COMMENT '简历照片',
  `status` int(2) NOT NULL DEFAULT '1' COMMENT '2：创建完毕；1:未创建',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cv_persons_uid_index` (`uid`),
  KEY `cv_persons_mobile_index` (`mobile`),
  KEY `cv_persons_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for cv_interests
-- ----------------------------
DROP TABLE IF EXISTS `cv_interests`;
CREATE TABLE `cv_interests` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cvid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '简历 UID',
  `interests` text NOT NULL COMMENT '兴趣爱好',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cv_interests_cvid_unique` (`cvid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for cv_experiences
-- ----------------------------
DROP TABLE IF EXISTS `cv_experiences`;
CREATE TABLE `cv_experiences` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cvid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '简历 UID',
  `from` date NOT NULL DEFAULT '1000-01-01' COMMENT '起始年份',
  `to` date NOT NULL DEFAULT '1000-01-01' COMMENT '结束年份',
  `company` varchar(128) NOT NULL DEFAULT '' COMMENT '公司名称',
  `position` varchar(128) NOT NULL DEFAULT '' COMMENT '职位',
  `jobdescription` text  NOT NULL DEFAULT '' COMMENT '职位介绍',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cv_experiences_cvid_index` (`cvid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for cv_educations
-- ----------------------------
DROP TABLE IF EXISTS `cv_educations`;
CREATE TABLE `cv_educations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cvid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '简历 UID',
  `enrolled` date NOT NULL DEFAULT '1000-01-01' COMMENT '入学年份',
  `graduated` date NOT NULL DEFAULT '1000-01-01' COMMENT '毕业年份',
  `school` varchar(128) NOT NULL DEFAULT '' COMMENT '学校名称',
  `department` varchar(128) NOT NULL DEFAULT '' COMMENT '院系',
  `education` varchar(128) NOT NULL DEFAULT '' COMMENT '学历',
  `success` varchar(128) NOT NULL DEFAULT '' COMMENT '取得成就',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cv_educations_cvid_index` (`cvid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for cv_diplomas
-- ----------------------------
DROP TABLE IF EXISTS `cv_diplomas`;
CREATE TABLE `cv_diplomas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cvid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '简历 UID',
  `certificate` varchar(128)  NOT NULL DEFAULT '' COMMENT '证书',
  `supplementary` varchar(128)  NOT NULL DEFAULT '' COMMENT '补充证书',
  `achivement` varchar(128) NOT NULL DEFAULT '' COMMENT '成绩',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cv_diplomas_cvid_index` (`cvid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for cv_honors
-- ----------------------------
DROP TABLE IF EXISTS `cv_honors`;
CREATE TABLE `cv_honors` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cvid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '简历 UID',
  `received_at` date NOT NULL DEFAULT '1000-01-01' COMMENT '获得日期',
  `award` text NOT NULL COMMENT '奖项内容',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cv_honors_cvid_index` (`cvid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for cv_projects
-- ----------------------------
DROP TABLE IF EXISTS `cv_projects`;
CREATE TABLE `cv_projects` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cvid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '简历 UID',
  `worked_at` date NOT NULL DEFAULT '1000-01-01' COMMENT '作品时间',
  `title` varchar(128) NOT NULL DEFAULT '' COMMENT '标题',
  `subtitle` varchar(128) NOT NULL DEFAULT '' COMMENT '副标题',
  `description` text NOT NULL COMMENT '描述',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cv_projects_cvid_index` (`cvid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for cv_advices
-- ----------------------------
DROP TABLE IF EXISTS `cv_advices`;
CREATE TABLE `cv_advices` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cvid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '简历 UID',
  `word_period` varchar(64) NOT NULL DEFAULT '' COMMENT '工作年限',
  `province` varchar(128) NOT NULL DEFAULT '' COMMENT '省份 ',
  `city` varchar(128) NOT NULL DEFAULT '' COMMENT '期望城市',
  `position` varchar(128) NOT NULL DEFAULT '' COMMENT '期望职位',
  `salary` varchar(128) NOT NULL DEFAULT '' COMMENT '期望月薪',
  `employment_type` varchar(128) NOT NULL DEFAULT '' COMMENT '工作性质',
  `job_type` varchar(128) NOT NULL DEFAULT '' COMMENT '求职状态',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cv_advices_cvid_unique_index` (`cvid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for cv_certificates
-- ----------------------------
DROP TABLE IF EXISTS `cv_certificates`;
CREATE TABLE `cv_certificates` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(125) DEFAULT '0' COMMENT '名称',
  `pid` int(11) unsigned DEFAULT '0' COMMENT '0为行业其他为父id',
  `order` int(11) unsigned DEFAULT '0' COMMENT '排序',
  `created_at` timestamp NULL DEFAULT NULL,
  `updatedat` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `cv_certificates_pid_index` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for cv_positions
-- ----------------------------
DROP TABLE IF EXISTS `cv_positions`;
CREATE TABLE `cv_positions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) unsigned DEFAULT '0' COMMENT '父id',
  `name` varchar(125) DEFAULT '0' COMMENT '名称',
  `order` int(11) unsigned DEFAULT '0' COMMENT '排序',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `cv_positions_pid_index` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for cv_school
-- ----------------------------
DROP TABLE IF EXISTS `cv_school`;
CREATE TABLE `cv_school` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(125) DEFAULT NULL,
  `cityid` int(11) unsigned DEFAULT NULL,
  `type` tinyint(1) unsigned DEFAULT NULL COMMENT '1本科高校2高职院校3独立学院4其他',
  `order` int(10) unsigned DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for cv_majors
-- ----------------------------
DROP TABLE IF EXISTS `cv_majors`;
CREATE TABLE `cv_majors` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(125) DEFAULT '0' COMMENT '专业名称',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for cv_archives
-- ----------------------------
DROP TABLE IF EXISTS `cv_archives`;
CREATE TABLE `cv_archives` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户 UID',
  `cvid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '简历 UID',
  `title` varchar(125) NOT NULL DEFAULT '""' COMMENT '简历标题',
  `model` varchar(64) NOT NULL DEFAULT '""' COMMENT '模板名称',
  `delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1为删除',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `cv_archives_uid_index` (`uid`),
  KEY `cv_archives_cvid_index` (`cvid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for cv_templates
-- ----------------------------
DROP TABLE IF EXISTS `cv_templates`;
CREATE TABLE `cv_templates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `profession_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '求职意向',
  `subject` varchar(128) NOT NULL DEFAULT '' COMMENT '名称',
  `feature` text COMMENT '特点',
  `preview` varchar(64) NOT NULL DEFAULT '' COMMENT '预览图',
  `file` varchar(64) NOT NULL DEFAULT '' COMMENT '模版文件',
  `language` char(8) NOT NULL DEFAULT '' COMMENT '语言',
  `colorscheme` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '颜色主题',
  `downloaded` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '下载次数',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cv_templates_profession_id_index` (`profession_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for cv_professions
-- ----------------------------
DROP TABLE IF EXISTS `cv_professions`;
CREATE TABLE `cv_professions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(128) NOT NULL DEFAULT '' COMMENT '求职意向',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 表的结构 `referral_codes`
--
DROP TABLE IF EXISTS `referral_codes`;
CREATE TABLE `referral_codes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(64) NOT NULL COMMENT '邀请码',
  `used` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0：未使用 1：已使用',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `referral_codes_code_index` (`code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='邀请码表' AUTO_INCREMENT=51 ;
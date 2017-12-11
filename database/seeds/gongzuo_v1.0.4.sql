
DROP TABLE IF EXISTS `questions_noline`;
CREATE TABLE `questions_noline` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) NOT NULL COMMENT '提问主题',
  `tagsjson` text NOT NULL COMMENT '标签json',
  `detail` text NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0未发布1已发布2已删除',
  `adminid` int(11) unsigned DEFAULT NULL COMMENT '创建人id',
  `uid` int(11) unsigned DEFAULT NULL COMMENT '发布人id',
  `is_question` tinyint(1) unsigned DEFAULT '0' COMMENT '0为未统计1为统计了',
  `release_at` int(10) unsigned DEFAULT '0' COMMENT '发布时间',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `questions_user`;
CREATE TABLE `questions_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL COMMENT 'uid',
  `adminid` int(11) unsigned DEFAULT NULL COMMENT '创建者id',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE  `content_user` ADD  `read` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' COMMENT  '0-未读 1-已读' AFTER  `content_id`;

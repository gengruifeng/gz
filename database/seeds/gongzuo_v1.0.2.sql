ALTER TABLE  `users` ADD  `error_num` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' COMMENT  '错误次数' AFTER  `slogan` ,
ADD  `error_time` INT( 10 ) UNSIGNED NOT NULL DEFAULT  '0' COMMENT  '错误时间' AFTER  `error_num`;
ALTER TABLE `referral_codes` ADD `issued` TINYINT(1) UNSIGNED NULL DEFAULT '0' AFTER `used`;
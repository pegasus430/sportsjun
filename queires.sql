CREATE TABLE `otp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `token` varchar(100) NOT NULL,
  `otp` varchar(10) NOT NULL,
  `item_id` int(11) NOT NULL,
  `item_type` varchar(20) NOT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT '0' COMMENT '0-not verified, 1- verified',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8

UPDATE `states` SET `state_name` = 'Puducherry' WHERE `id` = '31';
DELETE FROM `states` WHERE `id` = '18'; 
DELETE FROM `states` WHERE `id` = '27'; 
DELETE FROM `states` WHERE `id` = '28'; 
DELETE FROM `states` WHERE `id` = '30'; 
DELETE FROM `states` WHERE `id` = '40';
ALTER TABLE `marketplace` CHANGE `item` `item` VARCHAR(100) CHARSET utf8 COLLATE utf8_general_ci NULL;

/* 31-Mar-2016 */
ALTER TABLE `sports` ADD COLUMN `is_schedule_available` TINYINT(1) DEFAULT 0 NULL AFTER `sports_type`, ADD COLUMN `is_scorecard_available` TINYINT(1) DEFAULT 0 NULL AFTER `is_schedule_available`;
ALTER TABLE `user_statistics` CHANGE `allowed_sports` `allowed_sports` TEXT CHARSET utf8 COLLATE utf8_general_ci NULL COMMENT 'Interested to play the matches for sports for team events (cricket,soccer)', ADD COLUMN `allowed_player_matches` TEXT NULL COMMENT 'Interested to play the matches for sports (singles)' AFTER `allowed_sports`; 
ALTER TABLE `organization` CHANGE `organization_type` `organization_type` ENUM('academy','college','school','other','corporate') CHARSET utf8 COLLATE utf8_general_ci NULL;


/* 06-Apr-2015 */
ALTER TABLE match_schedules ALTER COLUMN score_added_by SET DEFAULT 'NULL';
/* 
Page Reviews plugin
Filename: pagereviews.install.sql
@package pagereviews
@version 1.0.1
@author webitprof
@copyright Copyright (c) 2025
@license BSD
*/

CREATE TABLE IF NOT EXISTS `cot_pagereviews` (
  `item_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `item_userid` INT(11) NOT NULL,
  `item_pageid` INT(11) NOT NULL,
  `item_text` TEXT COLLATE utf8mb4_unicode_ci,
  `item_title` VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `item_score` INT(11) DEFAULT NULL,
  `item_date` INT(11) DEFAULT NULL,
  PRIMARY KEY (`item_id`),
  INDEX `idx_userid` (`item_userid`),
  INDEX `idx_pageid` (`item_pageid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `cot_pagereviews_complaints` (
  `complaint_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `complaint_userid` INT(11) NOT NULL,
  `complaint_itemid` INT(10) UNSIGNED NOT NULL,
  `complaint_text` TEXT COLLATE utf8mb4_unicode_ci NOT NULL,
  `complaint_date` INT(11) NOT NULL,
  `complaint_resolved_date` INT(11) DEFAULT NULL,
  `complaint_status` ENUM('pending', 'rejected', 'approved') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  PRIMARY KEY (`complaint_id`),
  INDEX `idx_userid` (`complaint_userid`),
  INDEX `idx_itemid` (`complaint_itemid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
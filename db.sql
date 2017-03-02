# create database
CREATE DATABASE rss_news CHARACTER SET utf8 COLLATE utf8_general_ci;

# create table
CREATE TABLE `news` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `description` tinytext NOT NULL,
  `source` varchar(255) NOT NULL
) ENGINE='InnoDB';

# add index
ALTER TABLE `news` ADD UNIQUE `link` (`link`);

# update table: add column
ALTER TABLE `news` ADD `pub_date` timestamp NOT NULL;

# update table: change column
ALTER TABLE `news` CHANGE `pub_date` `pub_date` datetime NOT NULL AFTER `source`;
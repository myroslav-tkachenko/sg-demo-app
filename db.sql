# create database
create database rss_news character set utf8 collate utf8_general_ci;

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
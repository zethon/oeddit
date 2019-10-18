-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 09, 2015 at 02:58 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `db`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `comm_id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('NORMAL','DELETED') NOT NULL DEFAULT 'NORMAL',
  `text` text NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`comm_id`),
  KEY `post_id` (`post_id`),
  KEY `user_id` (`user_id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=52 ;

-- --------------------------------------------------------

--
-- Table structure for table `comm_control_log`
--

CREATE TABLE IF NOT EXISTS `comm_control_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `comm_id` int(11) NOT NULL,
  `mod_id` int(11) NOT NULL,
  `action` enum('DELETE','UNDELETE') NOT NULL,
  `comment` varchar(300) DEFAULT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`log_id`),
  KEY `comm_id` (`comm_id`),
  KEY `mod_id` (`mod_id`),
  FULLTEXT KEY `comment` (`comment`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `comm_reports`
--

CREATE TABLE IF NOT EXISTS `comm_reports` (
  `report_id` int(11) NOT NULL,
  `comm_id` int(11) NOT NULL,
  PRIMARY KEY (`report_id`),
  KEY `comm_id` (`comm_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `comm_votes`
--

CREATE TABLE IF NOT EXISTS `comm_votes` (
  `comm_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `vote` enum('UP','DOWN') NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`comm_id`,`user_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `login_log`
--

CREATE TABLE IF NOT EXISTS `login_log` (
  `user_id` int(11) NOT NULL,
  `ip` char(45) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `result` enum('SUCCESS','FAILURE') NOT NULL,
  PRIMARY KEY (`user_id`,`time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pms`
--

CREATE TABLE IF NOT EXISTS `pms` (
  `pm_id` int(11) NOT NULL AUTO_INCREMENT,
  `sender` int(11) NOT NULL,
  `receiver` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `subject` varchar(300) DEFAULT NULL,
  `msg` text NOT NULL,
  `read_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pm_id`),
  KEY `sender` (`sender`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
  `post_id` int(11) NOT NULL AUTO_INCREMENT,
  `soc_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` enum('NORMAL','DELETED','LOCKED','STICKIED') NOT NULL DEFAULT 'NORMAL',
  `title` varchar(300) NOT NULL,
  `text` text,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`post_id`),
  KEY `soc_id` (`soc_id`),
  KEY `user_id` (`user_id`),
  FULLTEXT KEY `title` (`title`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

-- --------------------------------------------------------

--
-- Table structure for table `post_control_log`
--

CREATE TABLE IF NOT EXISTS `post_control_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `mod_id` int(11) NOT NULL,
  `action` enum('DELETE','UNDELETE','STICKY','UNSTICKY') NOT NULL,
  `comment` varchar(300) DEFAULT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`log_id`),
  KEY `mod_id` (`mod_id`),
  KEY `post_id` (`post_id`),
  FULLTEXT KEY `comment` (`comment`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `post_reports`
--

CREATE TABLE IF NOT EXISTS `post_reports` (
  `report_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  PRIMARY KEY (`report_id`),
  KEY `post_id` (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `post_subs`
--

CREATE TABLE IF NOT EXISTS `post_subs` (
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`post_id`,`user_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `post_views`
--

CREATE TABLE IF NOT EXISTS `post_views` (
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`post_id`,`user_id`,`time`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `post_votes`
--

CREATE TABLE IF NOT EXISTS `post_votes` (
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `vote` enum('UP','DOWN') NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`post_id`,`user_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE IF NOT EXISTS `reports` (
  `report_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `reason` text NOT NULL,
  PRIMARY KEY (`report_id`),
  FULLTEXT KEY `reason` (`reason`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `societies`
--

CREATE TABLE IF NOT EXISTS `societies` (
  `soc_id` int(11) NOT NULL AUTO_INCREMENT,
  `soc_name` varchar(50) NOT NULL,
  `created_by` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('NORMAL','DELETED','LOCKED') NOT NULL DEFAULT 'NORMAL',
  `rev_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`soc_id`),
  UNIQUE KEY `soc_name` (`soc_name`),
  UNIQUE KEY `det_revision` (`rev_id`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Table structure for table `soc_bans`
--

CREATE TABLE IF NOT EXISTS `soc_bans` (
  `soc_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`soc_id`,`user_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `soc_control_admin_log`
--

CREATE TABLE IF NOT EXISTS `soc_control_admin_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL,
  `soc_id` int(11) NOT NULL,
  `action` enum('DELETE','UNDELETE','LOCK','UNLOCK') NOT NULL,
  `comment` varchar(300) DEFAULT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`log_id`),
  KEY `admin_id` (`admin_id`),
  KEY `soc_id` (`soc_id`),
  FULLTEXT KEY `comment` (`comment`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `soc_details`
--

CREATE TABLE IF NOT EXISTS `soc_details` (
  `rev_id` int(11) NOT NULL AUTO_INCREMENT,
  `soc_id` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `revised_by` int(11) NOT NULL,
  `info` text NOT NULL,
  PRIMARY KEY (`rev_id`),
  KEY `soc_id` (`soc_id`),
  KEY `revised_by` (`revised_by`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `soc_mods`
--

CREATE TABLE IF NOT EXISTS `soc_mods` (
  `soc_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`soc_id`,`user_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `soc_reports`
--

CREATE TABLE IF NOT EXISTS `soc_reports` (
  `report_id` int(11) NOT NULL,
  `soc_id` int(11) NOT NULL,
  PRIMARY KEY (`report_id`),
  KEY `soc_id` (`soc_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `soc_subs`
--

CREATE TABLE IF NOT EXISTS `soc_subs` (
  `soc_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`soc_id`,`user_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `soc_views`
--

CREATE TABLE IF NOT EXISTS `soc_views` (
  `soc_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `soc_id` (`soc_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('ADMIN','NORMAL','BANNED') NOT NULL DEFAULT 'NORMAL',
  `failed_logins` int(10) unsigned NOT NULL DEFAULT '0',
  `join_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_activity`
--

CREATE TABLE IF NOT EXISTS `user_activity` (
  `user_id` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_control_admin_log`
--

CREATE TABLE IF NOT EXISTS `user_control_admin_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` enum('DELETE','UNDELETE','BAN','UNBAN','ADMIN','DEADMIN') NOT NULL,
  `comment` varchar(300) DEFAULT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`log_id`),
  KEY `admin_id` (`admin_id`),
  KEY `user_id` (`user_id`),
  FULLTEXT KEY `comment` (`comment`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_control_mod_log`
--

CREATE TABLE IF NOT EXISTS `user_control_mod_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `mod_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `soc_id` int(11) NOT NULL,
  `action` enum('BAN','UNBAN','MOD','DEMOD') NOT NULL,
  `comment` varchar(300) DEFAULT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`log_id`),
  KEY `mod_id` (`mod_id`),
  KEY `user_id` (`user_id`),
  KEY `soc_id` (`soc_id`),
  FULLTEXT KEY `comment` (`comment`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_reports`
--

CREATE TABLE IF NOT EXISTS `user_reports` (
  `report_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`report_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`parent_id`) REFERENCES `comments` (`comm_id`),
  ADD CONSTRAINT `comments_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `comm_control_log`
--
ALTER TABLE `comm_control_log`
  ADD CONSTRAINT `comm_control_log_ibfk_1` FOREIGN KEY (`comm_id`) REFERENCES `comments` (`comm_id`),
  ADD CONSTRAINT `comm_control_log_ibfk_2` FOREIGN KEY (`mod_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `comm_reports`
--
ALTER TABLE `comm_reports`
  ADD CONSTRAINT `comm_reports_ibfk_1` FOREIGN KEY (`report_id`) REFERENCES `reports` (`report_id`),
  ADD CONSTRAINT `comm_reports_ibfk_2` FOREIGN KEY (`comm_id`) REFERENCES `comments` (`comm_id`);

--
-- Constraints for table `comm_votes`
--
ALTER TABLE `comm_votes`
  ADD CONSTRAINT `comm_votes_ibfk_1` FOREIGN KEY (`comm_id`) REFERENCES `comments` (`comm_id`),
  ADD CONSTRAINT `comm_votes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `login_log`
--
ALTER TABLE `login_log`
  ADD CONSTRAINT `login_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `pms`
--
ALTER TABLE `pms`
  ADD CONSTRAINT `pms_ibfk_1` FOREIGN KEY (`sender`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `pms_ibfk_2` FOREIGN KEY (`receiver`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`soc_id`) REFERENCES `societies` (`soc_id`),
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `post_control_log`
--
ALTER TABLE `post_control_log`
  ADD CONSTRAINT `post_control_log_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`),
  ADD CONSTRAINT `post_control_log_ibfk_2` FOREIGN KEY (`mod_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `post_reports`
--
ALTER TABLE `post_reports`
  ADD CONSTRAINT `post_reports_ibfk_1` FOREIGN KEY (`report_id`) REFERENCES `reports` (`report_id`),
  ADD CONSTRAINT `post_reports_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`);

--
-- Constraints for table `post_subs`
--
ALTER TABLE `post_subs`
  ADD CONSTRAINT `post_subs_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`),
  ADD CONSTRAINT `post_subs_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `post_views`
--
ALTER TABLE `post_views`
  ADD CONSTRAINT `post_views_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`),
  ADD CONSTRAINT `post_views_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `post_votes`
--
ALTER TABLE `post_votes`
  ADD CONSTRAINT `post_votes_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`),
  ADD CONSTRAINT `post_votes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `societies`
--
ALTER TABLE `societies`
  ADD CONSTRAINT `societies_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `societies_ibfk_2` FOREIGN KEY (`rev_id`) REFERENCES `soc_details` (`rev_id`);

--
-- Constraints for table `soc_bans`
--
ALTER TABLE `soc_bans`
  ADD CONSTRAINT `soc_bans_ibfk_1` FOREIGN KEY (`soc_id`) REFERENCES `societies` (`soc_id`),
  ADD CONSTRAINT `soc_bans_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `soc_control_admin_log`
--
ALTER TABLE `soc_control_admin_log`
  ADD CONSTRAINT `soc_control_admin_log_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `soc_control_admin_log_ibfk_2` FOREIGN KEY (`soc_id`) REFERENCES `societies` (`soc_id`);

--
-- Constraints for table `soc_details`
--
ALTER TABLE `soc_details`
  ADD CONSTRAINT `soc_details_ibfk_1` FOREIGN KEY (`soc_id`) REFERENCES `societies` (`soc_id`),
  ADD CONSTRAINT `soc_details_ibfk_2` FOREIGN KEY (`revised_by`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `soc_mods`
--
ALTER TABLE `soc_mods`
  ADD CONSTRAINT `soc_mods_ibfk_1` FOREIGN KEY (`soc_id`) REFERENCES `societies` (`soc_id`),
  ADD CONSTRAINT `soc_mods_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `soc_reports`
--
ALTER TABLE `soc_reports`
  ADD CONSTRAINT `soc_reports_ibfk_1` FOREIGN KEY (`soc_id`) REFERENCES `societies` (`soc_id`),
  ADD CONSTRAINT `soc_reports_ibfk_2` FOREIGN KEY (`report_id`) REFERENCES `reports` (`report_id`);

--
-- Constraints for table `soc_subs`
--
ALTER TABLE `soc_subs`
  ADD CONSTRAINT `soc_subs_ibfk_1` FOREIGN KEY (`soc_id`) REFERENCES `societies` (`soc_id`),
  ADD CONSTRAINT `soc_subs_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `soc_views`
--
ALTER TABLE `soc_views`
  ADD CONSTRAINT `soc_views_ibfk_1` FOREIGN KEY (`soc_id`) REFERENCES `societies` (`soc_id`),
  ADD CONSTRAINT `soc_views_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `user_activity`
--
ALTER TABLE `user_activity`
  ADD CONSTRAINT `user_activity_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `user_control_admin_log`
--
ALTER TABLE `user_control_admin_log`
  ADD CONSTRAINT `user_control_admin_log_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `user_control_admin_log_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `user_control_mod_log`
--
ALTER TABLE `user_control_mod_log`
  ADD CONSTRAINT `user_control_mod_log_ibfk_1` FOREIGN KEY (`mod_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `user_control_mod_log_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `user_control_mod_log_ibfk_3` FOREIGN KEY (`soc_id`) REFERENCES `societies` (`soc_id`);

--
-- Constraints for table `user_reports`
--
ALTER TABLE `user_reports`
  ADD CONSTRAINT `user_reports_ibfk_1` FOREIGN KEY (`report_id`) REFERENCES `reports` (`report_id`),
  ADD CONSTRAINT `user_reports_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

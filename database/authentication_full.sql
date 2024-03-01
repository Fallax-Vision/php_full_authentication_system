-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 26, 2023 at 12:21 PM
-- Server version: 5.7.36
-- PHP Version: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `authentication_full`
--

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

DROP TABLE IF EXISTS `system_settings`;
CREATE TABLE IF NOT EXISTS `system_settings` (
  `st_id` int(11) NOT NULL AUTO_INCREMENT,
  `st_system_name` varchar(100) NOT NULL,
  `st_description` varchar(1000) NOT NULL,
  `st_logo_file_name` varchar(200) NOT NULL,
  `st_initial_config_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `st_last_config_edit_date` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`st_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Table containing basic system settings.';

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_firstname` varchar(60) DEFAULT NULL,
  `user_lastname` varchar(60) DEFAULT NULL,
  `user_email` varchar(60) DEFAULT NULL,
  `user_email_verified` varchar(20) DEFAULT 'no' COMMENT 'No, Yes : to know whether the user has verified their email address or not.',
  `user_phone` varchar(30) DEFAULT NULL,
  `user_username` varchar(50) DEFAULT NULL,
  `user_password` varchar(50) DEFAULT NULL,
  `user_account_pin` varchar(20) DEFAULT NULL,
  `user_reg_date_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date and time when the user created the user registered.',
  `user_login_status` int(2) DEFAULT '0' COMMENT '0 = not logged in\r\n1 : logged in',
  `user_account_status` varchar(20) NOT NULL DEFAULT 'active' COMMENT 'active, suspended, deleted',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COMMENT='Table containing the list of all users registered.';

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_firstname`, `user_lastname`, `user_email`, `user_email_verified`, `user_phone`, `user_username`, `user_password`, `user_account_pin`, `user_reg_date_time`, `user_login_status`, `user_account_status`) VALUES
(1, 'Juliette', 'Bombo', 'luliette.bombo@gmail.com', 'no', NULL, 'user', 'auRlsnGKD2ljw', 'au0nwsIsbyz7E', '2023-04-26 09:53:13', 0, 'active'),
(3, 'Askas', 'Jeremy', 'jeremy.jasereka@gmail.com', 'no', NULL, 'jerry', 'auRlsnGKD2ljw', 'au0nwsIsbyz7E', '2023-04-26 11:45:02', 0, 'active');

-- --------------------------------------------------------

--
-- Table structure for table `users_login_sessions`
--

DROP TABLE IF EXISTS `users_login_sessions`;
CREATE TABLE IF NOT EXISTS `users_login_sessions` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `log_session_key` varchar(50) DEFAULT NULL,
  `log_user_id` int(11) NOT NULL,
  `log_date_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `log_logout_date` varchar(20) DEFAULT NULL,
  `log_logout_time` varchar(20) DEFAULT NULL,
  `log_status` varchar(20) NOT NULL DEFAULT 'active' COMMENT 'active, ended',
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COMMENT='List of login session by users.';

--
-- Dumping data for table `users_login_sessions`
--

INSERT INTO `users_login_sessions` (`log_id`, `log_session_key`, `log_user_id`, `log_date_time`, `log_logout_date`, `log_logout_time`, `log_status`) VALUES
(1, '64490e8e515de', 3, '2023-04-26 14:44:14', '2023-04-26', '02:47pm', 'ended');

-- --------------------------------------------------------

--
-- Table structure for table `user_recover_password`
--

DROP TABLE IF EXISTS `user_recover_password`;
CREATE TABLE IF NOT EXISTS `user_recover_password` (
  `urp_id` int(11) NOT NULL AUTO_INCREMENT,
  `urp_user_id` int(11) NOT NULL,
  `urp_recovery_key` varchar(20) NOT NULL,
  `urp_recovery_key_used` varchar(20) NOT NULL DEFAULT 'no' COMMENT 'no, yes',
  `urp_date_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `urp_status` varchar(20) NOT NULL DEFAULT 'pending' COMMENT 'pending, success, failed',
  PRIMARY KEY (`urp_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='List of password recover attempts';
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

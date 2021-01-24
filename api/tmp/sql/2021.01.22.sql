-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- 생성 시간: 21-01-22 06:34
-- 서버 버전: 10.5.5-MariaDB
-- PHP 버전: 7.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 데이터베이스: `wordpress`
--

-- --------------------------------------------------------

--
-- 테이블 구조 `bio`
--

CREATE TABLE `bio` (
  `user_ID` int(10) UNSIGNED NOT NULL,
  `name` varchar(32) NOT NULL DEFAULT '',
  `birthdate` mediumint(9) NOT NULL DEFAULT 0,
  `gender` char(1) NOT NULL DEFAULT '',
  `height` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `weight` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `city` varchar(32) NOT NULL DEFAULT '',
  `drinking` char(1) NOT NULL DEFAULT '',
  `smoking` char(1) NOT NULL DEFAULT '',
  `hobby` varchar(32) NOT NULL DEFAULT '',
  `dateMethod` varchar(255) NOT NULL DEFAULT '',
  `profile_photo_url` varchar(255) NOT NULL DEFAULT '',
  `createdAt` int(10) UNSIGNED NOT NULL,
  `updatedAt` int(10) UNSIGNED NOT NULL,
  `latitude` double NOT NULL DEFAULT 0,
  `longitude` double NOT NULL DEFAULT 0,
  `accuracy` double NOT NULL DEFAULT 0,
  `altitude` double NOT NULL DEFAULT 0,
  `speed` double NOT NULL DEFAULT 0,
  `heading` double NOT NULL DEFAULT 0,
  `time` double NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 테이블 구조 `jewelry_credit`
--

CREATE TABLE `jewelry_credit` (
  `user_ID` int(10) UNSIGNED NOT NULL,
  `diamond` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `gold` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `silver` int(10) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 테이블 구조 `jewelry_daily_bonus`
--

CREATE TABLE `jewelry_daily_bonus` (
  `ID` int(10) UNSIGNED NOT NULL,
  `user_ID` int(10) UNSIGNED NOT NULL,
  `date` int(10) UNSIGNED NOT NULL,
  `stamp` int(10) UNSIGNED NOT NULL,
  `diamond` smallint(5) UNSIGNED NOT NULL,
  `gold` smallint(5) UNSIGNED NOT NULL,
  `silver` smallint(5) UNSIGNED NOT NULL,
  `history_diamond` smallint(5) UNSIGNED NOT NULL,
  `history_gold` smallint(5) UNSIGNED NOT NULL,
  `history_silver` smallint(5) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 테이블 구조 `jewelry_log`
--

CREATE TABLE `jewelry_log` (
  `ID` int(10) UNSIGNED NOT NULL,
  `stamp` int(10) UNSIGNED NOT NULL,
  `bonus_count` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `before_diamond` int(10) UNSIGNED NOT NULL,
  `before_gold` int(10) UNSIGNED NOT NULL,
  `before_silver` int(10) UNSIGNED NOT NULL,
  `before_bonus_diamond` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `before_bonus_gold` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `before_bonus_silver` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `apply_diamond` int(10) UNSIGNED NOT NULL,
  `apply_gold` int(10) UNSIGNED NOT NULL,
  `apply_silver` int(10) UNSIGNED NOT NULL,
  `after_diamond` int(10) UNSIGNED NOT NULL,
  `after_gold` int(10) UNSIGNED NOT NULL,
  `after_silver` int(10) UNSIGNED NOT NULL,
  `after_bonus_diamond` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `after_bonus_gold` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `after_bonus_silver` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `from_user_ID` int(10) UNSIGNED NOT NULL,
  `to_user_ID` int(10) UNSIGNED NOT NULL,
  `reason` varchar(32) NOT NULL,
  `item` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 테이블 구조 `location`
--

CREATE TABLE `location` (
  `user_ID` int(10) UNSIGNED NOT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `accuracy` double NOT NULL,
  `altitude` double NOT NULL,
  `speed` double NOT NULL,
  `heading` double NOT NULL,
  `time` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 테이블 구조 `purchase_history`
--

CREATE TABLE `purchase_history` (
  `ID` int(10) UNSIGNED NOT NULL,
  `user_ID` int(10) UNSIGNED NOT NULL,
  `stamp` int(10) UNSIGNED NOT NULL,
  `status` varchar(8) NOT NULL DEFAULT '',
  `platform` varchar(7) NOT NULL,
  `productID` varchar(32) NOT NULL,
  `purchaseID` varchar(128) NOT NULL,
  `price` varchar(32) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `applicationUsername` varchar(255) NOT NULL,
  `transactionDate` bigint(20) UNSIGNED NOT NULL,
  `productIdentifier` varchar(32) NOT NULL,
  `quantity` smallint(5) UNSIGNED NOT NULL,
  `transactionIdentifier` bigint(20) UNSIGNED NOT NULL,
  `transactionTimeStamp` double UNSIGNED NOT NULL,
  `localVerificationData` mediumtext NOT NULL,
  `serverVerificationData` mediumtext NOT NULL,
  `localVerificationData_packageName` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 테이블 구조 `push_token`
--

CREATE TABLE `push_token` (
  `token` varchar(255) NOT NULL,
  `user_ID` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `platform` varchar(64) NOT NULL DEFAULT '',
  `stamp` int(10) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 테이블 구조 `translations`
--

CREATE TABLE `translations` (
  `language` varchar(16) NOT NULL,
  `code` varchar(255) NOT NULL,
  `value` longtext NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 덤프된 테이블의 인덱스
--

--
-- 테이블의 인덱스 `bio`
--
ALTER TABLE `bio`
  ADD PRIMARY KEY (`user_ID`),
  ADD KEY `birthdate` (`birthdate`),
  ADD KEY `height` (`height`),
  ADD KEY `weight` (`weight`),
  ADD KEY `city` (`city`),
  ADD KEY `hobby` (`hobby`),
  ADD KEY `profile_photo_url` (`profile_photo_url`(8)),
  ADD KEY `name` (`name`),
  ADD KEY `latitude` (`latitude`),
  ADD KEY `longitude` (`longitude`);

--
-- 테이블의 인덱스 `jewelry_credit`
--
ALTER TABLE `jewelry_credit`
  ADD PRIMARY KEY (`user_ID`);

--
-- 테이블의 인덱스 `jewelry_daily_bonus`
--
ALTER TABLE `jewelry_daily_bonus`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `user_ID_date` (`user_ID`,`date`),
  ADD KEY `user_ID` (`user_ID`),
  ADD KEY `date` (`date`),
  ADD KEY `stamp` (`stamp`);

--
-- 테이블의 인덱스 `jewelry_log`
--
ALTER TABLE `jewelry_log`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `stamp` (`stamp`),
  ADD KEY `from_user_ID` (`from_user_ID`),
  ADD KEY `to_user_ID` (`to_user_ID`),
  ADD KEY `from_user_ID_to_user_ID` (`from_user_ID`,`to_user_ID`),
  ADD KEY `item` (`item`),
  ADD KEY `to_user_ID_item` (`to_user_ID`,`item`),
  ADD KEY `to_user_ID_from_user_ID` (`to_user_ID`,`from_user_ID`) USING BTREE,
  ADD KEY `from_user_ID_reason` (`from_user_ID`,`reason`),
  ADD KEY `to_user_ID_reason` (`to_user_ID`,`reason`);

--
-- 테이블의 인덱스 `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`user_ID`),
  ADD KEY `latitude` (`latitude`),
  ADD KEY `longitude` (`longitude`);

--
-- 테이블의 인덱스 `purchase_history`
--
ALTER TABLE `purchase_history`
  ADD PRIMARY KEY (`ID`);

--
-- 테이블의 인덱스 `push_token`
--
ALTER TABLE `push_token`
  ADD PRIMARY KEY (`token`),
  ADD KEY `user_ID` (`user_ID`),
  ADD KEY `stamp` (`stamp`),
  ADD KEY `platform` (`platform`) USING BTREE;

--
-- 테이블의 인덱스 `translations`
--
ALTER TABLE `translations`
  ADD UNIQUE KEY `language_code` (`language`,`code`);

--
-- 덤프된 테이블의 AUTO_INCREMENT
--

--
-- 테이블의 AUTO_INCREMENT `jewelry_daily_bonus`
--
ALTER TABLE `jewelry_daily_bonus`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 테이블의 AUTO_INCREMENT `jewelry_log`
--
ALTER TABLE `jewelry_log`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 테이블의 AUTO_INCREMENT `purchase_history`
--
ALTER TABLE `purchase_history`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
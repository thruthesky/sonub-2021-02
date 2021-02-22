-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- 생성 시간: 21-02-22 06:52
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
-- 데이터베이스: `sonub`
--

-- --------------------------------------------------------

--
-- 테이블 구조 `api_forum_vote_history`
--

CREATE TABLE `api_forum_vote_history` (
                                          `ID` int(11) NOT NULL,
                                          `user_ID` int(10) UNSIGNED NOT NULL,
                                          `target_ID` varchar(16) NOT NULL,
                                          `choice` char(1) NOT NULL,
                                          `stamp` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 테이블 구조 `api_in_app_purchase_history`
--

CREATE TABLE `api_in_app_purchase_history` (
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
-- 테이블 구조 `api_order_history`
--

CREATE TABLE `api_order_history` (
                                     `ID` int(11) NOT NULL,
                                     `user_ID` int(10) UNSIGNED NOT NULL,
                                     `info` text NOT NULL,
                                     `stamp` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 테이블 구조 `api_point_history`
--

CREATE TABLE `api_point_history` (
                                     `ID` int(10) UNSIGNED NOT NULL,
                                     `from_user_ID` int(10) UNSIGNED NOT NULL,
                                     `to_user_ID` int(10) UNSIGNED NOT NULL,
                                     `reason` varchar(32) NOT NULL,
                                     `target_ID` varchar(16) NOT NULL,
                                     `cat_ID` int(10) UNSIGNED NOT NULL DEFAULT 0,
                                     `stamp` int(10) UNSIGNED NOT NULL,
                                     `from_user_point_apply` int(11) NOT NULL,
                                     `from_user_point_after` int(11) NOT NULL,
                                     `to_user_point_apply` int(11) NOT NULL,
                                     `to_user_point_after` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 테이블 구조 `api_push_tokens`
--

CREATE TABLE `api_push_tokens` (
                                   `token` varchar(255) NOT NULL,
                                   `user_ID` int(10) UNSIGNED NOT NULL DEFAULT 0,
                                   `platform` varchar(64) NOT NULL DEFAULT '',
                                   `stamp` int(10) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 테이블 구조 `api_translations`
--

CREATE TABLE `api_translations` (
                                    `language` varchar(16) NOT NULL,
                                    `code` varchar(255) NOT NULL,
                                    `value` longtext NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 덤프된 테이블의 인덱스
--

--
-- 테이블의 인덱스 `api_forum_vote_history`
--
ALTER TABLE `api_forum_vote_history`
    ADD PRIMARY KEY (`ID`);

--
-- 테이블의 인덱스 `api_in_app_purchase_history`
--
ALTER TABLE `api_in_app_purchase_history`
    ADD PRIMARY KEY (`ID`);

--
-- 테이블의 인덱스 `api_order_history`
--
ALTER TABLE `api_order_history`
    ADD PRIMARY KEY (`ID`);

--
-- 테이블의 인덱스 `api_point_history`
--
ALTER TABLE `api_point_history`
    ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `ID` (`ID`,`from_user_ID`,`to_user_ID`,`reason`),
  ADD KEY `to_user_ID_reason` (`to_user_ID`,`stamp`),
  ADD KEY `from_user_ID_reason` (`ID`,`to_user_ID`),
  ADD KEY `from_user_ID_cat_ID_reason_stamp` (`from_user_ID`,`cat_ID`,`reason`,`stamp`);

--
-- 테이블의 인덱스 `api_push_tokens`
--
ALTER TABLE `api_push_tokens`
    ADD PRIMARY KEY (`token`),
  ADD KEY `user_ID` (`user_ID`),
  ADD KEY `stamp` (`stamp`),
  ADD KEY `platform` (`platform`) USING BTREE;

--
-- 테이블의 인덱스 `api_translations`
--
ALTER TABLE `api_translations`
    ADD UNIQUE KEY `language_code` (`language`,`code`);

--
-- 덤프된 테이블의 AUTO_INCREMENT
--

--
-- 테이블의 AUTO_INCREMENT `api_forum_vote_history`
--
ALTER TABLE `api_forum_vote_history`
    MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- 테이블의 AUTO_INCREMENT `api_in_app_purchase_history`
--
ALTER TABLE `api_in_app_purchase_history`
    MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 테이블의 AUTO_INCREMENT `api_order_history`
--
ALTER TABLE `api_order_history`
    MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- 테이블의 AUTO_INCREMENT `api_point_history`
--
ALTER TABLE `api_point_history`
    MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

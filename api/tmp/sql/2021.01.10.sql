-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- 생성 시간: 21-01-11 12:19
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
  `name` varchar(32) NOT NULL DEFAULT '''',
  `birthdate` char(8) NOT NULL DEFAULT '''',
  `gender` char(1) NOT NULL DEFAULT '''',
  `height` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `weight` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `city` varchar(32) NOT NULL DEFAULT '''',
  `drinking` char(1) NOT NULL DEFAULT '''',
  `smoking` char(1) NOT NULL DEFAULT '''',
  `hobby` varchar(32) NOT NULL DEFAULT '''',
  `dateMethod` varchar(255) NOT NULL DEFAULT '''',
  `createdAt` int(10) UNSIGNED NOT NULL,
  `updatedAt` int(10) UNSIGNED NOT NULL
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
  ADD KEY `hobby` (`hobby`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

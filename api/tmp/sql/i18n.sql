
CREATE TABLE `i18n` (
  `language` varchar(16) NOT NULL,
  `code` varchar(255) NOT NULL,
  `value` longtext NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 덤프된 테이블의 인덱스
--

--
-- 테이블의 인덱스 `i18n`
--
ALTER TABLE `i18n`
  ADD UNIQUE KEY `language_code` (`language`,`code`);
-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- ホスト: localhost
-- 生成日時: 2012 年 6 月 15 日 22:25
-- サーバのバージョン: 5.1.62
-- PHP のバージョン: 5.3.12

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- データベース: `book_library`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `books`
--

CREATE TABLE IF NOT EXISTS `books` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `ISBN-10` int(11) NOT NULL,
  `ISBN-13` int(11) NOT NULL,
  `saled_at` bigint(20) NOT NULL,
  `created_at` bigint(20) NOT NULL,
  `place` text NOT NULL,
  PRIMARY KEY (`ISBN-13`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

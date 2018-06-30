-- phpMyAdmin SQL Dump
-- version 3.4.1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Июл 21 2011 г., 08:27
-- Версия сервера: 5.0.67
-- Версия PHP: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `Selena`
--

-- --------------------------------------------------------

--
-- Структура таблицы `t_abon`
--

DROP TABLE IF EXISTS `t_abon`;
CREATE TABLE IF NOT EXISTS `t_abon` (
  `d_time` datetime NOT NULL,
  `account` varchar(20) NOT NULL,
  `txn_id` varchar(20) NOT NULL,
  `prv_txn` int(20) NOT NULL,
  `sum` double(6,2) NOT NULL,
  `trm_id` int(16) NOT NULL,
  `result` int(3) default NULL,
  `prv_id` int(6) NOT NULL,
  `coun` int(3) NOT NULL,
  `error` int(1) NOT NULL,
  PRIMARY KEY  (`txn_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

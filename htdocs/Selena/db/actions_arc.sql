-- phpMyAdmin SQL Dump
-- version 3.4.1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Июн 09 2011 г., 22:20
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
-- Структура таблицы `actions`
--

CREATE TABLE IF NOT EXISTS `actions` (
  `TabNum` int(4) NOT NULL COMMENT 'Табельный номер сотрудника',
  `Bill_Dog` int(6) NOT NULL,
  `Nic` varchar(20) default NULL COMMENT 'Сетевой Ник пользователя',
  `Login` varchar(20) default NULL COMMENT 'Интернет логин пользователя',
  `InputDate` datetime default NULL COMMENT 'Дата ввода операции',
  `Date_start` date default NULL COMMENT 'Дата к операции',
  `Date_end` date default NULL,
  `Summa` int(5) default NULL COMMENT 'Сумма по операции',
  `id_ActionType` int(2) default NULL COMMENT 'идентификатор типа операции',
  `Comment` varchar(40) default NULL COMMENT 'идентификатор комментария',
  `TN_canc` int(4) default NULL,
  `canc` tinyint(4) default '0',
  `Date_ed` datetime default NULL,
  KEY `i_Nic` (`Nic`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

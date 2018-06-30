-- phpMyAdmin SQL Dump
-- version 3.4.1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Июн 06 2011 г., 17:30
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
-- Структура для представления `v_t_inet`
--

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_t_inet` AS select `t_inet`.`d_time` AS `d_time`,`t_inet`.`account` AS `account`,`t_inet`.`txn_id` AS `txn_id`,`t_inet`.`prv_txn` AS `prv_txn`,`t_inet`.`sum` AS `sum`,`t_inet`.`trm_id` AS `trm_id`,`t_inet`.`result` AS `result`,`t_inet`.`prv_id` AS `prv_id`,`t_inet`.`coun` AS `coun`,`t_inet`.`error` AS `error`,`spr_error`.`er_descr` AS `er_descr` from (`t_inet` join `spr_error` on((`spr_error`.`error` = `t_inet`.`error`)));

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

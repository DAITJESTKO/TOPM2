-- phpMyAdmin SQL Dump
-- version 3.4.1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Авг 07 2011 г., 21:11
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
-- Структура для представления `v_hist_cod`
--

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_hist_cod` AS select `hist_cod`.`Bill_Dog` AS `Bill_Dog`,`hist_cod`.`ch_date` AS `ch_date`,`hist_cod`.`new_cod` AS `new_cod`,`hist_cod`.`TabNum` AS `TabNum`,`v_cod_adr`.`flat` AS `flat`,`v_cod_adr`.`Num_build` AS `Num_build`,`v_cod_adr`.`Korpus` AS `Korpus`,`v_cod_adr`.`name_street` AS `name_street`,`v_cod_adr`.`Town` AS `Town`,`v_cod_adr`.`RegionName` AS `RegionName` from (`hist_cod` join `v_cod_adr` on((`hist_cod`.`new_cod` = `v_cod_adr`.`Cod_flat`)));

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

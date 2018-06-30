-- phpMyAdmin SQL Dump
-- version 3.4.1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Авг 07 2011 г., 21:53
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
-- Структура для представления `v_cod_adr`
--

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_cod_adr` AS select `cod_flat`.`Cod_flat` AS `Cod_flat`,`cod_flat`.`flat` AS `flat`,`cod_flat`.`floor` AS `floor`,`spr_build`.`Num_build` AS `Num_build`,`spr_build`.`Korpus` AS `Korpus`,`spr_street`.`name_street` AS `name_street`,`spr_town`.`Town` AS `Town`,`cod_flat`.`id_Podjezd` AS `id_Podjezd`,`spr_region`.`RegionName` AS `RegionName` from (((((`cod_flat` join `spr_podjezd` on((`cod_flat`.`id_Podjezd` = `spr_podjezd`.`id_Podjezd`))) join `spr_build` on((`spr_podjezd`.`id_korp` = `spr_build`.`id_korp`))) join `spr_street` on((`spr_build`.`id_street` = `spr_street`.`id_street`))) join `spr_town` on((`spr_street`.`id_Town` = `spr_town`.`id_Town`))) join `spr_region` on((`spr_build`.`id_Region` = `spr_region`.`id_Region`)));

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

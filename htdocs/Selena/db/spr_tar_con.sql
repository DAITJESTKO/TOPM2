-- phpMyAdmin SQL Dump
-- version 3.4.1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Июн 14 2011 г., 00:04
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

--
-- Дамп данных таблицы `spr_tar_con`
--

INSERT INTO `spr_tar_con` (`id_tar_con`, `name_cn`, `name_con`, `con_sum`, `opl_period`, `ab_sum`, `con_typ`, `perstypes`, `k_tar`, `id_tarifab`) VALUES
(0, '    -', '', NULL, NULL, 200, 0, NULL, NULL, NULL),
(1, '1300+1м', 'Стандарт 1300+1м', 1300, 1, 200, 1, 3, 1.00, 1),
(2, 'СтдЛьгт', 'Стандарт Льготный', 600, 1, 100, 1, 3, 0.50, 5),
(3, '1000+5м', 'Абонплата 1000', 1000, 5, 1000, 1, 3, 1.00, 1),
(4, '900+6м', 'Абонплата 1200', 900, 6, 1200, 1, 3, 1.00, 1),
(5, '800+7м', 'Абонплата 1400', 800, 7, 1400, 1, 3, 1.00, 1),
(6, '700+8м', 'Абонплата 1600', 700, 8, 1600, 1, 3, 1.00, 1),
(7, '600+9м', 'Абонплата 1800', 600, 9, 1800, 1, 3, 1.00, 1),
(8, '500+10м', 'Абонплата 2000', 500, 10, 2000, 1, 3, 1.00, 1),
(9, '400+11м', 'Абонплата 2200', 400, 11, 2200, 1, 3, 1.00, 1),
(10, '300+12м', 'Абонплата 2400', 300, 12, 2400, 1, 3, 1.00, 1),
(11, 'VIP', 'VIP', NULL, 1, 500, 1, 2, NULL, 6),
(12, 'сеть', 'Свой/работа в сети', NULL, NULL, NULL, 1, 1, NULL, 2),
(13, 'Свой', 'Свой - без аб/платы', NULL, NULL, NULL, 1, 1, NULL, 3),
(14, 'Б/нал', 'Безнал', NULL, NULL, NULL, 1, 3, NULL, 4),
(15, '500+1мес', '', 500, 1, 200, 4, 3, 1.00, 1),
(16, 'доп.комп', '', 750, 1, 150, 2, 3, NULL, 1),
(17, 'смена вл.', '', 50, NULL, NULL, 3, 3, NULL, 1),
(18, 'смена адр.', '', 300, NULL, NULL, 5, 3, NULL, 1),
(19, 'переофор.', '', 300, NULL, NULL, 6, 3, NULL, 1),
(20, '0+6мес', '', 0, 6, 1200, 4, 3, 1.00, 1),
(21, '0+3мес', '', 0, 3, 600, 4, 3, 1.00, 1),
(23, '100+1мес.', 'Подкл.должника', 100, 1, NULL, 4, 3, NULL, 1),
(22, 'др.сеть', 'только интернет', NULL, NULL, NULL, 1, 3, 0.00, 7);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

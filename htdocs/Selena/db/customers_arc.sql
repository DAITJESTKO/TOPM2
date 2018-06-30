SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS `customers_arc` (
  `Cod_flat` int(6) NOT NULL COMMENT 'Код квартиры',
  `Bill_Dog` int(6) NOT NULL COMMENT 'счёт (№ аб.договора)',
  `Saldo` double(7,2) default NULL,
  `cost` int(6) default NULL,
  `Fam` varchar(40) default NULL COMMENT 'Фамилия',
  `Name` varchar(40) default NULL COMMENT 'Имя',
  `Father` varchar(40) default NULL COMMENT 'Отчество',
  `id_Podjezd` int(11) default NULL COMMENT 'идентификатор подъезда',
  `flat` int(3) default NULL COMMENT '№ квартиры',
  `IP` varchar(15) default NULL COMMENT 'IP адрес',
  `mac` varchar(6) default NULL COMMENT 'Мас адрес',
  `pasp_Ser` varchar(5) default NULL COMMENT 'серия паспорта',
  `pasp_Num` int(6) default NULL COMMENT 'Номер паспорта',
  `pasp_Date` date default NULL COMMENT 'Дата выдачи паспорта',
  `pasp_Uvd` varchar(100) default NULL COMMENT 'Кем выдан паспорт',
  `pasp_Adr` varchar(80) default NULL COMMENT 'регистрация паспорта',
  `phone_Home` varchar(25) default NULL COMMENT 'Домашний телефон',
  `phone_Cell` varchar(25) default NULL COMMENT 'Сотовый телефон',
  `phone_Work` varchar(8) default NULL COMMENT 'рабочий телефон',
  `Birthday` date default NULL COMMENT 'Дата рождения',
  `Jur` tinyint(1) default NULL COMMENT 'Юридическое лицо ?',
  `Comment` varchar(50) default NULL COMMENT 'комментарий',
  `Nic` varchar(20) NOT NULL COMMENT 'Сетевой Ник',
  `Bill_frend` int(6) unsigned default NULL COMMENT 'Номер счёта друга',
  `TabNum` int(4) default NULL COMMENT 'Кто корректировал',
  `DateKor` datetime default NULL COMMENT 'Дата корректировки',
  `state` int(1) default NULL COMMENT 'статус',
  `conn` int(1) default NULL COMMENT 'подключение',
  `id_tarifab` int(1) default NULL COMMENT 'абон.тариф',
  `tarifab_date` date default NULL COMMENT 'дата подкл аб.тарифа',
  `Date_start_st` date default NULL COMMENT 'дата начала состояния',
  `Date_end_st` date default NULL COMMENT 'дата оконч.состояния',
  `Date_pay` date default NULL COMMENT 'оплачено по',
  `inet` tinyint(4) default NULL COMMENT 'учётка интернет?',
  `mont` int(4) default NULL COMMENT 'монтажник',
  `From_Net` varchar(15) default NULL COMMENT 'из какой сети',
  `floor` int(2) default NULL COMMENT 'этаж',
  PRIMARY KEY  (`Bill_Dog`),
  KEY `i_Nic` (`Nic`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

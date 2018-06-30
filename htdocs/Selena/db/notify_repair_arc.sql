SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS `notify_repair` (
  `Cod_flat` int(6) NOT NULL COMMENT 'код адресса',
  `Bill_Dog` int(6) NOT NULL COMMENT 'Счёт (какой?)',
  `Date_in` datetime NOT NULL COMMENT 'Дата подачи заявки',
  `id_Region` int(2) NOT NULL COMMENT 'Район',
  `Date_Plan` date default NULL COMMENT 'Планируемая дата выполнения заявки',
  `Date_Fact` date default NULL COMMENT 'Фактическая дата выполнения заявки',
  `Date_ed` datetime default NULL,
  `id_Sphere` int(2) default NULL COMMENT 'идентификатор области неисправности',
  `Notify` varchar(100) default NULL COMMENT 'Содержание заявки',
  `Num_Notify` int(6) NOT NULL auto_increment COMMENT 'Номер заявки',
  `Nic` varchar(20) NOT NULL COMMENT 'Ник заявителя',
  `TabNum` int(4) default NULL,
  `conn` varchar(20) default NULL,
  `id_p` int(11) default NULL,
  `fl` int(3) default NULL,
  `mont` int(4) default NULL COMMENT 'монтажник',
  `phone_Dop` varchar(15) default '0',
  `comment` varchar(20) default NULL,
  `TN_canc` int(4) default NULL,
  `fwdH` int(4) NOT NULL,
  `fwdW` datetime default NULL,
  `fwd2` int(4) NOT NULL,
  PRIMARY KEY  (`Num_Notify`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29871 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

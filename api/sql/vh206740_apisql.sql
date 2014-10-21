-- phpMyAdmin SQL Dump
-- version 3.5.4
-- http://www.phpmyadmin.net
--
-- Хост: 83.69.230.13
-- Время создания: Окт 21 2014 г., 17:03
-- Версия сервера: 5.1.67-log
-- Версия PHP: 5.3.21

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `vh206740_apisql`
--

-- --------------------------------------------------------

--
-- Структура таблицы `caffes`
--

CREATE TABLE IF NOT EXISTS `caffes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `addres` varchar(100) NOT NULL,
  `telephones` varchar(100) NOT NULL,
  `working_time` varchar(100) NOT NULL,
  `short_info` varchar(250) NOT NULL,
  `info` varchar(1000) NOT NULL,
  `preview_img` varchar(40) NOT NULL,
  `album_name` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `caffe_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `table_id` int(11) NOT NULL,
  `username` varchar(18) NOT NULL,
  `time` varchar(15) NOT NULL,
  `type` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `enter_time` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `rooms`
--

CREATE TABLE IF NOT EXISTS `rooms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `caffe_id` int(11) NOT NULL,
  `background_img` varchar(40) NOT NULL,
  `xLength` int(11) NOT NULL,
  `yLength` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `tables`
--

CREATE TABLE IF NOT EXISTS `tables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `number` int(11) NOT NULL,
  `room_index` int(11) NOT NULL,
  `xPos` int(11) NOT NULL,
  `yPox` int(11) NOT NULL,
  `table_type` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mail` varchar(32) NOT NULL,
  `password_hash` varchar(64) NOT NULL,
  `telephone` varchar(18) NOT NULL,
  `name` varchar(18) NOT NULL,
  `lastname` varchar(18) NOT NULL,
  `access_level` int(11) NOT NULL,
  `reg_code` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `mail`, `password_hash`, `telephone`, `name`, `lastname`, `access_level`, `reg_code`) VALUES
(1, 'mymail@mymail.by', 'hashPassword', '+375445378289', 'myname', 'mylastname', 1, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

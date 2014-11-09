-- phpMyAdmin SQL Dump
-- version 3.5.4
-- http://www.phpmyadmin.net
--
-- Хост: 83.69.230.13
-- Время создания: Ноя 09 2014 г., 16:41
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
  `address` varchar(100) NOT NULL,
  `telephones` varchar(100) NOT NULL,
  `working_time` varchar(100) NOT NULL,
  `short_info` varchar(250) NOT NULL,
  `info` varchar(1000) NOT NULL,
  `wifi` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `rating` float NOT NULL,
  `number_voters` int(11) NOT NULL,
  `sum_votes` int(11) NOT NULL,
  `preview_img` varchar(40) NOT NULL,
  `album_name` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Дамп данных таблицы `caffes`
--

INSERT INTO `caffes` (`id`, `name`, `address`, `telephones`, `working_time`, `short_info`, `info`, `wifi`, `type`, `rating`, `number_voters`, `sum_votes`, `preview_img`, `album_name`) VALUES
(1, 'цфв', 'фцв', '213123', '123', '21312', 'фцвфц', 1, 1, 1, 2, 9, 'фцвфцв', 'фцвфцв'),
(2, '1', '1', '1', '1', '1', '1', 1, 1, 1, 1, 1, '1', '1'),
(3, '1', '1', '1', '1', '1', '1', 1, 1, 1, 1, 1, '1', '1'),
(4, '1', '1', '1', '1', '1', '1', 1, 1, 1, 1, 1, '1', '1'),
(5, '1', '1', '1', '1', '1', '1', 1, 1, 1, 1, 1, '1', '1'),
(6, '1', '1', '1', '1', '1', '1', 1, 1, 1, 1, 1, '1', '1');

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `table_id` int(11) NOT NULL,
  `user_id` varchar(18) NOT NULL,
  `time` datetime NOT NULL,
  `type` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `enter_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `rooms`
--

INSERT INTO `rooms` (`id`, `name`, `caffe_id`, `background_img`, `xLength`, `yLength`) VALUES
(3, 'awdaw', 1, 'awdawd', 123, 93);

-- --------------------------------------------------------

--
-- Структура таблицы `tables`
--

CREATE TABLE IF NOT EXISTS `tables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `number` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `xPos` int(11) NOT NULL,
  `yPos` int(11) NOT NULL,
  `tableType` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Дамп данных таблицы `tables`
--

INSERT INTO `tables` (`id`, `number`, `room_id`, `xPos`, `yPos`, `tableType`, `status`) VALUES
(8, 1, 3, 1, 2, 1, 4),
(9, 2, 3, 1, 2, 1, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(64) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `access_level` tinyint(3) unsigned NOT NULL,
  `firstname` varchar(35) NOT NULL,
  `lastname` varchar(35) NOT NULL,
  `reg_code` int(10) unsigned NOT NULL,
  `reg_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `uname_pass_index` (`email`,`password_hash`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `email`, `password_hash`, `phone`, `access_level`, `firstname`, `lastname`, `reg_code`, `reg_time`) VALUES
(1, 'awd', 'awd', '12312312', 1, 'awd', 'awd', 123, '2014-10-22 20:00:00'),
(20, '2we', '76d80224611fc919a5d54f0ff9fba446', '2dd32', 1, 'name', 'lastname', 8735, '2014-10-29 14:57:29'),
(21, '1we', 'e946b5dc678735e0d7b9a5b20110d764', '232', 1, 'name', 'lastname', 0, '2014-10-29 15:12:57'),
(22, '2e', 'e946b5dc678735e0d7b9a5b20110d764', '2', 0, 'name', 'lastname', 3393, '2014-10-29 15:16:00'),
(23, '2123e', 'e946b5dc678735e0d7b9a5b20110d764', '233', 1, 'name', 'lastname', 0, '2014-10-29 16:35:51'),
(24, 'azaza', '202cb962ac59075b964b07152d234b70', '123123', 0, 'name', 'familiableat', 1179, '2014-11-02 15:14:47');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

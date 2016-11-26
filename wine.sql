<<<<<<< HEAD
TEST 
=======
-- phpMyAdmin SQL Dump
-- version 4.0.10.10
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Ноя 24 2016 г., 04:17
-- Версия сервера: 5.5.45-log
-- Версия PHP: 5.4.44

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `wine`
--

-- --------------------------------------------------------

--
-- Структура таблицы `cart_items`
--

CREATE TABLE IF NOT EXISTS `cart_items` (
  `id_item` int(4) NOT NULL AUTO_INCREMENT,
  `id_order` int(4) NOT NULL,
  `id_wine` int(4) NOT NULL,
  `quantity` int(4) NOT NULL,
  PRIMARY KEY (`id_item`),
  KEY `fk_wine_id` (`id_wine`),
  KEY `fk_order_id` (`id_order`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Дамп данных таблицы `cart_items`
--

INSERT INTO `cart_items` (`id_item`, `id_order`, `id_wine`, `quantity`) VALUES
(1, 1, 6, 1),
(2, 1, 1, 4),
(3, 1, 2, 2),
(4, 2, 14, 2),
(5, 2, 16, 3),
(6, 4, 5, 1),
(7, 4, 7, 5),
(9, 5, 2, 2),
(10, 5, 10, 4),
(11, 5, 14, 1),
(12, 6, 4, 2),
(13, 6, 16, 1),
(14, 6, 11, 3);

-- --------------------------------------------------------

--
-- Структура таблицы `clients`
--

CREATE TABLE IF NOT EXISTS `clients` (
  `id_client` int(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `address` varchar(50) DEFAULT NULL,
  `id_user` int(4) DEFAULT NULL,
  PRIMARY KEY (`id_client`),
  KEY `fk_id_user` (`id_user`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Дамп данных таблицы `clients`
--

INSERT INTO `clients` (`id_client`, `name`, `phone`, `email`, `address`, `id_user`) VALUES
(1, 'Marina', '0993774688', 'mar@gmail.com', 'Shkolnaya street 5', 1),
(2, 'Maxim', '0661773542', NULL, 'Sumskaya 328', NULL),
(4, 'Pavel', '0883222222', NULL, 'Prospekt Pobedy 33', 2),
(5, 'Ivan', '077322484343', 'van@mail.ru', NULL, 3);

-- --------------------------------------------------------

--
-- Структура таблицы `colors`
--

CREATE TABLE IF NOT EXISTS `colors` (
  `id_color` int(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `image` varchar(50) NOT NULL,
  PRIMARY KEY (`id_color`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `colors`
--

INSERT INTO `colors` (`id_color`, `name`, `image`) VALUES
(1, 'blanc', 'blanc.png'),
(2, 'rose', 'rose.png'),
(3, 'rouge', 'rouge.png');

-- --------------------------------------------------------

--
-- Структура таблицы `countries`
--

CREATE TABLE IF NOT EXISTS `countries` (
  `id_country` int(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `image` varchar(50) NOT NULL,
  PRIMARY KEY (`id_country`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `countries`
--

INSERT INTO `countries` (`id_country`, `name`, `image`) VALUES
(1, 'Allemagne', 'allemagne.png'),
(2, 'France', 'france.png'),
(3, 'Italie', 'italie.png'),
(4, 'Autriche', 'autriche.png');

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `id_order` int(4) NOT NULL AUTO_INCREMENT,
  `id_client` int(4) NOT NULL,
  `date_create` date NOT NULL,
  `time_create` time NOT NULL,
  `date_delivery` date NOT NULL,
  `time_delivery` time DEFAULT NULL,
  `suggestions` text,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_order`),
  KEY `fk_client_id` (`id_client`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id_order`, `id_client`, `date_create`, `time_create`, `date_delivery`, `time_delivery`, `suggestions`, `status`) VALUES
(1, 1, '2016-11-23', '00:00:49', '2016-11-25', '20:30:00', NULL, 1),
(2, 2, '2016-11-23', '00:05:17', '2016-11-23', '00:00:00', NULL, 0),
(4, 1, '2016-11-23', '00:52:36', '2016-11-25', '00:00:00', NULL, 0),
(5, 4, '2016-11-23', '02:44:00', '2016-11-27', '11:11:00', NULL, 0),
(6, 1, '2016-11-23', '17:43:43', '2016-11-23', '00:00:00', NULL, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `sizes`
--

CREATE TABLE IF NOT EXISTS `sizes` (
  `id_size` int(4) NOT NULL AUTO_INCREMENT,
  `value` varchar(50) NOT NULL,
  PRIMARY KEY (`id_size`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `sizes`
--

INSERT INTO `sizes` (`id_size`, `value`) VALUES
(1, '0.5'),
(2, '0.75'),
(3, '1.0');

-- --------------------------------------------------------

--
-- Структура таблицы `types`
--

CREATE TABLE IF NOT EXISTS `types` (
  `id_type` int(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id_type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `types`
--

INSERT INTO `types` (`id_type`, `name`) VALUES
(1, 'sec'),
(2, 'demi-sec'),
(3, 'doux'),
(4, 'demi-doux');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id_user` int(4) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id_user`, `username`, `password`) VALUES
(1, 'mar', '$2y$12$xyuYGaNMTcisrOw35ImLfuAOk7bT5guCTD6685KTfmwQnW3Mtt6eS'),
(2, 'pavel', '$2y$12$KGp8mz2Aw0ZFGTHunzIL4uHcJEZEuKdGf0D.xWpjVJH93WDg3s4.C'),
(3, 'ivan', '$2y$12$N.8E3C4VEtSOxQUiStdOWORd1Vth0DKzMCrijGcvfJQZmpF.ccGke');

-- --------------------------------------------------------

--
-- Структура таблицы `wines`
--

CREATE TABLE IF NOT EXISTS `wines` (
  `id_wine` int(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `price` decimal(5,2) NOT NULL,
  `id_type` int(4) NOT NULL,
  `id_color` int(4) NOT NULL,
  `id_country` int(4) NOT NULL,
  `id_size` int(4) NOT NULL,
  `date` int(4) NOT NULL,
  `image` varchar(50) NOT NULL,
  PRIMARY KEY (`id_wine`),
  KEY `fk_id_color` (`id_color`),
  KEY `fk_id_country` (`id_country`),
  KEY `fk_id_size` (`id_size`),
  KEY `fk_id_type` (`id_type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Дамп данных таблицы `wines`
--

INSERT INTO `wines` (`id_wine`, `name`, `price`, `id_type`, `id_color`, `id_country`, `id_size`, `date`, `image`) VALUES
(1, 'Alceno', '10.00', 1, 3, 2, 2, 2013, 'alceno.png'),
(2, 'Beaujolais nouveau', '20.00', 3, 3, 4, 2, 2015, 'beaujolais-nouveau.jpg'),
(3, 'Chateau jean', '11.00', 2, 3, 1, 2, 2015, 'chateau-jean.png'),
(4, 'Cotes du rhone', '12.50', 4, 3, 2, 2, 2015, 'cotes-du-rhone.png'),
(5, 'Cuvee confidence', '8.40', 3, 3, 2, 3, 2014, 'cuvee-confidence.png'),
(6, 'La vieille ferme', '8.30', 2, 3, 2, 1, 2015, 'la-vieille-ferme.png'),
(7, 'Les darons', '7.50', 4, 3, 3, 3, 2013, 'les-darons.png'),
(8, 'Gris blanc', '9.60', 3, 2, 2, 2, 2014, 'gris-blanc.png'),
(9, 'Les jolies filles', '10.30', 3, 2, 3, 3, 2013, 'les-jolies-filles.png'),
(10, 'Les petits diables', '11.20', 4, 2, 2, 2, 2014, 'les-petits-diables.png'),
(11, 'Mip classic', '13.50', 3, 2, 4, 3, 2015, 'mip-classic.png'),
(12, 'Prestige rose', '15.00', 3, 2, 2, 3, 2014, 'prestige-rose.png'),
(13, 'Premieres grives', '12.10', 3, 1, 4, 3, 2013, 'premieres-grives.png'),
(14, 'Uby unique', '11.00', 4, 1, 3, 1, 2014, 'uby-collection-unique.png'),
(15, 'Uby colombard ugni', '13.20', 3, 1, 3, 2, 2013, 'uby-colombard-ugni.png'),
(16, 'Uby gros manseng', '12.50', 1, 1, 3, 1, 2015, 'uby-gros-manseng.png'),
(17, 'Viognier marius', '9.00', 4, 1, 2, 1, 2015, 'viognier-marius.png');

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`id_order`) REFERENCES `orders` (`id_order`),
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`id_wine`) REFERENCES `wines` (`id_wine`);

--
-- Ограничения внешнего ключа таблицы `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `clients_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);

--
-- Ограничения внешнего ключа таблицы `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`id_client`) REFERENCES `clients` (`id_client`);

--
-- Ограничения внешнего ключа таблицы `wines`
--
ALTER TABLE `wines`
  ADD CONSTRAINT `fk_id_colors` FOREIGN KEY (`id_color`) REFERENCES `colors` (`id_color`),
  ADD CONSTRAINT `fk_id_countries` FOREIGN KEY (`id_country`) REFERENCES `countries` (`id_country`),
  ADD CONSTRAINT `fk_id_sizes` FOREIGN KEY (`id_size`) REFERENCES `sizes` (`id_size`),
  ADD CONSTRAINT `fk_id_types` FOREIGN KEY (`id_type`) REFERENCES `types` (`id_type`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
>>>>>>> 263091530baabeaca1f72c645d4e8073740e3c2a

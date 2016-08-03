-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Авг 02 2016 г., 19:11
-- Версия сервера: 10.1.13-MariaDB
-- Версия PHP: 7.0.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `myecocity`
--

-- --------------------------------------------------------

--
-- Структура таблицы `events`
--

CREATE TABLE `events` (
  `ev_id` int(11) NOT NULL,
  `ev_title` varchar(200) COLLATE utf8_bin NOT NULL,
  `ev_description` text COLLATE utf8_bin,
  `ev_create_date` datetime DEFAULT NULL,
  `ev_begin_date` date DEFAULT NULL,
  `ev_begin_time` time NOT NULL,
  `ev_end_date` date NOT NULL,
  `ev_end_time` time NOT NULL,
  `ev_address` text COLLATE utf8_bin NOT NULL,
  `ev_archive` char(1) COLLATE utf8_bin NOT NULL DEFAULT 'Y',
  `ev_url` text COLLATE utf8_bin NOT NULL,
  `ev_image` text COLLATE utf8_bin NOT NULL,
  `u_id` mediumint(8) NOT NULL,
  `reg_id` mediumint(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `geo_points`
--

CREATE TABLE `geo_points` (
  `geo_id` mediumint(8) NOT NULL,
  `geo_x` char(10) COLLATE utf8_bin DEFAULT NULL,
  `geo_y` char(10) COLLATE utf8_bin NOT NULL,
  `geo_address` char(100) COLLATE utf8_bin NOT NULL,
  `obj_id` bigint(20) NOT NULL,
  `obj_type` tinytext COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `images`
--

CREATE TABLE `images` (
  `i_id` mediumint(9) NOT NULL,
  `i_file` char(10) COLLATE utf8_bin NOT NULL,
  `i_description` varchar(255) COLLATE utf8_bin NOT NULL,
  `obj_id` mediumint(9) NOT NULL,
  `obj_type` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `objects`
--

CREATE TABLE `objects` (
  `obj_id` bigint(20) NOT NULL,
  `obj_type` tinyint(4) NOT NULL,
  `p_id` mediumint(9) NOT NULL,
  `ev_id` mediumint(9) NOT NULL,
  `rc_id` mediumint(9) NOT NULL,
  `org_id` mediumint(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `organizations`
--

CREATE TABLE `organizations` (
  `org_id` mediumint(8) NOT NULL,
  `org_name` varchar(50) COLLATE utf8_bin NOT NULL,
  `org_slug` varchar(20) COLLATE utf8_bin NOT NULL,
  `org_site` varchar(100) COLLATE utf8_bin NOT NULL,
  `org_logo` varchar(10) COLLATE utf8_bin NOT NULL,
  `org_tel` varchar(12) COLLATE utf8_bin NOT NULL,
  `org_email` varchar(50) COLLATE utf8_bin NOT NULL,
  `org_contact` text COLLATE utf8_bin NOT NULL,
  `org_description` text COLLATE utf8_bin NOT NULL,
  `sh_id` mediumint(9) NOT NULL,
  `reg_id` mediumint(9) NOT NULL,
  `org_type_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `organization_types`
--

CREATE TABLE `organization_types` (
  `org_type_id` int(11) NOT NULL,
  `org_type_rus` char(255) COLLATE utf8_bin DEFAULT NULL,
  `org_type_eng` char(255) COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Дамп данных таблицы `organization_types`
--

INSERT INTO `organization_types` (`org_type_id`, `org_type_rus`, `org_type_eng`) VALUES
(1, 'Эко магазин', 'Eco shop'),
(2, 'Приют для животных', 'Eco shelter'),
(3, 'Эко кафе', 'Eco cafe'),
(4, 'Пункт велопроката', 'Bicycles for rent'),
(5, 'Эко организация', 'Eco organization');

-- --------------------------------------------------------

--
-- Структура таблицы `pages`
--

CREATE TABLE `pages` (
  `p_id` mediumint(9) NOT NULL,
  `u_id` mediumint(9) NOT NULL,
  `p_date` datetime NOT NULL,
  `p_preview` char(255) COLLATE utf8_bin DEFAULT NULL,
  `p_text` text COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `recycling_points`
--

CREATE TABLE `recycling_points` (
  `rc_id` mediumint(8) NOT NULL,
  `rc_name` varchar(50) COLLATE utf8_bin NOT NULL,
  `rc_info` varchar(100) COLLATE utf8_bin NOT NULL,
  `sh_id` mediumint(9) NOT NULL,
  `rc_danger` tinyint(1) NOT NULL,
  `rc_paper` tinyint(1) NOT NULL,
  `rc_glass` tinyint(1) NOT NULL,
  `rc_plastic` tinyint(1) NOT NULL,
  `rc_metal` tinyint(1) NOT NULL,
  `rc_clothes` tinyint(1) NOT NULL,
  `rc_books` tinyint(1) NOT NULL,
  `rc_building` tinyint(1) NOT NULL,
  `rc_other` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `recycling_points_names`
--

CREATE TABLE `recycling_points_names` (
  `id` int(11) NOT NULL,
  `name_eng` char(250) COLLATE utf8_bin NOT NULL,
  `name_rus` char(250) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Дамп данных таблицы `recycling_points_names`
--

INSERT INTO `recycling_points_names` (`id`, `name_eng`, `name_rus`) VALUES
(0, 'Dangerous garbage', 'Опасные отходы'),
(1, 'Paper', 'Бумага'),
(2, 'Glass', 'Стекло'),
(3, 'Plastic', 'Пластик'),
(4, 'Metal', 'Металл'),
(5, 'Clothes', 'Одежда'),
(6, 'Books', 'Книги'),
(7, 'Construction materials', 'Строительные материалы'),
(8, 'Other', 'Другое');

-- --------------------------------------------------------

--
-- Структура таблицы `regions`
--

CREATE TABLE `regions` (
  `reg_id` mediumint(9) NOT NULL,
  `reg_union` mediumint(9) NOT NULL DEFAULT '0',
  `reg_county` mediumint(9) NOT NULL DEFAULT '0',
  `reg_province` mediumint(9) NOT NULL DEFAULT '0',
  `reg_district` mediumint(9) NOT NULL DEFAULT '0',
  `reg_city` mediumint(9) NOT NULL DEFAULT '0',
  `reg_name` char(40) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `roles`
--

CREATE TABLE `roles` (
  `r_id` int(11) NOT NULL,
  `r_type` char(100) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `schedule`
--

CREATE TABLE `schedule` (
  `sh_id` mediumint(9) NOT NULL,
  `sh_default` tinyint(1) NOT NULL DEFAULT '0',
  `sh_mon_start` time DEFAULT NULL,
  `sh_mon_end` time DEFAULT NULL,
  `sh_tue_start` time DEFAULT NULL,
  `sh_tue_end` time DEFAULT NULL,
  `sh_wed_start` time DEFAULT NULL,
  `sh_wed_end` time DEFAULT NULL,
  `sh_thu_start` time DEFAULT NULL,
  `sh_thu_end` time NOT NULL,
  `sh_fri_end` time DEFAULT NULL,
  `sh_fri_start` time DEFAULT NULL,
  `sh_sat_start` time DEFAULT NULL,
  `sh_sat_end` time DEFAULT NULL,
  `sh_sun_end` time DEFAULT NULL,
  `sh_sun_start` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `tags`
--

CREATE TABLE `tags` (
  `tag_id` smallint(5) NOT NULL,
  `tag_name` text COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `tags_icons`
--

CREATE TABLE `tags_icons` (
  `tag_id` smallint(6) NOT NULL,
  `ic_layout` char(30) COLLATE utf8_bin NOT NULL,
  `ic_image` char(10) COLLATE utf8_bin NOT NULL,
  `ic_baloontype` char(10) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `tags_objects`
--

CREATE TABLE `tags_objects` (
  `tag_id` smallint(5) NOT NULL,
  `obj_id` int(10) NOT NULL,
  `obj_type` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `tags_tree`
--

CREATE TABLE `tags_tree` (
  `mpatch` bigint(20) NOT NULL,
  `tag_level` tinyint(3) NOT NULL,
  `tag_id` smallint(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `u_id` mediumint(8) NOT NULL,
  `u_name` varchar(50) COLLATE utf8_bin NOT NULL,
  `u_fname` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `u_password` varchar(60) COLLATE utf8_bin NOT NULL,
  `u_email` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `u_telephone` varchar(15) COLLATE utf8_bin DEFAULT NULL,
  `u_create_date` datetime NOT NULL,
  `u_active_date` datetime NOT NULL,
  `reg_id` bigint(20) NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `geo_points`
--
ALTER TABLE `geo_points`
  ADD PRIMARY KEY (`geo_id`);

--
-- Индексы таблицы `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`i_id`);

--
-- Индексы таблицы `objects`
--
ALTER TABLE `objects`
  ADD PRIMARY KEY (`obj_id`);

--
-- Индексы таблицы `organizations`
--
ALTER TABLE `organizations`
  ADD PRIMARY KEY (`org_id`);

--
-- Индексы таблицы `organization_types`
--
ALTER TABLE `organization_types`
  ADD PRIMARY KEY (`org_type_id`),
  ADD UNIQUE KEY `org_type_id` (`org_type_id`);

--
-- Индексы таблицы `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`p_id`);

--
-- Индексы таблицы `recycling_points`
--
ALTER TABLE `recycling_points`
  ADD PRIMARY KEY (`rc_id`);

--
-- Индексы таблицы `recycling_points_names`
--
ALTER TABLE `recycling_points_names`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Индексы таблицы `regions`
--
ALTER TABLE `regions`
  ADD PRIMARY KEY (`reg_id`);

--
-- Индексы таблицы `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`r_id`);

--
-- Индексы таблицы `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`sh_id`);

--
-- Индексы таблицы `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`tag_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`u_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `geo_points`
--
ALTER TABLE `geo_points`
  MODIFY `geo_id` mediumint(8) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `organizations`
--
ALTER TABLE `organizations`
  MODIFY `org_id` mediumint(8) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `organization_types`
--
ALTER TABLE `organization_types`
  MODIFY `org_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `u_id` mediumint(8) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

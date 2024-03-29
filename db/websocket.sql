-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Мар 29 2024 г., 09:50
-- Версия сервера: 10.4.28-MariaDB
-- Версия PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `websocket`
--

-- --------------------------------------------------------

--
-- Структура таблицы `chatrooms`
--

CREATE TABLE `chatrooms` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `msg` varchar(200) NOT NULL,
  `created_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `chatrooms`
--

INSERT INTO `chatrooms` (`id`, `userid`, `msg`, `created_on`) VALUES
(108, 72, 'hi', '2024-03-29 10:27:34'),
(109, 82, 'hello', '2024-03-29 11:40:29'),
(110, 80, 'good day', '2024-03-29 11:42:01'),
(111, 72, 'morning', '2024-03-29 11:42:14');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `reg_status` tinyint(4) NOT NULL DEFAULT 0,
  `login_status` tinyint(4) NOT NULL DEFAULT 0,
  `last_login` datetime NOT NULL,
  `avatar` tinyint(4) NOT NULL DEFAULT 0,
  `hide_email` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `token`, `reg_status`, `login_status`, `last_login`, `avatar`, `hide_email`) VALUES
(72, 'TesterCat1', 'test1@mail.ru', '$2y$10$vTrt4d848dBFYxwDxcRDdu2wBMOmuMf3JFi8vGdiOghEcMJ3KjSz6', '', 1, 0, '2024-03-29 11:42:43', 1, 1),
(80, 'Tester2', 'test2@mail.ru', '$2y$10$FXGQDfLtmp07tXI3R7QzoOli/54O5FVtBqFzUyL8Bo31Pelvm1emC', '', 1, 0, '2024-03-29 11:42:41', 1, 0),
(82, 'test3@mail.ru', 'test3@mail.ru', '$2y$10$XVz.5oW9qkSnQD2OKdUwou.43nzxe6cEnf.WcogXWiCO2Y6ky28aC', '', 1, 0, '2024-03-29 11:41:07', 1, 0);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `chatrooms`
--
ALTER TABLE `chatrooms`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `chatrooms`
--
ALTER TABLE `chatrooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

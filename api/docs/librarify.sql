-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: symfony-api-rest-db
-- Tiempo de generación: 16-08-2021 a las 08:12:17
-- Versión del servidor: 8.0.26
-- Versión de PHP: 7.4.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `librarify`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `authors`
--

CREATE TABLE `authors` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:uuid)',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `authors`
--

INSERT INTO `authors` (`id`, `name`) VALUES
('29424b55-ef3c-4e4f-93dc-587cd8e28454', 'jose luis lerele'),
('d39e91e2-36a1-456c-abbd-9526926c04e2', 'Alvaro Boosteado Storm');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `books`
--

CREATE TABLE `books` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:uuid)',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(512) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `read_at` date DEFAULT NULL,
  `score_value` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `books`
--

INSERT INTO `books` (`id`, `title`, `image`, `description`, `created_at`, `read_at`, `score_value`) VALUES
('06021a92-851f-4a4b-bd77-d11dd71d96dc', 'Boosteado en WoW classic', 'book_6108e2d1274733.70109140.jpeg', 'Como tirar a la basura la experiencia de wow', '2021-08-03 06:31:45', NULL, NULL),
('3c7fe383-7c1a-4958-9c2c-680efdc93951', 'Boosteado en WoW TBC classic', 'book_6108e411c55509.53640095.jpeg', 'Como tirar a la basura la experiencia de wow TBC', '2021-08-03 06:37:05', NULL, NULL),
('49d14c0f-a664-4608-9f51-e24bf1ca28ed', 'Test event subcriber', 'book_610a59fc52cde0.76820342.jpeg', 'event subcriber', '2021-08-04 11:12:28', NULL, NULL),
('a0df2b11-624e-405c-9947-c607cfc774cf', 'Boosteado en WoW WOLK classic', 'book_6108f2db855b79.85359541.jpeg', 'Como tirar a la basura la experiencia de wow WOLK', '2021-08-03 07:40:11', NULL, NULL),
('c4888963-3a7e-4ec0-a7d4-14c4585f01e5', 'Boosteado en WoW Shadowlands', 'book_610a5784386b23.89011132.jpeg', 'Como tirar a la basura la experiencia de wow', '2021-08-04 11:01:56', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `book_author`
--

CREATE TABLE `book_author` (
  `book_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:uuid)',
  `author_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:uuid)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `book_author`
--

INSERT INTO `book_author` (`book_id`, `author_id`) VALUES
('06021a92-851f-4a4b-bd77-d11dd71d96dc', 'd39e91e2-36a1-456c-abbd-9526926c04e2'),
('3c7fe383-7c1a-4958-9c2c-680efdc93951', 'd39e91e2-36a1-456c-abbd-9526926c04e2'),
('49d14c0f-a664-4608-9f51-e24bf1ca28ed', 'd39e91e2-36a1-456c-abbd-9526926c04e2'),
('a0df2b11-624e-405c-9947-c607cfc774cf', 'd39e91e2-36a1-456c-abbd-9526926c04e2'),
('c4888963-3a7e-4ec0-a7d4-14c4585f01e5', 'd39e91e2-36a1-456c-abbd-9526926c04e2');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `book_category`
--

CREATE TABLE `book_category` (
  `book_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:uuid)',
  `category_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:uuid)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `book_category`
--

INSERT INTO `book_category` (`book_id`, `category_id`) VALUES
('06021a92-851f-4a4b-bd77-d11dd71d96dc', '14fe5af3-613c-4a1d-8da0-f12d091f60d9'),
('3c7fe383-7c1a-4958-9c2c-680efdc93951', '14fe5af3-613c-4a1d-8da0-f12d091f60d9'),
('3c7fe383-7c1a-4958-9c2c-680efdc93951', 'ccc2fea3-9cb8-46c0-aefd-f0c8f96534dd'),
('49d14c0f-a664-4608-9f51-e24bf1ca28ed', '14fe5af3-613c-4a1d-8da0-f12d091f60d9'),
('49d14c0f-a664-4608-9f51-e24bf1ca28ed', 'c75ad61c-153f-43d8-a6f1-e91f3d89c852'),
('a0df2b11-624e-405c-9947-c607cfc774cf', '14fe5af3-613c-4a1d-8da0-f12d091f60d9'),
('a0df2b11-624e-405c-9947-c607cfc774cf', 'b61ffb6a-1a42-4053-a34a-c8bec42b351c'),
('c4888963-3a7e-4ec0-a7d4-14c4585f01e5', '14fe5af3-613c-4a1d-8da0-f12d091f60d9'),
('c4888963-3a7e-4ec0-a7d4-14c4585f01e5', '761cae1e-c8ef-4e82-a498-43d5ebccc912');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categories`
--

CREATE TABLE `categories` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:uuid)',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
('13e34703-78a7-47b4-ab32-3affc711d898', 'Dungeons & Fantasy'),
('14fe5af3-613c-4a1d-8da0-f12d091f60d9', 'Rol'),
('1ae55406-3d3f-4900-8673-619d00a24b17', 'sonata admin'),
('1b8807c6-223a-4117-8feb-9a1c6a7c5a4b', 'test xinoso'),
('1ede8b28-4169-4dd6-bbf0-bbab07ab47f3', 'Rol y Fantasía'),
('25925403-af46-4659-b17e-de2dad061e53', 'MMORPG'),
('27d81611-c0b8-4f9a-bf76-7ae737a208dc', 'Refactoring'),
('280a8f08-f911-47de-bb1f-49691e7721aa', 'Programación'),
('2a592b4f-ab23-40ed-a93c-2110f0f5ecd5', 'Automovilismo Histórico'),
('2cd2fe06-bc03-4ee9-bc42-cb0ec6a47e5f', 'PHP'),
('2eb4095a-68ed-4491-a95a-cfd8302355ad', 'Infantil'),
('308d175c-be20-46e0-925d-6b43c43f9626', 'Wow Retail Classic'),
('39f0593f-7e47-433c-ad07-2b02c2bbb732', 'Frameworks PHP'),
('3de6ba06-0399-4919-ad2c-f79817090cd4', 'Aventuras'),
('4a9c2234-6081-4d2c-8f3d-4319117093da', 'Ciencia Ficción'),
('5bf86773-7978-44a0-8f64-670675a5d4a1', 'Rol y Fantasía Chinosa'),
('6613232b-6ac1-44e6-9337-376e6f2c79b9', 'Terror'),
('742d5cfa-1336-4ce2-b378-6264efe983f8', 'test unique category'),
('750f4c04-f301-4e32-9312-d543e46fb73e', 'Dungeons'),
('761cae1e-c8ef-4e82-a498-43d5ebccc912', 'Wow Retail Classic'),
('88817cb1-9ad7-48da-b032-7ab542b8fdeb', 'Rol y Fantasía Chinosa'),
('94bd5ebb-83f7-427e-a714-1a0aef2de4c2', 'Wow Retail Classic'),
('9a7150ec-640b-4d49-9ec2-5332ca25b281', 'Frameworks'),
('9a95d559-6b08-490f-83ae-efb78bb7c99a', 'Carreras'),
('9c585a69-1323-435a-9425-f7d932052a19', 'Frameworks PHP'),
('b47d2f91-65a3-445b-bbec-b08f72a958f4', 'Automovilismo Moderno'),
('b61ffb6a-1a42-4053-a34a-c8bec42b351c', 'Wow Retail Classic'),
('c192272a-7cf4-4ce7-a693-6b0004ba091b', 'Dungeons'),
('c75ad61c-153f-43d8-a6f1-e91f3d89c852', 'Wow Retail Classic'),
('ccc2fea3-9cb8-46c0-aefd-f0c8f96534dd', 'Wow Classic'),
('daefbba0-2b6c-495e-a159-a00e7b82842d', 'Rol y Fantasía Chinosa(lerelosa)'),
('fb710409-0f55-432b-b0dc-1b078d3d7747', 'lerele6 65465 v6');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20210816075514', '2021-08-16 08:00:48', 1661);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:uuid)',
  `email` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `authors`
--
ALTER TABLE `authors`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `book_author`
--
ALTER TABLE `book_author`
  ADD PRIMARY KEY (`book_id`,`author_id`),
  ADD KEY `IDX_9478D34516A2B381` (`book_id`),
  ADD KEY `IDX_9478D345F675F31B` (`author_id`);

--
-- Indices de la tabla `book_category`
--
ALTER TABLE `book_category`
  ADD PRIMARY KEY (`book_id`,`category_id`),
  ADD KEY `IDX_1FB30F9816A2B381` (`book_id`),
  ADD KEY `IDX_1FB30F9812469DE2` (`category_id`);

--
-- Indices de la tabla `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_88BDF3E9E7927C74` (`email`);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `book_author`
--
ALTER TABLE `book_author`
  ADD CONSTRAINT `FK_9478D34516A2B381` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_9478D345F675F31B` FOREIGN KEY (`author_id`) REFERENCES `authors` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `book_category`
--
ALTER TABLE `book_category`
  ADD CONSTRAINT `FK_1FB30F9812469DE2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_1FB30F9816A2B381` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 10, 2018 at 05:30 PM
-- Server version: 10.1.29-MariaDB
-- PHP Version: 7.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `timeslots`
--

-- --------------------------------------------------------

--
-- Table structure for table `calendars`
--

CREATE TABLE `calendars` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `increment` int(11) NOT NULL DEFAULT '30',
  `event_duration` int(11) NOT NULL DEFAULT '30',
  `price` double(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `calendars`
--

INSERT INTO `calendars` (`id`, `user_id`, `increment`, `event_duration`, `price`, `created_at`, `updated_at`) VALUES
(1, 2, 30, 30, 35.00, '2018-03-09 13:27:04', '2018-03-09 13:27:04');

-- --------------------------------------------------------

--
-- Table structure for table `custom_events`
--

CREATE TABLE `custom_events` (
  `id` int(10) UNSIGNED NOT NULL,
  `employee_id` int(11) NOT NULL,
  `day` date NOT NULL,
  `start` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `end` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color_id` int(11) NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `day` date NOT NULL,
  `timeslot` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `google_calendars`
--

CREATE TABLE `google_calendars` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `calendar_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(68, '2014_10_12_000000_create_users_table', 1),
(69, '2014_10_12_100000_create_password_resets_table', 1),
(70, '2018_02_05_203024_create_permission_tables', 1),
(71, '2018_02_12_130119_create_verify_users_table', 1),
(72, '2018_02_12_204416_create_profiles_table', 1),
(73, '2018_02_12_221229_create_calendars_table', 1),
(74, '2018_02_13_161545_create_schedules_table', 1),
(75, '2018_02_13_163527_create_timeslots_table', 1),
(76, '2018_02_14_145545_create_special_schedules_table', 1),
(77, '2018_02_14_145745_create_special_timeslots_table', 1),
(78, '2018_02_19_162959_create_events_table', 1),
(79, '2018_02_23_160735_create_google_calendars_table', 1),
(80, '2018_02_25_221502_create_custom_events_table', 1),
(81, '2018_02_28_133011_create_jobs_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` int(10) UNSIGNED NOT NULL,
  `model_id` int(10) UNSIGNED NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` int(10) UNSIGNED NOT NULL,
  `model_id` int(10) UNSIGNED NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_id`, `model_type`) VALUES
(1, 1, 'App\\User'),
(2, 2, 'App\\User'),
(3, 3, 'App\\User'),
(3, 4, 'App\\User'),
(3, 5, 'App\\User');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `role_name`, `created_at`, `updated_at`) VALUES
(1, 'view user calendar', 'web', 'user', '2018-03-08 14:47:11', '2018-03-08 14:47:11'),
(2, 'book appointment', 'web', 'user', '2018-03-08 14:47:11', '2018-03-08 14:47:11'),
(3, 'pay', 'web', 'user', '2018-03-08 14:47:11', '2018-03-08 14:47:11'),
(4, 'view user transactions', 'web', 'user', '2018-03-08 14:47:11', '2018-03-08 14:47:11'),
(5, 'edit user profile', 'web', 'user', '2018-03-08 14:47:11', '2018-03-08 14:47:11'),
(6, 'view employee calendar', 'web', 'employee', '2018-03-08 14:47:12', '2018-03-08 14:47:12'),
(8, 'book employee event', 'web', 'employee', '2018-03-08 14:47:12', '2018-03-08 14:47:12'),
(9, 'edit rules', 'web', 'employee', '2018-03-08 14:47:12', '2018-03-08 14:47:12'),
(10, 'view employee transactions', 'web', 'employee', '2018-03-08 14:47:13', '2018-03-08 14:47:13'),
(11, 'view employee events', 'web', 'employee', '2018-03-08 14:47:13', '2018-03-08 14:47:13'),
(12, 'edit employee profile', 'web', 'employee', '2018-03-08 14:47:13', '2018-03-08 14:47:13'),
(13, 'view all calendars', 'web', 'admin', '2018-03-08 14:47:13', '2018-03-08 14:47:13'),
(14, 'book admin appointment', 'web', 'admin', '2018-03-08 14:47:13', '2018-03-08 14:47:13'),
(15, 'book admin event', 'web', 'admin', '2018-03-08 14:47:13', '2018-03-08 14:47:13'),
(16, 'view roles', 'web', 'admin', '2018-03-08 14:47:14', '2018-03-08 14:47:14'),
(17, 'edit roles', 'web', 'admin', '2018-03-08 14:47:14', '2018-03-08 14:47:14'),
(18, 'view users', 'web', 'admin', '2018-03-08 14:47:14', '2018-03-08 14:47:14'),
(19, 'add users', 'web', 'admin', '2018-03-08 14:47:14', '2018-03-08 14:47:14'),
(20, 'edit users', 'web', 'admin', '2018-03-08 14:47:14', '2018-03-08 14:47:14'),
(21, 'edit all rules', 'web', 'admin', '2018-03-08 14:47:14', '2018-03-08 14:47:14'),
(22, 'delete users', 'web', 'admin', '2018-03-08 14:47:14', '2018-03-08 14:47:14'),
(23, 'view all events', 'web', 'admin', '2018-03-08 14:47:14', '2018-03-08 14:47:14'),
(24, 'delete events', 'web', 'admin', '2018-03-08 14:47:14', '2018-03-08 14:47:14'),
(25, 'view all transactions', 'web', 'admin', '2018-03-08 14:47:14', '2018-03-08 14:47:14'),
(26, 'delete transactions', 'web', 'admin', '2018-03-08 14:47:14', '2018-03-08 14:47:14'),
(27, 'edit admin profile', 'web', 'admin', '2018-03-08 14:47:14', '2018-03-08 14:47:14');

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

CREATE TABLE `profiles` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notifications` tinyint(1) NOT NULL DEFAULT '1',
  `gcalendar` tinyint(1) NOT NULL DEFAULT '0',
  `access_token` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'web', '2018-03-08 14:47:10', '2018-03-08 14:47:10'),
(2, 'employee', 'web', '2018-03-08 14:47:10', '2018-03-08 14:47:10'),
(3, 'user', 'web', '2018-03-08 14:47:10', '2018-03-08 14:47:10');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 3),
(2, 3),
(3, 3),
(4, 3),
(5, 3),
(6, 2),
(8, 2),
(9, 2),
(10, 2),
(11, 2),
(12, 2),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1);

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `calendar_id` int(11) NOT NULL,
  `monday_available` tinyint(1) DEFAULT '0',
  `tuesday_available` tinyint(1) DEFAULT '0',
  `wednesday_available` tinyint(1) DEFAULT '0',
  `thursday_available` tinyint(1) DEFAULT '0',
  `friday_available` tinyint(1) DEFAULT '0',
  `saturday_available` tinyint(1) DEFAULT '0',
  `sunday_available` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`id`, `user_id`, `calendar_id`, `monday_available`, `tuesday_available`, `wednesday_available`, `thursday_available`, `friday_available`, `saturday_available`, `sunday_available`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 1, 1, 1, 0, 0, 0, 0, '2018-03-09 13:27:31', '2018-03-09 13:27:31');

-- --------------------------------------------------------

--
-- Table structure for table `special_schedules`
--

CREATE TABLE `special_schedules` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `calendar_id` int(11) NOT NULL,
  `day` date NOT NULL,
  `available` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `special_timeslots`
--

CREATE TABLE `special_timeslots` (
  `id` int(10) UNSIGNED NOT NULL,
  `special_schedule_id` int(11) NOT NULL,
  `timeslot` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `timeslots`
--

CREATE TABLE `timeslots` (
  `id` int(10) UNSIGNED NOT NULL,
  `schedule_id` int(11) NOT NULL,
  `day` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `timeslot` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `timeslots`
--

INSERT INTO `timeslots` (`id`, `schedule_id`, `day`, `timeslot`, `created_at`, `updated_at`) VALUES
(1, 1, 'monday', '7:00', '2018-03-09 13:27:31', '2018-03-09 13:27:31'),
(2, 1, 'monday', '7:15', '2018-03-09 13:27:31', '2018-03-09 13:27:31'),
(3, 1, 'monday', '7:30', '2018-03-09 13:27:32', '2018-03-09 13:27:32'),
(4, 1, 'monday', '7:45', '2018-03-09 13:27:32', '2018-03-09 13:27:32'),
(5, 1, 'monday', '8:00', '2018-03-09 13:27:32', '2018-03-09 13:27:32'),
(6, 1, 'monday', '8:15', '2018-03-09 13:27:32', '2018-03-09 13:27:32'),
(7, 1, 'monday', '8:30', '2018-03-09 13:27:32', '2018-03-09 13:27:32'),
(8, 1, 'monday', '8:45', '2018-03-09 13:27:32', '2018-03-09 13:27:32'),
(9, 1, 'monday', '9:00', '2018-03-09 13:27:32', '2018-03-09 13:27:32'),
(10, 1, 'monday', '9:15', '2018-03-09 13:27:32', '2018-03-09 13:27:32'),
(11, 1, 'monday', '9:30', '2018-03-09 13:27:32', '2018-03-09 13:27:32'),
(12, 1, 'monday', '9:45', '2018-03-09 13:27:32', '2018-03-09 13:27:32'),
(13, 1, 'monday', '10:00', '2018-03-09 13:27:32', '2018-03-09 13:27:32'),
(14, 1, 'monday', '10:15', '2018-03-09 13:27:32', '2018-03-09 13:27:32'),
(15, 1, 'monday', '10:30', '2018-03-09 13:27:32', '2018-03-09 13:27:32'),
(16, 1, 'monday', '10:45', '2018-03-09 13:27:32', '2018-03-09 13:27:32'),
(17, 1, 'monday', '11:00', '2018-03-09 13:27:32', '2018-03-09 13:27:32'),
(18, 1, 'monday', '11:15', '2018-03-09 13:27:32', '2018-03-09 13:27:32'),
(19, 1, 'monday', '11:30', '2018-03-09 13:27:32', '2018-03-09 13:27:32'),
(20, 1, 'monday', '11:45', '2018-03-09 13:27:32', '2018-03-09 13:27:32'),
(21, 1, 'monday', '12:00', '2018-03-09 13:27:32', '2018-03-09 13:27:32'),
(22, 1, 'monday', '12:15', '2018-03-09 13:27:32', '2018-03-09 13:27:32'),
(23, 1, 'monday', '12:30', '2018-03-09 13:27:33', '2018-03-09 13:27:33'),
(24, 1, 'monday', '12:45', '2018-03-09 13:27:33', '2018-03-09 13:27:33'),
(25, 1, 'monday', '13:00', '2018-03-09 13:27:33', '2018-03-09 13:27:33'),
(26, 1, 'monday', '13:15', '2018-03-09 13:27:33', '2018-03-09 13:27:33'),
(27, 1, 'monday', '13:30', '2018-03-09 13:27:33', '2018-03-09 13:27:33'),
(28, 1, 'tuesday', '7:00', '2018-03-09 13:27:33', '2018-03-09 13:27:33'),
(29, 1, 'tuesday', '7:15', '2018-03-09 13:27:33', '2018-03-09 13:27:33'),
(30, 1, 'tuesday', '7:30', '2018-03-09 13:27:33', '2018-03-09 13:27:33'),
(31, 1, 'tuesday', '7:45', '2018-03-09 13:27:33', '2018-03-09 13:27:33'),
(32, 1, 'tuesday', '8:00', '2018-03-09 13:27:33', '2018-03-09 13:27:33'),
(33, 1, 'tuesday', '8:15', '2018-03-09 13:27:33', '2018-03-09 13:27:33'),
(34, 1, 'tuesday', '8:30', '2018-03-09 13:27:33', '2018-03-09 13:27:33'),
(35, 1, 'tuesday', '8:45', '2018-03-09 13:27:33', '2018-03-09 13:27:33'),
(36, 1, 'tuesday', '9:00', '2018-03-09 13:27:33', '2018-03-09 13:27:33'),
(37, 1, 'tuesday', '9:15', '2018-03-09 13:27:33', '2018-03-09 13:27:33'),
(38, 1, 'tuesday', '9:30', '2018-03-09 13:27:33', '2018-03-09 13:27:33'),
(39, 1, 'tuesday', '9:45', '2018-03-09 13:27:33', '2018-03-09 13:27:33'),
(40, 1, 'tuesday', '10:00', '2018-03-09 13:27:33', '2018-03-09 13:27:33'),
(41, 1, 'tuesday', '10:15', '2018-03-09 13:27:33', '2018-03-09 13:27:33'),
(42, 1, 'tuesday', '10:30', '2018-03-09 13:27:33', '2018-03-09 13:27:33'),
(43, 1, 'tuesday', '10:45', '2018-03-09 13:27:33', '2018-03-09 13:27:33'),
(44, 1, 'tuesday', '11:00', '2018-03-09 13:27:33', '2018-03-09 13:27:33'),
(45, 1, 'tuesday', '11:15', '2018-03-09 13:27:33', '2018-03-09 13:27:33'),
(46, 1, 'tuesday', '11:30', '2018-03-09 13:27:33', '2018-03-09 13:27:33'),
(47, 1, 'tuesday', '11:45', '2018-03-09 13:27:34', '2018-03-09 13:27:34'),
(48, 1, 'tuesday', '12:00', '2018-03-09 13:27:34', '2018-03-09 13:27:34'),
(49, 1, 'tuesday', '12:15', '2018-03-09 13:27:34', '2018-03-09 13:27:34'),
(50, 1, 'tuesday', '12:30', '2018-03-09 13:27:34', '2018-03-09 13:27:34'),
(51, 1, 'tuesday', '12:45', '2018-03-09 13:27:34', '2018-03-09 13:27:34'),
(52, 1, 'tuesday', '13:00', '2018-03-09 13:27:34', '2018-03-09 13:27:34'),
(53, 1, 'tuesday', '13:15', '2018-03-09 13:27:34', '2018-03-09 13:27:34'),
(54, 1, 'tuesday', '13:30', '2018-03-09 13:27:34', '2018-03-09 13:27:34'),
(55, 1, 'wednesday', '7:00', '2018-03-09 13:27:34', '2018-03-09 13:27:34'),
(56, 1, 'wednesday', '7:15', '2018-03-09 13:27:34', '2018-03-09 13:27:34'),
(57, 1, 'wednesday', '7:30', '2018-03-09 13:27:34', '2018-03-09 13:27:34'),
(58, 1, 'wednesday', '7:45', '2018-03-09 13:27:34', '2018-03-09 13:27:34'),
(59, 1, 'wednesday', '8:00', '2018-03-09 13:27:34', '2018-03-09 13:27:34'),
(60, 1, 'wednesday', '8:15', '2018-03-09 13:27:34', '2018-03-09 13:27:34'),
(61, 1, 'wednesday', '8:30', '2018-03-09 13:27:34', '2018-03-09 13:27:34'),
(62, 1, 'wednesday', '8:45', '2018-03-09 13:27:34', '2018-03-09 13:27:34'),
(63, 1, 'wednesday', '9:00', '2018-03-09 13:27:34', '2018-03-09 13:27:34'),
(64, 1, 'wednesday', '9:15', '2018-03-09 13:27:34', '2018-03-09 13:27:34'),
(65, 1, 'wednesday', '9:30', '2018-03-09 13:27:34', '2018-03-09 13:27:34'),
(66, 1, 'wednesday', '9:45', '2018-03-09 13:27:34', '2018-03-09 13:27:34'),
(67, 1, 'wednesday', '10:00', '2018-03-09 13:27:34', '2018-03-09 13:27:34'),
(68, 1, 'wednesday', '10:15', '2018-03-09 13:27:34', '2018-03-09 13:27:34'),
(69, 1, 'wednesday', '10:30', '2018-03-09 13:27:34', '2018-03-09 13:27:34'),
(70, 1, 'wednesday', '10:45', '2018-03-09 13:27:35', '2018-03-09 13:27:35'),
(71, 1, 'wednesday', '11:00', '2018-03-09 13:27:35', '2018-03-09 13:27:35'),
(72, 1, 'wednesday', '11:15', '2018-03-09 13:27:35', '2018-03-09 13:27:35'),
(73, 1, 'wednesday', '11:30', '2018-03-09 13:27:35', '2018-03-09 13:27:35'),
(74, 1, 'wednesday', '11:45', '2018-03-09 13:27:35', '2018-03-09 13:27:35'),
(75, 1, 'wednesday', '12:00', '2018-03-09 13:27:35', '2018-03-09 13:27:35'),
(76, 1, 'wednesday', '12:15', '2018-03-09 13:27:35', '2018-03-09 13:27:35'),
(77, 1, 'wednesday', '12:30', '2018-03-09 13:27:35', '2018-03-09 13:27:35'),
(78, 1, 'wednesday', '12:45', '2018-03-09 13:27:35', '2018-03-09 13:27:35'),
(79, 1, 'wednesday', '13:00', '2018-03-09 13:27:35', '2018-03-09 13:27:35'),
(80, 1, 'wednesday', '13:15', '2018-03-09 13:27:35', '2018-03-09 13:27:35'),
(81, 1, 'wednesday', '13:30', '2018-03-09 13:27:35', '2018-03-09 13:27:35');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT '0',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `verified`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin', 'admin@example.com', '$2y$10$//ySqOcqIwrAdUkmk9dppuv.yTqRzJd261s5QKe85iyNUFfFVQ4rS', 0, 'vuDeYSpwN5tvRhRjxiypboB4WHWYPXRfMckA4d8I3azfz2hadOJ7XKsUYc5F', '2018-03-08 14:47:10', '2018-03-08 14:47:10'),
(2, 'employee', 'employee', 'employee@example.com', '$2y$10$I1qjg3vARe1SQfL.b6/./.HZR.QBWrE9v6gkHGiRIcUdowiOVfxVi', 0, 'rNl5hm53V5HtjVBQMEFe3AQIVHfDz0KyKwz8USkwuCojJxc8DL3IBIO0LhUx', '2018-03-08 14:47:10', '2018-03-08 14:47:10'),
(3, 'user', 'user', 'user@example.com', '$2y$10$7ej8YKHtsEYM0l7Tt0wBYuTmixFL7jodmZMOqRScd8t.crDt792HG', 0, 'Ss48OHmR1EZurkNQaA6yuDstizc7pNqpP2RFSZXKhxdutGK5p2sKGkdMNFUq', '2018-03-08 14:47:11', '2018-03-08 14:47:11'),
(4, 'test', 'test444', 'shile@example.com', '$2y$10$jDQBjIFdh6GPpUD924pAmuLugKfUW66X/0IM9.9Sb9NRvFyxjBb9q', 1, NULL, '2018-03-09 18:06:33', '2018-03-10 09:52:12'),
(5, 'test', 'test', 'shilele90@gmail.com', '$2y$10$bwYCdafES44sZ6FsFgAVbOcnmCF4bZBFEpQc87j7SB4aeSz9Ce7xS', 1, NULL, '2018-03-10 10:12:25', '2018-03-10 10:31:38');

-- --------------------------------------------------------

--
-- Table structure for table `verify_users`
--

CREATE TABLE `verify_users` (
  `user_id` int(11) NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `verify_users`
--

INSERT INTO `verify_users` (`user_id`, `token`, `created_at`, `updated_at`) VALUES
(4, 'mwj7t858XG', '2018-03-09 18:06:34', '2018-03-09 18:06:34'),
(5, 'r00j0NdOJu', '2018-03-10 10:12:25', '2018-03-10 10:12:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `calendars`
--
ALTER TABLE `calendars`
  ADD PRIMARY KEY (`id`),
  ADD KEY `calendars_user_id_index` (`user_id`);

--
-- Indexes for table `custom_events`
--
ALTER TABLE `custom_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `custom_events_employee_id_index` (`employee_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `events_user_id_index` (`user_id`),
  ADD KEY `events_employee_id_index` (`employee_id`),
  ADD KEY `events_day_index` (`day`);

--
-- Indexes for table `google_calendars`
--
ALTER TABLE `google_calendars`
  ADD PRIMARY KEY (`id`),
  ADD KEY `google_calendars_user_id_index` (`user_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `profiles_user_id_index` (`user_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schedules_user_id_index` (`user_id`),
  ADD KEY `schedules_calendar_id_index` (`calendar_id`);

--
-- Indexes for table `special_schedules`
--
ALTER TABLE `special_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `special_schedules_user_id_index` (`user_id`),
  ADD KEY `special_schedules_day_index` (`day`);

--
-- Indexes for table `special_timeslots`
--
ALTER TABLE `special_timeslots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `special_timeslots_special_schedule_id_index` (`special_schedule_id`);

--
-- Indexes for table `timeslots`
--
ALTER TABLE `timeslots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `timeslots_schedule_id_index` (`schedule_id`),
  ADD KEY `timeslots_day_index` (`day`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `calendars`
--
ALTER TABLE `calendars`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `custom_events`
--
ALTER TABLE `custom_events`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `google_calendars`
--
ALTER TABLE `google_calendars`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `profiles`
--
ALTER TABLE `profiles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `special_schedules`
--
ALTER TABLE `special_schedules`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `special_timeslots`
--
ALTER TABLE `special_timeslots`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `timeslots`
--
ALTER TABLE `timeslots`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

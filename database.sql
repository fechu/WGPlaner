-- phpMyAdmin SQL Dump
-- version 3.5.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 23, 2013 at 06:24 PM
-- Server version: 5.5.29
-- PHP Version: 5.4.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `wgplaner`
--

-- --------------------------------------------------------

--
-- Table structure for table `billinglists_users`
--

CREATE TABLE `billinglists_users` (
  `purchaselist_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`purchaselist_id`,`user_id`),
  KEY `IDX_E05A475A1FF2F35D` (`purchaselist_id`),
  KEY `IDX_E05A475AA76ED395` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------

--
-- Table structure for table `CountList`
--

CREATE TABLE `CountList` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `startDate` datetime NOT NULL,
  `endDate` datetime NOT NULL,
  `created` datetime NOT NULL,
  `lastModified` datetime NOT NULL,
  `deleted` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `CountListEntry`
--

CREATE TABLE `CountListEntry` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `count` double NOT NULL,
  `created` datetime NOT NULL,
  `lastModified` datetime NOT NULL,
  `deleted` tinyint(1) NOT NULL,
  `countList_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_12FA413524916180` (`countList_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `countlist_user`
--

CREATE TABLE `countlist_user` (
  `countlist_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`countlist_id`,`user_id`),
  KEY `IDX_6739AF68EB2C581C` (`countlist_id`),
  KEY `IDX_6739AF68A76ED395` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Purchase`
--

CREATE TABLE `Purchase` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `date` datetime NOT NULL,
  `slipNumber` int(11) DEFAULT NULL,
  `store` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `amount` double NOT NULL,
  `created` datetime NOT NULL,
  `lastModified` datetime NOT NULL,
  `deleted` tinyint(1) NOT NULL,
  `purchaseList_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_9861B36DA76ED395` (`user_id`),
  KEY `IDX_9861B36DD04FCAC1` (`purchaseList_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=29 ;

-- --------------------------------------------------------

--
-- Table structure for table `PurchaseList`
--

CREATE TABLE `PurchaseList` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `startDate` datetime NOT NULL,
  `endDate` datetime NOT NULL,
  `created` datetime NOT NULL,
  `lastModified` datetime NOT NULL,
  `deleted` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=30 ;


-- --------------------------------------------------------

--
-- Table structure for table `purchaselist_user`
--

CREATE TABLE `purchaselist_user` (
  `purchaselist_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`purchaselist_id`,`user_id`),
  KEY `IDX_CF5F5F921FF2F35D` (`purchaselist_id`),
  KEY `IDX_CF5F5F92A76ED395` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `User`
--

CREATE TABLE `User` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `fullname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `emailAdress` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime NOT NULL,
  `lastModified` datetime NOT NULL,
  `deleted` tinyint(1) NOT NULL,
  `apiKey` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `User`
--

INSERT INTO `User` (`id`, `username`, `fullname`, `password`, `emailAdress`, `created`, `lastModified`, `deleted`, `apiKey`) VALUES
(1, 'admin', 'Max Muster', '$2y$14$jgOJoJt8lZiD/.IUXD4gpuQVXLyWBp7D6s5RFNo4hnSqmsyMnuXaq', 'i@myself.me', '2013-10-01 00:00:00', '2013-10-01 00:00:00', 0, '5e0190f72907e173c6c0f48453d069e8');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `billinglists_users`
--
ALTER TABLE `billinglists_users`
  ADD CONSTRAINT `FK_E05A475A1FF2F35D` FOREIGN KEY (`purchaselist_id`) REFERENCES `PurchaseList` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_E05A475AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `User` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `CountListEntry`
--
ALTER TABLE `CountListEntry`
  ADD CONSTRAINT `FK_12FA413524916180` FOREIGN KEY (`countList_id`) REFERENCES `CountList` (`id`);

--
-- Constraints for table `countlist_user`
--
ALTER TABLE `countlist_user`
  ADD CONSTRAINT `FK_6739AF68A76ED395` FOREIGN KEY (`user_id`) REFERENCES `User` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_6739AF68EB2C581C` FOREIGN KEY (`countlist_id`) REFERENCES `CountList` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `Purchase`
--
ALTER TABLE `Purchase`
  ADD CONSTRAINT `FK_9861B36DA76ED395` FOREIGN KEY (`user_id`) REFERENCES `User` (`id`),
  ADD CONSTRAINT `FK_9861B36DD04FCAC1` FOREIGN KEY (`purchaseList_id`) REFERENCES `PurchaseList` (`id`);

--
-- Constraints for table `purchaselist_user`
--
ALTER TABLE `purchaselist_user`
  ADD CONSTRAINT `FK_CF5F5F921FF2F35D` FOREIGN KEY (`purchaselist_id`) REFERENCES `PurchaseList` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_CF5F5F92A76ED395` FOREIGN KEY (`user_id`) REFERENCES `User` (`id`) ON DELETE CASCADE;

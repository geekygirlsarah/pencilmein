-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- PencilMeIn MySQL sample data
-- Sarah Withee
-- sarahwithee@umkc.edu
--
-- Host: 127.0.0.1
-- Generation Time: Dec 08, 2013 at 08:08 AM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `pencilmein`
--

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `business_id`, `start_time`, `duration`, `user_id`, `state`) VALUES
(1, 1, 1383292800, 30, 1, 'taken'),
(2, 1, 1383294600, 30, 1, 'taken'),
(3, 1, 1383296400, 0, 0, 'taken'),
(4, 1, 1383300000, 30, 1, 'taken'),
(5, 1, 1383303600, 30, 0, 'taken'),
(6, 1, 1383307200, 30, 1, 'taken'),
(11, 1, 1385973000, 30, 16091994, 'pending'),
(12, 1, 1385980200, 30, 16091994, 'pending'),
(13, 1, 1386316800, 30, 16091994, 'pending'),
(14, 1, 1386500400, 30, 16091994, 'pending'),
(15, 1, 1386505800, 30, 16091994, 'pending');

--
-- Dumping data for table `businesses`
--

INSERT INTO `businesses` (`id`, `name`, `street`, `city`, `state`, `zip`, `phone`) VALUES
(1, 'Bark, Bath, and Beyond Pet Groomer', '1111 N. Main St.', 'Kansas City', 'MO', '64111', 1111111111),
(2, 'I Do New Doos Hair Salon', '2222 N. Somewhere St.', 'Kansas City', 'MO', '64111', 1111111111),
(3, 'Stranger Than Fixin'' Car Repair', '3333 S. Street St.', 'Kansas City', 'MO', '64109', 1111111111),
(4, 'Don''t Hurt My Fillings Dentistry', '4444 E. Avenue Blvd', 'Kansas City', 'MO', '64111', 1111111111),
(5, 'Sarah''s Super Selling Stuff Store', '5555 N. Nowhere St', 'Somewhere', 'MO', '60000', 1111111111);

--
-- Dumping data for table `business_settings`
--

INSERT INTO `business_settings` (`business_id`, `owner_id`, `appt_duration`, `start_time`, `end_time`, `available_days`) VALUES
(1, 1, 30, 1384070400, 1384102800, '\0'),
(2, 1, 30, 1384070400, 1384102800, '\0'),
(3, 1, 30, 1384070400, 1384102800, '\0'),
(4, 1, 30, 1384070400, 1384102800, '\0'),
(5, 1, 30, 1384070400, 1384102800, '1');

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_id`, `first_name`, `last_name`, `google_id`, `street_address`, `city`, `state`, `zip`, `phone`) VALUES
(1, 'geekygirlsarah', 'Sarah', 'Withee', 0, '1234 S. Somewhere St.', 'Kansas City', 'MO', '64111', 8161234567),
(2, '', '', '', 0, '', '', '', '', 0),
(16091994, 'geekygirlsarah2', 'Sarah', 'Withee', NULL, '2345 S. Somewhere Else St.', 'Kansas City', 'MO', '64111', 8162345678);

-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 13, 2022 at 07:50 AM
-- Server version: 10.2.44-MariaDB
-- PHP Version: 7.2.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dandapp_dandapp`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `email`, `password`) VALUES
(1, 'admin@gmail.com', 'admin@123');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `follower_id` int(11) NOT NULL,
  `upload_id` int(11) NOT NULL,
  `comment` varchar(255) NOT NULL,
  `time` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `follower_id`, `upload_id`, `comment`, `time`) VALUES
(6, 3, 3, 'great stuff', '1661798925'),
(7, 4, 31, 'hi', '1665480791');

-- --------------------------------------------------------

--
-- Table structure for table `donate`
--

CREATE TABLE `donate` (
  `id` int(11) NOT NULL,
  `is_donate` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0=hide,1=show'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `followers`
--

CREATE TABLE `followers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `follower_id` int(11) NOT NULL,
  `follow_status` enum('1','2') NOT NULL COMMENT '1=pending,2=confirm'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `followers`
--

INSERT INTO `followers` (`id`, `user_id`, `follower_id`, `follow_status`) VALUES
(7, 1, 4, '1'),
(8, 2, 4, '1'),
(9, 3, 4, '1'),
(13, 1, 2, '1'),
(17, 1, 3, '1'),
(18, 2, 3, '1'),
(20, 4, 2, '1');

-- --------------------------------------------------------

--
-- Table structure for table `friend_list`
--

CREATE TABLE `friend_list` (
  `id` int(11) NOT NULL,
  `to_userid` int(11) NOT NULL,
  `from_userid` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `upload_id` int(11) NOT NULL,
  `follower_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `live_users`
--

CREATE TABLE `live_users` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `broadcast_id` varchar(255) NOT NULL,
  `b_url` longtext NOT NULL,
  `status` enum('0','1') NOT NULL COMMENT '0=live,1=archived'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `follower_id` int(11) NOT NULL,
  `upload_id` int(11) NOT NULL,
  `notification` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `follower_id`, `upload_id`, `notification`) VALUES
(18, 1, 3, 0, 'started following you'),
(14, 1, 2, 0, 'started following you'),
(8, 1, 4, 0, 'started following you'),
(9, 2, 4, 0, 'started following you'),
(10, 3, 4, 0, 'started following you'),
(19, 2, 3, 0, 'started following you'),
(21, 4, 2, 0, 'started following you');

-- --------------------------------------------------------

--
-- Table structure for table `register`
--

CREATE TABLE `register` (
  `user_id` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `country_code` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `gender` enum('1','2') NOT NULL COMMENT '1-Male|2-Female',
  `dob` varchar(255) NOT NULL,
  `profile_pic` varchar(255) NOT NULL,
  `device_type` varchar(255) NOT NULL,
  `device_token` varchar(255) NOT NULL,
  `deviceId` varchar(100) NOT NULL,
  `fcm_token` varchar(255) NOT NULL,
  `created_at` varchar(100) NOT NULL,
  `website` varchar(100) NOT NULL,
  `bio` varchar(255) NOT NULL,
  `membership` enum('1','2') NOT NULL COMMENT '1=not_purchase,2=purchased',
  `membership_date` varchar(255) NOT NULL,
  `paypal_id` varchar(255) NOT NULL,
  `is_active` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0=offline,1=online',
  `isblock` enum('0','1') NOT NULL DEFAULT '0',
  `is_donate` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0=hide,1=seen',
  `user_type` enum('0','1') NOT NULL COMMENT '0-user,1-admin',
  `countryIso` varchar(100) NOT NULL,
  `notification_send` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0-not send,1-sent'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `register`
--

INSERT INTO `register` (`user_id`, `fullname`, `username`, `email`, `country_code`, `contact`, `password`, `gender`, `dob`, `profile_pic`, `device_type`, `device_token`, `deviceId`, `fcm_token`, `created_at`, `website`, `bio`, `membership`, `membership_date`, `paypal_id`, `is_active`, `isblock`, `is_donate`, `user_type`, `countryIso`, `notification_send`) VALUES
(1, 'Neha Thakur ', 'hpsolver', 'hpsolver@gmail.com', '91', '7018792362', '12345678', '1', '', 'img_1086733957.jpg', 'A', 'dt-qljTkfcY:APA91bF8iRe0m6C9Xd_jMVBzNsDwp4pJpgeuS-uR164bbwvC4TWrNN25pu4qaM629FhjLqXMc8ck6IeIutWPf9iqssvdFyeJpdr2e71ByTFG_9ne3HPk9wcZ5Cl_BhXHNwNqsEJ0CxPw', '4365dd9d74c7c1b2', 'dt-qljTkfcY:APA91bF8iRe0m6C9Xd_jMVBzNsDwp4pJpgeuS-uR164bbwvC4TWrNN25pu4qaM629FhjLqXMc8ck6IeIutWPf9iqssvdFyeJpdr2e71ByTFG_9ne3HPk9wcZ5Cl_BhXHNwNqsEJ0CxPw', '2022-08-20 23:13:57', '', '', '1', '', '', '0', '0', '1', '0', 'KEN', '0'),
(2, 'Rohan', 'rohan', 'rohan@gmail.com', '91', '9816922193', '12345678', '1', '', '', 'A', 'dt-qljTkfcY:APA91bF8iRe0m6C9Xd_jMVBzNsDwp4pJpgeuS-uR164bbwvC4TWrNN25pu4qaM629FhjLqXMc8ck6IeIutWPf9iqssvdFyeJpdr2e71ByTFG_9ne3HPk9wcZ5Cl_BhXHNwNqsEJ0CxPw', '4365dd9d74c7c1b2', 'dt-qljTkfcY:APA91bF8iRe0m6C9Xd_jMVBzNsDwp4pJpgeuS-uR164bbwvC4TWrNN25pu4qaM629FhjLqXMc8ck6IeIutWPf9iqssvdFyeJpdr2e71ByTFG_9ne3HPk9wcZ5Cl_BhXHNwNqsEJ0CxPw', '2022-08-20 23:35:03', '', '', '1', '', '', '1', '1', '1', '0', 'IN', '0'),
(3, 'Sammy Ekaran', 'ekaransammy8', 'ekaransammy8@gmail.com', '254', '0711114002', '1234567i8', '1', '', '', 'A', 'fkpZtw0hE0o:APA91bEqS-zutwD7gayATIBjNff2DagueH782zYnI24XojNyUqUWBmKYAQGNdiQdwGmKvPu0HF2KJeCDelMYHNpRwmIlTCOsDBMynReQ_pwLewaR7dr6bt5H1KM5bMJdlVgUehiiQuj-', '7741aa9f17e36f69', 'fkpZtw0hE0o:APA91bEqS-zutwD7gayATIBjNff2DagueH782zYnI24XojNyUqUWBmKYAQGNdiQdwGmKvPu0HF2KJeCDelMYHNpRwmIlTCOsDBMynReQ_pwLewaR7dr6bt5H1KM5bMJdlVgUehiiQuj-', '2022-08-24 10:43:24', '', '', '1', '', '', '1', '1', '1', '0', 'KEN', '0'),
(4, 'Danda', 'doyaro828', 'doyaro828@gmail.com', '1', '17322092127', 'Freeupyourmind', '1', '', '', 'A', 'cp1vTjw3bok:APA91bHJkfHeO7qYcLAMuin8kVWmxIIaWITyYwOJAwMf3aqMEw1v3PeL8upLoJ7__y5y_YliceFMtWvNTOZsXcUDHrVkYQNqsJuJRtCCy3pSiLeSxl75coafCkbJvDPlIUDvBOOv776w', 'c82f85577738b4c7', 'cp1vTjw3bok:APA91bHJkfHeO7qYcLAMuin8kVWmxIIaWITyYwOJAwMf3aqMEw1v3PeL8upLoJ7__y5y_YliceFMtWvNTOZsXcUDHrVkYQNqsJuJRtCCy3pSiLeSxl75coafCkbJvDPlIUDvBOOv776w', '2022-08-25 12:33:31', '', '', '2', '', '', '1', '1', '1', '0', 'US', '0');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `reason_id` int(11) NOT NULL,
  `to_userid` int(11) NOT NULL,
  `from_userid` int(11) NOT NULL,
  `post_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trending`
--

CREATE TABLE `trending` (
  `id` int(11) NOT NULL,
  `upload_id` int(11) NOT NULL,
  `trending_count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `trending`
--

INSERT INTO `trending` (`id`, `upload_id`, `trending_count`) VALUES
(1, 1, 5),
(2, 3, 1),
(3, 31, 1);

-- --------------------------------------------------------

--
-- Table structure for table `uploads`
--

CREATE TABLE `uploads` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_parent` int(11) NOT NULL,
  `uploads` varchar(255) NOT NULL,
  `post_id` int(11) NOT NULL,
  `postMsg` varchar(200) NOT NULL,
  `thumbnail` varchar(255) NOT NULL,
  `upload_type` varchar(255) NOT NULL,
  `caption` varchar(255) NOT NULL,
  `date` varchar(100) NOT NULL,
  `time` varchar(100) NOT NULL,
  `explicit` enum('1','2') NOT NULL COMMENT '1=no explicit ,2=explicit'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `uploads`
--

INSERT INTO `uploads` (`id`, `user_id`, `post_parent`, `uploads`, `post_id`, `postMsg`, `thumbnail`, `upload_type`, `caption`, `date`, `time`, `explicit`) VALUES
(2, 3, 0, 'img_1554980907.jpg', 0, '', 'img_1661318129.jpg', 'I', 'comps', '22-08-24', '10:45:29', ''),
(3, 3, 0, 'img_2005005633.jpg', 0, '', 'img_1661318181.jpg', 'I', '', '22-08-24', '10:46:21', ''),
(29, 1, 0, 'vid1194689504.mp4', 0, '', 'img_1662096698.jpg', 'V', '#123', '22-09-02', '11:01:38', '1'),
(31, 4, 0, 'img_1707205916.jpg', 0, '', 'img_1664238892.jpg', 'I', '', '22-09-27', '06:04:52', '1'),
(33, 3, 0, 'vid766784106.mp4', 0, '', 'img_1665480671.jpg', 'V', 'happy birthdayðŸŽ‰', '22-10-11', '15:01:11', '1');

-- --------------------------------------------------------

--
-- Table structure for table `upload_content`
--

CREATE TABLE `upload_content` (
  `id` int(11) NOT NULL,
  `upload_id` int(11) NOT NULL,
  `content` varchar(255) NOT NULL,
  `content_description` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `upload_views`
--

CREATE TABLE `upload_views` (
  `id` int(11) NOT NULL,
  `upload_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `upload_views`
--

INSERT INTO `upload_views` (`id`, `upload_id`, `user_id`) VALUES
(1, 29, 2),
(2, 33, 3),
(3, 29, 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `donate`
--
ALTER TABLE `donate`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `followers`
--
ALTER TABLE `followers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `friend_list`
--
ALTER TABLE `friend_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `live_users`
--
ALTER TABLE `live_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `register`
--
ALTER TABLE `register`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trending`
--
ALTER TABLE `trending`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uploads`
--
ALTER TABLE `uploads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `upload_content`
--
ALTER TABLE `upload_content`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `upload_views`
--
ALTER TABLE `upload_views`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `followers`
--
ALTER TABLE `followers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `friend_list`
--
ALTER TABLE `friend_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `live_users`
--
ALTER TABLE `live_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `register`
--
ALTER TABLE `register`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trending`
--
ALTER TABLE `trending`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `uploads`
--
ALTER TABLE `uploads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `upload_content`
--
ALTER TABLE `upload_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `upload_views`
--
ALTER TABLE `upload_views`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

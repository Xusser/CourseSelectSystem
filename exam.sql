-- phpMyAdmin SQL Dump
-- version 4.8.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 2018-12-02 20:28:16
-- 服务器版本： 5.5.60-log
-- PHP Version: 7.2.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `exam`
--

-- --------------------------------------------------------

--
-- 表的结构 `admin`
--

CREATE TABLE `admin` (
  `adminid` int(50) NOT NULL,
  `adminpassword` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- 转存表中的数据 `admin`
--

INSERT INTO `admin` (`adminid`, `adminpassword`) VALUES
(123456, '123456');

-- --------------------------------------------------------

--
-- 表的结构 `college`
--

CREATE TABLE `college` (
  `collegeid` int(50) NOT NULL,
  `collegename` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `col_disable` int(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- 转存表中的数据 `college`
--

INSERT INTO `college` (`collegeid`, `collegename`, `col_disable`) VALUES
(1, '计算机学院', 0),
(2, '生物学院', 0),
(3, '外国语学院', 0);

-- --------------------------------------------------------

--
-- 表的结构 `course`
--

CREATE TABLE `course` (
  `courseid` int(50) NOT NULL,
  `coursename` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `teaid` int(50) NOT NULL,
  `selected` int(50) NOT NULL,
  `total` int(50) NOT NULL,
  `classtime` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `classroom` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `credit` int(50) NOT NULL,
  `shangketime` int(50) NOT NULL,
  `shiyantime` int(50) NOT NULL,
  `prevcourse` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `cou_disable` int(1) NOT NULL DEFAULT '0',
  `pick_allowed` int(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- 转存表中的数据 `course`
--

INSERT INTO `course` (`courseid`, `coursename`, `teaid`, `selected`, `total`, `classtime`, `classroom`, `credit`, `shangketime`, `shiyantime`, `prevcourse`, `cou_disable`, `pick_allowed`) VALUES
(1, '计算机基础1', 1, 1, 10, '周三1400PM', 'd1-101', 5, 0, 0, '无', 0, 1),
(2, '计算机基础1', 1, 2, 5, '周四1400PM', 'd1-101', 5, 0, 0, '无', 0, 1),
(3, '生物基础1', 5, 1, 2, '周三1400PM', 'd1-102', 5, 0, 0, '无', 1, 0),
(4, '生物基础1', 5, 0, 5, '周四1400PM', 'd1-102', 5, 50, 10, '无', 0, 1),
(5, '计算机通识I', 2, 1, 6, '周五1400PM', 'd2-101', 2, 50, 10, '无', 0, 1),
(6, '计算机通识I', 2, 2, 2, '周六0800AM', 'd2-101', 2, 50, 10, '无', 1, 1),
(7, '计算机通识II', 2, 0, 2, '周一1000AM', 'd1-102', 2, 50, 10, '计算机通识I', 0, 1),
(8, '计算机通识II', 2, 1, 2, '周一1000AM', 'd1-102', 2, 50, 10, '计算机通识I', 0, 1),
(9, '计算机通识III', 2, 0, 5, '周二1600PM', 'd2-401', 2, 0, 0, '计算机通识II', 0, 1),
(10, '生物通识I', 6, 0, 1, '周一1100AM', 'd1-501', 2, 50, 10, '生物基础1', 0, 1),
(11, '生物通识II', 6, 0, 1, '周一1100AM', 'd1-501', 2, 50, 10, '生物通识I', 0, 1),
(12, '生物通识II', 6, 0, 2, '周一0800AM', 'd1-502', 2, 50, 10, '生物通识I', 0, 1);

-- --------------------------------------------------------

--
-- 表的结构 `privilege`
--

CREATE TABLE `privilege` (
  `stu_SelectAllow` int(1) NOT NULL DEFAULT '1',
  `tea_ReleaseAllow` int(1) NOT NULL DEFAULT '1',
  `tea_CourseModAllow` int(1) NOT NULL DEFAULT '1',
  `tea_StuDelAllow` int(1) NOT NULL DEFAULT '0',
  `tea_StuFinAllow` int(11) NOT NULL DEFAULT '0',
  `global_ban` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `privilege`
--

INSERT INTO `privilege` (`stu_SelectAllow`, `tea_ReleaseAllow`, `tea_CourseModAllow`, `tea_StuDelAllow`, `tea_StuFinAllow`, `global_ban`) VALUES
(1, 0, 0, 0, 1, 0);

-- --------------------------------------------------------

--
-- 表的结构 `stucourse`
--

CREATE TABLE `stucourse` (
  `stuid` int(50) NOT NULL,
  `stuname` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `collegeid` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `major` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `class` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `teaid` int(50) NOT NULL,
  `courseid` int(11) NOT NULL,
  `classtime` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `fin` int(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- 转存表中的数据 `stucourse`
--

INSERT INTO `stucourse` (`stuid`, `stuname`, `collegeid`, `major`, `class`, `teaid`, `courseid`, `classtime`, `fin`) VALUES
(1, 's1', '1', 'Default', 's1b1', 1, 2, '周四1400PM', 0),
(1, 's1', '1', 'Default', 's1b1', 2, 6, '周六0800AM', 1),
(2, 's2', '1', 'Default', 's1b1', 2, 6, '周六0800AM', 0),
(2, 's2', '1', 'Default', 's1b1', 1, 2, '周四1400PM', 0),
(1, 's1', '1', 'Default', 's1b1', 2, 8, '周一1000AM', 0),
(5, 's4', '1', 'Default', 's1b2', 1, 1, '周三1400PM', 0),
(6, 'w1', '2', 'Default', 'w1b1', 2, 5, '周五1400PM', 0),
(6, 'w1', '2', 'Default', 'w1b1', 5, 3, '周三1400PM', 1);

-- --------------------------------------------------------

--
-- 表的结构 `student`
--

CREATE TABLE `student` (
  `stuid` int(50) NOT NULL,
  `stuname` varchar(50) COLLATE utf8_bin NOT NULL,
  `collegeid` int(50) NOT NULL,
  `major` varchar(50) COLLATE utf8_bin NOT NULL,
  `sex` varchar(50) COLLATE utf8_bin NOT NULL,
  `class` varchar(50) COLLATE utf8_bin NOT NULL,
  `stupassword` varchar(20) COLLATE utf8_bin NOT NULL,
  `stu_disable` int(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- 转存表中的数据 `student`
--

INSERT INTO `student` (`stuid`, `stuname`, `collegeid`, `major`, `sex`, `class`, `stupassword`, `stu_disable`) VALUES
(1, 's1', 1, 'Default', '男', 's1b1', '123456', 0),
(2, 's2', 1, 'Default', '男', 's1b1', '123456', 0),
(3, 's3', 1, 'Default', '女', 's1b2', '123456', 0),
(4, 's4', 1, 'Default', '女', 's1b3', '123456', 0),
(5, 's4', 1, 'Default', '男', 's1b2', '123456', 0),
(6, 'w1', 2, 'Default', '女', 'w1b1', '123456', 0),
(7, 'w2', 2, 'Default', '女', 'w1b2', '123456', 0),
(8, 'w3', 2, 'Default', '女', 'w1b2', '123456', 0),
(9, 'w4', 2, 'Default', '男', 'w1b3', '123456', 0),
(10, 'h1', 3, 'Default', '女', 'h1b1', '123456', 0),
(11, 'h2', 3, 'Default', '女', 'h1b1', '123456', 0),
(12, 'h3', 3, 'Default', '男', 'w1b1', '123456', 0),
(13, 'h4', 3, 'Default', '女', 'h1b2', '123456', 0);

-- --------------------------------------------------------

--
-- 表的结构 `teacher`
--

CREATE TABLE `teacher` (
  `teaid` int(50) NOT NULL,
  `teaname` varchar(50) COLLATE utf8_bin NOT NULL,
  `sex` varchar(50) COLLATE utf8_bin NOT NULL,
  `collegename` varchar(50) COLLATE utf8_bin NOT NULL,
  `introduction` varchar(50) COLLATE utf8_bin NOT NULL,
  `teapassword` varchar(20) COLLATE utf8_bin NOT NULL,
  `tea_disable` int(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- 转存表中的数据 `teacher`
--

INSERT INTO `teacher` (`teaid`, `teaname`, `sex`, `collegename`, `introduction`, `teapassword`, `tea_disable`) VALUES
(1, '张1', '男', '计算机学院', '', '123456', 0),
(2, '张2', '男', '计算机学院', '', '123456', 0),
(3, '张3', '男', '计算机学院', '', '123456', 1),
(4, '张4', '女', '计算机学院', '', '123456', 1),
(5, '王1', '男', '生物学院', '', '123456', 0),
(6, '王2', '男', '生物学院', '', '123456', 0),
(7, '王3', '女', '生物学院', '', '123456', 1),
(8, '王4', '女', '生物学院', '', '123456', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `college`
--
ALTER TABLE `college`
  ADD PRIMARY KEY (`collegeid`);

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`courseid`);

--
-- Indexes for table `stucourse`
--
ALTER TABLE `stucourse`
  ADD PRIMARY KEY (`stuid`,`courseid`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`stuid`);

--
-- Indexes for table `teacher`
--
ALTER TABLE `teacher`
  ADD PRIMARY KEY (`teaid`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `college`
--
ALTER TABLE `college`
  MODIFY `collegeid` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- 使用表AUTO_INCREMENT `course`
--
ALTER TABLE `course`
  MODIFY `courseid` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- 使用表AUTO_INCREMENT `student`
--
ALTER TABLE `student`
  MODIFY `stuid` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- 使用表AUTO_INCREMENT `teacher`
--
ALTER TABLE `teacher`
  MODIFY `teaid` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

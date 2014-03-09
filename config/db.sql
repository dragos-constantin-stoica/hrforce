-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 21, 2010 at 02:08 PM
-- Server version: 5.1.36
-- PHP Version: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `hrforce`
--

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--
-- Creation: Apr 09, 2010 at 02:25 PM
-- Last update: Apr 21, 2010 at 05:56 PM
--

DROP TABLE IF EXISTS `companies`;
CREATE TABLE IF NOT EXISTS `companies` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `COMPANYNAME` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`ID`, `COMPANYNAME`) VALUES
(1, 'TEST'),
(2, 'ACME');

-- --------------------------------------------------------

--
-- Table structure for table `hradmins`
--
-- Creation: Apr 09, 2010 at 02:25 PM
--

DROP TABLE IF EXISTS `hradmins`;
CREATE TABLE IF NOT EXISTS `hradmins` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `USERNAME` varchar(255) NOT NULL,
  `PASSWORD` varchar(12) NOT NULL,
  `COMPANY_ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `hradmins`
--

INSERT INTO `hradmins` (`ID`, `USERNAME`, `PASSWORD`, `COMPANY_ID`) VALUES
(1, 'hradmin', 'hradmin', 2);

-- --------------------------------------------------------

--
-- Table structure for table `organisation`
--
-- Creation: Apr 22, 2010 at 04:50 PM
--

DROP TABLE IF EXISTS `organisation`;
CREATE TABLE IF NOT EXISTS `organisation` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `TITLE` varchar(32) COLLATE utf8_bin NOT NULL,
  `TYPE` varchar(32) COLLATE utf8_bin NOT NULL COMMENT 'COMPANY, DIVISION, DEPARTMENT, TEAM, OTHER',
  `PARENT_ID` int(11) DEFAULT NULL,
  `COMPANY_ID` int(11) NOT NULL,
  `LEVEL` int(11) NOT NULL,
  `LINEAGE` varchar(255) COLLATE utf8_bin NOT NULL,
  `LFT_NODE` int(11) NOT NULL,
  `RGT_NODE` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=66 ;

--
-- Dumping data for table `organisation`
--

INSERT INTO `organisation` (`ID`, `TITLE`, `TYPE`, `PARENT_ID`, `COMPANY_ID`, `LEVEL`, `LINEAGE`, `LFT_NODE`, `RGT_NODE`) VALUES
(1, 'ACME', 'COMPANY', -1, 2, 0, '#', 1, 4),
(62, 'lvl 3', 'OTHER', 1, 2, 0, '', 2, 3);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--
-- Creation: Apr 09, 2010 at 02:25 PM
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `USERNAME` varchar(255) NOT NULL,
  `PASSWORD` varchar(12) NOT NULL,
  `TYPE` varchar(3) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `USERNAME`, `PASSWORD`, `TYPE`) VALUES
(1, 'admin', 'admin', '1');

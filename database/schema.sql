-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 31, 2018 at 11:31 AM
-- Server version: 5.6.12-log
-- PHP Version: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


-- --------------------------------------------------------

--
-- Table structure for table `academic_years`
--

CREATE TABLE IF NOT EXISTS `academic_years` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `description` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `is_deleted` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `achievements`
--

CREATE TABLE IF NOT EXISTS `achievements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_by` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL COMMENT 'id from students/employees table',
  `user_type` int(11) NOT NULL COMMENT '1- student, 2- employee',
  `achievement_title` varchar(220) NOT NULL,
  `description` text NOT NULL,
  `doc_title` varchar(220) NOT NULL,
  `file` varchar(220) NOT NULL,
  `file_type` varchar(220) NOT NULL,
  `created_at` date NOT NULL,
  `is_deleted` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `activity_feed`
--

CREATE TABLE IF NOT EXISTS `activity_feed` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `initiator_id` int(11) NOT NULL,
  `activity_type` int(11) NOT NULL,
  `goal_id` int(11) DEFAULT NULL,
  `goal_name` varchar(120) DEFAULT NULL,
  `user_role` varchar(225) NOT NULL,
  `system_ip` varchar(225) NOT NULL,
  `field_name` varchar(120) DEFAULT NULL,
  `initial_field_value` varchar(120) DEFAULT NULL,
  `new_field_value` varchar(120) DEFAULT NULL,
  `activity_time` timestamp NOT NULL DEFAULT now() ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `initiator_id` (`initiator_id`),
  KEY `activity_type` (`activity_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `activity_type`
--

CREATE TABLE IF NOT EXISTS `activity_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `text` varchar(120) NOT NULL,
  `color_code` varchar(120) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29 ;

-- --------------------------------------------------------

--
-- Table structure for table `allotment`
--

CREATE TABLE IF NOT EXISTS `allotment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` varchar(120) DEFAULT NULL,
  `bed_no` varchar(120) DEFAULT NULL,
  `room_no` varchar(120) DEFAULT NULL,
  `floor` varchar(120) DEFAULT NULL,
  `hostel_id` varchar(120) DEFAULT NULL,
  `status` varchar(120) DEFAULT NULL COMMENT 'c:vacant  s:occupiad',
  `created` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `assets`
--

CREATE TABLE IF NOT EXISTS `assets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `amount` int(11) DEFAULT NULL,
  `is_inactive` tinyint(1) DEFAULT '0',
  `is_deleted` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `attendance_settings`
--

CREATE TABLE IF NOT EXISTS `attendance_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `config_key` varchar(500) NOT NULL,
  `config_value` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `authassignment`
--

CREATE TABLE IF NOT EXISTS `authassignment` (
  `itemname` varchar(64) NOT NULL,
  `userid` varchar(64) NOT NULL,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`itemname`,`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `authitem`
--

CREATE TABLE IF NOT EXISTS `authitem` (
  `name` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `authitemchild`
--

CREATE TABLE IF NOT EXISTS `authitemchild` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `author`
--

CREATE TABLE IF NOT EXISTS `author` (
  `auth_id` int(11) NOT NULL AUTO_INCREMENT,
  `author_name` varchar(120) DEFAULT NULL,
  `desc` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`auth_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `batches`
--

CREATE TABLE IF NOT EXISTS `batches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `academic_yr_id` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `is_deleted` tinyint(1) DEFAULT '0',
  `employee_id` int(11) DEFAULT NULL,
  `exam_format` int(11) NOT NULL COMMENT '1=>default, 2=>cbsc',
  `timetable_format` int(11) NOT NULL DEFAULT '1' COMMENT '1 - Fixed, 2 - Flexible',
  `semester_id` int(11) DEFAULT NULL COMMENT 'id from semester table',
  PRIMARY KEY (`id`),
  KEY `course_id` (`course_id`),
  KEY `academic_yr_id` (`academic_yr_id`),
  KEY `employee_id` (`employee_id`),
  KEY `semester_id` (`semester_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `batch_students`
--

CREATE TABLE IF NOT EXISTS `batch_students` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `roll_no` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `batch_id` int(11) DEFAULT NULL,
  `academic_yr_id` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL COMMENT '1=Current Active Batch, 0 = Previous Batch, 2=>Inactive',
  `result_status` int(11) NOT NULL DEFAULT '0' COMMENT '0 = In Progress, 1 = Pass, -1 = Fail, 2 = Alumni, 3=>previous',
  PRIMARY KEY (`id`),
  KEY `index_batch_students_on_batch_id_and_student_id` (`batch_id`,`student_id`),
  KEY `student_id` (`student_id`),
  KEY `academic_yr_id` (`academic_yr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `book`
--

CREATE TABLE IF NOT EXISTS `book` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `isbn` varchar(120) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `category` int(11) DEFAULT NULL,
  `author` int(11) DEFAULT NULL,
  `edition` varchar(255) DEFAULT NULL,
  `publisher` varchar(255) DEFAULT NULL,
  `copy` int(255) DEFAULT NULL,
  `copy_taken` varchar(120) DEFAULT NULL,
  `book_position` varchar(120) DEFAULT NULL,
  `shelf_no` varchar(120) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `status` varchar(120) DEFAULT NULL,
  `is_deleted` int(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `category` (`category`),
  KEY `author` (`author`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `book_fine`
--

CREATE TABLE IF NOT EXISTS `book_fine` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) DEFAULT NULL,
  `book_id` int(11) DEFAULT NULL,
  `amount` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `book_id` (`book_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `borrow_book`
--

CREATE TABLE IF NOT EXISTS `borrow_book` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) DEFAULT NULL,
  `book_name` varchar(120) DEFAULT NULL,
  `subject` varchar(120) DEFAULT NULL,
  `book_id` int(11) DEFAULT NULL,
  `issue_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `created` date DEFAULT NULL,
  `status` varchar(120) DEFAULT NULL COMMENT 'C:issued R:return',
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `book_id` (`book_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `bus_log`
--

CREATE TABLE IF NOT EXISTS `bus_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vehicle_id` int(11) DEFAULT NULL,
  `start_time_reading` varchar(120) DEFAULT NULL,
  `end_time_reading` varchar(120) DEFAULT NULL,
  `fuel_consumption` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vehicle_id` (`vehicle_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`cat_id`),
  KEY `cat_id` (`cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cbscexam_scores_split`
--

CREATE TABLE IF NOT EXISTS `cbscexam_scores_split` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `exam_scores_id` int(11) NOT NULL COMMENT 'cbsc_exam_scores  table id ',
  `mark` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `exam_scores_id` (`exam_scores_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cbsc_coscholastic_score`
--

CREATE TABLE IF NOT EXISTS `cbsc_coscholastic_score` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `coscholastic_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `score` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `coscholastic_id` (`coscholastic_id`),
  KEY `student_id` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cbsc_co_scholastic`
--

CREATE TABLE IF NOT EXISTS `cbsc_co_scholastic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `batch_id` int(11) NOT NULL,
  `skill` text NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `batch_id` (`batch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cbsc_exams`
--

CREATE TABLE IF NOT EXISTS `cbsc_exams` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `exam_group_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `maximum_marks` int(11) NOT NULL DEFAULT '0',
  `minimum_marks` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `exam_group_id` (`exam_group_id`),
  KEY `subject_id` (`subject_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cbsc_exams_17`
--

CREATE TABLE IF NOT EXISTS `cbsc_exams_17` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `exam_group_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `maximum_marks` int(11) NOT NULL DEFAULT '0',
  `minimum_marks` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `exam_group_id` (`exam_group_id`),
  KEY `subject_id` (`subject_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cbsc_exam_grade`
--

CREATE TABLE IF NOT EXISTS `cbsc_exam_grade` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `grade` varchar(20) NOT NULL,
  `min_mark` int(11) NOT NULL,
  `max_mark` int(11) NOT NULL,
  `grade_point` decimal(10,1) NOT NULL,
  `status` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `cbsc_exam_groups`
--

CREATE TABLE IF NOT EXISTS `cbsc_exam_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `term_id` int(11) NOT NULL,
  `batch_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `exam_type` varchar(255) NOT NULL,
  `date_published` int(11) NOT NULL COMMENT '0=>not published, 1=>published',
  `result_published` int(11) NOT NULL COMMENT '0=>not published, 1=>published',
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `term_id` (`term_id`),
  KEY `batch_id` (`batch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cbsc_exam_group_17`
--

CREATE TABLE IF NOT EXISTS `cbsc_exam_group_17` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `batch_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` int(11) NOT NULL COMMENT '1 - CBSE 2016/, 2 - CBSE 2017',
  `class` int(11) NOT NULL COMMENT '1-Class 1 & 2 , 2-Class 3 - 8  , 3-Class 9 -10, 4-Class 11 - 12',
  `is_final` int(11) NOT NULL,
  `date_published` int(11) NOT NULL,
  `result_published` int(11) NOT NULL,
  `created_at` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `batch_id` (`batch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cbsc_exam_scores`
--

CREATE TABLE IF NOT EXISTS `cbsc_exam_scores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL COMMENT 'id of cbsc_exams table',
  `marks` int(11) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `is_failed` tinyint(1) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `exam_id` (`exam_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cbsc_exam_scores_17`
--

CREATE TABLE IF NOT EXISTS `cbsc_exam_scores_17` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `written_exam` float(10,1) DEFAULT NULL,
  `periodic_test` float(10,1) DEFAULT NULL,
  `note_book` float(10,1) DEFAULT NULL,
  `subject_enrichment` float(10,1) DEFAULT NULL,
  `total` float(10,1) NOT NULL,
  `remarks` varchar(225) NOT NULL,
  `is_failed` tinyint(4) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `exam_id` (`exam_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cbsc_exam_scores_split_17`
--

CREATE TABLE IF NOT EXISTS `cbsc_exam_scores_split_17` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `exam_scores_id` int(11) NOT NULL COMMENT ' cbsc_exam_scores_17 table id',
  `mark` float(10,1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `exam_scores_id` (`exam_scores_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cbsc_exam_settings`
--

CREATE TABLE IF NOT EXISTS `cbsc_exam_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `academic_yr_id` int(11) NOT NULL,
  `fa1_weightage` int(11) NOT NULL,
  `fa2_weightage` int(11) NOT NULL,
  `sa1_weightage` int(11) NOT NULL,
  `sa2_weightage` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `academic_yr_id` (`academic_yr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `class_timings`
--

CREATE TABLE IF NOT EXISTS `class_timings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `batch_id` int(11) DEFAULT '0' COMMENT '0 => common class timings',
  `name` varchar(255) DEFAULT NULL,
  `start_time` varchar(120) DEFAULT NULL,
  `end_time` varchar(120) DEFAULT NULL,
  `is_break` tinyint(1) DEFAULT NULL,
  `admin_id` int(11) NOT NULL COMMENT 'This the Id of common class timing(ie, entry that contain batch_id as 0)',
  `is_edit` int(11) NOT NULL COMMENT '1 => Yes, 0 => No',
  `on_sunday` int(11) NOT NULL DEFAULT '0' COMMENT 'class timing will be shown on sunday',
  `on_monday` int(11) NOT NULL DEFAULT '0' COMMENT 'class timing will be shown on monday',
  `on_tuesday` int(11) NOT NULL DEFAULT '0' COMMENT 'class timing will be shown on tuesday',
  `on_wednesday` int(11) NOT NULL DEFAULT '0' COMMENT 'class timing will be shown on wednesday',
  `on_thursday` int(11) NOT NULL DEFAULT '0' COMMENT 'class timing will be shown on thursday',
  `on_friday` int(11) NOT NULL DEFAULT '0' COMMENT 'class timing will be shown on friday',
  `on_saturday` int(11) NOT NULL DEFAULT '0' COMMENT 'class timing will be shown on saturday',
  PRIMARY KEY (`id`),
  KEY `index_class_timings_on_batch_id_and_start_time_and_end_time` (`batch_id`,`start_time`,`end_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE IF NOT EXISTS `complaints` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `subject` varchar(120) NOT NULL,
  `complaint` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `reopened_date` timestamp NOT NULL DEFAULT now(),
  `viewed` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `closed_by` int(11) DEFAULT NULL COMMENT 'User Id of the user who close the complaint',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `category_id` (`category_id`),
  KEY `closed_by` (`closed_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `complaint_categories`
--

CREATE TABLE IF NOT EXISTS `complaint_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(120) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `complaint_feedback`
--

CREATE TABLE IF NOT EXISTS `complaint_feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `complaint_id` int(11) NOT NULL,
  `feedback` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `complaint_id` (`complaint_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `configurations`
--

CREATE TABLE IF NOT EXISTS `configurations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `config_key` varchar(255) DEFAULT NULL,
  `config_value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `index_configurations_on_config_key` (`config_key`(10)),
  KEY `index_configurations_on_config_value` (`config_value`(10))
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=50 ;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE IF NOT EXISTS `contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` text NOT NULL,
  `last_name` text NOT NULL,
  `mobile` text NOT NULL,
  `email` text NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contacts_groups`
--

CREATE TABLE IF NOT EXISTS `contacts_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` text NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contacts_list`
--

CREATE TABLE IF NOT EXISTS `contacts_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`),
  KEY `contact_id` (`contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE IF NOT EXISTS `countries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=196 ;

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE IF NOT EXISTS `courses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `course_name` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `section_name` varchar(255) DEFAULT NULL,
  `academic_yr_id` int(11) NOT NULL,
  `semester_enabled` int(11) NOT NULL DEFAULT '0' COMMENT '0 - Disabled, 1 - Enabled',
  `is_deleted` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `exam_format` int(11) NOT NULL DEFAULT '1',
  `timetable_format` int(11) NOT NULL DEFAULT '1' COMMENT '1 - Fixed, 2 - Flexible',
  PRIMARY KEY (`id`),
  KEY `academic_yr_id` (`academic_yr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `currency`
--

CREATE TABLE IF NOT EXISTS `currency` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `country` varchar(45) NOT NULL DEFAULT '',
  `code` char(3) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=501 ;

-- --------------------------------------------------------

--
-- Table structure for table `dashboard`
--

CREATE TABLE IF NOT EXISTS `dashboard` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `block` varchar(255) NOT NULL,
  `is_visible` int(11) NOT NULL DEFAULT '1' COMMENT '0- not visible 1- visible',
  `portal` int(11) DEFAULT NULL COMMENT '1- admin',
  `default_order` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `dashboard_settings`
--

CREATE TABLE IF NOT EXISTS `dashboard_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `block_id` int(11) NOT NULL COMMENT 'id from dashboard table',
  `block_order` int(11) NOT NULL,
  `is_visible` int(11) NOT NULL DEFAULT '1' COMMENT '0-No, 1-Yes',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `block_id` (`block_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `devices`
--

CREATE TABLE IF NOT EXISTS `devices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `device_id` varchar(255) NOT NULL COMMENT 'Android device ID',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `document_models`
--

CREATE TABLE IF NOT EXISTS `document_models` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `document_uploads`
--

CREATE TABLE IF NOT EXISTS `document_uploads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model_id` int(11) NOT NULL COMMENT 'id from document model table',
  `file_id` int(11) NOT NULL COMMENT 'if from corresponding table',
  `file_name` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL COMMENT '0- pending, 1- Approved, 2- Reject',
  `identifier` int(11) DEFAULT NULL COMMENT '1- achievement_document, 2 - employee_achievement_document, 3 - employee_document,  4 - employee_profile_image, 5- student_document, 6- student_profile_image,7- shared,  8 - school_logo,  9 - school_favicon',
  `created_by` int(11) DEFAULT NULL,
  `created_at` date NOT NULL,
  `reason` text,
  PRIMARY KEY (`id`),
  KEY `model_id` (`model_id`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `driver_details`
--

CREATE TABLE IF NOT EXISTS `driver_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vehicle_id` int(11) DEFAULT NULL,
  `first_name` varchar(120) DEFAULT NULL,
  `last_name` varchar(120) DEFAULT NULL,
  `address` varchar(120) DEFAULT NULL,
  `phn_no` varchar(225) NOT NULL,
  `dob` varchar(120) DEFAULT NULL,
  `license_no` varchar(120) DEFAULT NULL,
  `expiry_date` varchar(120) DEFAULT NULL,
  `status` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vehicle_id` (`vehicle_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `electives`
--

CREATE TABLE IF NOT EXISTS `electives` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `elective_group_id` int(11) DEFAULT NULL,
  `batch_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `is_deleted` tinyint(120) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `elective_group_id` (`elective_group_id`),
  KEY `batch_id` (`batch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `elective_exams`
--

CREATE TABLE IF NOT EXISTS `elective_exams` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `exam_group_id` int(11) DEFAULT NULL,
  `elective_id` int(11) DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `maximum_marks` decimal(10,2) DEFAULT NULL,
  `minimum_marks` decimal(10,2) DEFAULT NULL,
  `grading_level_id` int(11) DEFAULT NULL,
  `weightage` int(11) DEFAULT '0',
  `event_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `elective_groups`
--

CREATE TABLE IF NOT EXISTS `elective_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `batch_id` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `batch_id` (`batch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `elective_scores`
--

CREATE TABLE IF NOT EXISTS `elective_scores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) DEFAULT NULL,
  `exam_id` int(11) DEFAULT NULL,
  `marks` decimal(7,2) DEFAULT NULL,
  `grading_level_id` int(11) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `is_failed` tinyint(1) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `email_attachments`
--

CREATE TABLE IF NOT EXISTS `email_attachments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mail_id` int(11) NOT NULL,
  `file` varchar(200) NOT NULL,
  `file_type` varchar(50) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mail_id` (`mail_id`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `email_drafts`
--

CREATE TABLE IF NOT EXISTS `email_drafts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(120) NOT NULL,
  `message` text NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `status` varchar(11) NOT NULL DEFAULT '0',
  `is_mailshot` int(11) NOT NULL DEFAULT '0',
  `created_on` varchar(120) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `email_recipients`
--

CREATE TABLE IF NOT EXISTS `email_recipients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mail_id` int(11) DEFAULT NULL,
  `users` varchar(120) DEFAULT NULL,
  `batches` text,
  `user_email` text,
  PRIMARY KEY (`id`),
  KEY `mail_id` (`mail_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `email_templates`
--

CREATE TABLE IF NOT EXISTS `email_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_id` int(11) NOT NULL,
  `user_type` int(11) NOT NULL COMMENT '0 = General, 1= Parent, 2= Student, 3 = Employee',
  `name` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `template` text NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=35 ;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE IF NOT EXISTS `employees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(120) NOT NULL,
  `employee_category_id` int(11) DEFAULT NULL,
  `employee_number` varchar(255) DEFAULT NULL,
  `joining_date` date DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `job_title` varchar(255) DEFAULT NULL,
  `employee_position_id` int(11) DEFAULT NULL,
  `employee_department_id` int(11) DEFAULT NULL,
  `reporting_manager_id` int(11) DEFAULT NULL,
  `employee_grade_id` int(11) DEFAULT NULL,
  `qualification` varchar(255) DEFAULT NULL,
  `experience_detail` text,
  `experience_year` int(11) DEFAULT NULL,
  `experience_month` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `status_description` varchar(255) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `marital_status` varchar(255) DEFAULT NULL,
  `children_count` int(11) DEFAULT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `mother_name` varchar(255) DEFAULT NULL,
  `husband_name` varchar(255) DEFAULT NULL,
  `blood_group` varchar(255) DEFAULT NULL,
  `nationality_id` int(11) DEFAULT NULL,
  `home_address_line1` varchar(255) DEFAULT NULL,
  `home_address_line2` varchar(255) DEFAULT NULL,
  `home_city` varchar(255) DEFAULT NULL,
  `home_state` varchar(255) DEFAULT NULL,
  `home_country_id` int(11) DEFAULT NULL,
  `home_pin_code` varchar(255) DEFAULT NULL,
  `office_address_line1` varchar(255) DEFAULT NULL,
  `office_address_line2` varchar(255) DEFAULT NULL,
  `office_city` varchar(255) DEFAULT NULL,
  `office_state` varchar(255) DEFAULT NULL,
  `office_country_id` int(11) DEFAULT NULL,
  `office_pin_code` varchar(255) DEFAULT NULL,
  `office_phone1` varchar(255) DEFAULT NULL,
  `office_phone2` varchar(255) DEFAULT NULL,
  `mobile_phone` varchar(255) DEFAULT NULL,
  `home_phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `fax` varchar(255) DEFAULT NULL,
  `photo_file_name` varchar(255) DEFAULT NULL,
  `photo_content_type` varchar(255) DEFAULT NULL,
  `photo_data` longblob,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `photo_file_size` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `is_deleted` tinyint(4) DEFAULT '0',
  `date_join` varchar(255) NOT NULL,
  `salary_date` varchar(255) NOT NULL,
  `bank_name` varchar(255) NOT NULL,
  `bank_acc_no` int(20) NOT NULL,
  `basic_pay` int(11) NOT NULL,
  `HRA` int(11) NOT NULL,
  `PF` int(11) NOT NULL,
  `tds_type` tinyint(4) DEFAULT NULL COMMENT '0:amount 1:percentage',
  `TDS` decimal(5,2) NOT NULL,
  `DA` varchar(255) NOT NULL,
  `EPF` decimal(5,2) NOT NULL,
  `ESI` decimal(5,2) NOT NULL,
  `others1` varchar(255) NOT NULL,
  `others2` varchar(255) NOT NULL,
  `passport_no` int(11) DEFAULT NULL,
  `passport_expiry` date DEFAULT NULL,
  `user_type` int(11) NOT NULL DEFAULT '0' COMMENT '0:employees 1:other staffs',
  `staff_type` int(11) DEFAULT NULL COMMENT 'reference to user_role table',
  PRIMARY KEY (`id`),
  KEY `index_employees_on_employee_number` (`employee_number`(10)),
  KEY `employee_category_id` (`employee_category_id`),
  KEY `employee_position_id` (`employee_position_id`),
  KEY `employee_department_id` (`employee_department_id`),
  KEY `employee_grade_id` (`employee_grade_id`),
  KEY `nationality_id` (`nationality_id`),
  KEY `home_country_id` (`home_country_id`),
  KEY `office_country_id` (`office_country_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `employees_subjects`
--

CREATE TABLE IF NOT EXISTS `employees_subjects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `index_employees_subjects_on_subject_id` (`subject_id`),
  KEY `employee_id` (`employee_id`),
  KEY `subject_id` (`subject_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `employee_attendances`
--

CREATE TABLE IF NOT EXISTS `employee_attendances` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attendance_date` date DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `employee_leave_type_id` int(11) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `is_half_day` tinyint(1) DEFAULT NULL,
  `half` tinyint(1) DEFAULT '0' COMMENT '1=>Morning Half; 2=>Afternoon half',
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`),
  KEY `employee_leave_type_id` (`employee_leave_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `employee_categories`
--

CREATE TABLE IF NOT EXISTS `employee_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `prefix` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `employee_departments`
--

CREATE TABLE IF NOT EXISTS `employee_departments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `employee_document`
--

CREATE TABLE IF NOT EXISTS `employee_document` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) DEFAULT NULL,
  `title` varchar(120) DEFAULT NULL,
  `file` varchar(200) DEFAULT NULL,
  `file_type` varchar(120) DEFAULT NULL,
  `is_approved` int(11) NOT NULL COMMENT '0 = Pending, 1= Approved, -1 = Rejected',
  `uploaded_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`),
  KEY `uploaded_by` (`uploaded_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `employee_elective_subjects`
--

CREATE TABLE IF NOT EXISTS `employee_elective_subjects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) DEFAULT NULL,
  `elective_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`),
  KEY `elective_id` (`elective_id`),
  KEY `subject_id` (`subject_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `employee_grades`
--

CREATE TABLE IF NOT EXISTS `employee_grades` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `priority` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `max_hours_day` int(11) DEFAULT NULL,
  `max_hours_week` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `employee_positions`
--

CREATE TABLE IF NOT EXISTS `employee_positions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `employee_category_id` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_category_id` (`employee_category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE IF NOT EXISTS `events` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) DEFAULT NULL,
  `title` varchar(1000) DEFAULT NULL,
  `desc` text NOT NULL,
  `type` int(255) NOT NULL,
  `allDay` smallint(5) unsigned NOT NULL DEFAULT '0',
  `start` int(10) unsigned DEFAULT NULL,
  `end` int(10) unsigned DEFAULT NULL,
  `editable` tinyint(1) NOT NULL DEFAULT '1',
  `placeholder` varchar(120) DEFAULT NULL,
  `code` int(11) NOT NULL,
  `organizer` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `events_helper`
--

CREATE TABLE IF NOT EXISTS `events_helper` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` int(255) NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `title` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `events_type`
--

CREATE TABLE IF NOT EXISTS `events_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(120) DEFAULT NULL,
  `colour_code` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `events_user_preference`
--

CREATE TABLE IF NOT EXISTS `events_user_preference` (
  `user_id` int(10) unsigned NOT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `mobile_alert` tinyint(1) NOT NULL DEFAULT '0',
  `email` varchar(40) DEFAULT NULL,
  `email_alert` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `exams`
--

CREATE TABLE IF NOT EXISTS `exams` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `exam_group_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `maximum_marks` int(11) DEFAULT NULL,
  `minimum_marks` int(11) DEFAULT NULL,
  `grading_level_id` int(11) DEFAULT NULL,
  `weightage` int(11) DEFAULT '0',
  `event_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `index_exams_on_exam_group_id_and_subject_id` (`exam_group_id`,`subject_id`),
  KEY `exam_group_id` (`exam_group_id`),
  KEY `subject_id` (`subject_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `exams_common`
--

CREATE TABLE IF NOT EXISTS `exams_common` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `exam_type` varchar(255) DEFAULT NULL,
  `is_published` tinyint(1) DEFAULT '0' COMMENT '0 - No, 1 - Yes',
  `result_published` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - No, 1 - Yes',
  `exam_date` date DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `exam_format`
--

CREATE TABLE IF NOT EXISTS `exam_format` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `exam_format` varchar(255) NOT NULL,
  `value` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `exam_groups`
--

CREATE TABLE IF NOT EXISTS `exam_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `batch_id` int(11) DEFAULT NULL,
  `common_exam_id` int(11) DEFAULT NULL,
  `exam_type` varchar(255) DEFAULT NULL,
  `is_published` tinyint(1) DEFAULT '0',
  `result_published` tinyint(1) DEFAULT '0',
  `exam_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `batch_id` (`batch_id`),
  KEY `common_exam_id` (`common_exam_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `exam_scores`
--

CREATE TABLE IF NOT EXISTS `exam_scores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) DEFAULT NULL,
  `exam_id` int(11) DEFAULT NULL,
  `marks` int(11) DEFAULT NULL,
  `grading_level_id` int(11) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `is_failed` tinyint(1) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `index_exam_scores_on_student_id_and_exam_id` (`student_id`,`exam_id`),
  KEY `student_id` (`student_id`),
  KEY `exam_id` (`exam_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `exam_scores_split`
--

CREATE TABLE IF NOT EXISTS `exam_scores_split` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `exam_scores_id` int(11) NOT NULL COMMENT ' exam_scores table id',
  `mark` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `exam_scores_id` (`exam_scores_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `e_background_job`
--

CREATE TABLE IF NOT EXISTS `e_background_job` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `progress` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `start_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_time` timestamp NULL DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  `request` text,
  `status_text` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `fav_icon`
--

CREATE TABLE IF NOT EXISTS `fav_icon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `icon` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `fee_categories`
--

CREATE TABLE IF NOT EXISTS `fee_categories` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `academic_year_id` int(11) NOT NULL,
  `type` int(11) DEFAULT '1' COMMENT '1=>default 2=> Transportation',
  `name` varchar(250) NOT NULL,
  `description` text,
  `subscription_type` int(11) NOT NULL COMMENT '1 - Yearly, 2 - Half Yearly, 3 - Quarterly, 4 - Monthly, 5 - Weekly, 6 - Custom',
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `last_edited` datetime NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `edited_by` int(11) DEFAULT NULL,
  `invoice_generated` int(11) NOT NULL DEFAULT '0' COMMENT '0 - No, 1 - Yes',
  `amount_divided` int(11) NOT NULL DEFAULT '0' COMMENT '(Divide amount by number of subscriptions) 0 - No, 1 - Yes',
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `edited_by` (`edited_by`),
  KEY `academic_year_id` (`academic_year_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `fee_configurations`
--

CREATE TABLE IF NOT EXISTS `fee_configurations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tax_in_fee` int(11) NOT NULL COMMENT '0 - No, 1 - Yes',
  `discount_in_fee` int(11) NOT NULL COMMENT '0 - No, 1 - Yes',
  `discount_in_invoice` int(11) NOT NULL COMMENT '0 - No, 1 - Yes',
  `invoice_template` int(11) NOT NULL COMMENT 'template file index',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `fee_invoices`
--

CREATE TABLE IF NOT EXISTS `fee_invoices` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `academic_year_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL DEFAULT '0',
  `user_type` int(11) NOT NULL COMMENT '1 - Student',
  `table_id` int(11) NOT NULL COMMENT '`id` from the corresponding user''s table. eg:students, employees',
  `fee_id` bigint(20) NOT NULL,
  `subscription_id` bigint(20) NOT NULL,
  `name` varchar(250) NOT NULL,
  `description` text NOT NULL,
  `subscription_type` int(11) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `due_date` datetime NOT NULL,
  `total_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `amount_paid` decimal(10,2) NOT NULL DEFAULT '0.00',
  `is_paid` int(11) NOT NULL COMMENT '0- No, 1 -Yes',
  `created_at` datetime NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_canceled` int(11) NOT NULL DEFAULT '0' COMMENT '0 - No, 1 - Yes',
  PRIMARY KEY (`id`),
  KEY `academic_year_id` (`academic_year_id`),
  KEY `fee_id` (`fee_id`),
  KEY `subscription_id` (`subscription_id`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `fee_invoice_particulars`
--

CREATE TABLE IF NOT EXISTS `fee_invoice_particulars` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint(20) NOT NULL,
  `name` varchar(250) NOT NULL,
  `description` text NOT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tax` int(11) NOT NULL DEFAULT '0' COMMENT '`id` from fee_taxes table',
  `discount_type` int(11) NOT NULL DEFAULT '0' COMMENT '1 - Percentage, 2 - Amount',
  `discount_value` decimal(10,2) NOT NULL DEFAULT '0.00',
  `fine_amount` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `invoice_id` (`invoice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `fee_particulars`
--

CREATE TABLE IF NOT EXISTS `fee_particulars` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `academic_year_id` int(11) NOT NULL,
  `fee_id` bigint(20) NOT NULL,
  `name` varchar(250) NOT NULL,
  `description` text,
  `tax` int(11) NOT NULL DEFAULT '0' COMMENT '`id` from fee_taxes table',
  `discount_type` int(11) NOT NULL DEFAULT '0' COMMENT '1 - Percentage, 2 - Amount',
  `discount_value` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created_at` datetime NOT NULL,
  `last_edited` datetime NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `edited_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `academic_year_id` (`academic_year_id`),
  KEY `fee_id` (`fee_id`),
  KEY `created_by` (`created_by`),
  KEY `edited_by` (`edited_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `fee_particular_access`
--

CREATE TABLE IF NOT EXISTS `fee_particular_access` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `academic_year_id` int(11) NOT NULL,
  `particular_id` bigint(20) NOT NULL,
  `access_type` int(11) NOT NULL COMMENT '1 - Default, 2 - Admission Number',
  `course` int(11) DEFAULT NULL,
  `batch` int(11) DEFAULT NULL,
  `student_category_id` int(11) DEFAULT NULL,
  `admission_no` varchar(250) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created_at` datetime NOT NULL,
  `last_edited` datetime NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `edited_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `academic_year_id` (`academic_year_id`),
  KEY `particular_id` (`particular_id`),
  KEY `course` (`course`),
  KEY `batch` (`batch`),
  KEY `student_category_id` (`student_category_id`),
  KEY `created_by` (`created_by`),
  KEY `edited_by` (`edited_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `fee_payment_types`
--

CREATE TABLE IF NOT EXISTS `fee_payment_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(200) NOT NULL,
  `is_gateway` int(11) NOT NULL DEFAULT '0' COMMENT '0 - No, 1 - Yes',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `is_active` int(11) NOT NULL DEFAULT '1' COMMENT '0 - No, 1 - Yes',
  `is_editable` int(11) NOT NULL DEFAULT '1' COMMENT '0 - No, 1 - Yes',
  `current_gateway` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `fee_paypal_config`
--

CREATE TABLE IF NOT EXISTS `fee_paypal_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `apiusername` text NOT NULL,
  `apipassword` text NOT NULL,
  `apisignature` text NOT NULL,
  `apicurrency` varchar(50) NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `fee_subscriptions`
--

CREATE TABLE IF NOT EXISTS `fee_subscriptions` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `fee_id` bigint(20) NOT NULL,
  `subscription_type` int(11) NOT NULL,
  `due_date` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `fee_id` (`fee_id`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `fee_taxes`
--

CREATE TABLE IF NOT EXISTS `fee_taxes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(200) NOT NULL,
  `value` decimal(10,2) NOT NULL COMMENT 'Value is considered as %',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `is_active` int(11) NOT NULL DEFAULT '1' COMMENT '0 - No, 1 - Yes',
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `fee_transactions`
--

CREATE TABLE IF NOT EXISTS `fee_transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint(20) NOT NULL,
  `date` datetime NOT NULL,
  `payment_type` int(11) DEFAULT NULL COMMENT '`id` from `fee_payment_types` table',
  `transaction_id` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `proof` varchar(200) DEFAULT NULL,
  `proof_type` varchar(200) DEFAULT NULL,
  `is_deleted` int(11) NOT NULL DEFAULT '0' COMMENT '0 - No, 1 - Yes',
  `deleted_by` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '-1 - Error, 0 - pending, 1 - completed',
  PRIMARY KEY (`id`),
  KEY `invoice_id` (`invoice_id`),
  KEY `payment_type` (`payment_type`),
  KEY `deleted_by` (`deleted_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `file_uploads`
--

CREATE TABLE IF NOT EXISTS `file_uploads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `category` int(11) NOT NULL,
  `placeholder` varchar(50) DEFAULT NULL,
  `course` int(11) DEFAULT NULL,
  `batch` int(11) DEFAULT NULL,
  `file` varchar(200) NOT NULL,
  `description` text,
  `is_special_student` int(11) NOT NULL DEFAULT '0' COMMENT '0 => Not, 1 => For special students',
  `file_type` varchar(50) NOT NULL,
  `academic_yr_id` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `category` (`category`),
  KEY `course` (`course`),
  KEY `batch` (`batch`),
  KEY `academic_yr_id` (`academic_yr_id`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `file_uploads_category`
--

CREATE TABLE IF NOT EXISTS `file_uploads_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(100) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `file_uploads_students`
--

CREATE TABLE IF NOT EXISTS `file_uploads_students` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `table_id` int(11) DEFAULT NULL COMMENT 'Id of ''file_uploads'' table',
  `student_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `table_id` (`table_id`),
  KEY `student_id` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `floor`
--

CREATE TABLE IF NOT EXISTS `floor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hostel_id` int(11) DEFAULT NULL,
  `floor_no` varchar(120) DEFAULT NULL,
  `no_of_rooms` varchar(120) DEFAULT NULL,
  `created` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hostel_id` (`hostel_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `food_info`
--

CREATE TABLE IF NOT EXISTS `food_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `food_preference` varchar(120) DEFAULT NULL,
  `amount` int(120) DEFAULT NULL,
  `is_deleted` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `form_fields`
--

CREATE TABLE IF NOT EXISTS `form_fields` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `varname` varchar(50) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `field_type` varchar(50) DEFAULT NULL,
  `field_size` varchar(15) DEFAULT NULL,
  `field_size_min` varchar(15) DEFAULT NULL,
  `required` int(1) DEFAULT NULL,
  `match` varchar(255) DEFAULT NULL,
  `range` varchar(255) DEFAULT NULL,
  `error_message` varchar(255) DEFAULT NULL,
  `other_validator` varchar(100) DEFAULT NULL,
  `default` varchar(100) DEFAULT NULL,
  `widget` varchar(100) DEFAULT NULL,
  `widgetparams` varchar(100) DEFAULT NULL,
  `position` int(2) DEFAULT NULL,
  `visible` int(1) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `label` varchar(255) DEFAULT NULL,
  `admin_student_reg_form` int(1) DEFAULT NULL,
  `online_admission_form` int(1) DEFAULT NULL,
  `student_profile_pdf` int(1) DEFAULT NULL,
  `student_profile` int(1) DEFAULT NULL,
  `student_portal` int(1) DEFAULT NULL,
  `parent_portal` int(1) DEFAULT NULL,
  `teacher_portal` int(1) DEFAULT NULL,
  `form_field_type` int(2) DEFAULT NULL,
  `tab_selection` int(2) DEFAULT NULL,
  `tab_sub_section` int(2) DEFAULT NULL,
  `order` int(10) DEFAULT NULL,
  `is_dynamic` int(1) DEFAULT NULL,
  `is_exception` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=92 ;

-- --------------------------------------------------------

--
-- Table structure for table `form_field_data`
--

CREATE TABLE IF NOT EXISTS `form_field_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `field_id` int(11) NOT NULL,
  `option_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `fuel_consumption`
--

CREATE TABLE IF NOT EXISTS `fuel_consumption` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vehicle_id` int(11) DEFAULT NULL,
  `fuel_consumed` varchar(120) DEFAULT NULL,
  `amount` varchar(120) DEFAULT NULL,
  `consumed_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vehicle_id` (`vehicle_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `grading_levels`
--

CREATE TABLE IF NOT EXISTS `grading_levels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `batch_id` int(11) DEFAULT NULL,
  `min_score` int(11) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `index_grading_levels_on_batch_id_and_is_deleted` (`batch_id`,`is_deleted`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `grp_name` varchar(50) NOT NULL,
  `users_ids` varchar(120) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `guardians`
--

CREATE TABLE IF NOT EXISTS `guardians` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(120) NOT NULL,
  `ward_id` int(11) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `relation` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `office_phone1` varchar(255) DEFAULT NULL,
  `office_phone2` varchar(255) DEFAULT NULL,
  `mobile_phone` varchar(255) DEFAULT NULL,
  `office_address_line1` varchar(255) DEFAULT NULL,
  `office_address_line2` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `occupation` varchar(255) DEFAULT NULL,
  `income` varchar(255) DEFAULT NULL,
  `education` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `is_delete` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ward_id` (`ward_id`),
  KEY `country_id` (`country_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `guardian_list`
--

CREATE TABLE IF NOT EXISTS `guardian_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guardian_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `relation` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `guardian_id` (`guardian_id`),
  KEY `student_id` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `holidays`
--

CREATE TABLE IF NOT EXISTS `holidays` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) DEFAULT NULL,
  `title` varchar(1000) DEFAULT NULL,
  `desc` text NOT NULL,
  `allDay` smallint(5) unsigned NOT NULL DEFAULT '0',
  `start` int(10) unsigned DEFAULT NULL,
  `end` int(10) unsigned DEFAULT NULL,
  `editable` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `hosteldetails`
--

CREATE TABLE IF NOT EXISTS `hosteldetails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hostel_name` varchar(120) DEFAULT NULL,
  `address` varchar(120) DEFAULT NULL,
  `is_deleted` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ip_filters`
--

CREATE TABLE IF NOT EXISTS `ip_filters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(255) NOT NULL,
  `mismatch_count` tinyint(4) NOT NULL,
  `created_at` datetime NOT NULL,
  `is_blocked` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1:blocked 0:allow',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `leave_requests`
--

CREATE TABLE IF NOT EXISTS `leave_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `leave_type_id` int(11) DEFAULT NULL,
  `requested_by` int(11) NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `is_half_day` int(11) NOT NULL COMMENT '0=>fullday, 1=>fore noon, 2=>after noon',
  `reason` text NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `status` int(11) NOT NULL COMMENT '0=>pending, 1=>approved, 2=>rejected, 3=>cancelled',
  `handled_by` int(11) NOT NULL COMMENT 'approved / disapproved/ cancelled by',
  `handled_at` datetime NOT NULL COMMENT 'approved / disapproved/ cancelled at',
  `response` text COMMENT 'approved / disapproved response',
  `cancel_reason` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `leave_type_id` (`leave_type_id`),
  KEY `requested_by` (`requested_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `leave_types`
--

CREATE TABLE IF NOT EXISTS `leave_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `category` int(11) NOT NULL COMMENT '1=>per quarter, 1=>per year, 2=>whole carrer',
  `gender` int(11) NOT NULL COMMENT '0=>all, 1=>male, 2=>female',
  `count` int(11) NOT NULL,
  `is_deleted` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `logo`
--

CREATE TABLE IF NOT EXISTS `logo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `photo_file_name` varchar(120) NOT NULL,
  `photo_content_type` varchar(120) NOT NULL,
  `photo_file_size` varchar(120) NOT NULL,
  `photo_data` blob NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `log_category`
--

CREATE TABLE IF NOT EXISTS `log_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `editable` int(11) NOT NULL DEFAULT '0',
  `visible` int(11) DEFAULT '0',
  `is_deleted` int(11) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL COMMENT '1-student 2-employee',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `log_comment`
--

CREATE TABLE IF NOT EXISTS `log_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_by` int(11) DEFAULT NULL,
  `user_id` varchar(20) NOT NULL,
  `user_type` int(11) NOT NULL COMMENT '1-student 2-employee',
  `comment` text NOT NULL,
  `date` varchar(20) NOT NULL,
  `category_id` int(11) NOT NULL,
  `notice` int(11) NOT NULL DEFAULT '0',
  `notice_p1` int(11) NOT NULL DEFAULT '0',
  `notice_p2` int(11) NOT NULL DEFAULT '0',
  `visible_p` int(10) NOT NULL DEFAULT '0',
  `visible_t` int(10) NOT NULL DEFAULT '0',
  `visible_s` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mailbox_conversation`
--

CREATE TABLE IF NOT EXISTS `mailbox_conversation` (
  `conversation_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `initiator_id` int(10) NOT NULL,
  `interlocutor_id` int(10) NOT NULL,
  `subject` varchar(100) NOT NULL DEFAULT '',
  `bm_read` tinyint(3) NOT NULL DEFAULT '0',
  `bm_deleted` tinyint(3) NOT NULL DEFAULT '0',
  `modified` int(10) unsigned NOT NULL,
  `is_system` enum('yes','no') NOT NULL DEFAULT 'no',
  `initiator_del` tinyint(1) unsigned DEFAULT '0',
  `interlocutor_del` tinyint(1) unsigned DEFAULT '0',
  PRIMARY KEY (`conversation_id`),
  KEY `initiator_id` (`initiator_id`),
  KEY `interlocutor_id` (`interlocutor_id`),
  KEY `conversation_ts` (`modified`),
  KEY `subject` (`subject`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mailbox_message`
--

CREATE TABLE IF NOT EXISTS `mailbox_message` (
  `message_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `conversation_id` int(10) unsigned NOT NULL,
  `created` int(10) unsigned NOT NULL DEFAULT '0',
  `sender_id` int(10) unsigned NOT NULL DEFAULT '0',
  `recipient_id` int(10) unsigned NOT NULL DEFAULT '0',
  `text` mediumtext NOT NULL,
  `crc64` bigint(20) NOT NULL,
  PRIMARY KEY (`message_id`),
  KEY `sender_profile_id` (`sender_id`),
  KEY `recipient_profile_id` (`recipient_id`),
  KEY `conversation_id` (`conversation_id`),
  KEY `timestamp` (`created`),
  KEY `crc64` (`crc64`),
  FULLTEXT KEY `text` (`text`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mess_fee`
--

CREATE TABLE IF NOT EXISTS `mess_fee` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) DEFAULT NULL,
  `allotment_id` int(11) DEFAULT NULL,
  `is_paid` varchar(120) NOT NULL DEFAULT '0',
  `created` date DEFAULT NULL,
  `status` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `allotment_id` (`allotment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mess_manage`
--

CREATE TABLE IF NOT EXISTS `mess_manage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) DEFAULT NULL,
  `food_preference` varchar(120) DEFAULT NULL,
  `amount` varchar(120) DEFAULT NULL,
  `status` varchar(120) DEFAULT NULL,
  `created` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE IF NOT EXISTS `modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `control` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

-- --------------------------------------------------------

--
-- Table structure for table `module_access`
--

CREATE TABLE IF NOT EXISTS `module_access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`),
  KEY `module_id` (`module_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `nationality`
--

CREATE TABLE IF NOT EXISTS `nationality` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=194 ;

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `content` text,
  `privacy` varchar(225) NOT NULL,
  `author_id` int(11) DEFAULT NULL,
  `conversation_id` int(11) NOT NULL,
  `message_id` int(11) NOT NULL,
  `is_published` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `notification_settings`
--

CREATE TABLE IF NOT EXISTS `notification_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `settings_key` varchar(255) NOT NULL,
  `sms_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `mail_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `msg_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `student` tinyint(4) NOT NULL,
  `parent_1` tinyint(4) NOT NULL,
  `employee` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=24 ;

-- --------------------------------------------------------

--
-- Table structure for table `notify`
--

CREATE TABLE IF NOT EXISTS `notify` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_type` int(11) DEFAULT NULL COMMENT '1=>All, 2=>Students, 3=>Parents, 4=>Teachers, 5=>Non Teaching Staffs',
  `filter` int(11) DEFAULT NULL COMMENT '(If Students : 1=>All, 2=>Course, 3=>Category), (If Parents : 1=>All, 2=>Course, 3=>Category), (If Teacher : 1=>All, 2=>Course, 3=>Subject, 4=>Elective,5=>Category, 6=>Department, 7=>Position, 8=>Grade), (If Non Teaching Staffs : 1=>All, 2=>Staff Type)',
  `course_id` int(11) DEFAULT NULL COMMENT 'Id of ''courses'' table. Zero indicate All courses',
  `batch_id` int(11) DEFAULT NULL COMMENT 'Id of ''batches'' table. Zero indicate All batches',
  `subject_id` int(11) DEFAULT NULL COMMENT 'Id of ''subjects'' table. Zero indicate All subjects including electives',
  `elective_group_id` int(11) DEFAULT NULL COMMENT 'Id of ''subjects'' table. Zero indicates All Electives Group. From subject table get the elective_group_id',
  `elective_id` int(11) DEFAULT NULL COMMENT 'Id of ''electives'' table. Zero indicate All electives of the selected elective group',
  `category_id` int(11) DEFAULT NULL COMMENT '(If user type is Student or Parents, id of ''student_categories'' table), (If user type is Teacher : id of ''employee_categories'' table), (Zero indicate All)',
  `department_id` int(11) DEFAULT NULL COMMENT 'Id of ''employee_departments'' table. Zero indicate All ',
  `position_id` int(11) DEFAULT NULL COMMENT 'Id of ''employee_positions'' table. Zero indicate All ',
  `grade_id` int(11) DEFAULT NULL COMMENT 'Id of ''employee_grades'' table. Zero indicate All ',
  `staff_type` int(11) DEFAULT NULL COMMENT 'In case of Non Teaching Staff. Indicate id of ''user_roles'' table. Zero indicate All',
  `message` text,
  `type` int(11) DEFAULT NULL COMMENT '1=>None, 2=>Holiday, 3=>Notice, 4=>Alert',
  `is_mail` int(11) NOT NULL DEFAULT '0' COMMENT '0=>No, 1=>Yes',
  `academic_yr` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `total_receiver_count` int(11) NOT NULL DEFAULT '0' COMMENT 'Total number of receiver for the selected filter condition',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `notify_receivers`
--

CREATE TABLE IF NOT EXISTS `notify_receivers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `notify_id` int(11) DEFAULT NULL COMMENT 'Id of ''notify'' table',
  `receiver_id` int(11) DEFAULT NULL,
  `role` int(11) DEFAULT NULL COMMENT '1=>Student, 2=>Parent, 3=>Teacher, 4=>Non Teaching Staff',
  `status` int(11) DEFAULT NULL COMMENT '0=>Message not sent yet, 1=>Message has been sent, 2 => Something went wrong',
  PRIMARY KEY (`id`),
  KEY `notify_id` (`notify_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `online_exams`
--

CREATE TABLE IF NOT EXISTS `online_exams` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `batch_id` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `duration` int(11) DEFAULT NULL COMMENT 'duration in minutes',
  `choice_limit` int(11) DEFAULT NULL COMMENT 'Option limit of multi choice questions',
  `created_at` datetime NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '0- default, 1- Open, 2- closed, 3- result published',
  `is_deleted` int(11) DEFAULT NULL COMMENT '0 - No, 1- Yes',
  PRIMARY KEY (`id`),
  KEY `batch_id` (`batch_id`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `online_exam_answers`
--

CREATE TABLE IF NOT EXISTS `online_exam_answers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) NOT NULL COMMENT 'id from online_exam_questions table',
  `answer` text NOT NULL,
  `order` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `question_id` (`question_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `online_exam_questions`
--

CREATE TABLE IF NOT EXISTS `online_exam_questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `exam_id` int(11) NOT NULL COMMENT 'id from online_exams table',
  `question` text NOT NULL,
  `question_type` int(11) NOT NULL COMMENT '1- Multi choice, 2-True/False, 3-Short Answer, 4-Multi Line',
  `mark` float(10,2) DEFAULT NULL,
  `answer_id` int(11) DEFAULT NULL,
  `question_order` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `is_deleted` int(11) DEFAULT '0' COMMENT '0-No, 1-Yes',
  PRIMARY KEY (`id`),
  KEY `exam_id` (`exam_id`),
  KEY `answer_id` (`answer_id`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `online_exam_scores`
--

CREATE TABLE IF NOT EXISTS `online_exam_scores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `score` float NOT NULL DEFAULT '0' COMMENT 'score for single and multi line questions',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `exam_id` (`exam_id`),
  KEY `question_id` (`question_id`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `online_exam_students`
--

CREATE TABLE IF NOT EXISTS `online_exam_students` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL COMMENT 'online exam table id',
  `exam_start_time` datetime NOT NULL,
  `status` int(11) NOT NULL COMMENT '0=>not submit, 1=>submit',
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `exam_id` (`exam_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `online_exam_student_answers`
--

CREATE TABLE IF NOT EXISTS `online_exam_student_answers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL COMMENT 'id of online_exam_questions table',
  `ans` text NOT NULL COMMENT 'for question type 1,2=>online_exam_answer table id, type 3,4=>direct answer',
  `is_verified` int(11) NOT NULL COMMENT '0=>not verified 1=>verified',
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `exam_id` (`exam_id`),
  KEY `question_id` (`question_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `online_register_settings`
--

CREATE TABLE IF NOT EXISTS `online_register_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `config_key` varchar(255) NOT NULL,
  `config_value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `os_translated`
--

CREATE TABLE IF NOT EXISTS `os_translated` (
  `id` int(11) NOT NULL DEFAULT '0',
  `language` varchar(16) NOT NULL DEFAULT '',
  `translation` text,
  PRIMARY KEY (`id`,`language`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `portal_themes`
--

CREATE TABLE IF NOT EXISTS `portal_themes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `header_logo_background` varchar(10) DEFAULT NULL,
  `header_bar_background` varchar(100) DEFAULT NULL,
  `header_border` varchar(10) DEFAULT NULL,
  `header_dropdown_background` varchar(10) DEFAULT NULL,
  `header_dropdown_text` varchar(10) DEFAULT NULL,
  `header_dropdown_over` varchar(10) DEFAULT NULL,
  `header_text_color` varchar(10) DEFAULT NULL,
  `page_header_background` varchar(10) DEFAULT NULL,
  `page_header_text` varchar(10) DEFAULT NULL,
  `left_panel_background` varchar(10) DEFAULT NULL,
  `left_panel_text` varchar(10) DEFAULT NULL,
  `left_panel_over_background` varchar(10) DEFAULT NULL,
  `left_panel_over_text` varchar(10) DEFAULT NULL,
  `left_panel_active_background` varchar(10) DEFAULT NULL,
  `left_panel_active_text` varchar(10) DEFAULT NULL,
  `main_panel_background` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `previous_year_settings`
--

CREATE TABLE IF NOT EXISTS `previous_year_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `settings_key` varchar(120) NOT NULL,
  `settings_value` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

CREATE TABLE IF NOT EXISTS `profiles` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `lastname` varchar(50) NOT NULL DEFAULT '',
  `firstname` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`user_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `profiles_fields`
--

CREATE TABLE IF NOT EXISTS `profiles_fields` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `varname` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `field_type` varchar(50) NOT NULL,
  `field_size` varchar(15) NOT NULL DEFAULT '0',
  `field_size_min` varchar(15) NOT NULL DEFAULT '0',
  `required` int(1) NOT NULL DEFAULT '0',
  `match` varchar(255) NOT NULL DEFAULT '',
  `range` varchar(255) NOT NULL DEFAULT '',
  `error_message` varchar(255) NOT NULL DEFAULT '',
  `other_validator` varchar(5000) NOT NULL DEFAULT '',
  `default` varchar(255) NOT NULL DEFAULT '',
  `widget` varchar(255) NOT NULL DEFAULT '',
  `widgetparams` varchar(5000) NOT NULL DEFAULT '',
  `position` int(3) NOT NULL DEFAULT '0',
  `visible` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `varname` (`varname`,`widget`,`visible`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `promote_options`
--

CREATE TABLE IF NOT EXISTS `promote_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `option_name` varchar(225) NOT NULL,
  `option_value` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `publication`
--

CREATE TABLE IF NOT EXISTS `publication` (
  `publication_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(120) DEFAULT NULL,
  `location` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`publication_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_items`
--

CREATE TABLE IF NOT EXISTS `purchase_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_material_requistion`
--

CREATE TABLE IF NOT EXISTS `purchase_material_requistion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(11) NOT NULL DEFAULT '1' COMMENT '1 - Purchase by request, 2 -Sale',
  `purchaser` int(11) DEFAULT '0' COMMENT '1 - student, 2 - teacher, 3 - parent',
  `employee_id` int(11) NOT NULL COMMENT 'user_id',
  `department_id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL COMMENT 'id of purchase_items',
  `quantity` int(11) NOT NULL,
  `status` int(11) NOT NULL COMMENT '0=>pending, 1=>approved, 2=>disapproved ',
  `status_tchr` int(11) NOT NULL DEFAULT '0' COMMENT '0=>pending;1=>approve,2=>disapprove',
  `status_pm` int(11) NOT NULL DEFAULT '0' COMMENT '0=>pending;1=>approve,2=>disapprove',
  `return_reason` varchar(1000) NOT NULL,
  `return_date` date DEFAULT NULL,
  `is_issued` int(11) NOT NULL COMMENT '0=>not issued, 1=>issued 2=>Return',
  `is_send` int(11) NOT NULL DEFAULT '0' COMMENT '0=>not send,1=>send',
  `requested_date` date NOT NULL,
  `issued_date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`),
  KEY `material_id` (`material_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_products`
--

CREATE TABLE IF NOT EXISTS `purchase_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_id` int(11) NOT NULL COMMENT 'id of purchase_vendors',
  `item_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `item_id` (`item_id`),
  KEY `vendor_id` (`vendor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_stock`
--

CREATE TABLE IF NOT EXISTS `purchase_stock` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `item_id` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_supply`
--

CREATE TABLE IF NOT EXISTS `purchase_supply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `status` int(11) NOT NULL COMMENT '0=>pending 1=>approved 2=>rejected',
  `send_mail` int(11) NOT NULL COMMENT '0=>not send, 1 =>send',
  `is_verify` int(11) NOT NULL COMMENT '0=>not verified 1=>verify',
  PRIMARY KEY (`id`),
  KEY `item_id` (`item_id`),
  KEY `vendor_id` (`vendor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_vendors`
--

CREATE TABLE IF NOT EXISTS `purchase_vendors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `address_1` varchar(255) NOT NULL,
  `address_2` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `country_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `currency` varchar(255) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `vat_number` varchar(255) NOT NULL,
  `cst_number` varchar(255) NOT NULL,
  `office_phone` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `push_notifications`
--

CREATE TABLE IF NOT EXISTS `push_notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `notification_number` int(11) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `description` text COMMENT 'How it works',
  `language` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `message` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=36 ;

-- --------------------------------------------------------

--
-- Table structure for table `registration`
--

CREATE TABLE IF NOT EXISTS `registration` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) DEFAULT NULL,
  `food_preference` int(11) DEFAULT NULL,
  `desc` varchar(120) DEFAULT NULL,
  `status` varchar(120) DEFAULT NULL COMMENT '''if room allot "S" else " C" and "R"=>Reject',
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `food_preference` (`food_preference`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `return_book`
--

CREATE TABLE IF NOT EXISTS `return_book` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) DEFAULT NULL,
  `book_id` int(11) DEFAULT NULL,
  `borrow_book_id` int(11) DEFAULT NULL,
  `issue_date` date DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `created_date` date DEFAULT NULL,
  `status` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `book_id` (`book_id`),
  KEY `borrow_book_id` (`borrow_book_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `rights`
--

CREATE TABLE IF NOT EXISTS `rights` (
  `itemname` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `weight` int(11) NOT NULL,
  PRIMARY KEY (`itemname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE IF NOT EXISTS `room` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `room_no` varchar(120) DEFAULT NULL,
  `hostel_id` int(11) NOT NULL,
  `floor` varchar(120) DEFAULT NULL,
  `is_full` varchar(120) DEFAULT NULL,
  `no_of_bed` int(120) DEFAULT NULL,
  `created` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hostel_id` (`hostel_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `roomrequest`
--

CREATE TABLE IF NOT EXISTS `roomrequest` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) DEFAULT NULL,
  `allot_id` varchar(120) DEFAULT NULL,
  `status` varchar(120) DEFAULT NULL COMMENT 'if room allot "S" else " C" and "R"=>Reject',
  `created_at` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `route_attendance`
--

CREATE TABLE IF NOT EXISTS `route_attendance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section_id` int(11) NOT NULL COMMENT '1 - Morning section, 2 - Evening section',
  `mode` int(11) NOT NULL COMMENT '0-Entry, 1-Exit',
  `route_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `route_id` (`route_id`),
  KEY `student_id` (`student_id`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `route_details`
--

CREATE TABLE IF NOT EXISTS `route_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `route_name` varchar(120) DEFAULT NULL,
  `no_of_stops` varchar(120) DEFAULT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vehicle_id` (`vehicle_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `route_devices`
--

CREATE TABLE IF NOT EXISTS `route_devices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `route_id` int(11) NOT NULL COMMENT 'primary key from `route_details`',
  `device_id` int(11) NOT NULL COMMENT 'Primary key from `devices` table',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `status` int(11) DEFAULT NULL COMMENT '0 - Waiting for approval, 1 - Approved',
  PRIMARY KEY (`id`),
  KEY `route_id` (`route_id`),
  KEY `device_id` (`device_id`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `salary_details`
--

CREATE TABLE IF NOT EXISTS `salary_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL COMMENT 'reference to employee_table',
  `salary_date` date NOT NULL,
  `basic_pay` decimal(10,2) NOT NULL,
  `incentive` decimal(10,2) NOT NULL,
  `over_time` decimal(10,2) NOT NULL,
  `hike` decimal(10,2) NOT NULL,
  `lop` decimal(10,2) NOT NULL,
  `loan` decimal(10,2) NOT NULL,
  `festival_bonus` decimal(10,2) NOT NULL,
  `tds` decimal(10,2) NOT NULL,
  `esi` decimal(10,2) NOT NULL,
  `epf` decimal(10,2) NOT NULL,
  `casual_leave` decimal(3,1) NOT NULL,
  `casual_remaining` decimal(3,1) NOT NULL,
  `sick_leave` decimal(3,1) NOT NULL,
  `sick_remaining` decimal(3,1) NOT NULL,
  `earn_total` decimal(10,2) NOT NULL,
  `deduction_total` decimal(10,2) NOT NULL,
  `net_salary` decimal(10,2) NOT NULL,
  `note` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `filename` varchar(255) NOT NULL COMMENT 'name of payslip pdf file',
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `savedsearches`
--

CREATE TABLE IF NOT EXISTS `savedsearches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `url` longtext,
  `type` int(11) DEFAULT NULL,
  `name` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `user_id_2` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `semester`
--

CREATE TABLE IF NOT EXISTS `semester` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(225) NOT NULL,
  `description` varchar(500) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `semester_courses`
--

CREATE TABLE IF NOT EXISTS `semester_courses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `semester_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `semester_id` (`semester_id`),
  KEY `course_id` (`course_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sms`
--

CREATE TABLE IF NOT EXISTS `sms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `phone_number` varchar(50) DEFAULT NULL,
  `message` text NOT NULL,
  `status` int(11) NOT NULL,
  `instance` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sms_count`
--

CREATE TABLE IF NOT EXISTS `sms_count` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `current` int(11) NOT NULL DEFAULT '0',
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sms_gateway`
--

CREATE TABLE IF NOT EXISTS `sms_gateway` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `url` varchar(255) NOT NULL,
  `method` int(11) NOT NULL,
  `responds_format` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sms_gateway_parameter`
--

CREATE TABLE IF NOT EXISTS `sms_gateway_parameter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gateway_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gateway_id` (`gateway_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sms_settings`
--

CREATE TABLE IF NOT EXISTS `sms_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `settings_key` varchar(255) DEFAULT NULL,
  `is_enabled` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sms_templates`
--

CREATE TABLE IF NOT EXISTS `sms_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `template` text NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sourcemessage`
--

CREATE TABLE IF NOT EXISTS `sourcemessage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(32) DEFAULT NULL,
  `message` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3503 ;

-- --------------------------------------------------------

--
-- Table structure for table `stop_details`
--

CREATE TABLE IF NOT EXISTS `stop_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `route_id` int(11) DEFAULT NULL,
  `stop_name` varchar(120) DEFAULT NULL,
  `fare` decimal(10,2) DEFAULT NULL,
  `arrival_mrng` varchar(120) DEFAULT NULL,
  `departure_mrng` time DEFAULT NULL,
  `arrival_evng` varchar(120) DEFAULT NULL,
  `departure_evng` time DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `route_id` (`route_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE IF NOT EXISTS `students` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(120) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `admission_no` varchar(255) DEFAULT NULL,
  `class_roll_no` varchar(255) DEFAULT NULL,
  `admission_date` date DEFAULT NULL,
  `national_student_id` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `batch_id` int(11) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `blood_group` varchar(255) DEFAULT NULL,
  `birth_place` varchar(255) DEFAULT NULL,
  `nationality_id` int(11) DEFAULT NULL,
  `language` varchar(255) DEFAULT NULL,
  `religion` varchar(255) DEFAULT NULL,
  `student_category_id` int(11) DEFAULT NULL,
  `address_line1` varchar(255) DEFAULT NULL,
  `address_line2` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `pin_code` varchar(255) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `phone1` varchar(255) DEFAULT NULL,
  `phone2` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `immediate_contact_id` int(11) DEFAULT NULL,
  `is_sms_enabled` tinyint(1) DEFAULT '1',
  `photo_file_name` varchar(255) DEFAULT NULL,
  `photo_content_type` varchar(255) DEFAULT NULL,
  `photo_data` longblob,
  `status_description` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_deleted` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `has_paid_fees` tinyint(1) DEFAULT '0',
  `photo_file_size` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `registration_id` int(120) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `registration_date` date DEFAULT NULL,
  `academic_yr` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '0 = Pending, 1 = Approved, -1 = Disapproved,-2 = Deleted,-3 = Waiting list',
  `is_completed` int(11) NOT NULL,
  `type` int(11) NOT NULL COMMENT '0 => Normal Admission, 1 => Online Admission',
  `is_online` int(11) NOT NULL COMMENT '0 => Admin Registration, 1 => Online Admission',
  `nr` int(11) DEFAULT NULL,
  `tr` int(11) DEFAULT NULL,
  `export_check` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `index_students_on_admission_no` (`admission_no`(10)),
  KEY `index_students_on_first_name_and_middle_name_and_last_name` (`first_name`(10),`middle_name`(10),`last_name`(10)),
  KEY `batch_id` (`batch_id`),
  KEY `nationality_id` (`nationality_id`),
  KEY `student_category_id` (`student_category_id`),
  KEY `immediate_contact_id` (`immediate_contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `student_attentance`
--

CREATE TABLE IF NOT EXISTS `student_attentance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `batch_id` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  `reason` varchar(120) NOT NULL,
  `leave_type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `batch_id` (`batch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `student_categories`
--

CREATE TABLE IF NOT EXISTS `student_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `student_document`
--

CREATE TABLE IF NOT EXISTS `student_document` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) DEFAULT NULL,
  `doc_type` varchar(120) DEFAULT NULL,
  `title` varchar(120) DEFAULT NULL,
  `file` varchar(200) DEFAULT NULL,
  `file_type` varchar(120) DEFAULT NULL,
  `is_approved` int(11) NOT NULL COMMENT '0 = Pending, 1= Approved, -1 = Rejected',
  `uploaded_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `student_document_list`
--

CREATE TABLE IF NOT EXISTS `student_document_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `mandatory` int(11) NOT NULL COMMENT '0-No;1-Yes,cannot be skipped;2-Yes,can be skipped',
  `is_required` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `student_electives`
--

CREATE TABLE IF NOT EXISTS `student_electives` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) DEFAULT NULL,
  `batch_id` int(11) DEFAULT NULL,
  `elective_id` int(11) DEFAULT NULL,
  `elective_group_id` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `batch_id` (`batch_id`),
  KEY `elective_id` (`elective_id`),
  KEY `elective_group_id` (`elective_group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `student_leave_types`
--

CREATE TABLE IF NOT EXISTS `student_leave_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `is_excluded` int(11) NOT NULL COMMENT '1: excluded ,0:not excluded',
  `status` int(11) NOT NULL COMMENT '1:active,2:inactive',
  `label` varchar(255) NOT NULL,
  `colour_code` varchar(225) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `student_previous_datas`
--

CREATE TABLE IF NOT EXISTS `student_previous_datas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) DEFAULT NULL,
  `institution` varchar(255) DEFAULT NULL,
  `year` varchar(255) DEFAULT NULL,
  `course` varchar(255) DEFAULT NULL,
  `total_mark` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `student_subjectwise_attentance`
--

CREATE TABLE IF NOT EXISTS `student_subjectwise_attentance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `timetable_id` int(11) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `leavetype_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `weekday_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `timetable_id` (`timetable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE IF NOT EXISTS `subjects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `split_subject` int(11) NOT NULL,
  `batch_id` int(11) DEFAULT NULL,
  `no_exams` tinyint(1) DEFAULT '0',
  `max_weekly_classes` int(11) DEFAULT NULL,
  `elective_group_id` int(11) NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `admin_id` int(11) NOT NULL COMMENT 'Id in the subjects_common_pool table ',
  `is_edit` int(11) NOT NULL DEFAULT '0' COMMENT '1 => Yes, 0 => No',
  `cbsc_common` int(11) DEFAULT '0' COMMENT '0=>normal subject, 1=>cbsc common subject',
  PRIMARY KEY (`id`),
  KEY `index_subjects_on_batch_id_and_elective_group_id_and_is_deleted` (`batch_id`,`elective_group_id`,`is_deleted`),
  KEY `batch_id` (`batch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `subjects_common_pool`
--

CREATE TABLE IF NOT EXISTS `subjects_common_pool` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `course_id` int(11) NOT NULL,
  `subject_name` varchar(255) DEFAULT NULL,
  `subject_code` varchar(225) NOT NULL,
  `max_weekly_classes` int(11) NOT NULL,
  `split_subject` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `course_id` (`course_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `subject_commonpool_split`
--

CREATE TABLE IF NOT EXISTS `subject_commonpool_split` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject_id` int(11) NOT NULL,
  `split_name` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `subject_id` (`subject_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `subject_split`
--

CREATE TABLE IF NOT EXISTS `subject_split` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject_id` int(11) NOT NULL,
  `split_name` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `subject_id` (`subject_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `system_offline_settings`
--

CREATE TABLE IF NOT EXISTS `system_offline_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `offline_message` varchar(255) DEFAULT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `allowed_users` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `system_templates`
--

CREATE TABLE IF NOT EXISTS `system_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_id` int(11) NOT NULL,
  `user_type` int(11) NOT NULL,
  `template` text NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=42 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_timezone`
--

CREATE TABLE IF NOT EXISTS `tbl_timezone` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=462 ;

-- --------------------------------------------------------

--
-- Table structure for table `teacher_subjectwise_attentance`
--

CREATE TABLE IF NOT EXISTS `teacher_subjectwise_attentance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) DEFAULT NULL,
  `timetable_id` int(11) DEFAULT NULL,
  `reason` varchar(255) NOT NULL,
  `leavetype_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `weekday_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`),
  KEY `timetable_id` (`timetable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `teacher_substitution`
--

CREATE TABLE IF NOT EXISTS `teacher_substitution` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` int(11) NOT NULL,
  `date_leave` date NOT NULL,
  `batch` int(11) NOT NULL,
  `time_table_entry_id` int(11) NOT NULL,
  `substitute_emp_id` int(11) NOT NULL,
  `leave_requested_emp_id` int(11) NOT NULL,
  `leave_request_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `terms`
--

CREATE TABLE IF NOT EXISTS `terms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `term_id` varchar(11) NOT NULL COMMENT '1=>term 1, 2=>term 2',
  `academic_yr_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `academic_yr_id` (`academic_yr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `themes`
--

CREATE TABLE IF NOT EXISTS `themes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `topbar_background` varchar(10) DEFAULT NULL,
  `topbar_background_text` varchar(10) DEFAULT NULL,
  `topbar_message` varchar(10) DEFAULT NULL,
  `topbar_account_background` varchar(10) DEFAULT NULL,
  `topbar_account_color` varchar(10) DEFAULT NULL,
  `body_background` varchar(10) DEFAULT NULL,
  `search_background` varchar(10) DEFAULT NULL,
  `search_color` varchar(10) DEFAULT NULL,
  `menu_background` varchar(10) DEFAULT NULL,
  `menu_border` varchar(10) DEFAULT NULL,
  `menu_text_color` varchar(10) DEFAULT NULL,
  `menu_active_background` varchar(10) DEFAULT NULL,
  `menu_active_color` varchar(10) DEFAULT NULL,
  `breadcrumbs_background` varchar(10) DEFAULT NULL,
  `breadcrumbs_color` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `timetable_entries`
--

CREATE TABLE IF NOT EXISTS `timetable_entries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `batch_id` int(11) DEFAULT NULL,
  `weekday_id` int(11) DEFAULT NULL,
  `class_timing_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `is_elective` int(11) NOT NULL COMMENT '0- non elective 2-elective',
  `split_subject` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `by_timetable` (`weekday_id`,`batch_id`,`class_timing_id`),
  KEY `batch_id` (`batch_id`),
  KEY `weekday_id` (`weekday_id`),
  KEY `class_timing_id` (`class_timing_id`),
  KEY `employee_id` (`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `timezone`
--

CREATE TABLE IF NOT EXISTS `timezone` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timezone` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=462 ;

-- --------------------------------------------------------

--
-- Table structure for table `transportation`
--

CREATE TABLE IF NOT EXISTS `transportation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) DEFAULT NULL,
  `stop_id` int(11) DEFAULT NULL,
  `is_paid` int(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `stop_id` (`stop_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(128) NOT NULL,
  `salt` varchar(255) DEFAULT NULL,
  `email` varchar(128) NOT NULL,
  `mobile_number` varchar(255) DEFAULT NULL,
  `activkey` varchar(128) NOT NULL DEFAULT '',
  `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastvisit_at` timestamp NOT NULL DEFAULT now(),
  `superuser` int(1) NOT NULL DEFAULT '0',
  `status` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `status` (`status`),
  KEY `superuser` (`superuser`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_device`
--

CREATE TABLE IF NOT EXISTS `user_device` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `device_id` text NOT NULL,
  `auth_key` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_events`
--

CREATE TABLE IF NOT EXISTS `user_events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_otp_details`
--

CREATE TABLE IF NOT EXISTS `user_otp_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `otp` int(11) NOT NULL,
  `key` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE IF NOT EXISTS `user_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(120) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_settings`
--

CREATE TABLE IF NOT EXISTS `user_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `dateformat` varchar(120) DEFAULT NULL,
  `displaydate` varchar(120) DEFAULT NULL,
  `timezone` varchar(120) DEFAULT NULL,
  `timeformat` varchar(120) DEFAULT NULL,
  `name_format` varchar(120) DEFAULT NULL,
  `language` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `vacate`
--

CREATE TABLE IF NOT EXISTS `vacate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) DEFAULT NULL,
  `room_no` varchar(120) DEFAULT NULL,
  `allot_id` int(11) DEFAULT NULL,
  `status` varchar(120) DEFAULT NULL,
  `admit_date` date DEFAULT NULL,
  `vacate_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `allot_id` (`allot_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_details`
--

CREATE TABLE IF NOT EXISTS `vehicle_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vehicle_no` varchar(120) DEFAULT NULL,
  `vehicle_code` varchar(120) DEFAULT NULL,
  `no_of_seats` varchar(120) DEFAULT NULL,
  `maximum_capacity` varchar(120) DEFAULT NULL,
  `vehicle_type` varchar(120) DEFAULT NULL,
  `address` varchar(120) DEFAULT NULL,
  `city` varchar(120) DEFAULT NULL,
  `state` varchar(120) DEFAULT NULL,
  `phone` varchar(120) DEFAULT NULL,
  `insurance` varchar(120) DEFAULT NULL,
  `tax_remitted` varchar(120) DEFAULT NULL,
  `permit` varchar(120) DEFAULT NULL,
  `status` varchar(120) DEFAULT NULL,
  `is_deleted` int(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `waitinglist_students`
--

CREATE TABLE IF NOT EXISTS `waitinglist_students` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `batch_id` int(11) NOT NULL,
  `priority` int(11) NOT NULL,
  `status` int(11) NOT NULL COMMENT '0 = active, 1 = deleted',
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `batch_id` (`batch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `weekdays`
--

CREATE TABLE IF NOT EXISTS `weekdays` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `batch_id` int(11) DEFAULT NULL,
  `weekday` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `index_weekdays_on_batch_id` (`batch_id`),
  KEY `batch_id` (`batch_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `achievements`
--
ALTER TABLE `achievements`
  ADD CONSTRAINT `achievements_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `activity_feed`
--
ALTER TABLE `activity_feed`
  ADD CONSTRAINT `activity_feed_ibfk_1` FOREIGN KEY (`initiator_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `activity_feed_ibfk_2` FOREIGN KEY (`activity_type`) REFERENCES `activity_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `authassignment`
--
ALTER TABLE `authassignment`
  ADD CONSTRAINT `authassignment_ibfk_1` FOREIGN KEY (`itemname`) REFERENCES `authitem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `authitemchild`
--
ALTER TABLE `authitemchild`
  ADD CONSTRAINT `authitemchild_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `authitem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `authitemchild_ibfk_2` FOREIGN KEY (`child`) REFERENCES `authitem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `batches`
--
ALTER TABLE `batches`
  ADD CONSTRAINT `batches_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `batches_ibfk_2` FOREIGN KEY (`academic_yr_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `batches_ibfk_3` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `batches_ibfk_4` FOREIGN KEY (`semester_id`) REFERENCES `semester` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `batch_students`
--
ALTER TABLE `batch_students`
  ADD CONSTRAINT `batch_students_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `batch_students_ibfk_2` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `batch_students_ibfk_3` FOREIGN KEY (`academic_yr_id`) REFERENCES `academic_years` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `book`
--
ALTER TABLE `book`
  ADD CONSTRAINT `book_ibfk_1` FOREIGN KEY (`category`) REFERENCES `category` (`cat_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `book_ibfk_2` FOREIGN KEY (`author`) REFERENCES `author` (`auth_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `book_fine`
--
ALTER TABLE `book_fine`
  ADD CONSTRAINT `book_fine_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `book_fine_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `book` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `borrow_book`
--
ALTER TABLE `borrow_book`
  ADD CONSTRAINT `borrow_book_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `borrow_book_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `book` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `bus_log`
--
ALTER TABLE `bus_log`
  ADD CONSTRAINT `bus_log_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicle_details` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cbscexam_scores_split`
--
ALTER TABLE `cbscexam_scores_split`
  ADD CONSTRAINT `cbscexam_scores_split_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cbscexam_scores_split_ibfk_2` FOREIGN KEY (`exam_scores_id`) REFERENCES `cbsc_exam_scores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cbsc_coscholastic_score`
--
ALTER TABLE `cbsc_coscholastic_score`
  ADD CONSTRAINT `cbsc_coscholastic_score_ibfk_1` FOREIGN KEY (`coscholastic_id`) REFERENCES `cbsc_co_scholastic` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cbsc_coscholastic_score_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cbsc_co_scholastic`
--
ALTER TABLE `cbsc_co_scholastic`
  ADD CONSTRAINT `cbsc_co_scholastic_ibfk_1` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cbsc_exams`
--
ALTER TABLE `cbsc_exams`
  ADD CONSTRAINT `cbsc_exams_ibfk_1` FOREIGN KEY (`exam_group_id`) REFERENCES `cbsc_exam_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cbsc_exams_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cbsc_exams_17`
--
ALTER TABLE `cbsc_exams_17`
  ADD CONSTRAINT `cbsc_exams_17_ibfk_1` FOREIGN KEY (`exam_group_id`) REFERENCES `cbsc_exam_group_17` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cbsc_exams_17_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cbsc_exam_groups`
--
ALTER TABLE `cbsc_exam_groups`
  ADD CONSTRAINT `cbsc_exam_groups_ibfk_1` FOREIGN KEY (`term_id`) REFERENCES `terms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cbsc_exam_groups_ibfk_2` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cbsc_exam_group_17`
--
ALTER TABLE `cbsc_exam_group_17`
  ADD CONSTRAINT `cbsc_exam_group_17_ibfk_1` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cbsc_exam_scores`
--
ALTER TABLE `cbsc_exam_scores`
  ADD CONSTRAINT `cbsc_exam_scores_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cbsc_exam_scores_ibfk_2` FOREIGN KEY (`exam_id`) REFERENCES `cbsc_exams` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cbsc_exam_scores_17`
--
ALTER TABLE `cbsc_exam_scores_17`
  ADD CONSTRAINT `cbsc_exam_scores_17_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cbsc_exam_scores_17_ibfk_2` FOREIGN KEY (`exam_id`) REFERENCES `cbsc_exams_17` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cbsc_exam_scores_split_17`
--
ALTER TABLE `cbsc_exam_scores_split_17`
  ADD CONSTRAINT `cbsc_exam_scores_split_17_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cbsc_exam_scores_split_17_ibfk_2` FOREIGN KEY (`exam_scores_id`) REFERENCES `cbsc_exam_scores_17` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cbsc_exam_settings`
--
ALTER TABLE `cbsc_exam_settings`
  ADD CONSTRAINT `cbsc_exam_settings_ibfk_1` FOREIGN KEY (`academic_yr_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `complaints`
--
ALTER TABLE `complaints`
  ADD CONSTRAINT `complaints_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `complaints_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `complaint_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `complaints_ibfk_3` FOREIGN KEY (`closed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `complaint_feedback`
--
ALTER TABLE `complaint_feedback`
  ADD CONSTRAINT `complaint_feedback_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `complaint_feedback_ibfk_2` FOREIGN KEY (`complaint_id`) REFERENCES `complaints` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `contacts`
--
ALTER TABLE `contacts`
  ADD CONSTRAINT `contacts_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `contacts_groups`
--
ALTER TABLE `contacts_groups`
  ADD CONSTRAINT `contacts_groups_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `contacts_list`
--
ALTER TABLE `contacts_list`
  ADD CONSTRAINT `contacts_list_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `contacts_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `contacts_list_ibfk_2` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`academic_yr_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dashboard_settings`
--
ALTER TABLE `dashboard_settings`
  ADD CONSTRAINT `dashboard_settings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `dashboard_settings_ibfk_2` FOREIGN KEY (`block_id`) REFERENCES `dashboard` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `devices`
--
ALTER TABLE `devices`
  ADD CONSTRAINT `devices_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `document_uploads`
--
ALTER TABLE `document_uploads`
  ADD CONSTRAINT `document_uploads_ibfk_1` FOREIGN KEY (`model_id`) REFERENCES `document_models` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `document_uploads_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `driver_details`
--
ALTER TABLE `driver_details`
  ADD CONSTRAINT `driver_details_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicle_details` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `electives`
--
ALTER TABLE `electives`
  ADD CONSTRAINT `electives_ibfk_1` FOREIGN KEY (`elective_group_id`) REFERENCES `elective_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `electives_ibfk_2` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `elective_groups`
--
ALTER TABLE `elective_groups`
  ADD CONSTRAINT `elective_groups_ibfk_1` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `email_attachments`
--
ALTER TABLE `email_attachments`
  ADD CONSTRAINT `email_attachments_ibfk_1` FOREIGN KEY (`mail_id`) REFERENCES `email_drafts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `email_attachments_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `email_drafts`
--
ALTER TABLE `email_drafts`
  ADD CONSTRAINT `email_drafts_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `email_recipients`
--
ALTER TABLE `email_recipients`
  ADD CONSTRAINT `email_recipients_ibfk_1` FOREIGN KEY (`mail_id`) REFERENCES `email_drafts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`employee_category_id`) REFERENCES `employee_categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `employees_ibfk_2` FOREIGN KEY (`employee_position_id`) REFERENCES `employee_positions` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `employees_ibfk_3` FOREIGN KEY (`employee_department_id`) REFERENCES `employee_departments` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `employees_ibfk_4` FOREIGN KEY (`employee_grade_id`) REFERENCES `employee_grades` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `employees_ibfk_5` FOREIGN KEY (`nationality_id`) REFERENCES `nationality` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `employees_ibfk_6` FOREIGN KEY (`home_country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `employees_ibfk_7` FOREIGN KEY (`office_country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `employees_ibfk_8` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `employees_subjects`
--
ALTER TABLE `employees_subjects`
  ADD CONSTRAINT `employees_subjects_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `employees_subjects_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `employee_attendances`
--
ALTER TABLE `employee_attendances`
  ADD CONSTRAINT `employee_attendances_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `employee_attendances_ibfk_2` FOREIGN KEY (`employee_leave_type_id`) REFERENCES `leave_types` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `employee_document`
--
ALTER TABLE `employee_document`
  ADD CONSTRAINT `employee_document_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `employee_document_ibfk_2` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `employee_elective_subjects`
--
ALTER TABLE `employee_elective_subjects`
  ADD CONSTRAINT `employee_elective_subjects_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `employee_elective_subjects_ibfk_2` FOREIGN KEY (`elective_id`) REFERENCES `electives` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `employee_elective_subjects_ibfk_3` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `employee_positions`
--
ALTER TABLE `employee_positions`
  ADD CONSTRAINT `employee_positions_ibfk_1` FOREIGN KEY (`employee_category_id`) REFERENCES `employee_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `events_ibfk_2` FOREIGN KEY (`type`) REFERENCES `events_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `exams`
--
ALTER TABLE `exams`
  ADD CONSTRAINT `exams_ibfk_1` FOREIGN KEY (`exam_group_id`) REFERENCES `exam_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `exams_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `exams_common`
--
ALTER TABLE `exams_common`
  ADD CONSTRAINT `exams_common_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `exam_groups`
--
ALTER TABLE `exam_groups`
  ADD CONSTRAINT `exam_groups_ibfk_1` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `exam_groups_ibfk_2` FOREIGN KEY (`common_exam_id`) REFERENCES `exams_common` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `exam_scores`
--
ALTER TABLE `exam_scores`
  ADD CONSTRAINT `exam_scores_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `exam_scores_ibfk_2` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `exam_scores_split`
--
ALTER TABLE `exam_scores_split`
  ADD CONSTRAINT `exam_scores_split_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `exam_scores_split_ibfk_2` FOREIGN KEY (`exam_scores_id`) REFERENCES `exam_scores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `fee_categories`
--
ALTER TABLE `fee_categories`
  ADD CONSTRAINT `fee_categories_ibfk_1` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fee_categories_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fee_categories_ibfk_3` FOREIGN KEY (`edited_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `fee_invoices`
--
ALTER TABLE `fee_invoices`
  ADD CONSTRAINT `fee_invoices_ibfk_1` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fee_invoices_ibfk_2` FOREIGN KEY (`fee_id`) REFERENCES `fee_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fee_invoices_ibfk_3` FOREIGN KEY (`subscription_id`) REFERENCES `fee_subscriptions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fee_invoices_ibfk_4` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `fee_invoice_particulars`
--
ALTER TABLE `fee_invoice_particulars`
  ADD CONSTRAINT `fee_invoice_particulars_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `fee_invoices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `fee_particulars`
--
ALTER TABLE `fee_particulars`
  ADD CONSTRAINT `fee_particulars_ibfk_1` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fee_particulars_ibfk_2` FOREIGN KEY (`fee_id`) REFERENCES `fee_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fee_particulars_ibfk_4` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fee_particulars_ibfk_5` FOREIGN KEY (`edited_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `fee_particular_access`
--
ALTER TABLE `fee_particular_access`
  ADD CONSTRAINT `fee_particular_access_ibfk_1` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fee_particular_access_ibfk_2` FOREIGN KEY (`particular_id`) REFERENCES `fee_particulars` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fee_particular_access_ibfk_3` FOREIGN KEY (`course`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fee_particular_access_ibfk_4` FOREIGN KEY (`batch`) REFERENCES `batches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fee_particular_access_ibfk_5` FOREIGN KEY (`student_category_id`) REFERENCES `student_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fee_particular_access_ibfk_6` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fee_particular_access_ibfk_7` FOREIGN KEY (`edited_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `fee_payment_types`
--
ALTER TABLE `fee_payment_types`
  ADD CONSTRAINT `fee_payment_types_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `fee_subscriptions`
--
ALTER TABLE `fee_subscriptions`
  ADD CONSTRAINT `fee_subscriptions_ibfk_1` FOREIGN KEY (`fee_id`) REFERENCES `fee_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fee_subscriptions_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `fee_taxes`
--
ALTER TABLE `fee_taxes`
  ADD CONSTRAINT `fee_taxes_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `fee_transactions`
--
ALTER TABLE `fee_transactions`
  ADD CONSTRAINT `fee_transactions_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `fee_invoices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fee_transactions_ibfk_2` FOREIGN KEY (`payment_type`) REFERENCES `fee_payment_types` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fee_transactions_ibfk_3` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `file_uploads`
--
ALTER TABLE `file_uploads`
  ADD CONSTRAINT `file_uploads_ibfk_1` FOREIGN KEY (`category`) REFERENCES `file_uploads_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `file_uploads_ibfk_2` FOREIGN KEY (`course`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `file_uploads_ibfk_3` FOREIGN KEY (`batch`) REFERENCES `batches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `file_uploads_ibfk_4` FOREIGN KEY (`academic_yr_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `file_uploads_ibfk_5` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `file_uploads_category`
--
ALTER TABLE `file_uploads_category`
  ADD CONSTRAINT `file_uploads_category_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `file_uploads_students`
--
ALTER TABLE `file_uploads_students`
  ADD CONSTRAINT `file_uploads_students_ibfk_1` FOREIGN KEY (`table_id`) REFERENCES `file_uploads` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `file_uploads_students_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `floor`
--
ALTER TABLE `floor`
  ADD CONSTRAINT `floor_ibfk_1` FOREIGN KEY (`hostel_id`) REFERENCES `hosteldetails` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `fuel_consumption`
--
ALTER TABLE `fuel_consumption`
  ADD CONSTRAINT `fuel_consumption_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicle_details` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `guardians`
--
ALTER TABLE `guardians`
  ADD CONSTRAINT `guardians_ibfk_1` FOREIGN KEY (`ward_id`) REFERENCES `students` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `guardians_ibfk_2` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `guardian_list`
--
ALTER TABLE `guardian_list`
  ADD CONSTRAINT `guardian_list_ibfk_1` FOREIGN KEY (`guardian_id`) REFERENCES `guardians` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `guardian_list_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `holidays`
--
ALTER TABLE `holidays`
  ADD CONSTRAINT `holidays_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD CONSTRAINT `leave_requests_ibfk_1` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `leave_requests_ibfk_2` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `log_comment`
--
ALTER TABLE `log_comment`
  ADD CONSTRAINT `log_comment_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `log_comment_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `log_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `mess_fee`
--
ALTER TABLE `mess_fee`
  ADD CONSTRAINT `mess_fee_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mess_fee_ibfk_2` FOREIGN KEY (`allotment_id`) REFERENCES `allotment` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `mess_manage`
--
ALTER TABLE `mess_manage`
  ADD CONSTRAINT `mess_manage_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `module_access`
--
ALTER TABLE `module_access`
  ADD CONSTRAINT `module_access_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `user_roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `module_access_ibfk_2` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `notify_receivers`
--
ALTER TABLE `notify_receivers`
  ADD CONSTRAINT `notify_receivers_ibfk_1` FOREIGN KEY (`notify_id`) REFERENCES `notify` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `online_exams`
--
ALTER TABLE `online_exams`
  ADD CONSTRAINT `online_exams_ibfk_1` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `online_exams_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `online_exam_answers`
--
ALTER TABLE `online_exam_answers`
  ADD CONSTRAINT `online_exam_answers_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `online_exam_questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `online_exam_questions`
--
ALTER TABLE `online_exam_questions`
  ADD CONSTRAINT `online_exam_questions_ibfk_1` FOREIGN KEY (`exam_id`) REFERENCES `online_exams` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `online_exam_questions_ibfk_2` FOREIGN KEY (`answer_id`) REFERENCES `online_exam_answers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `online_exam_questions_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `online_exam_scores`
--
ALTER TABLE `online_exam_scores`
  ADD CONSTRAINT `online_exam_scores_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `online_exam_scores_ibfk_2` FOREIGN KEY (`exam_id`) REFERENCES `online_exams` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `online_exam_scores_ibfk_3` FOREIGN KEY (`question_id`) REFERENCES `online_exam_questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `online_exam_scores_ibfk_4` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `online_exam_students`
--
ALTER TABLE `online_exam_students`
  ADD CONSTRAINT `online_exam_students_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `online_exam_students_ibfk_2` FOREIGN KEY (`exam_id`) REFERENCES `online_exams` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `online_exam_student_answers`
--
ALTER TABLE `online_exam_student_answers`
  ADD CONSTRAINT `online_exam_student_answers_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `online_exam_student_answers_ibfk_2` FOREIGN KEY (`exam_id`) REFERENCES `online_exams` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `online_exam_student_answers_ibfk_3` FOREIGN KEY (`question_id`) REFERENCES `online_exam_questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `os_translated`
--
ALTER TABLE `os_translated`
  ADD CONSTRAINT `FK_os_translated_SourceMessage` FOREIGN KEY (`id`) REFERENCES `sourcemessage` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `portal_themes`
--
ALTER TABLE `portal_themes`
  ADD CONSTRAINT `portal_themes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `profiles`
--
ALTER TABLE `profiles`
  ADD CONSTRAINT `user_profile_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_material_requistion`
--
ALTER TABLE `purchase_material_requistion`
  ADD CONSTRAINT `purchase_material_requistion_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `purchase_material_requistion_ibfk_2` FOREIGN KEY (`material_id`) REFERENCES `purchase_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `purchase_products`
--
ALTER TABLE `purchase_products`
  ADD CONSTRAINT `purchase_products_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `purchase_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `purchase_products_ibfk_2` FOREIGN KEY (`vendor_id`) REFERENCES `purchase_vendors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `purchase_stock`
--
ALTER TABLE `purchase_stock`
  ADD CONSTRAINT `purchase_stock_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `purchase_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `purchase_supply`
--
ALTER TABLE `purchase_supply`
  ADD CONSTRAINT `purchase_supply_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `purchase_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `purchase_supply_ibfk_2` FOREIGN KEY (`vendor_id`) REFERENCES `purchase_vendors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `registration`
--
ALTER TABLE `registration`
  ADD CONSTRAINT `registration_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `registration_ibfk_2` FOREIGN KEY (`food_preference`) REFERENCES `food_info` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `return_book`
--
ALTER TABLE `return_book`
  ADD CONSTRAINT `return_book_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `return_book_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `book` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `return_book_ibfk_3` FOREIGN KEY (`borrow_book_id`) REFERENCES `borrow_book` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rights`
--
ALTER TABLE `rights`
  ADD CONSTRAINT `rights_ibfk_1` FOREIGN KEY (`itemname`) REFERENCES `authitem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `room`
--
ALTER TABLE `room`
  ADD CONSTRAINT `room_ibfk_1` FOREIGN KEY (`hostel_id`) REFERENCES `hosteldetails` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `roomrequest`
--
ALTER TABLE `roomrequest`
  ADD CONSTRAINT `roomrequest_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `route_attendance`
--
ALTER TABLE `route_attendance`
  ADD CONSTRAINT `route_attendance_ibfk_1` FOREIGN KEY (`route_id`) REFERENCES `route_details` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `route_attendance_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `route_attendance_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `route_details`
--
ALTER TABLE `route_details`
  ADD CONSTRAINT `route_details_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicle_details` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `route_devices`
--
ALTER TABLE `route_devices`
  ADD CONSTRAINT `route_devices_ibfk_1` FOREIGN KEY (`route_id`) REFERENCES `route_details` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `route_devices_ibfk_2` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `route_devices_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `salary_details`
--
ALTER TABLE `salary_details`
  ADD CONSTRAINT `salary_details_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `savedsearches`
--
ALTER TABLE `savedsearches`
  ADD CONSTRAINT `savedsearches_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `semester_courses`
--
ALTER TABLE `semester_courses`
  ADD CONSTRAINT `semester_courses_ibfk_1` FOREIGN KEY (`semester_id`) REFERENCES `semester` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `semester_courses_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sms_gateway_parameter`
--
ALTER TABLE `sms_gateway_parameter`
  ADD CONSTRAINT `sms_gateway_parameter_ibfk_1` FOREIGN KEY (`gateway_id`) REFERENCES `sms_gateway` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sms_templates`
--
ALTER TABLE `sms_templates`
  ADD CONSTRAINT `sms_templates_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `stop_details`
--
ALTER TABLE `stop_details`
  ADD CONSTRAINT `stop_details_ibfk_1` FOREIGN KEY (`route_id`) REFERENCES `route_details` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `students_ibfk_2` FOREIGN KEY (`nationality_id`) REFERENCES `nationality` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `students_ibfk_3` FOREIGN KEY (`student_category_id`) REFERENCES `student_categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `students_ibfk_4` FOREIGN KEY (`immediate_contact_id`) REFERENCES `guardians` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `student_attentance`
--
ALTER TABLE `student_attentance`
  ADD CONSTRAINT `student_attentance_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `student_attentance_ibfk_2` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `student_document`
--
ALTER TABLE `student_document`
  ADD CONSTRAINT `student_document_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `student_electives`
--
ALTER TABLE `student_electives`
  ADD CONSTRAINT `student_electives_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `student_electives_ibfk_2` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `student_electives_ibfk_3` FOREIGN KEY (`elective_id`) REFERENCES `electives` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `student_electives_ibfk_4` FOREIGN KEY (`elective_group_id`) REFERENCES `elective_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `student_previous_datas`
--
ALTER TABLE `student_previous_datas`
  ADD CONSTRAINT `student_previous_datas_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `student_subjectwise_attentance`
--
ALTER TABLE `student_subjectwise_attentance`
  ADD CONSTRAINT `student_subjectwise_attentance_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `student_subjectwise_attentance_ibfk_2` FOREIGN KEY (`timetable_id`) REFERENCES `timetable_entries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `subjects`
--
ALTER TABLE `subjects`
  ADD CONSTRAINT `subjects_ibfk_1` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `subjects_common_pool`
--
ALTER TABLE `subjects_common_pool`
  ADD CONSTRAINT `subjects_common_pool_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `subject_commonpool_split`
--
ALTER TABLE `subject_commonpool_split`
  ADD CONSTRAINT `subject_commonpool_split_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `subjects_common_pool` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `teacher_subjectwise_attentance`
--
ALTER TABLE `teacher_subjectwise_attentance`
  ADD CONSTRAINT `teacher_subjectwise_attentance_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `teacher_subjectwise_attentance_ibfk_2` FOREIGN KEY (`timetable_id`) REFERENCES `timetable_entries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `terms`
--
ALTER TABLE `terms`
  ADD CONSTRAINT `terms_ibfk_1` FOREIGN KEY (`academic_yr_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `themes`
--
ALTER TABLE `themes`
  ADD CONSTRAINT `themes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `timetable_entries`
--
ALTER TABLE `timetable_entries`
  ADD CONSTRAINT `timetable_entries_ibfk_1` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `timetable_entries_ibfk_3` FOREIGN KEY (`class_timing_id`) REFERENCES `class_timings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `timetable_entries_ibfk_4` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transportation`
--
ALTER TABLE `transportation`
  ADD CONSTRAINT `transportation_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transportation_ibfk_2` FOREIGN KEY (`stop_id`) REFERENCES `stop_details` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_device`
--
ALTER TABLE `user_device`
  ADD CONSTRAINT `user_device_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_otp_details`
--
ALTER TABLE `user_otp_details`
  ADD CONSTRAINT `user_otp_details_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_settings`
--
ALTER TABLE `user_settings`
  ADD CONSTRAINT `user_settings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `vacate`
--
ALTER TABLE `vacate`
  ADD CONSTRAINT `vacate_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `vacate_ibfk_2` FOREIGN KEY (`allot_id`) REFERENCES `allotment` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `waitinglist_students`
--
ALTER TABLE `waitinglist_students`
  ADD CONSTRAINT `waitinglist_students_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `waitinglist_students_ibfk_2` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `weekdays`
--
ALTER TABLE `weekdays`
  ADD CONSTRAINT `weekdays_ibfk_1` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

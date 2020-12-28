-- phpMyAdmin SQL Dump
-- version 4.3.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 10, 2016 at 11:28 AM
-- Server version: 5.6.24
-- PHP Version: 5.6.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `prod_rz`
--

-- --------------------------------------------------------

--
-- Table structure for table `language`
--

CREATE TABLE IF NOT EXISTS `language` (
  `id` bigint(20) unsigned NOT NULL,
  `phrase` varchar(255) NOT NULL,
  `english` text,
  `french` text,
  `spanish` text,
  `chinese` text
) ENGINE=InnoDB AUTO_INCREMENT=164 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `language`
--

INSERT INTO `language` (`id`, `phrase`, `english`, `french`, `spanish`, `chinese`) VALUES
(1, 'supervisor_dashboard', 'SuperVisor Dashboard', 'french dashboard', NULL, NULL),
(2, 'tasks', 'Tasks', NULL, NULL, NULL),
(3, 'sos', 'SOS', NULL, NULL, NULL),
(4, 'users', 'Users', NULL, NULL, NULL),
(5, 'upload', 'Upload', NULL, NULL, NULL),
(6, 'profile', 'Profile', NULL, NULL, NULL),
(7, 'log_out', 'Log Out', NULL, NULL, NULL),
(8, 'sign_in', 'Sign In', NULL, NULL, NULL),
(9, 'sign_up', 'Sign Up', NULL, NULL, NULL),
(10, 'or', 'Or', NULL, NULL, NULL),
(11, 'supervisor_email', 'Supervisor Email', NULL, NULL, NULL),
(12, 'password', 'Password', NULL, NULL, NULL),
(13, 'submit', 'Submit', NULL, NULL, NULL),
(14, 'forgot_password?', 'Forgot Password?', NULL, NULL, NULL),
(15, 'confirm_password_mismatch', 'Confirm Password must same value as Password', NULL, NULL, NULL),
(16, 'system_title', 'Geoclarity Supervisor Dashboard', NULL, NULL, NULL),
(17, 'confirm_password', 'Confirm Password', NULL, NULL, NULL),
(18, 'supervisor_name', 'Supervisor Name', NULL, NULL, NULL),
(19, 'department', 'Department', NULL, NULL, NULL),
(20, 'teamsize', 'TeamSize', NULL, NULL, NULL),
(21, 'home_phone', 'Home Phone', NULL, NULL, NULL),
(22, 'office_phone', 'Office Phone', NULL, NULL, NULL),
(23, 'mobile_phone', 'Mobile Phone', NULL, NULL, NULL),
(24, 'supervisor_address', 'Supervisor Address', NULL, NULL, NULL),
(25, 'company_name', 'Company Name', NULL, NULL, NULL),
(27, 'forgot_password', 'Forgot Password', NULL, NULL, NULL),
(28, 'my_account', 'My Account', NULL, NULL, NULL),
(29, 'email', 'Email', NULL, NULL, NULL),
(30, 'save', 'Save', NULL, NULL, NULL),
(31, 'current_time', 'Current Time', NULL, NULL, NULL),
(32, 'searched_contacts', 'Searched_contacts', NULL, NULL, NULL),
(33, 'no', 'No', NULL, NULL, NULL),
(34, 'name', 'Name', NULL, NULL, NULL),
(35, 'phone', 'Phone', NULL, NULL, NULL),
(36, 'address', 'Address', NULL, NULL, NULL),
(37, 'city', 'City', NULL, NULL, NULL),
(38, 'sms', 'SMS', NULL, NULL, NULL),
(39, 'searched_tasks', 'Searched Tasks', NULL, NULL, NULL),
(40, 'task_category', 'Task Category', NULL, NULL, NULL),
(41, 'user', 'User', NULL, NULL, NULL),
(42, 'schedule_start', 'Schedule Start', NULL, NULL, NULL),
(43, 'schedule_end', 'Schedule End', NULL, NULL, NULL),
(44, 'contact_name', 'Contact Name', NULL, NULL, NULL),
(45, 'contact_address', 'Contact Address', NULL, NULL, NULL),
(46, 'contact_phone', 'Contact Phone', NULL, NULL, NULL),
(47, 'task_status', 'Task Status', NULL, NULL, NULL),
(48, 'notes', 'Notes', NULL, NULL, NULL),
(49, 'searched_users', 'Searched Users', NULL, NULL, NULL),
(50, 'user_name', 'User Name', NULL, NULL, NULL),
(51, 'user_role', 'User Role', NULL, NULL, NULL),
(52, 'vihicle_type', 'Vehicle Type', NULL, NULL, NULL),
(53, 'vihicle_reg', 'Vehicle Reg', NULL, NULL, NULL),
(54, 'send_sms_to_contact', 'Send SMS to Contact', NULL, NULL, NULL),
(55, 'sms_text', 'SMS Text', NULL, NULL, NULL),
(56, 'send', 'Send', NULL, NULL, NULL),
(57, 'close', 'Close', NULL, NULL, NULL),
(58, 'current_sos_tasks', 'Current SOS Tasks', NULL, NULL, NULL),
(59, 'sos_request_time', 'SOS Request Time', NULL, NULL, NULL),
(60, 'original_user', 'Original User', NULL, NULL, NULL),
(61, 'other_users_response', 'Other Users Response', NULL, NULL, NULL),
(62, 'past_sos_tasks', 'Past SOS Tasks', NULL, NULL, NULL),
(63, 'status', 'Status', NULL, NULL, NULL),
(64, 'assigned_user', 'Assigned User', NULL, NULL, NULL),
(65, 'all', 'All', NULL, NULL, NULL),
(66, 'delayed', 'Delayed', NULL, NULL, NULL),
(67, 'completed', 'Completed', NULL, NULL, NULL),
(68, 'cancelled', 'Cancelled', NULL, NULL, NULL),
(69, 'top', 'Top', NULL, NULL, NULL),
(70, 'installer_name', 'Installer Name', NULL, NULL, NULL),
(71, 'vehicle_registration_no', 'Vehicle Registration No', NULL, NULL, NULL),
(72, 'total_tasks', 'Total Tasks', NULL, NULL, NULL),
(73, 'total_completed_tasks', 'Total Completed Tasks', NULL, NULL, NULL),
(74, 'total_cancelled_tasks', 'Total Cancelled Tasks', NULL, NULL, NULL),
(75, 'total_delayed_tasks', 'Total Delayed Tasks', NULL, NULL, NULL),
(76, 'total_sos_tasks', 'Total SOS Tasks', NULL, NULL, NULL),
(77, 'search_user_by_name', 'Search User by Name', NULL, NULL, NULL),
(78, 'generate_tracks', 'Generate Tracks', NULL, NULL, NULL),
(79, 'search_tasks_for_any_region', 'Search Tasks for any Region', NULL, NULL, NULL),
(80, 'region', 'Region', NULL, NULL, NULL),
(81, 'post_code', 'Post Code', NULL, NULL, NULL),
(82, 'detail', 'Detail', NULL, NULL, NULL),
(83, 'task_add', 'Task Add', NULL, NULL, NULL),
(84, 'sos_status', 'SOS Status', NULL, NULL, NULL),
(85, 'yes', 'Yes', NULL, NULL, NULL),
(86, 'schedule_start_time', 'Schedule Start Time', NULL, NULL, NULL),
(87, 'schedule_end_time', 'Schedule End Time', NULL, NULL, NULL),
(88, 'country', 'Country', NULL, NULL, NULL),
(89, 'cancel', 'Cancel', NULL, NULL, NULL),
(90, 'all_tasks', 'All Tasks', NULL, NULL, NULL),
(91, 'add_task', 'Add Tasks', NULL, NULL, NULL),
(92, 'CREATED', 'CREATED', NULL, NULL, NULL),
(93, 'PENDSCHEDULE', 'PENDSCHEDULE', NULL, NULL, NULL),
(94, 'INPROGRESS', 'INPROGRESS', NULL, NULL, NULL),
(95, 'DELAYED', 'DELAYED', NULL, NULL, NULL),
(96, 'RESCHEDULED', 'RESCHEDULED', NULL, NULL, NULL),
(97, 'COMPLETED', 'COMPLETED', NULL, NULL, NULL),
(98, 'CANCELLED', 'CANCELLED', NULL, NULL, NULL),
(99, 'date', 'Date', NULL, NULL, NULL),
(100, 'today', 'Today', NULL, NULL, NULL),
(101, 'yesterday', 'Yesterday', NULL, NULL, NULL),
(102, '1_week', '1 Week', NULL, NULL, NULL),
(103, '1_month', '1 Month', NULL, NULL, NULL),
(104, 'edit', 'Edit', NULL, NULL, NULL),
(105, 'DELAYED', 'DELAYED', NULL, NULL, NULL),
(106, 'COMPLETED', 'COMPLETED', NULL, NULL, NULL),
(107, 'CANCELLED', 'CANCELLED', NULL, NULL, NULL),
(108, 'task_edit', 'Task Edit', NULL, NULL, NULL),
(109, 'INSTALL', 'INSTALL', NULL, NULL, NULL),
(110, 'DELIVERY', 'DELIVERY', NULL, NULL, NULL),
(111, 'REPAIR', 'REPAIR', NULL, NULL, NULL),
(112, 'COLLECT', 'COLLECT', NULL, NULL, NULL),
(113, 'CONSTRUCT', 'CONSTRUCT', NULL, NULL, NULL),
(114, 'SALES', 'SALES', NULL, NULL, NULL),
(115, 'DELAYED', 'DELAYED', NULL, NULL, NULL),
(116, 'COMPLETED', 'COMPLETED', NULL, NULL, NULL),
(117, 'CANCELLED', 'CANCELLED', NULL, NULL, NULL),
(118, 'DELAYED', 'DELAYED', NULL, NULL, NULL),
(119, 'COMPLETED', 'COMPLETED', NULL, NULL, NULL),
(120, 'CANCELLED', 'CANCELLED', NULL, NULL, NULL),
(121, 'upload_tasks', 'Upload Tasks', NULL, NULL, NULL),
(122, 'upload_users', 'Upload Users', NULL, NULL, NULL),
(123, 'add_a_new_user', 'Add a new User', NULL, NULL, NULL),
(124, 'user_email', 'User Email', NULL, NULL, NULL),
(125, 'INSTALLER', 'INSTALLER', NULL, NULL, NULL),
(126, 'EXPERT_TECH', 'EXPERT_TECH', NULL, NULL, NULL),
(127, 'DEVICE_COLLECTOR', 'DEVICE_COLLECTOR', NULL, NULL, NULL),
(128, 'MONEY_RECOLLECT', 'MONEY_RECOLLECT', NULL, NULL, NULL),
(129, 'vehicle_type', 'Vehicle Type', NULL, NULL, NULL),
(130, 'VAN', 'VAN', NULL, NULL, NULL),
(131, 'TRUCK', 'TRUCK', NULL, NULL, NULL),
(132, 'BIKE', 'BIKE', NULL, NULL, NULL),
(133, 'CAR', 'CAR', NULL, NULL, NULL),
(134, 'DRONE', 'DRONE', NULL, NULL, NULL),
(135, 'PLANE', 'PLANE', NULL, NULL, NULL),
(136, 'SHIP', 'SHIP', NULL, NULL, NULL),
(137, 'BOAT', 'BOAT', NULL, NULL, NULL),
(138, 'vehicle_reg', 'Vehicle Reg', NULL, NULL, NULL),
(139, 'company_dashboard', 'Company Dashboard', NULL, NULL, NULL),
(140, 'search', 'Search', NULL, NULL, NULL),
(141, 'supervisors', 'SuperVisors', NULL, NULL, NULL),
(142, 'today_tasks', 'Today Tasks', NULL, NULL, NULL),
(143, 'today_completed_tasks', 'Today Completed Tasks', NULL, NULL, NULL),
(144, 'today_cancelled_tasks', 'Today Cancelled Tasks', NULL, NULL, NULL),
(145, 'today_delayed_tasks', 'Today Delayed Tasks', NULL, NULL, NULL),
(146, 'today_sos_tasks', 'Today SOS Tasks', NULL, NULL, NULL),
(147, 'month_completed_rates', 'Month Completed Rates', NULL, NULL, NULL),
(148, 'month_cancelled_rates', 'Month Cancelled Rates', NULL, NULL, NULL),
(149, 'month_delayed_rates', 'Month Delayed Rate', NULL, NULL, NULL),
(150, 'search_supervisor_by_name', 'Search Supervisor by Name', NULL, NULL, NULL),
(151, 'show_supervisor_dashboard', 'Show Supervisor Dashboard', NULL, NULL, NULL),
(152, 'search_tasks_for_any_regin', 'Search Tasks for any Regin', NULL, NULL, NULL),
(153, 'timezone', 'Timezone', NULL, NULL, NULL),
(154, 'logo', 'Logo', NULL, NULL, NULL),
(155, 'upload_supervisors', 'Upload Supervisors', NULL, NULL, NULL),
(156, 'company_email', 'Company Email', NULL, NULL, NULL),
(157, 'company_phone', 'Company Phone', NULL, NULL, NULL),
(158, 'company_address', 'Company Address', NULL, NULL, NULL),
(159, 'estimated_number_of_field_users_to_use', 'Estimated Number of Field Users to Use', NULL, NULL, NULL),
(160, 'estimated_number_of_supervisors_to_use', 'Estimated Number of Supervisors to Use', NULL, NULL, NULL),
(161, 'send_sms', NULL, NULL, NULL, NULL),
(162, 'searched_supervisors', NULL, NULL, NULL, NULL),
(163, 'team_size', NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `language`
--
ALTER TABLE `language`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `language`
--
ALTER TABLE `language`
  MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=164;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

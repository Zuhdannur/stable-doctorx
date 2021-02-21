-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 07, 2020 at 03:12 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.3.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dokter_x`
--

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_03_03_211814_create_bouncer_tables', 2),
(4, '2019_03_05_111953_create_settings_table', 3),
(5, '2014_10_12_000000_create_notifications_table', 4),
(6, '2016_08_03_072729_create_provinces_table', 5),
(7, '2016_08_03_072750_create_cities_table', 6),
(8, '2016_08_03_072804_create_districts_table', 6),
(9, '2016_08_03_072819_create_villages_table', 6),
(10, '2020_05_07_195335_create_abilities_table', 0),
(11, '2020_05_07_195335_create_admission_types_table', 0),
(12, '2020_05_07_195335_create_appointment_invoices_table', 0),
(13, '2020_05_07_195335_create_appointment_status_table', 0),
(14, '2020_05_07_195335_create_appointments_table', 0),
(15, '2020_05_07_195335_create_appointments_copy_table', 0),
(16, '2020_05_07_195335_create_assigned_roles_table', 0),
(17, '2020_05_07_195335_create_attribute_blood_banks_table', 0),
(18, '2020_05_07_195335_create_attribute_departments_table', 0),
(19, '2020_05_07_195335_create_attribute_gender_table', 0),
(20, '2020_05_07_195335_create_attribute_info_media_table', 0),
(21, '2020_05_07_195335_create_attribute_info_sources_table', 0),
(22, '2020_05_07_195335_create_attribute_marital_status_table', 0),
(23, '2020_05_07_195335_create_attribute_religions_table', 0),
(24, '2020_05_07_195335_create_attribute_works_table', 0),
(25, '2020_05_07_195335_create_departments_table', 0),
(26, '2020_05_07_195335_create_diagnose_items_table', 0),
(27, '2020_05_07_195335_create_diagnoses_table', 0),
(28, '2020_05_07_195335_create_floors_table', 0),
(29, '2020_05_07_195335_create_incentive_details_table', 0),
(30, '2020_05_07_195335_create_incentive_staff_table', 0),
(31, '2020_05_07_195335_create_incentive_staff_details_table', 0),
(32, '2020_05_07_195335_create_incentives_table', 0),
(33, '2020_05_07_195335_create_indonesia_cities_old_table', 0),
(34, '2020_05_07_195335_create_indonesia_districts_old_table', 0),
(35, '2020_05_07_195335_create_indonesia_provinces_table', 0),
(36, '2020_05_07_195335_create_indonesia_provinces_old_table', 0),
(37, '2020_05_07_195335_create_indonesia_villages_old_table', 0),
(38, '2020_05_07_195335_create_invoice_details_table', 0),
(39, '2020_05_07_195335_create_invoice_service_details_table', 0),
(40, '2020_05_07_195335_create_invoices_table', 0),
(41, '2020_05_07_195335_create_password_histories_table', 0),
(42, '2020_05_07_195335_create_password_resets_table', 0),
(43, '2020_05_07_195335_create_patient_admissions_table', 0),
(44, '2020_05_07_195335_create_patient_before_afters_table', 0),
(45, '2020_05_07_195335_create_patient_flags_table', 0),
(46, '2020_05_07_195335_create_patient_media_info_table', 0),
(47, '2020_05_07_195335_create_patient_queues_table', 0),
(48, '2020_05_07_195335_create_patient_timeline_details_table', 0),
(49, '2020_05_07_195335_create_patient_timelines_table', 0),
(50, '2020_05_07_195335_create_patients_table', 0),
(51, '2020_05_07_195335_create_permissions_table', 0),
(52, '2020_05_07_195335_create_prescription_details_table', 0),
(53, '2020_05_07_195335_create_prescription_treatments_table', 0),
(54, '2020_05_07_195335_create_prescriptions_table', 0),
(55, '2020_05_07_195335_create_product_categories_table', 0),
(56, '2020_05_07_195335_create_product_package_details_table', 0),
(57, '2020_05_07_195335_create_product_packages_table', 0),
(58, '2020_05_07_195335_create_products_table', 0),
(59, '2020_05_07_195335_create_roles_table', 0),
(60, '2020_05_07_195335_create_room_groups_table', 0),
(61, '2020_05_07_195335_create_room_types_table', 0),
(62, '2020_05_07_195335_create_rooms_table', 0),
(63, '2020_05_07_195335_create_service_categories_table', 0),
(64, '2020_05_07_195335_create_services_table', 0),
(65, '2020_05_07_195335_create_social_accounts_table', 0),
(66, '2020_05_07_195335_create_staff_table', 0),
(67, '2020_05_07_195335_create_staff_designations_table', 0),
(68, '2020_05_07_195335_create_treatment_details_table', 0),
(69, '2020_05_07_195335_create_treatment_invoices_table', 0),
(70, '2020_05_07_195335_create_treatments_table', 0),
(71, '2020_05_07_195337_add_foreign_keys_to_admission_types_table', 0),
(72, '2020_05_07_195337_add_foreign_keys_to_appointment_invoices_table', 0),
(73, '2020_05_07_195337_add_foreign_keys_to_appointments_table', 0),
(74, '2020_05_07_195337_add_foreign_keys_to_appointments_copy_table', 0),
(75, '2020_05_07_195337_add_foreign_keys_to_assigned_roles_table', 0),
(76, '2020_05_07_195337_add_foreign_keys_to_diagnoses_table', 0),
(77, '2020_05_07_195337_add_foreign_keys_to_incentive_details_table', 0),
(78, '2020_05_07_195337_add_foreign_keys_to_incentive_staff_table', 0),
(79, '2020_05_07_195337_add_foreign_keys_to_incentive_staff_details_table', 0),
(80, '2020_05_07_195337_add_foreign_keys_to_indonesia_cities_old_table', 0),
(81, '2020_05_07_195337_add_foreign_keys_to_indonesia_districts_old_table', 0),
(82, '2020_05_07_195337_add_foreign_keys_to_indonesia_villages_old_table', 0),
(83, '2020_05_07_195337_add_foreign_keys_to_invoice_details_table', 0),
(84, '2020_05_07_195337_add_foreign_keys_to_invoice_service_details_table', 0),
(85, '2020_05_07_195337_add_foreign_keys_to_invoices_table', 0),
(86, '2020_05_07_195337_add_foreign_keys_to_password_histories_table', 0),
(87, '2020_05_07_195337_add_foreign_keys_to_patient_admissions_table', 0),
(88, '2020_05_07_195337_add_foreign_keys_to_patient_before_afters_table', 0),
(89, '2020_05_07_195337_add_foreign_keys_to_patient_media_info_table', 0),
(90, '2020_05_07_195337_add_foreign_keys_to_patient_queues_table', 0),
(91, '2020_05_07_195337_add_foreign_keys_to_patient_timeline_details_table', 0),
(92, '2020_05_07_195337_add_foreign_keys_to_patient_timelines_table', 0),
(93, '2020_05_07_195337_add_foreign_keys_to_patients_table', 0),
(94, '2020_05_07_195337_add_foreign_keys_to_permissions_table', 0),
(95, '2020_05_07_195337_add_foreign_keys_to_prescription_details_table', 0),
(96, '2020_05_07_195337_add_foreign_keys_to_prescription_treatments_table', 0),
(97, '2020_05_07_195337_add_foreign_keys_to_prescriptions_table', 0),
(98, '2020_05_07_195337_add_foreign_keys_to_product_categories_table', 0),
(99, '2020_05_07_195337_add_foreign_keys_to_products_table', 0),
(100, '2020_05_07_195337_add_foreign_keys_to_room_groups_table', 0),
(101, '2020_05_07_195337_add_foreign_keys_to_rooms_table', 0),
(102, '2020_05_07_195337_add_foreign_keys_to_services_table', 0),
(103, '2020_05_07_195337_add_foreign_keys_to_social_accounts_table', 0),
(104, '2020_05_07_195337_add_foreign_keys_to_staff_table', 0),
(105, '2020_05_07_195337_add_foreign_keys_to_treatment_details_table', 0),
(106, '2020_05_07_195337_add_foreign_keys_to_treatment_invoices_table', 0),
(107, '2020_05_07_195337_add_foreign_keys_to_treatments_table', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

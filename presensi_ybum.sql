/*
SQLyog Professional v12.5.1 (64 bit)
MySQL - 10.4.28-MariaDB : Database - presensi_ybum
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`presensi_ybum` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `presensi_ybum`;

/*Table structure for table `pegawai` */

DROP TABLE IF EXISTS `pegawai`;

CREATE TABLE `pegawai` (
  `nik` char(5) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `jabatan` varchar(20) NOT NULL,
  `no_hp` varchar(13) NOT NULL,
  `foto` varchar(30) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`nik`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `pegawai` */

insert  into `pegawai`(`nik`,`nama_lengkap`,`jabatan`,`no_hp`,`foto`,`password`,`remember_token`) values 
('123','Hudda','Guru','085799946964',NULL,'',NULL),
('12345','Muhammad nur Hudda','satpam','085799946966','12345.jpg','$2y$10$xbRGmKoHRXFxPriVcgLHx.AzblVxkVSseyqfu2yb4Lw/8/ZZzc/Cq',NULL);

/*Table structure for table `pengajuan_izin` */

DROP TABLE IF EXISTS `pengajuan_izin`;

CREATE TABLE `pengajuan_izin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nik` char(5) NOT NULL,
  `tanggal_izin` date NOT NULL,
  `status` char(1) NOT NULL COMMENT 'i:izin s:sakit',
  `keterangan` varchar(255) NOT NULL,
  `status_approved` char(1) DEFAULT '0' COMMENT '0:pending 1:Disetujui 2:ditolak',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `pengajuan_izin` */

insert  into `pengajuan_izin`(`id`,`nik`,`tanggal_izin`,`status`,`keterangan`,`status_approved`) values 
(3,'12345','2023-12-12','i','qwerty','0'),
(4,'12345','2023-12-13','s','sakit hati ditinggal pacar','1'),
(5,'12345','2023-12-13','s','sakit hati ditinggal pacar','2');

/*Table structure for table `presensi` */

DROP TABLE IF EXISTS `presensi`;

CREATE TABLE `presensi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nik` char(5) NOT NULL,
  `tanggal_presensi` date NOT NULL,
  `jam_in` time NOT NULL,
  `jam_out` time DEFAULT NULL,
  `foto_in` varchar(255) NOT NULL,
  `foto_out` varchar(255) DEFAULT NULL,
  `lokasi_in` text NOT NULL,
  `lokasi_out` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `presensi` */

insert  into `presensi`(`id`,`nik`,`tanggal_presensi`,`jam_in`,`jam_out`,`foto_in`,`foto_out`,`lokasi_in`,`lokasi_out`) values 
(48,'12345','2023-12-11','22:40:01','22:40:31','12345-2023-12-11-in.png','12345-2023-12-11-out.png','-7.7483753,110.3592382','-7.7483753,110.3592382');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `users` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

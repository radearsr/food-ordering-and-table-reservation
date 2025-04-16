/*
SQLyog Community v12.4.0 (64 bit)
MySQL - 10.1.38-MariaDB : Database - mfors
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`mfors` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `mfors`;

/*Table structure for table `admin` */

DROP TABLE IF EXISTS `admin`;

CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `admin` */

insert  into `admin`(`id`,`username`,`password`) values 
(1,'admin','202cb962ac59075b964b07152d234b70');

/*Table structure for table `basket` */

DROP TABLE IF EXISTS `basket`;

CREATE TABLE `basket` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(255) NOT NULL,
  `contact_number` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `total` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `date_made` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

/*Data for the table `basket` */

insert  into `basket`(`id`,`customer_name`,`contact_number`,`address`,`email`,`total`,`status`,`date_made`) values 
(13,'Wada','08065463632','Wadagailcom','gg@gmail.com','700','confirmed','2016-12-31 15:50:21'),
(14,'tes','077222','tes','tes@mail.com','350','confirmed','2025-04-16 09:56:13'),
(15,'SUTRIMO','081391509467','Jl Dukuh Teseh Makmur 2 RT 001 RW 004','mimow.aja@gmail.com','50','pending','2025-04-16 10:11:42'),
(16,'SUTRIMO','081391509467','Jl Dukuh Teseh Makmur 2 RT 001 RW 004','mimow.aja@gmail.com','100','pending','2025-04-16 10:12:25'),
(17,'SUTRIMO','081391509467','Jl Dukuh Teseh Makmur 2 RT 001 RW 004','mimow.aja@gmail.com','50','confirmed','2025-04-16 10:42:51');

/*Table structure for table `contact` */

DROP TABLE IF EXISTS `contact`;

CREATE TABLE `contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `contact` */

insert  into `contact`(`id`,`customer_name`,`subject`,`email`,`message`) values 
(1,'Adam Abdulrahman','Late Delivery','abdulflezy13@yahoo.com','Please ensure that your delivery guys deliver the meals at the required time because they are often late.'),
(2,'Zainab Adamu','Late Delivery','Zee@yahoo.com','I need an email of the GM if possible');

/*Table structure for table `food` */

DROP TABLE IF EXISTS `food`;

CREATE TABLE `food` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `food_name` varchar(255) NOT NULL,
  `food_category` varchar(255) NOT NULL,
  `food_price` varchar(255) NOT NULL,
  `food_description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

/*Data for the table `food` */

insert  into `food`(`id`,`food_name`,`food_category`,`food_price`,`food_description`) values 
(1,'spicyburger','lunch','50.00','Vestibulum tortor quam feugiat vitae ultricies eget tempor sit amet ante Donec eu libero sit amet quam egestas semper Aenean ultricies mi vitae est Mauris placerat eleifend leo Quisque sit amet est et sapien ullamcorper pharetra Vestibulum erat wisi condimentum sed commodo vitae'),
(2,'snailchoc','breakfast','50.00','Vestibulum tortor quam feugiat vitae ultricies eget tempor sit amet ante Donec eu libero sit amet quam egestas semper Aenean ultricies mi vitae est Mauris placerat eleifend leo Quisque sit amet est et sapien ullamcorper pharetra Vestibulum erat wisi condimentum sed commodo vitae'),
(3,'salad','lunch','50.00','Vestibulum tortor quam feugiat vitae ultricies eget tempor sit amet ante Donec eu libero sit amet quam egestas semper Aenean ultricies mi vitae est Mauris placerat eleifend leo Quisque sit amet est et sapien ullamcorper pharetra Vestibulum erat wisi condimentum sed commodo vitae'),
(4,'pizza','lunch','350.00','Vestibulum tortor quam feugiat vitae ultricies eget tempor sit amet ante Donec eu libero sit amet quam egestas semper Aenean ultricies mi vitae est Mauris placerat eleifend leo Quisque sit amet est et sapien ullamcorper pharetra Vestibulum erat wisi condimentum sed commodo vitae'),
(5,'shawarma','breakfast','350.00','Vestibulum tortor quam feugiat vitae ultricies eget tempor sit amet ante Donec eu libero sit amet quam egestas semper Aenean ultricies mi vitae est Mauris placerat eleifend leo Quisque sit amet est et sapien ullamcorper pharetra Vestibulum erat wisi condimentum sed commodo vitae'),
(6,'Rice','lunch','50.00','This is a tasty meal i bet you dont want miss enjoying the yummy taste'),
(7,'Jellyfish','dinner','400','Try this delicay and i promise you will keep coming back for more'),
(8,'Ice Cream','special','4000','desc'),
(9,'Pounded Yam','dinner','800','This is one of our best meal and it is prepared deliciously for you'),
(10,'Eba and Vegetable','dinner','600','This is a very nice combination'),
(11,'Somovita and Egusi soup','breakfast','800','Semovita is one of the delicacies you definitely want to try for breakfast');

/*Table structure for table `items` */

DROP TABLE IF EXISTS `items`;

CREATE TABLE `items` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` varchar(100) NOT NULL,
  `food` varchar(100) NOT NULL,
  `qty` varchar(100) NOT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Data for the table `items` */

insert  into `items`(`item_id`,`order_id`,`food`,`qty`) values 
(1,'13','pizza','2'),
(2,'14','shawarma','1'),
(3,'15','spicyburger','1'),
(4,'16','spicyburger','2'),
(5,'17','spicyburger','1');

/*Table structure for table `reservation` */

DROP TABLE IF EXISTS `reservation`;

CREATE TABLE `reservation` (
  `reserve_id` int(11) NOT NULL AUTO_INCREMENT,
  `no_of_guest` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `date_res` varchar(100) NOT NULL,
  `time` varchar(100) NOT NULL,
  `suggestions` varchar(100) NOT NULL,
  `table_id` int(11) DEFAULT NULL,
  `reservation_code` varchar(50) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_status` enum('pending','paid','failed') NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`reserve_id`),
  KEY `fk_reservation_table` (`table_id`),
  CONSTRAINT `fk_reservation_table` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

/*Data for the table `reservation` */

insert  into `reservation`(`reserve_id`,`no_of_guest`,`email`,`phone`,`date_res`,`time`,`suggestions`,`table_id`,`reservation_code`,`payment_method`,`payment_status`) values 
(1,'2','abdulflezy13@yahoo.com','09087676543','2016-12-14','15:00','suggestions suggestions suggestions',NULL,NULL,NULL,'pending'),
(2,'2','abdulflezy13@ymail.com','09087676546','2016-12-30','18:00','suggestions suggestions suggestions',NULL,NULL,NULL,'pending'),
(3,'10','admin@unitedtronik.co.id','62895366019094','2025-04-16','12:10','lorem\r\n',NULL,NULL,NULL,'pending'),
(4,'1231','123@gmail.com','123','2025-04-10','05:00','Pengen makan',NULL,NULL,NULL,'pending'),
(5,'1','mimow.aja@gmail.com','6287247039428','2025-04-16','10:42','asdasd',NULL,NULL,NULL,'pending'),
(6,'12','admin@admin.com','077222','2025-04-16','11:59','asdadasdasdassdasda',2,NULL,'transfer','pending'),
(7,'3','mimow.aja@gmail.com','081391509467','2025-04-18','16:18','oke',1,NULL,'kasir','pending'),
(8,'121','admin@admin.com','0895366019094','2025-04-16','18:04','asdadasdasd',1,NULL,'transfer','pending'),
(9,'8','saniatulkhuluq@gmail.com','0123456789','2025-04-25','10:30','',6,NULL,'kasir','pending');

/*Table structure for table `reservation_foods` */

DROP TABLE IF EXISTS `reservation_foods`;

CREATE TABLE `reservation_foods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reservation_id` int(11) NOT NULL,
  `food_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

/*Data for the table `reservation_foods` */

insert  into `reservation_foods`(`id`,`reservation_id`,`food_id`,`quantity`,`created_at`) values 
(1,6,1,1,'2025-04-16 11:58:45'),
(2,6,2,2,'2025-04-16 11:58:45'),
(3,6,11,1,'2025-04-16 11:58:45'),
(4,7,2,2,'2025-04-16 16:18:37'),
(5,7,1,1,'2025-04-16 16:18:37'),
(6,7,10,1,'2025-04-16 16:18:37'),
(7,8,2,1,'2025-04-16 18:04:35'),
(8,8,1,1,'2025-04-16 18:04:35'),
(9,8,9,1,'2025-04-16 18:04:35'),
(10,9,1,2,'2025-04-16 20:06:02'),
(11,9,3,2,'2025-04-16 20:06:02'),
(12,9,7,2,'2025-04-16 20:06:02'),
(13,9,8,2,'2025-04-16 20:06:02');

/*Table structure for table `tables` */

DROP TABLE IF EXISTS `tables`;

CREATE TABLE `tables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `table_number` varchar(10) NOT NULL,
  `capacity` int(11) NOT NULL,
  `location` varchar(255) NOT NULL,
  `status` enum('available','reserved','occupied','maintenance') NOT NULL DEFAULT 'available',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

/*Data for the table `tables` */

insert  into `tables`(`id`,`table_number`,`capacity`,`location`,`status`) values 
(1,'T01',2,'Window Side','available'),
(2,'T02',2,'Window Side','available'),
(3,'T03',4,'Center','available'),
(4,'T04',4,'Center','available'),
(5,'T05',6,'Corner','available'),
(6,'T06',8,'Private Room','available'),
(7,'T07',4,'Balcony','available'),
(8,'T08',4,'Garden','available');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

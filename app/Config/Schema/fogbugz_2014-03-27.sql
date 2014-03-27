# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: localhost (MySQL 5.6.14)
# Database: fogbugz
# Generation Time: 2014-03-27 05:25:34 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fogbugz_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL DEFAULT '',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`id`, `fogbugz_id`, `name`, `email`, `created`, `modified`)
VALUES
	(1,38,'Adam Wallace','awallace@signup4.com','2014-03-27 01:17:40','2014-03-27 01:17:40'),
	(2,41,'Anne Kosmicki','akosmicki@signup4.com','2014-03-27 01:17:47','2014-03-27 01:17:47'),
	(3,29,'Brandon Schust','bschust@signup4.com','2014-03-27 01:17:54','2014-03-27 01:17:54'),
	(4,73,'Brian Schust','brischust@signup4.com','2014-03-27 01:18:07','2014-03-27 01:18:07'),
	(5,74,'Charles','cgray@SignUp4.com','2014-03-27 01:18:13','2014-03-27 01:18:13'),
	(6,56,'Chase Brock','cbrock@signup4.com','2014-03-27 01:18:22','2014-03-27 01:18:22'),
	(7,82,'Chris Albright','calbright@signup4.com','2014-03-27 01:18:29','2014-03-27 01:18:29'),
	(8,39,'Christian Banks','cbanks@signup4.com','2014-03-27 01:18:44','2014-03-27 01:18:44'),
	(9,3,'Doug Wetzel','dwetzel@signup4.com','2014-03-27 01:18:56','2014-03-27 01:18:56'),
	(10,46,'Erika Groszhart','egroszhart@signup4.com','2014-03-27 01:19:04','2014-03-27 01:19:04'),
	(11,57,'James Eldridge','jeldridge@signup4.com','2014-03-27 01:19:50','2014-03-27 01:19:50'),
	(12,27,'Jason Lunt','jlunt@Signup4.com','2014-03-27 01:19:58','2014-03-27 01:19:58'),
	(13,61,'Jeff (Huey) Huelsbeck','jhuelsbeck@signup4.com','2014-03-27 01:20:08','2014-03-27 01:20:08'),
	(14,33,'Jorge Diaz','jdiaz@signup4.com','2014-03-27 01:20:19','2014-03-27 01:20:19'),
	(15,34,'Justin McCall','jmccall@signup4.com','2014-03-27 01:20:28','2014-03-27 01:20:28'),
	(16,35,'Kiley Reynolds','kreynolds@signup4.com','2014-03-27 01:20:42','2014-03-27 01:20:42'),
	(17,12,'Marvell Thompson','mthompson@signup4.com','2014-03-27 01:21:07','2014-03-27 01:21:07'),
	(18,76,'Rob Miller','rmiller@signup4.com','2014-03-27 01:21:26','2014-03-27 01:21:26'),
	(19,26,'Robert Carpenter','rcarpenter@signup4.com','2014-03-27 01:21:34','2014-03-27 01:21:34'),
	(20,18,'Thai Dang','tdang@signup4.com','2014-03-27 01:21:53','2014-03-27 01:21:53'),
	(21,79,'Thomas Wonneberger','twonneberger@SignUp4.com','2014-03-27 01:22:03','2014-03-27 01:22:03');

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

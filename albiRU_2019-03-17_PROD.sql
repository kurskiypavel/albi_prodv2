# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 178.128.238.166 (MySQL 5.7.25-0ubuntu0.16.04.2)
# Database: albiRU
# Generation Time: 2019-03-18 02:14:11 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table events
# ------------------------------------------------------------

DROP TABLE IF EXISTS `events`;

CREATE TABLE `events` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `group_event_id` varchar(250) DEFAULT NULL,
  `program` varchar(11) DEFAULT NULL,
  `student` varchar(11) DEFAULT NULL,
  `instructor` varchar(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `comment` varchar(2000) DEFAULT NULL,
  `private` int(11) DEFAULT '0',
  `confirmed` int(11) DEFAULT '0',
  `repeatble` varchar(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;

INSERT INTO `events` (`id`, `group_event_id`, `program`, `student`, `instructor`, `date`, `time`, `comment`, `private`, `confirmed`, `repeatble`)
VALUES
	(4,'3','3','1','1',NULL,NULL,'',0,NULL,'1'),
	(5,'1','1','1','1',NULL,NULL,'',0,NULL,'1'),
	(6,'5','5','1','1',NULL,NULL,'',0,NULL,'1'),
	(10,'3','3','19','1',NULL,NULL,'Мармышка',0,NULL,'1'),
	(11,'4','4','19','1',NULL,NULL,'Сама такая',0,1,'1'),
	(12,'1','1','19','1',NULL,NULL,'Запишите меня, я -бревно, но через 2 занятия хочу сесть в шпагат',0,NULL,'1'),
	(19,'2','2','19','1',NULL,NULL,'Запись 25.12',0,NULL,'1'),
	(20,NULL,NULL,'37','1','2019-01-08','00:17:00','',1,NULL,'1'),
	(22,'1','1','42','1',NULL,NULL,'',0,NULL,'1'),
	(23,NULL,NULL,'42','1','2019-02-13','22:12:00','Пееее',1,NULL,'1');

/*!40000 ALTER TABLE `events` ENABLE KEYS */;
UNLOCK TABLES;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `new_booking` AFTER INSERT ON `events` FOR EACH ROW BEGIN

IF (NEW.program IS NULL) THEN
		INSERT INTO `notifications-booking` (date,time,owner,event_id,repeatble,confirmed) 
    select date, time, student, id,repeatble,confirmed
from events where id = NEW.id;
      ELSE
			INSERT INTO `notifications-booking` (owner,program,schedule,program_id,event_id,confirmed) 
			select events.student, programs.title, programs.schedule, events.program, events.id, events.confirmed
from events join programs on programs.id = events.program where events.id=NEW.id;
      END IF;
      
  END */;;
/*!50003 SET SESSION SQL_MODE="ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `private_update` BEFORE UPDATE ON `events` FOR EACH ROW BEGIN

/*Update - Private*/
IF (NEW.program IS NULL AND NEW.confirmed IS NULL) THEN
	INSERT INTO `notifications-booking` (date,time,old_date,old_time,owner,event_id,repeatble,`change`) 
    select NEW.date, NEW.time,OLD.date,OLD.time, student, id,repeatble,'1'
	from events where id = NEW.id;

END IF;	
END */;;
/*!50003 SET SESSION SQL_MODE="ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `update_booking` AFTER UPDATE ON `events` FOR EACH ROW BEGIN

/*Confirmation Private*/
IF (NEW.program IS NULL AND NEW.confirmed AND NEW.confirmed = '1') THEN
	
	INSERT INTO `notifications-booking` (date,time,owner,event_id,repeatble,confirmed) 
    select date, time, student, id,repeatble,1
	from events where id = NEW.id;
	
	/*for admin confirmation trigger in notif-booking table*/
	

	
/*Update - Group*/
ELSEIF (NEW.program IS NOT NULL AND NEW.confirmed IS NULL) THEN
	
	INSERT INTO `notifications-booking_admin` (user_name,user_phone,owner,program, program_id, schedule,comment,event_id) 
    select users.first_name,users.phone,events.student,programs.title, events.program,  programs.schedule,events.comment, 	events.id
	from events,programs,users where events.id=NEW.id and programs.id=NEW.program and users.id=NEW.student;

END IF;
      
      
END */;;
/*!50003 SET SESSION SQL_MODE="NO_ENGINE_SUBSTITUTION" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `cancel` BEFORE DELETE ON `events` FOR EACH ROW BEGIN


	/*UPDATE `notifications-booking` SET `canceled` = '1'
where `event_id`=OLD.id;
		UPDATE `notifications-booking_admin` SET `canceled` = '1'
where `event_id`=OLD.id;*/

	
	
	IF (OLD.program IS NULL) THEN
		INSERT INTO `notifications-booking` (date,time,owner,event_id,repeatble,canceled) 
    select date, time, student, id,repeatble,'1'
from events where id = OLD.id;
      ELSE
			INSERT INTO `notifications-booking` (owner,program,schedule,program_id,event_id,canceled) 
	select events.student, programs.title, programs.schedule, events.program, events.id, '1'
	from events join programs on programs.id = events.program where events.id=OLD.id;
      END IF;
      
END */;;
DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE */;


# Dump of table facebook
# ------------------------------------------------------------

DROP TABLE IF EXISTS `facebook`;

CREATE TABLE `facebook` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(40) DEFAULT NULL,
  `name` varchar(40) DEFAULT NULL,
  `fb_id` varchar(40) DEFAULT NULL,
  `registered_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `facebook` WRITE;
/*!40000 ALTER TABLE `facebook` DISABLE KEYS */;

INSERT INTO `facebook` (`id`, `email`, `name`, `fb_id`, `registered_at`)
VALUES
	(1,'response.email','response.id','response.id','2018-08-30 13:55:35'),
	(2,'response.email','response.id','response.id','2018-08-30 13:56:30');

/*!40000 ALTER TABLE `facebook` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table favorite-programs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `favorite-programs`;

CREATE TABLE `favorite-programs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user` varchar(11) DEFAULT NULL,
  `program` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `favorite-programs` WRITE;
/*!40000 ALTER TABLE `favorite-programs` DISABLE KEYS */;

INSERT INTO `favorite-programs` (`id`, `user`, `program`)
VALUES
	(16,'2','4'),
	(17,'3','1'),
	(18,'3','3'),
	(23,'7','1'),
	(24,'10','2'),
	(29,'10','1'),
	(30,'13','1'),
	(31,'13','2'),
	(32,'13','3'),
	(33,'13','4'),
	(34,'10','4'),
	(35,'14','2'),
	(36,'14','1'),
	(37,'14','3'),
	(38,'14','4'),
	(39,'14','7'),
	(40,'15','1'),
	(41,'15','3'),
	(42,'15','7'),
	(43,'15','2'),
	(48,'11','1'),
	(49,'11','3'),
	(50,'11','7'),
	(51,'1','2'),
	(52,'1','3'),
	(53,'1','1'),
	(54,'1','4'),
	(55,'19','3'),
	(56,'19','1'),
	(57,'34','1'),
	(58,'18','3'),
	(59,'42','1');

/*!40000 ALTER TABLE `favorite-programs` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table messages
# ------------------------------------------------------------

DROP TABLE IF EXISTS `messages`;

CREATE TABLE `messages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `conversation` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `author` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` mediumtext COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table notifications-booking
# ------------------------------------------------------------

DROP TABLE IF EXISTS `notifications-booking`;

CREATE TABLE `notifications-booking` (
  `readed` varchar(6) DEFAULT NULL,
  `program` varchar(40) DEFAULT NULL,
  `schedule` varchar(40) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `old_date` date DEFAULT NULL,
  `old_time` time DEFAULT NULL,
  `repeatble` varchar(10) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `owner` varchar(100) DEFAULT NULL,
  `program_id` varchar(11) DEFAULT NULL,
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` int(11) DEFAULT NULL,
  `canceled` varchar(10) DEFAULT NULL,
  `confirmed` varchar(11) DEFAULT NULL,
  `change` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `notifications-booking` WRITE;
/*!40000 ALTER TABLE `notifications-booking` DISABLE KEYS */;

INSERT INTO `notifications-booking` (`readed`, `program`, `schedule`, `date`, `time`, `old_date`, `old_time`, `repeatble`, `created_at`, `owner`, `program_id`, `id`, `event_id`, `canceled`, `confirmed`, `change`)
VALUES
	(NULL,'Бодрое утро','субботам с 10.30 до 12.00',NULL,NULL,NULL,NULL,NULL,'2018-12-22 10:18:38','1','1',6,3,NULL,NULL,NULL),
	(NULL,'Бодрое утро','субботам с 10.30 до 12.00',NULL,NULL,NULL,NULL,NULL,'2018-12-22 10:21:43','1','1',7,3,'1',NULL,NULL),
	(NULL,'Сукшма-вьяяма','записи',NULL,NULL,NULL,NULL,NULL,'2018-12-22 10:26:31','1','3',8,4,NULL,NULL,NULL),
	(NULL,'Бодрое утро','субботам с 10.30 до 12.00',NULL,NULL,NULL,NULL,NULL,'2018-12-22 10:26:45','1','1',9,5,NULL,NULL,NULL),
	(NULL,'Антистресс и спокойствие без лекарств','четвергам с 19.30 до 21.00',NULL,NULL,NULL,NULL,NULL,'2018-12-22 10:36:08','1','5',10,6,NULL,NULL,NULL),
	('1','Сукшма-вьяяма','записи',NULL,NULL,NULL,NULL,NULL,'2018-12-22 16:46:11','18','3',13,8,NULL,NULL,NULL),
	('1','Йога для бегунов','записи',NULL,NULL,NULL,NULL,NULL,'2018-12-22 17:50:31','19','2',14,9,NULL,NULL,NULL),
	('1','Сукшма-вьяяма','записи',NULL,NULL,NULL,NULL,NULL,'2018-12-22 17:50:48','19','3',15,10,NULL,NULL,NULL),
	('1','Power yoga','записи',NULL,NULL,NULL,NULL,NULL,'2018-12-22 17:51:04','19','4',16,11,NULL,NULL,NULL),
	('1','Бодрое утро','субботам с 10.30 до 12.00',NULL,NULL,NULL,NULL,NULL,'2018-12-22 17:56:20','19','1',17,12,NULL,NULL,NULL),
	('1','Йога для бегунов','записи',NULL,NULL,NULL,NULL,NULL,'2018-12-22 17:56:45','19','2',18,9,'1',NULL,NULL),
	('1',NULL,NULL,'2019-12-12',NULL,NULL,NULL,'0','2018-12-22 18:56:59','18',NULL,19,13,NULL,'0',NULL),
	('1',NULL,NULL,'2019-12-12','21:00:00',NULL,NULL,'0','2018-12-22 19:07:53','18',NULL,20,13,NULL,'1',NULL),
	('1','Бодрое утро','субботам с 10.30 до 12.00',NULL,NULL,NULL,NULL,NULL,'2018-12-22 21:01:09','18','1',21,14,NULL,NULL,NULL),
	('1',NULL,NULL,'2019-12-12','23:02:00','2019-12-12','21:00:00','0','2018-12-22 22:11:04','18',NULL,22,13,NULL,NULL,'1'),
	('1','Бодрое утро','субботам с 10.30 до 12.00',NULL,NULL,NULL,NULL,NULL,'2018-12-22 22:12:43','18','1',23,14,'1',NULL,NULL),
	('1','Бодрое утро','субботам с 10.30 до 12.00',NULL,NULL,NULL,NULL,NULL,'2018-12-22 22:15:21','18','1',24,15,NULL,NULL,NULL),
	('1',NULL,NULL,'2018-12-22','17:15:00',NULL,NULL,'','2018-12-22 22:15:30','18',NULL,25,16,NULL,NULL,NULL),
	('1','Сукшма-вьяяма','записи',NULL,NULL,NULL,NULL,NULL,'2018-12-24 02:04:35','18','3',26,8,'1',NULL,NULL),
	('1','Бодрое утро','субботам с 10.30 до 12.00',NULL,NULL,NULL,NULL,NULL,'2018-12-24 02:04:39','18','1',27,15,'1',NULL,NULL),
	('1',NULL,NULL,'2019-12-12','23:02:00',NULL,NULL,'','2018-12-24 02:04:43','18',NULL,28,13,'1',NULL,NULL),
	('1',NULL,NULL,'2018-12-22','17:15:00',NULL,NULL,'','2018-12-24 02:04:47','18',NULL,29,16,'1',NULL,NULL),
	('1','Бодрое утро','субботам с 10.30 до 12.00',NULL,NULL,NULL,NULL,NULL,'2018-12-24 19:00:07','18','1',30,17,NULL,NULL,NULL),
	('1','Бодрое утро','субботам с 10.30 до 12.00',NULL,NULL,NULL,NULL,NULL,'2018-12-24 19:00:49','18','1',31,17,'1',NULL,NULL),
	('1','Бодрое утро','субботам с 10.30 до 12.00',NULL,NULL,NULL,NULL,NULL,'2018-12-24 19:01:05','18','1',32,18,NULL,NULL,NULL),
	(NULL,'Йога для бегунов','записи',NULL,NULL,NULL,NULL,NULL,'2018-12-25 14:11:28','19','2',33,19,NULL,NULL,NULL),
	(NULL,'Бодрое утро','субботам с 10.30 до 12.00',NULL,NULL,NULL,NULL,NULL,'2019-01-02 22:02:09','18','1',34,18,'1',NULL,NULL),
	('1',NULL,NULL,'2019-01-08','12:17:00',NULL,NULL,'1','2019-01-08 17:17:53','37',NULL,35,20,NULL,NULL,NULL),
	('1',NULL,NULL,'2019-01-08','00:17:00','2019-01-08','12:17:00','1','2019-02-02 01:35:45','37',NULL,36,20,NULL,NULL,'1'),
	('1','Йога для бегунов','записи',NULL,NULL,NULL,NULL,NULL,'2019-02-02 01:37:40','37','2',37,21,NULL,NULL,NULL),
	('1','Йога для бегунов','записи',NULL,NULL,NULL,NULL,NULL,'2019-02-02 01:37:57','37','2',38,21,'1',NULL,NULL),
	('1','Бодрое утро','субботам с 10.30 до 12.00',NULL,NULL,NULL,NULL,NULL,'2019-02-11 14:45:14','42','1',39,22,NULL,NULL,NULL),
	('1',NULL,NULL,'2019-02-13','22:12:00',NULL,NULL,'1','2019-02-13 19:12:20','42',NULL,40,23,NULL,NULL,NULL);

/*!40000 ALTER TABLE `notifications-booking` ENABLE KEYS */;
UNLOCK TABLES;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_ENGINE_SUBSTITUTION" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `new_booking_admin` AFTER INSERT ON `notifications-booking` FOR EACH ROW BEGIN

/*Cancel*/
IF (NEW.canceled ='1' AND NEW.program IS NULL) THEN

	INSERT INTO `notifications-booking_admin` (date,time,user_name,user_phone,event_id,canceled) 
    select events.date, events.time, users.`first_name`,users.`phone`,events.id,1
	from events,users where events.id=NEW.event_id and users.id=NEW.owner;

ELSEIF (NEW.canceled ='1' AND NEW.program IS NOT NULL) THEN

	INSERT INTO `notifications-booking_admin` (user_name,user_phone,program, program_id, schedule,event_id,canceled) 
    select users.first_name,users.phone,programs.title, events.program,  programs.schedule, events.id,1
	from events,programs,users where events.id=NEW.event_id and programs.id=NEW.program_id and users.id=NEW.owner;

/*Update + New - Private*/
ELSEIF (NEW.program IS NULL AND NEW.confirmed IS NULL) THEN
	INSERT INTO `notifications-booking_admin` (date,time,old_date,old_time,comment,user_name,user_phone,event_id,repeatble,`change`) 	
	select NEW.date,NEW.time,NEW.`old_date`,NEW.`old_time`,events.comment, users.`first_name`,users.`phone`, events.id,events.repeatble,NEW.change
	from events join users on users.id = events.student where events.id=NEW.event_id;

/*Update + New - Group*/
ELSEIF (NEW.program IS NOT NULL AND NEW.confirmed IS NULL) THEN
	INSERT INTO `notifications-booking_admin` (user_name,user_phone,program, program_id, schedule,comment,event_id,`change`) 
    select users.first_name,users.phone,programs.title, events.program,  programs.schedule,events.comment,events.id,NEW.change
	from events,programs,users where events.id=NEW.event_id and programs.id=NEW.program_id and users.id=NEW.owner;
END IF;
      
END */;;
DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE */;


# Dump of table notifications-booking_admin
# ------------------------------------------------------------

DROP TABLE IF EXISTS `notifications-booking_admin`;

CREATE TABLE `notifications-booking_admin` (
  `program` varchar(40) DEFAULT NULL,
  `schedule` varchar(40) DEFAULT NULL,
  `user_name` varchar(100) DEFAULT NULL,
  `user_phone` varchar(40) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `old_date` date DEFAULT NULL,
  `old_time` time DEFAULT NULL,
  `comment` varchar(500) DEFAULT NULL,
  `repeatble` varchar(10) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `program_id` varchar(11) DEFAULT NULL,
  `event_id` int(11) DEFAULT NULL,
  `canceled` varchar(11) DEFAULT NULL,
  `change` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `notifications-booking_admin` WRITE;
/*!40000 ALTER TABLE `notifications-booking_admin` DISABLE KEYS */;

INSERT INTO `notifications-booking_admin` (`program`, `schedule`, `user_name`, `user_phone`, `date`, `time`, `old_date`, `old_time`, `comment`, `repeatble`, `created_at`, `id`, `program_id`, `event_id`, `canceled`, `change`)
VALUES
	('Бодрое утро','субботам с 10.30 до 12.00','Альбина','(963) 686-1278',NULL,NULL,NULL,NULL,'Добавляю комент',NULL,'2018-12-22 10:18:38',6,'1',3,NULL,NULL),
	('Бодрое утро','субботам с 10.30 до 12.00','Альбина','(963) 686-1278',NULL,NULL,NULL,NULL,NULL,NULL,'2018-12-22 10:21:43',7,'1',3,'1',NULL),
	('Сукшма-вьяяма','записи','Альбина','(963) 686-1278',NULL,NULL,NULL,NULL,'',NULL,'2018-12-22 10:26:31',8,'3',4,NULL,NULL),
	('Бодрое утро','субботам с 10.30 до 12.00','Альбина','(963) 686-1278',NULL,NULL,NULL,NULL,'',NULL,'2018-12-22 10:26:45',9,'1',5,NULL,NULL),
	('Антистресс и спокойствие без лекарств','четвергам с 19.30 до 21.00','Альбина','(963) 686-1278',NULL,NULL,NULL,NULL,'',NULL,'2018-12-22 10:36:08',10,'5',6,NULL,NULL),
	('Сукшма-вьяяма','записи','poe','(289) 830-1724',NULL,NULL,NULL,NULL,'фвцфц',NULL,'2018-12-22 16:46:11',13,'3',8,NULL,NULL),
	('Йога для бегунов','записи','Буська','(222) 222-2222',NULL,NULL,NULL,NULL,'Буська записалась',NULL,'2018-12-22 17:50:31',14,'2',9,NULL,NULL),
	('Сукшма-вьяяма','записи','Буська','(222) 222-2222',NULL,NULL,NULL,NULL,'Мармышка',NULL,'2018-12-22 17:50:48',15,'3',10,NULL,NULL),
	('Power yoga','записи','Буська','(222) 222-2222',NULL,NULL,NULL,NULL,'Сама такая',NULL,'2018-12-22 17:51:04',16,'4',11,NULL,NULL),
	('Бодрое утро','субботам с 10.30 до 12.00','Буська','(222) 222-2222',NULL,NULL,NULL,NULL,'Запишите меня, я -бревно, но через 2 занятия хочу сесть в шпагат',NULL,'2018-12-22 17:56:20',17,'1',12,NULL,NULL),
	('Йога для бегунов','записи','Буська','(222) 222-2222',NULL,NULL,NULL,NULL,NULL,NULL,'2018-12-22 17:56:45',18,'2',9,'1',NULL),
	('Бодрое утро','субботам с 10.30 до 12.00','pavel','(289) 830-1724',NULL,NULL,NULL,NULL,'Jnin\r\n',NULL,'2018-12-22 21:01:09',19,'1',14,NULL,NULL),
	(NULL,NULL,'pavel','(289) 830-1724','2019-12-12','23:02:00','2019-12-12','21:00:00','пила четыре','0','2018-12-22 22:11:04',20,NULL,13,NULL,'1'),
	('Бодрое утро','субботам с 10.30 до 12.00','pavel','(289) 830-1724',NULL,NULL,NULL,NULL,NULL,NULL,'2018-12-22 22:12:43',21,'1',14,'1',NULL),
	('Бодрое утро','субботам с 10.30 до 12.00','pavel','(289) 830-1724',NULL,NULL,NULL,NULL,'',NULL,'2018-12-22 22:15:21',22,'1',15,NULL,NULL),
	(NULL,NULL,'pavel','(289) 830-1724','2018-12-22','17:15:00',NULL,NULL,'','','2018-12-22 22:15:30',23,NULL,16,NULL,NULL),
	('Сукшма-вьяяма','записи','pavel','(289) 830-1724',NULL,NULL,NULL,NULL,NULL,NULL,'2018-12-24 02:04:35',24,'3',8,'1',NULL),
	('Бодрое утро','субботам с 10.30 до 12.00','pavel','(289) 830-1724',NULL,NULL,NULL,NULL,NULL,NULL,'2018-12-24 02:04:39',25,'1',15,'1',NULL),
	(NULL,NULL,'pavel','(289) 830-1724','2019-12-12','23:02:00',NULL,NULL,NULL,NULL,'2018-12-24 02:04:43',26,NULL,13,'1',NULL),
	(NULL,NULL,'pavel','(289) 830-1724','2018-12-22','17:15:00',NULL,NULL,NULL,NULL,'2018-12-24 02:04:47',27,NULL,16,'1',NULL),
	('Бодрое утро','субботам с 10.30 до 12.00','pavel','(289) 830-1724',NULL,NULL,NULL,NULL,'',NULL,'2018-12-24 19:00:07',28,'1',17,NULL,NULL),
	('Бодрое утро','субботам с 10.30 до 12.00','pavel','(289) 830-1724',NULL,NULL,NULL,NULL,NULL,NULL,'2018-12-24 19:00:49',29,'1',17,'1',NULL),
	('Бодрое утро','субботам с 10.30 до 12.00','pavel','(289) 830-1724',NULL,NULL,NULL,NULL,'',NULL,'2018-12-24 19:01:05',30,'1',18,NULL,NULL),
	('Йога для бегунов','записи','Буська','(222) 222-2222',NULL,NULL,NULL,NULL,'Запись 25.12',NULL,'2018-12-25 14:11:28',31,'2',19,NULL,NULL),
	('Бодрое утро','субботам с 10.30 до 12.00','pavel','(289) 830-1724',NULL,NULL,NULL,NULL,NULL,NULL,'2019-01-02 22:02:09',32,'1',18,'1',NULL),
	(NULL,NULL,'Thinkology','','2019-01-08','12:17:00',NULL,NULL,'','1','2019-01-08 17:17:53',33,NULL,20,NULL,NULL),
	(NULL,NULL,'Thinkology','','2019-01-08','00:17:00','2019-01-08','12:17:00','','1','2019-02-02 01:35:45',34,NULL,20,NULL,'1'),
	('Йога для бегунов','записи','Thinkology','',NULL,NULL,NULL,NULL,'',NULL,'2019-02-02 01:37:40',35,'2',21,NULL,NULL),
	('Йога для бегунов','записи','Thinkology','',NULL,NULL,NULL,NULL,NULL,NULL,'2019-02-02 01:37:57',36,'2',21,'1',NULL),
	('Бодрое утро','субботам с 10.30 до 12.00','Albina','',NULL,NULL,NULL,NULL,'',NULL,'2019-02-11 14:45:14',37,'1',22,NULL,NULL),
	(NULL,NULL,'Albina','','2019-02-13','22:12:00',NULL,NULL,'Пееее','1','2019-02-13 19:12:20',38,NULL,23,NULL,NULL);

/*!40000 ALTER TABLE `notifications-booking_admin` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table phones
# ------------------------------------------------------------

DROP TABLE IF EXISTS `phones`;

CREATE TABLE `phones` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `phone` varchar(40) DEFAULT NULL,
  `registered_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `phones` WRITE;
/*!40000 ALTER TABLE `phones` DISABLE KEYS */;

INSERT INTO `phones` (`id`, `phone`, `registered_at`)
VALUES
	(1,'223232323','2018-09-01 16:53:05'),
	(2,'212222','2018-09-01 16:53:21'),
	(3,'22846318313','2018-09-02 00:09:42'),
	(4,'','2018-09-02 00:17:07'),
	(5,'','2018-09-02 02:16:18'),
	(6,'79636861278','2018-09-02 12:54:52'),
	(7,'89266111113','2018-09-02 17:06:15'),
	(8,'79688433618','2018-09-04 14:38:43'),
	(9,'79688433618','2018-09-04 14:41:34'),
	(10,'','2018-09-04 17:09:11'),
	(11,'','2018-09-06 01:55:38'),
	(12,'79031834299','2018-09-06 16:34:24'),
	(13,'25465645521','2018-10-16 01:34:26'),
	(14,'','2018-10-18 14:33:13'),
	(15,'','2018-10-22 18:39:19');

/*!40000 ALTER TABLE `phones` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table programs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `programs`;

CREATE TABLE `programs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `schedule` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` mediumtext COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `focus` varchar(250) CHARACTER SET utf8 DEFAULT NULL,
  `level` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `duration` varchar(11) CHARACTER SET utf8 DEFAULT NULL,
  `instructor_id` varchar(11) CHARACTER SET utf8 DEFAULT NULL,
  `new` int(11) DEFAULT '1',
  `group_size` varchar(11) CHARACTER SET utf8 DEFAULT NULL,
  `image` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `programs` WRITE;
/*!40000 ALTER TABLE `programs` DISABLE KEYS */;

INSERT INTO `programs` (`id`, `title`, `schedule`, `description`, `created_at`, `focus`, `level`, `duration`, `instructor_id`, `new`, `group_size`, `image`)
VALUES
	(1,'Бодрое утро','субботам с 10.30 до 12.00','Последовательность  выполнения приветствия Солнцу (Сурья намаскар(а) известны давно своей волшебной силой активизировать тело и сознание человека. В комплексе \"Бодрое утро\" применятся техника приветствия Солнцу различных школ. Включены асаны на подготовку тела и сознания к полноценному и активному рабочему дню.','2018-10-02 16:22:31','зарядиться энергией через приветствия солнцу и  приобрести бодрость с раджастичными асанами','Начинающий-средний','60','1',1,'20','Бодрое-утро.png'),
	(2,'Йога для бегунов','записи','Комплекс асан, который разработан специально для бегунов. Он позволяет расслаблять  активные и укреплять вспомогательные мышцы ног и спины. Дает расслабление всему телу после актвных занятий бегом. ','2018-10-01 16:22:31','безопасная разминка и заминка для мышц стопы, голени, бедра, ягодиц и спины','Начинающий-средний','60','1',1,'10','Йога-для-бегунов.png'),
	(3,'Сукшма-вьяяма','записи','Последовательность мощных упражнений, оказывающая очень сильное воздействие непосредственно на энергетическое тело, а также на все группы связок и суставов.','2018-10-25 21:47:39','динамичная проработка и разогрев всех групп суставов и мышц','Начинающий-средний','60','1',1,'5','Сукшма-вььяма.png'),
	(4,'Power yoga','записи','Пауэр йога- стиль, родившийся в Штатах, включает в себя последовательное выполнение каждого блоков упражнений. Сочетает выполнение блока асан и виньяс. Динамичная практика направлена на сосредоточение внутри себя, концентрации внимания, что отвечает одной из целей йоги. Стиль сравнивают с Аштанга виньясой, однако, имеет с ней мало общего.','2018-10-25 21:47:41','сочетает активное выполнение блоков асан и виньяс','средний-продвинутый','60','1',1,'10','Power-yoga.png'),
	(5,'Антистресс и спокойствие без лекарств','четвергам с 19.30 до 21.00','Комплекс асан направлен на релаксацию после тяжелых рабочих будней и стрессовых ситуаций. Включает в себя асаны на расслабление, вытяжение, скрутки и дыхательные практики.','2018-10-25 21:51:06','развитие, прокачивание и расслабение верхних отделов тела: плеч, грудной клетки, верхнего отдела спины, шеи и головы','начальный, средний, продвинутый','90','1',1,'20','Антистресс.png'),
	(6,'Шпагат или хануманасана?','записи','Курс для освоения или улучшения выполнения шпагата. Упор на принципы травмобезопасности при раскрытии тазобедренного сустава, опоре на колено и вытяжении мышц спины. Подготовительные и подводящие асаны.','2018-10-25 21:51:09','раскрытие бедра, развитие подвижности мышц и связок тазобедренного сустава','продвинутый','90','1',1,'10','хануманасана.png'),
	(7,'Балансы в движении','записи','Энергичная и активная практика, позволяющая интенсивно работать не только с телом, но и с мозгом, не позволяя ему отвлекаться на посторонние темы. Вся практика проходит на удержание баланса, при выполнеии активных сетов.','2018-10-25 21:51:10','развитие вестибулярного аппарата, освоение балансов, укрепление мелких мышц ног и спины, усиление концентрации и внимания','средний-продвинутый','60','1',1,'15','движении.png');

/*!40000 ALTER TABLE `programs` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `avatar` varchar(100) DEFAULT 'default1.png',
  `first_name` varchar(40) DEFAULT NULL,
  `last_name` varchar(40) DEFAULT NULL,
  `gender` varchar(11) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `level` varchar(40) DEFAULT NULL,
  `location` varchar(40) DEFAULT NULL,
  `email` varchar(40) DEFAULT NULL,
  `phone` varchar(40) DEFAULT NULL,
  `about` varchar(500) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `available` int(11) DEFAULT '1',
  `facebook` varchar(60) DEFAULT NULL,
  `instagram` varchar(60) DEFAULT NULL,
  `noMail` varchar(11) DEFAULT NULL,
  `googleID` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`id`, `avatar`, `first_name`, `last_name`, `gender`, `birthdate`, `level`, `location`, `email`, `phone`, `about`, `password`, `created_at`, `available`, `facebook`, `instagram`, `noMail`, `googleID`)
VALUES
	(1,'how_to_become_a_yoga_instructor.png','Альбина','Курская','женский','1980-10-11','Instructor','Москва','galieva.albi@yandex.ru','(963) 686-1278','Я – разносторонний, жизнерадостный, социальноактивный человек, люблю животных, но не вегетарианка. За здоровый образ жизни и чистую окружающую среду. Стараюсь помогать людям. В восторге от грамотных и вежливых людей. Окончила 200 часовой курс инструктора по йоге в Федерации Йоги России. Стаж личной практики – 5 лет.','$2y$10$2He9t4HY04nWhOoKV3b62uOyqZkk9CCiG6hUXy2vwlxHVS2Zm6HQa','2018-09-03 12:49:09',1,NULL,'',NULL,NULL),
	(11,'default1.png','Айгуль',NULL,NULL,NULL,NULL,NULL,NULL,'(968) 744-8365',NULL,'$2y$10$ReUKs6Qzda51SWPYfNVfged5k0BXDveiYwqLlSVS.9b3cqMYFU6By','2018-12-03 08:25:08',1,NULL,NULL,NULL,NULL),
	(12,'default1.png','Greg',NULL,NULL,NULL,NULL,NULL,NULL,'(416) 892-2343',NULL,'$2y$10$rttsMTJ/wCHW3JUH061r9u0tIeFtP9VzI3AXVoDHA935/zxhcJxNi','2018-12-04 01:03:25',1,NULL,NULL,NULL,NULL),
	(15,'default1.png','Дмитрий',NULL,NULL,NULL,NULL,NULL,NULL,'(925) 071-6899',NULL,'$2y$10$o3YTdXxWiYRwo1CXIX5Tiey8ESDdWfD7.n9ccuL0LHn7axEkA1VT2','2018-12-08 19:33:54',1,NULL,NULL,NULL,NULL),
	(16,'default1.png','На',NULL,NULL,NULL,NULL,NULL,NULL,'(916) 806-6631',NULL,'$2y$10$YmyFtB7kjf84i1n8ZbOZG.xJFQ0XuvYzhKjB4nnZtXPV/EuMhV1VS','2018-12-10 18:14:22',1,NULL,NULL,NULL,NULL),
	(17,'default1.png','Сергей',NULL,NULL,NULL,NULL,NULL,NULL,'(916) 695-9095',NULL,'$2y$10$LnipmKG5cAPzHJoy7Rqe9uYce1VYP.Pn/JBvo6L.YXAZZ06zdbOg2','2018-12-10 18:22:15',1,NULL,NULL,NULL,NULL),
	(18,'default1.png','pavel','kurskii',NULL,'1990-09-09',NULL,'Latyshskaya','jampaul@mail.ru','(289) 830-1724',NULL,'$2y$10$Z97l29f/YFOgfLeaqDh0pOFCiNLK5dkuI7Sl8PqRo/p8BvSYk9DRq','2018-12-10 18:27:43',1,NULL,NULL,NULL,NULL),
	(19,'default1.png','Буська',NULL,NULL,NULL,NULL,NULL,NULL,'(222) 222-2222','Очкарики готовы на все','$2y$10$eB6Y/ms//TCQwJIYfAFE0uWKTsfeGBzC65LjlgihybTnSMWsxRqoy','2018-12-10 18:30:30',1,NULL,NULL,NULL,NULL),
	(20,'default1.png','Александра',NULL,NULL,NULL,NULL,NULL,NULL,'(926) 206-1130',NULL,'$2y$10$EjiFIBBrxVoHoNOU4bpjYOhWTi78./Rg9mZo7C3N5GMTcPCpHiWH2','2018-12-10 18:55:53',1,NULL,NULL,NULL,NULL),
	(21,'default1.png','Набу',NULL,NULL,NULL,NULL,NULL,NULL,'(968) 880-5511',NULL,'$2y$10$a7B/3GjWsGL1OWiJi6CAuOPk.xkXibQCezCHk3QZYiG3HyeCgkjWO','2018-12-10 20:17:50',1,NULL,NULL,NULL,NULL),
	(22,'default1.png','Лена',NULL,NULL,NULL,NULL,NULL,NULL,'(980) 773-5185',NULL,'$2y$10$01B0vaElVi6EwiFKGmPw7uWiG9cAwzzrxQ/IUjEZ.ns1Z.eCvvJYy','2018-12-14 18:45:59',1,NULL,NULL,NULL,NULL),
	(23,'default1.png','Heal',NULL,NULL,NULL,NULL,NULL,NULL,'(915) 973-6263',NULL,'$2y$10$rt4cd3qFYtLwdAfMVVvzue2y3Xjf8kNI6wcSOSFZ2xS4k0XKZwYqi','2018-12-14 18:47:18',1,NULL,NULL,NULL,NULL),
	(24,'default1.png','Ирина',NULL,NULL,NULL,NULL,NULL,NULL,'(925) 083-9643',NULL,'$2y$10$eQCdtaQ.x48DVUX79i8MBe7JqE73HEhoKdFoPFCTZDbwoMf9yBzBu','2018-12-14 18:48:54',1,NULL,NULL,NULL,NULL),
	(25,'default1.png','Саша',NULL,NULL,NULL,NULL,NULL,NULL,'(798) 070-6166',NULL,'$2y$10$SOGuHAM55dDDPo6PWP96G.bQaEjvCweNdFCGsLllkPuNb.wJbV9hy','2018-12-14 18:53:25',1,NULL,NULL,NULL,NULL),
	(26,'default1.png','Иван',NULL,NULL,NULL,NULL,NULL,NULL,'(891) 597-8153',NULL,'$2y$10$nL/UiPHHy007VDrA6zaTTekUbX/b7qADxMJwskcxBmYl1zcON77Be','2018-12-14 19:04:33',1,NULL,NULL,NULL,NULL),
	(27,'default1.png','Не дадим Альбине умереть в БВ',NULL,NULL,NULL,NULL,NULL,NULL,'(926) 206-6409',NULL,'$2y$10$pZm1W/bXW47RR0FrtmqK8OPVwWyQF/JQb1ULvkwffNF3TKtsIKgYC','2018-12-14 19:14:12',1,NULL,NULL,NULL,NULL),
	(28,'default1.png','Елена',NULL,NULL,NULL,NULL,NULL,NULL,'(123) 321-1234',NULL,'$2y$10$NGX.d5bQgiDR5s5SM4iu7.qX0BwZEBmmATJOgshseK4CKj7ziiJF2','2018-12-18 09:10:29',1,NULL,NULL,NULL,NULL),
	(34,'default1.png','Белочка',NULL,NULL,NULL,NULL,NULL,NULL,'(444) 444-4444',NULL,'$2y$10$CjaeryoAg6gM/28UQKTKMOS2uTEA.h/c6nnJtL.AM//a/dp3IxfSm','2018-12-24 13:29:22',1,NULL,NULL,NULL,NULL),
	(37,'default1.png','Thinkology','',NULL,NULL,NULL,NULL,'kurskiy.ifc@gmail.com','',NULL,NULL,'2019-01-08 17:17:42',1,NULL,NULL,'','112938098478276389920'),
	(38,'default1.png','Альбина','Галиева',NULL,NULL,NULL,NULL,'albina.abdurashidovna@gmail.com','(999) 999-9999',NULL,NULL,'2019-01-08 17:33:44',1,NULL,NULL,'1','104488310976520127494'),
	(39,'default1.png','Paul','Kurskii',NULL,NULL,NULL,NULL,'paul.kurskii@gmail.com','',NULL,NULL,'2019-01-08 18:00:33',1,NULL,NULL,'','115568860865619453028'),
	(40,'default1.png','awd',NULL,NULL,NULL,NULL,NULL,NULL,'(232) 312-3232',NULL,'$2y$10$sZdQRFXaP2LfWgqBDGCCL.Y0zJWoRJX5QB4tq4itYL.dtm.ENMffa','2019-01-14 18:09:40',1,NULL,NULL,NULL,NULL),
	(41,'default1.png','Aegis','',NULL,NULL,NULL,NULL,'aegisbrand@gmail.com','',NULL,NULL,'2019-01-22 20:55:11',1,NULL,NULL,'','114353807329035238224'),
	(42,'default1.png','Albina','Kurskaya',NULL,NULL,NULL,NULL,'albina.kurskaya@gmail.com','','Оррнн',NULL,'2019-02-11 14:43:23',1,NULL,NULL,'','117060695450335383700'),
	(43,'default1.png','awdawd',NULL,NULL,NULL,NULL,NULL,NULL,'(289) 123-8918',NULL,'$2y$10$CKd7rz52bVQVMDTBa9XIH.95O1FZqk1JS0fn1c7GFuhaCVlTAQ2Qy','2019-02-19 15:06:16',1,NULL,NULL,NULL,NULL);

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

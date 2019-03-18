# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: localhost (MySQL 5.6.35)
# Database: playground
# Generation Time: 2019-03-18 02:14:41 +0000
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
  `date` varchar(20) DEFAULT NULL,
  `time` varchar(20) DEFAULT NULL,
  `comment` varchar(2000) DEFAULT NULL,
  `private` int(11) DEFAULT '0',
  `confirmed` int(11) DEFAULT '1',
  `repeatble` varchar(10) DEFAULT '0',
  `private_id` varchar(11) DEFAULT NULL,
  `google_cal_id` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;

INSERT INTO `events` (`id`, `group_event_id`, `program`, `student`, `instructor`, `date`, `time`, `comment`, `private`, `confirmed`, `repeatble`, `private_id`, `google_cal_id`)
VALUES
	(22,'1','1','52','1',NULL,NULL,'pachimu',0,1,'1',NULL,'1pjnn7bddq844518nijqobsar0');

/*!40000 ALTER TABLE `events` ENABLE KEYS */;
UNLOCK TABLES;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_ENGINE_SUBSTITUTION" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `new_booking` AFTER INSERT ON `events` FOR EACH ROW BEGIN
/* new private*/
IF (NEW.program IS NULL) THEN
		INSERT INTO `notifications-booking` (date,time,owner,event_id,repeatble) 
    select date, time, student, id,repeatble
from events where id = NEW.id;
      ELSE
      /*new group*/
			INSERT INTO `notifications-booking` (owner,program,schedule,program_id,event_id) 
			select events.student, programs.title, programs.schedule, events.program, events.id
from events join programs on programs.id = events.program where events.id=NEW.id;
      END IF;
      
  END */;;
/*!50003 SET SESSION SQL_MODE="NO_ENGINE_SUBSTITUTION" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `private_update` BEFORE UPDATE ON `events` FOR EACH ROW BEGIN

/*Update - Private*/
IF (NEW.program IS NULL) THEN
	INSERT INTO `notifications-booking` (date,time,old_date,old_time,owner,event_id,repeatble,`change`) 
    select NEW.date, NEW.time,OLD.date,OLD.time, student, id,NEW.repeatble,'1'
	from events where id = NEW.id;

END IF;	
END */;;
/*!50003 SET SESSION SQL_MODE="NO_ENGINE_SUBSTITUTION" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `update_booking` AFTER UPDATE ON `events` FOR EACH ROW BEGIN

	/*for admin confirmation trigger in notif-booking table*/
	

	
/*Update - Group*/
IF (NEW.program IS NOT NULL) THEN
	
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


# Dump of table favorite-programs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `favorite-programs`;

CREATE TABLE `favorite-programs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user` varchar(11) DEFAULT NULL,
  `program` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table messages
# ------------------------------------------------------------

DROP TABLE IF EXISTS `messages`;

CREATE TABLE `messages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `conversation` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `author` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` mediumtext COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `readed` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table notifications-booking
# ------------------------------------------------------------

DROP TABLE IF EXISTS `notifications-booking`;

CREATE TABLE `notifications-booking` (
  `readed` varchar(6) DEFAULT NULL,
  `program` varchar(40) DEFAULT NULL,
  `schedule` varchar(40) DEFAULT NULL,
  `date` varchar(20) DEFAULT NULL,
  `time` varchar(20) DEFAULT NULL,
  `old_date` varchar(20) DEFAULT NULL,
  `old_time` varchar(20) DEFAULT NULL,
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
	(NULL,'Бодрое утро','субботам с 10.30 до 12.00',NULL,NULL,NULL,NULL,NULL,'2019-03-17 19:54:04','52','1',17,22,NULL,NULL,NULL),
	(NULL,NULL,NULL,'суббота','15:00 - 16:30',NULL,NULL,'1','2019-03-17 20:02:57','53',NULL,22,25,NULL,NULL,NULL),
	(NULL,NULL,NULL,'суббота','12:30 - 14:00','суббота','15:00 - 16:30','1','2019-03-17 20:05:14','53',NULL,23,25,NULL,NULL,'1'),
	(NULL,NULL,NULL,'суббота','15:00 - 16:30','суббота','12:30 - 14:00','','2019-03-17 20:11:42','53',NULL,24,25,NULL,NULL,'1'),
	(NULL,NULL,NULL,'суббота','12:30 - 14:00','суббота','15:00 - 16:30','','2019-03-17 20:13:08','53',NULL,25,25,NULL,NULL,'1'),
	(NULL,NULL,NULL,'суббота','15:00 - 16:30','суббота','12:30 - 14:00','1','2019-03-17 20:13:31','53',NULL,26,25,NULL,NULL,'1'),
	(NULL,NULL,NULL,'суббота','15:00 - 16:30',NULL,NULL,'1','2019-03-17 20:15:42','53',NULL,27,25,'1',NULL,NULL);

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
ELSEIF (NEW.program IS NULL) THEN
	INSERT INTO `notifications-booking_admin` (date,time,old_date,old_time,comment,user_name,user_phone,event_id,repeatble,`change`) 	
	select NEW.date,NEW.time,NEW.`old_date`,NEW.`old_time`,events.comment, users.`first_name`,users.`phone`, events.id,NEW.repeatble,NEW.change
	from events join users on users.id = events.student where events.id=NEW.event_id;

/*Update + New - Group*/
ELSEIF (NEW.program IS NOT NULL) THEN
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
  `date` varchar(20) DEFAULT NULL,
  `time` varchar(20) DEFAULT NULL,
  `old_date` varchar(20) DEFAULT NULL,
  `old_time` varchar(20) DEFAULT NULL,
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
	('Бодрое утро','субботам с 10.30 до 12.00','Arsen','(289) 830-',NULL,NULL,NULL,NULL,'pachimu',NULL,'2019-03-17 19:54:04',45,'1',22,NULL,NULL),
	('Бодрое утро','субботам с 10.30 до 12.00','Paul','',NULL,NULL,NULL,NULL,'privetushki',NULL,'2019-03-17 19:57:17',46,'1',23,NULL,NULL),
	('Бодрое утро','субботам с 10.30 до 12.00','Paul','',NULL,NULL,NULL,NULL,NULL,NULL,'2019-03-17 20:00:07',47,'1',23,'1',NULL),
	('Бодрое утро','субботам с 10.30 до 12.00','Paul','',NULL,NULL,NULL,NULL,'',NULL,'2019-03-17 20:00:30',48,'1',24,NULL,NULL),
	('Бодрое утро','субботам с 10.30 до 12.00','Paul','',NULL,NULL,NULL,NULL,NULL,NULL,'2019-03-17 20:00:54',49,'1',24,'1',NULL),
	(NULL,NULL,'Paul','','суббота','15:00 - 16:30',NULL,NULL,'chisto','1','2019-03-17 20:02:57',50,NULL,25,NULL,NULL),
	(NULL,NULL,'Paul','','суббота','12:30 - 14:00','суббота','15:00 - 16:30','chisto','1','2019-03-17 20:05:14',51,NULL,25,NULL,'1'),
	(NULL,NULL,'Paul','','суббота','15:00 - 16:30','суббота','12:30 - 14:00','','','2019-03-17 20:11:42',52,NULL,25,NULL,'1'),
	(NULL,NULL,'Paul','','суббота','12:30 - 14:00','суббота','15:00 - 16:30','','','2019-03-17 20:13:08',53,NULL,25,NULL,'1'),
	(NULL,NULL,'Paul','','суббота','15:00 - 16:30','суббота','12:30 - 14:00','','1','2019-03-17 20:13:31',54,NULL,25,NULL,'1'),
	(NULL,NULL,'Paul','','суббота','15:00 - 16:30',NULL,NULL,NULL,NULL,'2019-03-17 20:15:42',55,NULL,25,'1',NULL);

/*!40000 ALTER TABLE `notifications-booking_admin` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table private-schedule
# ------------------------------------------------------------

DROP TABLE IF EXISTS `private-schedule`;

CREATE TABLE `private-schedule` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `date` varchar(20) DEFAULT 'null',
  `time` varchar(20) DEFAULT 'null',
  `taken` varchar(10) DEFAULT '0',
  `dayOfWeek` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `private-schedule` WRITE;
/*!40000 ALTER TABLE `private-schedule` DISABLE KEYS */;

INSERT INTO `private-schedule` (`id`, `date`, `time`, `taken`, `dayOfWeek`)
VALUES
	(1,'суббота','12:30 - 14:00','0',6),
	(2,'суббота','15:00 - 16:30','0',6),
	(5,'суббота','17:00 - 18:00','0',6),
	(6,'воскресенье','12:30 - 14:00','0',7),
	(7,'воскресенье','15:00 - 16:30','0',7),
	(8,'воскресенье','17:00 - 18:00','0',7);

/*!40000 ALTER TABLE `private-schedule` ENABLE KEYS */;
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
  `gWeekDay` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `startTime` time DEFAULT NULL,
  `endTime` time DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `programs` WRITE;
/*!40000 ALTER TABLE `programs` DISABLE KEYS */;

INSERT INTO `programs` (`id`, `title`, `schedule`, `description`, `created_at`, `focus`, `level`, `duration`, `instructor_id`, `new`, `group_size`, `image`, `gWeekDay`, `startTime`, `endTime`)
VALUES
	(1,'Бодрое утро','субботам с 10.30 до 12.00','Последовательность  выполнения приветствия Солнцу (Сурья намаскар(а) известны давно своей волшебной силой активизировать тело и сознание человека. В комплексе \"Бодрое утро\" применятся техника приветствия Солнцу различных школ. Включены асаны на подготовку тела и сознания к полноценному и активному рабочему дню.','2018-10-02 16:22:31','зарядиться энергией через приветствия солнцу и  приобрести бодрость с раджастичными асанами','Начинающий-средний','60','1',1,'20','Бодрое-утро.png','SA','10:30:00','12:00:00'),
	(2,'Йога для бегунов','записи','Комплекс асан, который разработан специально для бегунов. Он позволяет расслаблять  активные и укреплять вспомогательные мышцы ног и спины. Дает расслабление всему телу после актвных занятий бегом. ','2018-10-01 16:22:31','безопасная разминка и заминка для мышц стопы, голени, бедра, ягодиц и спины','Начинающий-средний','60','1',1,'10','Йога-для-бегунов.png','MO',NULL,NULL),
	(3,'Сукшма-вьяяма','записи','Последовательность мощных упражнений, оказывающая очень сильное воздействие непосредственно на энергетическое тело, а также на все группы связок и суставов.','2018-10-25 21:47:39','динамичная проработка и разогрев всех групп суставов и мышц','Начинающий-средний','60','1',1,'5','Сукшма-вььяма.png','MO',NULL,NULL),
	(4,'Power yoga','записи','Пауэр йога- стиль, родившийся в Штатах, включает в себя последовательное выполнение каждого блоков упражнений. Сочетает выполнение блока асан и виньяс. Динамичная практика направлена на сосредоточение внутри себя, концентрации внимания, что отвечает одной из целей йоги. Стиль сравнивают с Аштанга виньясой, однако, имеет с ней мало общего.','2018-10-25 21:47:41','сочетает активное выполнение блоков асан и виньяс','средний-продвинутый','60','1',1,'10','Power-yoga.png','MO',NULL,NULL),
	(5,'Антистресс и спокойствие без лекарств','четвергам с 19.30 до 21.00','Комплекс асан направлен на релаксацию после тяжелых рабочих будней и стрессовых ситуаций. Включает в себя асаны на расслабление, вытяжение, скрутки и дыхательные практики.','2018-10-25 21:51:06','развитие, прокачивание и расслабение верхних отделов тела: плеч, грудной клетки, верхнего отдела спины, шеи и головы','начальный, средний, продвинутый','90','1',1,'20','Антистресс.png','TH','19:30:00','21:00:00'),
	(6,'Шпагат или хануманасана?','записи','Курс для освоения или улучшения выполнения шпагата. Упор на принципы травмобезопасности при раскрытии тазобедренного сустава, опоре на колено и вытяжении мышц спины. Подготовительные и подводящие асаны.','2018-10-25 21:51:09','раскрытие бедра, развитие подвижности мышц и связок тазобедренного сустава','продвинутый','90','1',1,'10','хануманасана.png','MO',NULL,NULL),
	(7,'Балансы в движении','записи','Энергичная и активная практика, позволяющая интенсивно работать не только с телом, но и с мозгом, не позволяя ему отвлекаться на посторонние темы. Вся практика проходит на удержание баланса, при выполнеии активных сетов.','2018-10-25 21:51:10','развитие вестибулярного аппарата, освоение балансов, укрепление мелких мышц ног и спины, усиление концентрации и внимания','средний-продвинутый','60','1',1,'15','движении.png','MO',NULL,NULL);

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
	(1,'how_to_become_a_yoga_instructor.png','Альбина','Курская','женский','1980-10-11','Instructor','Москва','galieva.albi@yandex.ru','(963) 686-1278','Я – разносторонний, жизнерадостный, социальноактивный человек, люблю животных, но не вегетарианка. За здоровый образ жизни и чистую окружающую среду. Стараюсь помогать людям. В восторге от грамотных и вежливых людей. Окончила 200 часовой курс инструктора по йоге в Федерации Йоги России. Стаж личной практики – 5 лет.','$2y$10$90atz61sMgBH/zzdxOIY1eGVvIpXbvg6PKr2DRCSUrKSkJUTWrNx2','2018-09-03 12:49:09',1,NULL,'',NULL,NULL);

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_ENGINE_SUBSTITUTION" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `welcome_message` AFTER INSERT ON `users` FOR EACH ROW BEGIN
/* welcome message for new user*/

INSERT INTO `messages` (conversation,message,author) 
    select id,'Привет, друг! Теперь выбери практику и вперед! Встретимся на коврике)', '1'
from users where id = NEW.id;
          
END */;;
DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE */;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

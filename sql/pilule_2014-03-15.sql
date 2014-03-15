# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Hôte: 127.0.0.1 (MySQL 5.5.9)
# Base de données: pilule
# Temps de génération: 2014-03-15 19:05:44 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Affichage de la table cake_sessions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cake_sessions`;

CREATE TABLE `cake_sessions` (
  `id` varchar(255) NOT NULL DEFAULT '',
  `data` blob,
  `expires` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table capsule_requests
# ------------------------------------------------------------

DROP TABLE IF EXISTS `capsule_requests`;

CREATE TABLE `capsule_requests` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idul` varchar(10) NOT NULL DEFAULT '',
  `name` varchar(60) NOT NULL DEFAULT '',
  `timestamp` int(11) unsigned NOT NULL,
  `response_time` float(10,5) NOT NULL DEFAULT '0.00000' COMMENT 'Temps de réponse en ms',
  `md5` varchar(32) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idul` (`idul`),
  KEY `name` (`name`),
  KEY `md5` (`md5`),
  KEY `timestamp` (`timestamp`),
  KEY `response_time` (`response_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table classes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `classes`;

CREATE TABLE `classes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nrc` varchar(5) NOT NULL,
  `course_id` int(11) unsigned NOT NULL,
  `idcourse` varchar(10) NOT NULL,
  `semester` varchar(6) NOT NULL,
  `teacher` text NOT NULL,
  `timetable` text NOT NULL,
  `campus` varchar(32) NOT NULL,
  `notes` text NOT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `nrc` (`nrc`),
  KEY `idcourse` (`idcourse`),
  KEY `semester` (`semester`),
  KEY `course_id` (`course_id`),
  CONSTRAINT `classes_idcourse` FOREIGN KEY (`idcourse`) REFERENCES `courses` (`code`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `classes` WRITE;
/*!40000 ALTER TABLE `classes` DISABLE KEYS */;

INSERT INTO `classes` (`id`, `nrc`, `course_id`, `idcourse`, `semester`, `teacher`, `timetable`, `campus`, `notes`, `created`, `updated`)
VALUES
	(87451,'50944',5,'GGR-1000','201405','','a:1:{i:0;a:4:{s:4:\"type\";s:12:\"Sur Internet\";s:3:\"day\";s:2:\" \";s:9:\"day_start\";s:8:\"20140505\";s:7:\"day_end\";s:8:\"20140720\";}}','Principal','Ce cours est offert à distance. Pour plus d\'information, veuillez consulter la page du cours à l\'adresse www.distance.ulaval.ca.','2014-03-15 14:45:13','2014-03-15 14:45:13'),
	(87452,'56707',8,'COM-1002','201405','','a:1:{i:0;a:4:{s:4:\"type\";s:12:\"Sur Internet\";s:3:\"day\";s:2:\" \";s:9:\"day_start\";s:8:\"20140505\";s:7:\"day_end\";s:8:\"20140620\";}}','Principal','Ce cours est offert à distance. Pour plus dinformations, consultez la page du cours à ladresse www.distance.ulaval.ca.','2014-03-15 14:45:14','2014-03-15 14:45:14'),
	(87453,'51565',15,'COM-1500','201405','','a:2:{i:0;a:6:{s:4:\"type\";s:15:\"Cours en classe\";s:10:\"hour_start\";s:5:\"18:30\";s:8:\"hour_end\";s:5:\"21:20\";s:3:\"day\";s:1:\"L\";s:9:\"day_start\";s:8:\"20140505\";s:7:\"day_end\";s:8:\"20140620\";}i:1;a:6:{s:4:\"type\";s:15:\"Cours en classe\";s:10:\"hour_start\";s:5:\"18:30\";s:8:\"hour_end\";s:5:\"21:20\";s:3:\"day\";s:1:\"R\";s:9:\"day_start\";s:8:\"20140505\";s:7:\"day_end\";s:8:\"20140620\";}}','Principal','Ce cours vise à développer l\'habileté à communiquer oralement devant un groupe hétérogène. Étude des composantes verbales et non verbales nécessaires à une communication orale de qualité. Action et rétroaction orales guidées par le professeur, avec l\'appui logistique de la vidéo. Remarque - Ce cours vise la consolidation de la connaissance générale du français et la connaissance du français de niveau universitaire.','2014-03-15 14:45:15','2014-03-15 14:45:15'),
	(87454,'51566',15,'COM-1500','201405','','a:2:{i:0;a:6:{s:4:\"type\";s:15:\"Cours en classe\";s:10:\"hour_start\";s:5:\"18:30\";s:8:\"hour_end\";s:5:\"21:20\";s:3:\"day\";s:1:\"L\";s:9:\"day_start\";s:8:\"20140505\";s:7:\"day_end\";s:8:\"20140620\";}i:1;a:6:{s:4:\"type\";s:15:\"Cours en classe\";s:10:\"hour_start\";s:5:\"18:30\";s:8:\"hour_end\";s:5:\"21:20\";s:3:\"day\";s:1:\"R\";s:9:\"day_start\";s:8:\"20140505\";s:7:\"day_end\";s:8:\"20140620\";}}','Principal','Ce cours vise à développer l\'habileté à communiquer oralement devant un groupe hétérogène. Étude des composantes verbales et non verbales nécessaires à une communication orale de qualité. Action et rétroaction orales guidées par le professeur, avec l\'appui logistique de la vidéo. Remarque - Ce cours vise la consolidation de la connaissance générale du français et la connaissance du français de niveau universitaire.','2014-03-15 14:45:15','2014-03-15 14:45:15'),
	(87455,'51567',15,'COM-1500','201405','','a:2:{i:0;a:6:{s:4:\"type\";s:15:\"Cours en classe\";s:10:\"hour_start\";s:5:\"12:30\";s:8:\"hour_end\";s:5:\"15:20\";s:3:\"day\";s:1:\"M\";s:9:\"day_start\";s:8:\"20140505\";s:7:\"day_end\";s:8:\"20140620\";}i:1;a:6:{s:4:\"type\";s:15:\"Cours en classe\";s:10:\"hour_start\";s:5:\"12:30\";s:8:\"hour_end\";s:5:\"15:20\";s:3:\"day\";s:1:\"J\";s:9:\"day_start\";s:8:\"20140505\";s:7:\"day_end\";s:8:\"20140620\";}}','Principal','Ce cours vise à développer l\'habileté à communiquer oralement devant un groupe hétérogène. Étude des composantes verbales et non verbales nécessaires à une communication orale de qualité. Action et rétroaction orales guidées par le professeur, avec l\'appui logistique de la vidéo. Remarque - Ce cours vise la consolidation de la connaissance générale du français et la connaissance du français de niveau universitaire.','2014-03-15 14:45:15','2014-03-15 14:45:15'),
	(87456,'51952',15,'COM-1500','201405','','a:2:{i:0;a:6:{s:4:\"type\";s:15:\"Cours en classe\";s:10:\"hour_start\";s:5:\"12:30\";s:8:\"hour_end\";s:5:\"15:20\";s:3:\"day\";s:1:\"M\";s:9:\"day_start\";s:8:\"20140505\";s:7:\"day_end\";s:8:\"20140620\";}i:1;a:6:{s:4:\"type\";s:15:\"Cours en classe\";s:10:\"hour_start\";s:5:\"12:30\";s:8:\"hour_end\";s:5:\"15:20\";s:3:\"day\";s:1:\"J\";s:9:\"day_start\";s:8:\"20140505\";s:7:\"day_end\";s:8:\"20140620\";}}','Principal','Ce cours vise à développer l\'habileté à communiquer oralement devant un groupe hétérogène. Étude des composantes verbales et non verbales nécessaires à une communication orale de qualité. Action et rétroaction orales guidées par le professeur, avec l\'appui logistique de la vidéo. Remarque - Ce cours vise la consolidation de la connaissance générale du français et la connaissance du français de niveau universitaire.','2014-03-15 14:45:15','2014-03-15 14:45:15'),
	(87457,'50008',23,'COM-4150','201405','','a:1:{i:0;a:4:{s:4:\"type\";s:12:\"Sur Internet\";s:3:\"day\";s:2:\" \";s:9:\"day_start\";s:8:\"20140505\";s:7:\"day_end\";s:8:\"20140620\";}}','Principal','Ce cours est offert à distance. Pour plus dinformations, consultez la page du cours à ladresse www.distance.ulaval.ca','2014-03-15 14:45:16','2014-03-15 14:45:16'),
	(87458,'53620',33,'ANT-1200','201405','','a:1:{i:0;a:4:{s:4:\"type\";s:12:\"Sur Internet\";s:3:\"day\";s:2:\" \";s:9:\"day_start\";s:8:\"20140505\";s:7:\"day_end\";s:8:\"20140720\";}}','Principal','Ce cours est offert à distance. Pour plus dinformations, consultez la page du cours à ladresse www.distance.ulaval.ca.','2014-03-15 14:45:18','2014-03-15 14:45:18'),
	(87459,'55365',46,'POL-1003','201405','','a:1:{i:0;a:4:{s:4:\"type\";s:12:\"Sur Internet\";s:3:\"day\";s:2:\" \";s:9:\"day_start\";s:8:\"20140505\";s:7:\"day_end\";s:8:\"20140720\";}}','Principal','Ce cours est offert à distance. Pour plus d\'informations, consultez la page du cours à l\'adresse www.distance.ulaval.ca.','2014-03-15 14:45:21','2014-03-15 14:45:21'),
	(87460,'50405',47,'POL-1005','201405','','a:1:{i:0;a:4:{s:4:\"type\";s:12:\"Sur Internet\";s:3:\"day\";s:2:\" \";s:9:\"day_start\";s:8:\"20140505\";s:7:\"day_end\";s:8:\"20140720\";}}','Principal','Ce cours est offert à distance. Pour plus d\'informations, consultez la page du cours à l\'adresse www.distance.ulaval.ca.','2014-03-15 14:45:21','2014-03-15 14:45:21'),
	(87461,'51040',54,'JAP-1010','201405','','a:5:{i:0;a:6:{s:4:\"type\";s:15:\"Cours en classe\";s:10:\"hour_start\";s:4:\"9:30\";s:8:\"hour_end\";s:5:\"12:20\";s:3:\"day\";s:1:\"L\";s:9:\"day_start\";s:8:\"20140505\";s:7:\"day_end\";s:8:\"20140523\";}i:1;a:6:{s:4:\"type\";s:15:\"Cours en classe\";s:10:\"hour_start\";s:4:\"9:30\";s:8:\"hour_end\";s:5:\"12:20\";s:3:\"day\";s:1:\"M\";s:9:\"day_start\";s:8:\"20140505\";s:7:\"day_end\";s:8:\"20140523\";}i:2;a:6:{s:4:\"type\";s:15:\"Cours en classe\";s:10:\"hour_start\";s:4:\"9:30\";s:8:\"hour_end\";s:5:\"12:20\";s:3:\"day\";s:1:\"R\";s:9:\"day_start\";s:8:\"20140505\";s:7:\"day_end\";s:8:\"20140523\";}i:3;a:6:{s:4:\"type\";s:15:\"Cours en classe\";s:10:\"hour_start\";s:4:\"9:30\";s:8:\"hour_end\";s:5:\"12:20\";s:3:\"day\";s:1:\"J\";s:9:\"day_start\";s:8:\"20140505\";s:7:\"day_end\";s:8:\"20140523\";}i:4;a:6:{s:4:\"type\";s:15:\"Cours en classe\";s:10:\"hour_start\";s:4:\"9:30\";s:8:\"hour_end\";s:5:\"12:20\";s:3:\"day\";s:1:\"V\";s:9:\"day_start\";s:8:\"20140505\";s:7:\"day_end\";s:8:\"20140523\";}}','Principal','Cours intensif destiné aux personnes qui commencent l\'apprentissage du japonais. Initiation au japonais oral et écrit. Apprentissage de structures fondamentales dans des situations de communication usuelles. Apprentissage des syllabaires Hiragana et Katakana. Initiation à quelques idéogrammes (kanjis) simples. Présentation de quelques aspects de culture et de civilisation japonaises. Phonétique corrective. Laboratoire individuel et dirigé. Activités socioculturelles et ateliers en après-midi (la présence à plusieurs de ces activités est obligatoire). Des frais d\'encadrement de 75 $, argent comptant (montant exact), non remboursables, sont exigés pour l\'encadrement des étudiants qui suivent les cours intensifs Pour l\'inscription aux cours intensifs de japonais, vous devez communiquer avec le secrétariat de l\'École de langues: elul@elul.ulaval.ca , téléphone: 418 656-2131 poste 2321, ou encore avec le responsable de secteur, M. Tatsuhide Mizoe: tatsuhide.mizoe@elul.ulaval.ca .','2014-03-15 14:45:23','2014-03-15 14:45:23'),
	(87462,'51042',55,'JAP-1020','201405','','a:5:{i:0;a:6:{s:4:\"type\";s:15:\"Cours en classe\";s:10:\"hour_start\";s:4:\"9:30\";s:8:\"hour_end\";s:5:\"12:20\";s:3:\"day\";s:1:\"L\";s:9:\"day_start\";s:8:\"20140526\";s:7:\"day_end\";s:8:\"20140613\";}i:1;a:6:{s:4:\"type\";s:15:\"Cours en classe\";s:10:\"hour_start\";s:4:\"9:30\";s:8:\"hour_end\";s:5:\"12:20\";s:3:\"day\";s:1:\"M\";s:9:\"day_start\";s:8:\"20140526\";s:7:\"day_end\";s:8:\"20140613\";}i:2;a:6:{s:4:\"type\";s:15:\"Cours en classe\";s:10:\"hour_start\";s:4:\"9:30\";s:8:\"hour_end\";s:5:\"12:20\";s:3:\"day\";s:1:\"R\";s:9:\"day_start\";s:8:\"20140526\";s:7:\"day_end\";s:8:\"20140613\";}i:3;a:6:{s:4:\"type\";s:15:\"Cours en classe\";s:10:\"hour_start\";s:4:\"9:30\";s:8:\"hour_end\";s:5:\"12:20\";s:3:\"day\";s:1:\"J\";s:9:\"day_start\";s:8:\"20140526\";s:7:\"day_end\";s:8:\"20140613\";}i:4;a:6:{s:4:\"type\";s:15:\"Cours en classe\";s:10:\"hour_start\";s:4:\"9:30\";s:8:\"hour_end\";s:5:\"12:20\";s:3:\"day\";s:1:\"V\";s:9:\"day_start\";s:8:\"20140526\";s:7:\"day_end\";s:8:\"20140613\";}}','Principal','Cours intensif destiné aux personnes qui ont des notions de japonais. Apprentissage de la grammaire et du vocabulaire de base dans des situations de communication simples. Apprentissage des syllabaires Hiragana et Katakana. Initiation à quelques idéogrammes (Kanjis) simples. Présentation de quelques aspects de culture et de civilisation japonaises. Phonétique corrective. Laboratoire individuel et dirigé. Activités socioculturelles et ateliers en après-midi (la présence à plusieurs de ces activités est obligatoire). Des frais d\'encadrement de 75 $, argent comptant (montant exact), non remboursables, sont exigés pour l\'encadrement des étudiants qui suivent les cours intensifs Pour l\'inscription aux cours intensifs de japonais, vous devez communiquer avec le secrétariat de l\'École de langues: elul@elul.ulaval.ca , téléphone: 418 656-2131 poste 2321, ou encore avec le responsable de secteur, M. Tatsuhide Mizoe: tatsuhide.mizoe@elul.ulaval.ca','2014-03-15 14:45:23','2014-03-15 14:45:23'),
	(87463,'80324',5,'GGR-1000','201409','','a:1:{i:0;a:4:{s:4:\"type\";s:12:\"Sur Internet\";s:3:\"day\";s:2:\" \";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}}','Principal','','2014-03-15 14:46:06','2014-03-15 14:46:06'),
	(87464,'83289',6,'HST-1008','201409','','a:1:{i:0;a:6:{s:4:\"type\";s:15:\"Cours en classe\";s:10:\"hour_start\";s:5:\"15:30\";s:8:\"hour_end\";s:5:\"18:20\";s:3:\"day\";s:1:\"M\";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}}','Principal','','2014-03-15 14:46:07','2014-03-15 14:46:07'),
	(87465,'83290',7,'HST-1300','201409','','a:1:{i:0;a:6:{s:4:\"type\";s:15:\"Cours en classe\";s:10:\"hour_start\";s:5:\"15:30\";s:8:\"hour_end\";s:5:\"18:20\";s:3:\"day\";s:1:\"R\";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}}','Principal','','2014-03-15 14:46:07','2014-03-15 14:46:07'),
	(87466,'80268',8,'COM-1002','201409','','a:1:{i:0;a:6:{s:4:\"type\";s:15:\"Cours en classe\";s:10:\"hour_start\";s:5:\"12:30\";s:8:\"hour_end\";s:5:\"15:20\";s:3:\"day\";s:1:\"M\";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}}','Principal','','2014-03-15 14:46:07','2014-03-15 14:46:07'),
	(87467,'80271',8,'COM-1002','201409','','a:1:{i:0;a:6:{s:4:\"type\";s:15:\"Cours en classe\";s:10:\"hour_start\";s:4:\"8:30\";s:8:\"hour_end\";s:5:\"11:20\";s:3:\"day\";s:1:\"R\";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}}','Principal','','2014-03-15 14:46:07','2014-03-15 14:46:07'),
	(87468,'81407',9,'COM-1010','201409','','a:1:{i:0;a:6:{s:4:\"type\";s:15:\"Cours en classe\";s:10:\"hour_start\";s:5:\"15:30\";s:8:\"hour_end\";s:5:\"18:20\";s:3:\"day\";s:1:\"J\";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}}','Principal','','2014-03-15 14:46:08','2014-03-15 14:46:08'),
	(87469,'86843',9,'COM-1010','201409','','a:1:{i:0;a:4:{s:4:\"type\";s:12:\"Sur Internet\";s:3:\"day\";s:2:\" \";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}}','Principal','','2014-03-15 14:46:08','2014-03-15 14:46:08'),
	(87470,'83809',10,'COM-2403','201409','','a:1:{i:0;a:4:{s:4:\"type\";s:12:\"Sur Internet\";s:3:\"day\";s:2:\" \";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}}','Principal','','2014-03-15 14:46:08','2014-03-15 14:46:08'),
	(87471,'80119',12,'COM-1000','201409','','a:1:{i:0;a:6:{s:4:\"type\";s:15:\"Cours en classe\";s:10:\"hour_start\";s:5:\"15:30\";s:8:\"hour_end\";s:5:\"18:20\";s:3:\"day\";s:1:\"L\";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}}','Principal','','2014-03-15 14:46:09','2014-03-15 14:46:09'),
	(87472,'88248',12,'COM-1000','201409','','a:1:{i:0;a:6:{s:4:\"type\";s:15:\"Cours en classe\";s:10:\"hour_start\";s:5:\"12:30\";s:8:\"hour_end\";s:5:\"15:20\";s:3:\"day\";s:1:\"J\";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}}','Principal','','2014-03-15 14:46:09','2014-03-15 14:46:09'),
	(87473,'80296',12,'COM-1000','201409','','a:1:{i:0;a:4:{s:4:\"type\";s:12:\"Sur Internet\";s:3:\"day\";s:2:\" \";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}}','Principal','','2014-03-15 14:46:09','2014-03-15 14:46:09'),
	(87474,'82200',13,'COM-1011','201409','','a:1:{i:0;a:6:{s:4:\"type\";s:15:\"Cours en classe\";s:10:\"hour_start\";s:5:\"12:30\";s:8:\"hour_end\";s:5:\"15:20\";s:3:\"day\";s:1:\"R\";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}}','Principal','','2014-03-15 14:46:09','2014-03-15 14:46:09'),
	(87475,'82203',13,'COM-1011','201409','','a:1:{i:0;a:4:{s:4:\"type\";s:12:\"Sur Internet\";s:3:\"day\";s:2:\" \";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}}','Principal','','2014-03-15 14:46:09','2014-03-15 14:46:09'),
	(87476,'91234',14,'COM-1003','201409','','a:1:{i:0;a:4:{s:4:\"type\";s:12:\"Sur Internet\";s:3:\"day\";s:2:\" \";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}}','Principal','','2014-03-15 14:46:09','2014-03-15 14:46:09'),
	(87477,'82058',15,'COM-1500','201409','','a:1:{i:0;a:6:{s:4:\"type\";s:15:\"Cours en classe\";s:10:\"hour_start\";s:5:\"15:30\";s:8:\"hour_end\";s:5:\"18:20\";s:3:\"day\";s:1:\"L\";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}}','Principal','','2014-03-15 14:46:10','2014-03-15 14:46:10'),
	(87478,'82060',15,'COM-1500','201409','','a:1:{i:0;a:6:{s:4:\"type\";s:15:\"Cours en classe\";s:10:\"hour_start\";s:5:\"12:30\";s:8:\"hour_end\";s:5:\"15:20\";s:3:\"day\";s:1:\"M\";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}}','Principal','','2014-03-15 14:46:10','2014-03-15 14:46:10'),
	(87479,'82062',15,'COM-1500','201409','','a:1:{i:0;a:6:{s:4:\"type\";s:15:\"Cours en classe\";s:10:\"hour_start\";s:5:\"18:30\";s:8:\"hour_end\";s:5:\"21:20\";s:3:\"day\";s:1:\"M\";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}}','Principal','','2014-03-15 14:46:10','2014-03-15 14:46:10'),
	(87480,'82065',15,'COM-1500','201409','','a:1:{i:0;a:6:{s:4:\"type\";s:15:\"Cours en classe\";s:10:\"hour_start\";s:5:\"18:30\";s:8:\"hour_end\";s:5:\"21:20\";s:3:\"day\";s:1:\"R\";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}}','Principal','','2014-03-15 14:46:10','2014-03-15 14:46:10'),
	(87481,'82066',15,'COM-1500','201409','','a:1:{i:0;a:6:{s:4:\"type\";s:15:\"Cours en classe\";s:10:\"hour_start\";s:5:\"12:30\";s:8:\"hour_end\";s:5:\"15:20\";s:3:\"day\";s:1:\"J\";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}}','Principal','','2014-03-15 14:46:10','2014-03-15 14:46:10'),
	(87482,'82093',15,'COM-1500','201409','','a:1:{i:0;a:6:{s:4:\"type\";s:15:\"Cours en classe\";s:10:\"hour_start\";s:5:\"12:30\";s:8:\"hour_end\";s:5:\"15:20\";s:3:\"day\";s:1:\"L\";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}}','Principal','','2014-03-15 14:46:10','2014-03-15 14:46:10'),
	(87483,'91267',16,'COM-2000','201409','','a:1:{i:0;a:4:{s:4:\"type\";s:12:\"Sur Internet\";s:3:\"day\";s:2:\" \";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}}','Principal','','2014-03-15 14:46:10','2014-03-15 14:46:10'),
	(87484,'91500',16,'COM-2000','201409','','a:1:{i:0;a:4:{s:4:\"type\";s:12:\"Sur Internet\";s:3:\"day\";s:2:\" \";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}}','Principal','','2014-03-15 14:46:10','2014-03-15 14:46:10'),
	(87485,'81427',17,'COM-2150','201409','','a:1:{i:0;a:6:{s:4:\"type\";s:15:\"Cours en classe\";s:10:\"hour_start\";s:5:\"12:30\";s:8:\"hour_end\";s:5:\"15:20\";s:3:\"day\";s:1:\"L\";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}}','Principal','','2014-03-15 14:46:11','2014-03-15 14:46:11'),
	(87486,'81438',18,'COM-2300','201409','','a:1:{i:0;a:6:{s:4:\"type\";s:15:\"Cours en classe\";s:10:\"hour_start\";s:4:\"8:30\";s:8:\"hour_end\";s:5:\"11:20\";s:3:\"day\";s:1:\"J\";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}}','Principal','','2014-03-15 14:46:11','2014-03-15 14:46:11'),
	(87487,'81721',19,'COM-2301','201409','','a:1:{i:0;a:6:{s:4:\"type\";s:15:\"Cours en classe\";s:10:\"hour_start\";s:4:\"8:30\";s:8:\"hour_end\";s:5:\"11:20\";s:3:\"day\";s:1:\"M\";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}}','Principal','','2014-03-15 14:46:11','2014-03-15 14:46:11'),
	(87488,'92214',20,'COM-2303','201409','','a:1:{i:0;a:4:{s:4:\"type\";s:12:\"Sur Internet\";s:3:\"day\";s:2:\" \";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}}','Principal','','2014-03-15 14:46:12','2014-03-15 14:46:12'),
	(87489,'81444',21,'COM-2400','201409','','a:1:{i:0;a:6:{s:4:\"type\";s:15:\"Cours en classe\";s:10:\"hour_start\";s:4:\"8:30\";s:8:\"hour_end\";s:5:\"11:20\";s:3:\"day\";s:1:\"J\";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}}','Principal','','2014-03-15 14:46:12','2014-03-15 14:46:12'),
	(87490,'82335',22,'COM-4001','201409','','a:1:{i:0;a:6:{s:4:\"type\";s:15:\"Cours en classe\";s:10:\"hour_start\";s:4:\"8:30\";s:8:\"hour_end\";s:5:\"11:20\";s:3:\"day\";s:1:\"M\";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}}','Principal','','2014-03-15 14:46:12','2014-03-15 14:46:12'),
	(87491,'92322',23,'COM-4150','201409','','a:1:{i:0;a:4:{s:4:\"type\";s:12:\"Sur Internet\";s:3:\"day\";s:2:\" \";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}}','Principal','','2014-03-15 14:46:13','2014-03-15 14:46:13'),
	(87492,'83587',25,'ESP-1000','201409','','a:1:{i:0;a:6:{s:4:\"type\";s:15:\"Cours en classe\";s:10:\"hour_start\";s:5:\"12:30\";s:8:\"hour_end\";s:5:\"13:50\";s:3:\"day\";s:1:\"R\";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}}','Principal','','2014-03-15 14:46:16','2014-03-15 14:46:16'),
	(87493,'83598',25,'ESP-1000','201409','','a:1:{i:0;a:6:{s:4:\"type\";s:7:\"Atelier\";s:10:\"hour_start\";s:5:\"14:00\";s:8:\"hour_end\";s:5:\"15:20\";s:3:\"day\";s:1:\"R\";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}}','Principal','','2014-03-15 14:46:16','2014-03-15 14:46:16'),
	(87494,'83608',25,'ESP-1000','201409','','a:1:{i:0;a:6:{s:4:\"type\";s:7:\"Atelier\";s:10:\"hour_start\";s:5:\"14:00\";s:8:\"hour_end\";s:5:\"15:20\";s:3:\"day\";s:1:\"R\";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}}','Principal','','2014-03-15 14:46:16','2014-03-15 14:46:16'),
	(87495,'89874',27,'ESG-3100','201409','','a:5:{i:0;a:6:{s:4:\"type\";s:26:\"Classe virtuelle synchrone\";s:10:\"hour_start\";s:4:\"8:30\";s:8:\"hour_end\";s:5:\"12:00\";s:3:\"day\";s:1:\"R\";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}i:1;a:6:{s:4:\"type\";s:26:\"Classe virtuelle synchrone\";s:10:\"hour_start\";s:5:\"15:00\";s:8:\"hour_end\";s:5:\"20:00\";s:3:\"day\";s:1:\"R\";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}i:2;a:6:{s:4:\"type\";s:26:\"Classe virtuelle synchrone\";s:10:\"hour_start\";s:5:\"18:30\";s:8:\"hour_end\";s:5:\"21:30\";s:3:\"day\";s:1:\"R\";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}i:3;a:6:{s:4:\"type\";s:26:\"Classe virtuelle synchrone\";s:10:\"hour_start\";s:5:\"18:30\";s:8:\"hour_end\";s:5:\"21:30\";s:3:\"day\";s:1:\"R\";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}i:4;a:4:{s:4:\"type\";s:26:\"Classe virtuelle synchrone\";s:3:\"day\";s:2:\" \";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}}','Principal','','2014-03-15 14:46:17','2014-03-15 14:46:17'),
	(87496,'91110',28,'ESP-2000','201409','','a:2:{i:0;a:4:{s:4:\"type\";s:12:\"Sur Internet\";s:3:\"day\";s:2:\" \";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}i:1;a:6:{s:4:\"type\";s:26:\"Classe virtuelle synchrone\";s:10:\"hour_start\";s:4:\"9:00\";s:8:\"hour_end\";s:5:\"10:30\";s:3:\"day\";s:1:\"V\";s:9:\"day_start\";s:8:\"20141212\";s:7:\"day_end\";s:8:\"20141212\";}}','Principal','','2014-03-15 14:46:17','2014-03-15 14:46:17'),
	(87497,'83640',29,'ESP-2002','201409','','a:1:{i:0;a:6:{s:4:\"type\";s:15:\"Cours en classe\";s:10:\"hour_start\";s:5:\"18:30\";s:8:\"hour_end\";s:5:\"21:20\";s:3:\"day\";s:1:\"M\";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}}','Principal','','2014-03-15 14:46:17','2014-03-15 14:46:17'),
	(87498,'89463',34,'ANT-1203','201409','','a:1:{i:0;a:4:{s:4:\"type\";s:12:\"Sur Internet\";s:3:\"day\";s:2:\" \";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}}','Principal','','2014-03-15 14:46:18','2014-03-15 14:46:18'),
	(87499,'83637',35,'ESP-2001','201409','','a:1:{i:0;a:6:{s:4:\"type\";s:15:\"Cours en classe\";s:10:\"hour_start\";s:5:\"12:30\";s:8:\"hour_end\";s:5:\"15:20\";s:3:\"day\";s:1:\"J\";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}}','Principal','','2014-03-15 14:46:19','2014-03-15 14:46:19'),
	(87500,'92242',39,'ESP-2007','201409','','a:1:{i:0;a:6:{s:4:\"type\";s:15:\"Cours en classe\";s:10:\"hour_start\";s:5:\"15:30\";s:8:\"hour_end\";s:5:\"18:20\";s:3:\"day\";s:1:\"J\";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}}','Principal','','2014-03-15 14:46:20','2014-03-15 14:46:20'),
	(87501,'88974',42,'GIE-4038','201409','','a:1:{i:0;a:6:{s:4:\"type\";s:15:\"Cours en classe\";s:10:\"hour_start\";s:4:\"8:30\";s:8:\"hour_end\";s:5:\"11:20\";s:3:\"day\";s:1:\"V\";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}}','Principal','','2014-03-15 14:46:20','2014-03-15 14:46:20'),
	(87502,'81502',43,'GGR-2502','201409','','a:1:{i:0;a:6:{s:4:\"type\";s:15:\"Cours en classe\";s:10:\"hour_start\";s:4:\"8:30\";s:8:\"hour_end\";s:5:\"11:20\";s:3:\"day\";s:1:\"R\";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}}','Principal','','2014-03-15 14:46:21','2014-03-15 14:46:21'),
	(87503,'81727',46,'POL-1003','201409','','a:1:{i:0;a:6:{s:4:\"type\";s:15:\"Cours en classe\";s:10:\"hour_start\";s:4:\"8:30\";s:8:\"hour_end\";s:5:\"11:20\";s:3:\"day\";s:1:\"R\";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}}','Principal','','2014-03-15 14:46:22','2014-03-15 14:46:22'),
	(87504,'82261',47,'POL-1005','201409','','a:1:{i:0;a:4:{s:4:\"type\";s:12:\"Sur Internet\";s:3:\"day\";s:2:\" \";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}}','Principal','','2014-03-15 14:46:22','2014-03-15 14:46:22'),
	(87505,'89040',48,'POL-2326','201409','','a:1:{i:0;a:4:{s:4:\"type\";s:12:\"Sur Internet\";s:3:\"day\";s:2:\" \";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}}','Principal','','2014-03-15 14:46:22','2014-03-15 14:46:22'),
	(87506,'81831',52,'JAP-2010','201409','','a:1:{i:0;a:6:{s:4:\"type\";s:15:\"Cours en classe\";s:10:\"hour_start\";s:5:\"15:30\";s:8:\"hour_end\";s:5:\"18:20\";s:3:\"day\";s:1:\"R\";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}}','Principal','','2014-03-15 14:46:24','2014-03-15 14:46:24'),
	(87507,'81834',52,'JAP-2010','201409','','a:1:{i:0;a:6:{s:4:\"type\";s:15:\"Cours en classe\";s:10:\"hour_start\";s:5:\"18:30\";s:8:\"hour_end\";s:5:\"21:20\";s:3:\"day\";s:1:\"R\";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}}','Principal','','2014-03-15 14:46:24','2014-03-15 14:46:24'),
	(87508,'81827',54,'JAP-1010','201409','','a:1:{i:0;a:6:{s:4:\"type\";s:15:\"Cours en classe\";s:10:\"hour_start\";s:5:\"12:30\";s:8:\"hour_end\";s:5:\"15:20\";s:3:\"day\";s:1:\"M\";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}}','Principal','','2014-03-15 14:46:24','2014-03-15 14:46:24'),
	(87509,'81828',54,'JAP-1010','201409','','a:1:{i:0;a:6:{s:4:\"type\";s:15:\"Cours en classe\";s:10:\"hour_start\";s:5:\"18:30\";s:8:\"hour_end\";s:5:\"21:20\";s:3:\"day\";s:1:\"M\";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}}','Principal','','2014-03-15 14:46:24','2014-03-15 14:46:24'),
	(87510,'81830',54,'JAP-1010','201409','','a:1:{i:0;a:6:{s:4:\"type\";s:15:\"Cours en classe\";s:10:\"hour_start\";s:5:\"12:30\";s:8:\"hour_end\";s:5:\"15:20\";s:3:\"day\";s:1:\"R\";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}}','Principal','','2014-03-15 14:46:24','2014-03-15 14:46:24'),
	(87511,'88222',54,'JAP-1010','201409','','a:1:{i:0;a:6:{s:4:\"type\";s:15:\"Cours en classe\";s:10:\"hour_start\";s:5:\"12:30\";s:8:\"hour_end\";s:5:\"15:20\";s:3:\"day\";s:1:\"J\";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}}','Principal','','2014-03-15 14:46:24','2014-03-15 14:46:24'),
	(87512,'84786',57,'JAP-3010','201409','','a:1:{i:0;a:6:{s:4:\"type\";s:15:\"Cours en classe\";s:10:\"hour_start\";s:5:\"18:30\";s:8:\"hour_end\";s:5:\"21:20\";s:3:\"day\";s:1:\"J\";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}}','Principal','','2014-03-15 14:46:25','2014-03-15 14:46:25'),
	(87513,'81547',60,'GGR-2504','201409','','a:1:{i:0;a:6:{s:4:\"type\";s:15:\"Cours en classe\";s:10:\"hour_start\";s:4:\"8:30\";s:8:\"hour_end\";s:5:\"11:20\";s:3:\"day\";s:1:\"M\";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}}','Principal','','2014-03-15 14:46:26','2014-03-15 14:46:26'),
	(87514,'92282',62,'HST-1355','201409','','a:1:{i:0;a:6:{s:4:\"type\";s:15:\"Cours en classe\";s:10:\"hour_start\";s:5:\"15:30\";s:8:\"hour_end\";s:5:\"18:20\";s:3:\"day\";s:1:\"R\";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}}','Principal','','2014-03-15 14:46:26','2014-03-15 14:46:26'),
	(87515,'91263',65,'POL-2316','201409','','a:1:{i:0;a:6:{s:4:\"type\";s:15:\"Cours en classe\";s:10:\"hour_start\";s:5:\"12:30\";s:8:\"hour_end\";s:5:\"15:20\";s:3:\"day\";s:1:\"R\";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}}','Principal','','2014-03-15 14:46:27','2014-03-15 14:46:27'),
	(87516,'83622',67,'ESP-1004','201409','','a:1:{i:0;a:6:{s:4:\"type\";s:15:\"Cours en classe\";s:10:\"hour_start\";s:5:\"18:30\";s:8:\"hour_end\";s:5:\"21:20\";s:3:\"day\";s:1:\"L\";s:9:\"day_start\";s:8:\"20140902\";s:7:\"day_end\";s:8:\"20141212\";}}','Principal','','2014-03-15 14:46:27','2014-03-15 14:46:27');

/*!40000 ALTER TABLE `classes` ENABLE KEYS */;
UNLOCK TABLES;


# Affichage de la table classes_spots
# ------------------------------------------------------------

DROP TABLE IF EXISTS `classes_spots`;

CREATE TABLE `classes_spots` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `class_id` int(11) unsigned NOT NULL,
  `nrc` varchar(5) NOT NULL DEFAULT '',
  `total` smallint(4) NOT NULL,
  `registered` smallint(4) NOT NULL,
  `remaining` smallint(4) NOT NULL,
  `waiting_total` smallint(4) NOT NULL,
  `waiting_registered` smallint(4) NOT NULL,
  `waiting_remaining` smallint(4) NOT NULL,
  `last_update` varchar(20) NOT NULL DEFAULT '',
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `nrc` (`nrc`),
  KEY `class_id` (`class_id`),
  CONSTRAINT `classes_spots_nrc` FOREIGN KEY (`nrc`) REFERENCES `classes` (`nrc`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `classes_spots` WRITE;
/*!40000 ALTER TABLE `classes_spots` DISABLE KEYS */;

INSERT INTO `classes_spots` (`id`, `class_id`, `nrc`, `total`, `registered`, `remaining`, `waiting_total`, `waiting_registered`, `waiting_remaining`, `last_update`, `created`, `updated`)
VALUES
	(1,87451,'50944',999,42,957,0,0,0,'1394909159','2014-03-15 14:45:59','2014-03-15 14:45:59'),
	(2,87485,'81427',120,0,120,20,0,20,'1394909268','2014-03-15 14:47:48','2014-03-15 14:47:48'),
	(3,87488,'92214',150,0,150,0,0,0,'1394909275','2014-03-15 14:47:55','2014-03-15 14:47:55');

/*!40000 ALTER TABLE `classes_spots` ENABLE KEYS */;
UNLOCK TABLES;


# Affichage de la table course_subjects
# ------------------------------------------------------------

DROP TABLE IF EXISTS `course_subjects`;

CREATE TABLE `course_subjects` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(3) NOT NULL DEFAULT '',
  `title` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `course_subjects` WRITE;
/*!40000 ALTER TABLE `course_subjects` DISABLE KEYS */;

INSERT INTO `course_subjects` (`id`, `code`, `title`)
VALUES
	(1,'ACT','Actuariat'),
	(2,'AEE','Admin. et éval. en éducation'),
	(3,'ADM','Administration'),
	(4,'ADS','Administration scolaire'),
	(5,'APR','Affaires publ. et représ. int.'),
	(6,'AGC','Agroéconomie'),
	(7,'AGF','Agroforesterie'),
	(8,'AGN','Agronomie'),
	(9,'ALL','Allemand'),
	(10,'AME','Aménagement du territoire'),
	(11,'ANM','Anatomie'),
	(12,'ANG','Anglais'),
	(13,'ANL','Anglais (langue)'),
	(14,'ANT','Anthropologie'),
	(15,'ARA','Arabe'),
	(16,'ARL','Archéologie'),
	(17,'ARC','Architecture'),
	(18,'GAD','Archivistique'),
	(19,'ARD','Art dramatique'),
	(20,'ANI','Art et science de l\'animation'),
	(21,'ART','Arts'),
	(22,'ARV','Arts visuels'),
	(23,'ASR','Assurances'),
	(24,'BCM','Biochimie'),
	(25,'BCX','Biochimie médicale'),
	(26,'BIF','Bio-informatique'),
	(27,'BIO','Biologie'),
	(28,'BMO','Biologie cell. et moléculaire'),
	(29,'BVG','Biologie végétale'),
	(30,'BPH','Biophotonique'),
	(31,'CAT','Catéchèse'),
	(32,'CHM','Chimie'),
	(33,'CHN','Chinois'),
	(34,'CIN','Cinéma'),
	(35,'COM','Communication'),
	(36,'CTB','Comptabilité'),
	(37,'CNS','Consommation'),
	(38,'CSO','Counseling et orientation'),
	(39,'CRI','Criminologie'),
	(40,'DES','Design graphique'),
	(41,'DDU','Développement durable'),
	(42,'DVE','Développement économique'),
	(43,'DRI','Développement rural intégré'),
	(44,'DID','Didactique'),
	(45,'DRT','Droit'),
	(46,'ERU','Économie rurale'),
	(47,'ECN','Économique'),
	(48,'EDC','Éducation'),
	(49,'EPS','Éducation physique'),
	(50,'ENP','Enseignement préscol. et prim.'),
	(51,'ENS','Enseignement secondaire'),
	(52,'EER','Ens. en éthique et cult. rel.'),
	(53,'ENT','Entrepreneuriat'),
	(54,'ENV','Environnement'),
	(55,'EPM','Épidémiologie'),
	(56,'EGN','Ergonomie'),
	(57,'ERG','Ergothérapie'),
	(58,'ESP','Espagnol'),
	(59,'ESG','Espagnol (langue)'),
	(60,'ETH','Éthique'),
	(61,'EFN','Ethn. francoph. en Am. du N.'),
	(62,'ETN','Ethnologie'),
	(63,'EAN','Études anciennes'),
	(64,'FEM','Études féministes'),
	(65,'ETI','Études internationales'),
	(66,'PTR','Études patrimoniales'),
	(67,'GPL','Études pluridisciplinaires'),
	(68,'EXD','Examen de doctorat'),
	(69,'FOR','Foresterie'),
	(70,'FIS','Formation interdisc. en santé'),
	(71,'FPT','Formation prof. et technique'),
	(72,'FRN','Français'),
	(73,'FLE','Français lang. étr. ou seconde'),
	(74,'FLS','Français langue seconde'),
	(75,'GAA','Génie agroalimentaire'),
	(76,'GAE','Génie agroenvironnemental'),
	(77,'GAL','Génie alimentaire'),
	(78,'GCH','Génie chimique'),
	(79,'GCI','Génie civil'),
	(80,'GPG','Génie de la plasturgie'),
	(81,'GEX','Génie des eaux'),
	(82,'GEL','Génie électrique'),
	(83,'GSC','Génie et sciences'),
	(84,'GGL','Génie géologique'),
	(85,'GIN','Génie industriel'),
	(86,'GIF','Génie informatique'),
	(87,'GLO','Génie logiciel'),
	(88,'GMC','Génie mécanique'),
	(89,'GML','Génie métallurgique'),
	(90,'GMN','Génie minier'),
	(91,'GPH','Génie physique'),
	(92,'GGR','Géographie'),
	(93,'GLG','Géologie'),
	(94,'GMT','Géomatique'),
	(95,'GSO','Gestion des opérations'),
	(96,'GRH','Gestion des ress. humaines'),
	(97,'GSE','Gestion économique'),
	(98,'GSF','Gestion financière'),
	(99,'GIE','Gestion internationale'),
	(100,'GUI','Gest. urbaine et immobilière'),
	(101,'GRC','Grec'),
	(102,'HST','Histoire'),
	(103,'HAR','Histoire de l\'art'),
	(104,'IFT','Informatique'),
	(105,'IED','Intervention éducative'),
	(106,'ITL','Italien'),
	(107,'JAP','Japonais'),
	(108,'JOU','Journalisme'),
	(109,'KIN','Kinésiologie'),
	(110,'LMO','Langues modernes'),
	(111,'LOA','Langues orientales anciennes'),
	(112,'LAT','Latin'),
	(113,'LNG','Linguistique'),
	(114,'LIT','Littérature'),
	(115,'MNG','Management'),
	(116,'MRK','Marketing'),
	(117,'MAT','Mathématiques'),
	(118,'MED','Médecine'),
	(119,'MDD','Médecine dentaire'),
	(120,'MDX','Médecine expérimentale'),
	(121,'MEV','Mesure et évaluation'),
	(122,'MQT','Méthodes quantitatives'),
	(123,'MET','Méthodologie'),
	(124,'MCB','Microbiologie'),
	(125,'MSL','Muséologie'),
	(126,'MUS','Musique'),
	(127,'NRB','Neurobiologie'),
	(128,'NUT','Nutrition'),
	(129,'OCE','Océanographie'),
	(130,'OPV','Optique et santé de la vue'),
	(131,'ORT','Orthophonie'),
	(132,'PST','Pastorale'),
	(133,'PAT','Pathologie'),
	(134,'PHA','Pharmacie'),
	(135,'PHC','Pharmacologie'),
	(136,'PHI','Philosophie'),
	(137,'PHS','Physiologie'),
	(138,'PHT','Physiothérapie'),
	(139,'PHY','Physique'),
	(140,'PLG','Phytologie'),
	(141,'PFP','Planif. financière personnelle'),
	(142,'POR','Portugais'),
	(143,'PSA','Psychiatrie'),
	(144,'PSE','Psychoéducation'),
	(145,'PSY','Psychologie'),
	(146,'PPG','Psychopédagogie'),
	(147,'RLT','Relations industrielles'),
	(148,'RUS','Russe'),
	(149,'SAT','Santé au travail'),
	(150,'SAC','Santé communautaire'),
	(151,'POL','Science politique'),
	(152,'SAN','Sciences animales'),
	(153,'SBM','Sciences biomédicales'),
	(154,'SCR','Sciences des religions'),
	(155,'SBO','Sciences du bois'),
	(156,'SCG','Sciences géomatiques'),
	(157,'SIN','Sciences infirmières'),
	(158,'STC','Sciences, techniques civilis.'),
	(159,'STA','Sciences, technologie aliments'),
	(160,'SVS','Service social'),
	(161,'SOC','Sociologie'),
	(162,'SLS','Sols'),
	(163,'STT','Statistique'),
	(164,'SIO','Système information organisat.'),
	(165,'TEN','Technologie éducative'),
	(166,'THT','Théâtre'),
	(167,'THL','Théologie'),
	(168,'TCF','Thérapie conjug. et familiale'),
	(169,'TRE','Thèse, recherche, mémoire'),
	(170,'TXM','Toxicomanie'),
	(171,'TRD','Traduction'),
	(172,'TED','Troubles envahissants du dév.');

/*!40000 ALTER TABLE `course_subjects` ENABLE KEYS */;
UNLOCK TABLES;


# Affichage de la table courses
# ------------------------------------------------------------

DROP TABLE IF EXISTS `courses`;

CREATE TABLE `courses` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(10) NOT NULL DEFAULT '',
  `title` tinytext NOT NULL,
  `description` text NOT NULL,
  `credits` smallint(3) unsigned NOT NULL,
  `hours_theory` smallint(3) unsigned NOT NULL,
  `hours_lab` smallint(3) unsigned NOT NULL,
  `hours_other` smallint(3) NOT NULL,
  `restrictions` text NOT NULL,
  `prerequisites` text NOT NULL,
  `cycle` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `faculty` varchar(32) NOT NULL DEFAULT '',
  `department` varchar(32) NOT NULL,
  `av201109` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `av201201` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `av201205` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `av201209` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `av201301` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `checkup_201301` int(11) NOT NULL,
  `av201305` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `checkup_201305` int(11) NOT NULL,
  `av201309` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `checkup_201309` int(11) NOT NULL,
  `av201401` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `checkup_201401` int(11) NOT NULL,
  `av201405` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `checkup_201405` int(11) NOT NULL,
  `av201409` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `checkup_201409` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `cycle` (`cycle`),
  KEY `av201301` (`av201301`),
  KEY `av201305` (`av201305`),
  KEY `av201309` (`av201309`),
  KEY `av201209` (`av201209`),
  KEY `av201401` (`av201401`),
  KEY `av201409` (`av201409`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `courses` WRITE;
/*!40000 ALTER TABLE `courses` DISABLE KEYS */;

INSERT INTO `courses` (`id`, `code`, `title`, `description`, `credits`, `hours_theory`, `hours_lab`, `hours_other`, `restrictions`, `prerequisites`, `cycle`, `faculty`, `department`, `av201109`, `av201201`, `av201205`, `av201209`, `av201301`, `checkup_201301`, `av201305`, `checkup_201305`, `av201309`, `checkup_201309`, `av201401`, `checkup_201401`, `av201405`, `checkup_201405`, `av201409`, `checkup_201409`, `created`, `updated`)
VALUES
	(1,'ETN-1001','Exercices méthodologiques','Connaissance pratique des ressources bibliographiques. Apprentissage de la lecture et de l\'écriture pour les comptes rendus. Techniques de recherche de l\'information, de son classement et de sa conservation. Initiation en archives. Conduite de toutes les opérations d\'une dissertation: délimitation du sujet, hypothèse, recherche, plan de travail, rédaction, présentation.',3,3,3,3,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','',1,'Lettres et sciences humaines','FLSH-Dép. sc. historiques',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909112,0,1394909165,'2014-03-15 14:29:55','2014-03-15 14:29:55'),
	(2,'HST-3020','Ordre mondial au XXIe siècle : problématiques et perspectives','Ce cours vise à permettre à l\'étudiant d\'aborder plusieurs problématiques internationales au XXIe siècle dans une perspective pluridisciplinaire lui permettant d\'intégrer les connaissances acquises au cours de son cheminement. Les États-Unis dans le nouvel ordre mondial; les problématiques islamiques; le multilatéralisme; les mouvements alter/antimondialistes; l\'Union européenne, la Chine et l\'Inde; les nouveaux paradigmes conceptuels.',3,3,0,6,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','HST 1008 ET  HST 1300 ET   Crédits exigés : 36',1,'Lettres et sciences humaines','FLSH-Dép. sc. historiques',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909112,0,1394909166,'2014-03-15 14:29:56','2014-03-15 14:29:56'),
	(3,'MET-1000','Recherche et lecture critique de l\'information','Présentation critique de différents types d\'information. Connaissance pratique des ressources bibliographiques, de la bibliothèque et des technologies de l\'information usuelles. Techniques élémentaires de recherche, d\'évaluation, de classement et de conservation de l\'information sur divers supports (papier, électroniques, etc.). Lecture critique de divers documents contemporains (textes publiés, textes sur le Web, images fixes et animées, etc.) tenant compte du contexte de production, du support de diffusion et du public visé par le document.',3,2,1,6,'Doit être inscrit à l\'un des programmes suivants:     <br />      B études int.-langues modernes<br />Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','',1,'Lettres et sciences humaines','FLSH-Dép. sc. historiques',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909112,0,1394909166,'2014-03-15 14:29:56','2014-03-15 14:29:56'),
	(4,'MET-1001','Rédaction de documents stratégiques','Conduite systématique de toutes les opérations nécessaires à la rédaction de documents stratégiques de diverses natures (communiqués, résumés, rapports, travaux longs, etc.): délimitation du sujet, recherche élaborée de la documentation, analyse et synthèse de l\'information, plan de rédaction, rédaction proprement dite, révision et adaptation selon les objectifs de diffusion visés.',3,1,2,6,'Doit être inscrit à l\'un des programmes suivants:     <br />      B études int.-langues modernes<br />Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','',1,'Lettres et sciences humaines','FLSH-Dép. sc. historiques',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909113,0,1394909166,'2014-03-15 14:29:57','2014-03-15 14:29:57'),
	(5,'GGR-1000','Introduction à la carte du monde','Étude raisonnée de la configuration et de la position relative des grandes régions du monde. Analyse de leur contenu géographique distinct d\'une part et des traits dominants et communs d\'autre part. Attention particulière aux fondements historiques de la formation des États, à l\'actualité politique et à ses racines.',3,2,2,5,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','',1,'Foresterie, géographie géomat.','Géographie',0,0,0,0,0,0,0,0,0,0,0,0,1,1394909113,1,1394909166,'2014-03-15 14:29:57','2014-03-15 14:29:57'),
	(6,'HST-1008','Le monde aux XIXe et XXe siècles','Analyse des grands courants du monde contemporain (industrialisation, libéralisme, nationalisme, impérialisme et décolonisation); acquisition de connaissances générales sur les thèmes suivants: polarisation entre un monde communiste et un monde capitaliste, développement et sous-développement.',3,3,0,6,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','',1,'Lettres et sciences humaines','FLSH-Dép. sc. historiques',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909113,1,1394909167,'2014-03-15 14:29:57','2014-03-15 14:29:57'),
	(7,'HST-1300','État du monde : environnement économique et historique','À partir de l\'état du monde actuel, ce cours revient sur l\'environnement économique et historique des grands problèmes contemporains.',3,1,2,6,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','',1,'Lettres et sciences humaines','FLSH-Dép. sc. historiques',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909113,1,1394909167,'2014-03-15 14:29:58','2014-03-15 14:29:58'),
	(8,'COM-1002','Connaissance des médias québécois','Description du système médiatique québécois et de ses modes de fonctionnement. Aspects économiques et culturels, encadrement législatif et réglementaire. Aperçu des principaux débats contemporains relatifs aux médias.',3,3,0,6,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','',1,'Lettres et sciences humaines','FLSH-Dép. inform communication',0,0,0,0,0,0,0,0,0,0,0,0,1,1394909114,1,1394909167,'2014-03-15 14:29:58','2014-03-15 14:29:58'),
	(9,'COM-1010','Histoire des médias au Québec','Description des facteurs favorisant l\'apparition et l\'évolution des médias en lien avec celles du journalisme, de la publicité et des relations publiques, dans le contexte sociopolitique et économique du Québec. Présentation des principaux journaux et sociétés de radiodiffusion ainsi que des personnalités de l\'histoire des domaines professionnels susmentionnés.',3,3,0,6,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','',1,'Lettres et sciences humaines','FLSH-Dép. inform communication',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909114,1,1394909168,'2014-03-15 14:29:59','2014-03-15 14:29:59'),
	(10,'COM-2403','Planification et achats dans les médias','Formation pratique en achat et planification médias dans une perspective de marketing social. Utilisation des principales banques de données, établissement d\'un calendrier de diffusion, élaboration de stratégies respectant le budget alloué. À la fin de ce cours, l\'étudiant sera en mesure de faire un plan médias et de l\'évaluer.',3,3,0,6,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','',1,'Lettres et sciences humaines','FLSH-Dép. inform communication',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909114,1,1394909168,'2014-03-15 14:29:59','2014-03-15 14:29:59'),
	(11,'COM-1050','ComViz: communiquer par l\'image en pub et en journalisme','Autoapprentissage entièrement sur Internet: lectures, exercices interactifs. Évaluations, dossier. Dix modules, dont quatre sur la plastique, deux sur l\'image journalistique, deux sur l\'image publicitaire, un sur la mise en page et un sur la loi.',3,0,4,5,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','',1,'Lettres et sciences humaines','FLSH-Dép. inform communication',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909114,0,1394909168,'2014-03-15 14:29:59','2014-03-15 14:29:59'),
	(12,'COM-1000','Introduction à la communication','Initiation aux concepts et théories de la communication à l\'aide de l\'expérience personnelle quotidienne des phénomènes communicationnels sur les plans individuel, de groupe et de masse. Développement de la capacité d\'analyse et acquisition des connaissances nécessaires à des apprentissages plus avancés ou spécialisés.',3,3,0,6,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','',1,'Lettres et sciences humaines','FLSH-Dép. inform communication',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909114,1,1394909169,'2014-03-15 14:30:00','2014-03-15 14:30:00'),
	(13,'COM-1011','Psychosociologie de la communication','Étude de l\'interaction humaine permettant de comprendre les procédés de persuasion, les modalités de réception et d\'interprétation des contenus diffusés ou la production de significations durant les échanges médiatisés. Incidence des rapports sociaux dans la transmission de l\'information et dans les processus d\'influence. Initiation aux approches à caractère plus psychologique qui ont marqué le développement de la psychosociologie de la communication. Processus d\'interaction sous différents angles.',3,3,0,6,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','',1,'Lettres et sciences humaines','FLSH-Dép. inform communication',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909114,1,1394909169,'2014-03-15 14:30:00','2014-03-15 14:30:00'),
	(14,'COM-1003','La communication publique et ses pratiques','Caractérisation des trois pratiques de communication publique : journalisme, relations publiques et publicité sociale. Mise en évidence de leur rattachement à la communication publique. Examen de leur contribution respective au débat public.',3,3,0,6,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','',1,'Lettres et sciences humaines','FLSH-Dép. inform communication',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909114,1,1394909169,'2014-03-15 14:30:00','2014-03-15 14:30:00'),
	(15,'COM-1500','Communication orale en public','Ce cours vise à développer l\'habileté à communiquer oralement devant un groupe hétérogène. Étude des composantes verbales et non verbales nécessaires à une communication orale de qualité. Action et rétroaction orales guidées par le professeur, avec l\'appui logistique de la vidéo. Remarque - Ce cours vise la consolidation de la connaissance générale du français et la connaissance du français de niveau universitaire.',3,1,2,6,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','',1,'Lettres et sciences humaines','FLSH-École de langues',0,0,0,0,0,0,0,0,0,0,0,0,1,1394909115,1,1394909170,'2014-03-15 14:30:01','2014-03-15 14:30:01'),
	(16,'COM-2000','Argumentation et communication','Principaux procédés argumentatifs utilisés dans les différentes pratiques de communication publique. Définition opératoire, procédure de repérage de l\'argument, distinctions entre différents types d\'arguments et caractérisation de la structure argumentative.',3,2,1,6,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','',1,'Lettres et sciences humaines','FLSH-Dép. inform communication',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909115,1,1394909170,'2014-03-15 14:30:01','2014-03-15 14:30:01'),
	(17,'COM-2150','Communication et changement d\'attitude','Exposé des études américaines classiques sur le traitement de l\'information (processus cognitifs, attentionnels et mémoriels) et sur la communication persuasive : l\'influence de la source, les facteurs d\'efficacité du message, les théories de l\'équilibre, les théories du comportement planifié et de la probabilité d\'élaboration. À la fin de ce cours, l\'étudiant connaîtra les principaux modèles et théories utilisés pour la conception d\'une campagne de publicité sociale.',3,3,0,6,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','COM 1011',1,'Lettres et sciences humaines','FLSH-Dép. inform communication',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909115,1,1394909171,'2014-03-15 14:30:02','2014-03-15 14:30:02'),
	(18,'COM-2300','Introduction aux relations publiques','Initiation aux connaissances de base requises pour réaliser des stratégies de communication: connaissances de l\'organisation, de l\'environnement, de la psychologie de l\'individu, de l\'opinion publique, des médias et des techniques de communication. Les outils de recherche pour acquérir chacune de ces connaissances. Les modalités d\'évaluation des stratégies adoptées. Les relations publiques comme architecte de l\'image de l\'entreprise (publique ou privée) et outil de gestion de ses réalités.',3,3,0,6,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','',1,'Lettres et sciences humaines','FLSH-Dép. inform communication',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909115,1,1394909171,'2014-03-15 14:30:02','2014-03-15 14:30:02'),
	(19,'COM-2301','Démarche et moyens de relations publiques','Présentation des stratégies et des principaux outils, supports et techniques de communication utilisés en relations publiques.',3,3,1,5,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','COM 2300',1,'Lettres et sciences humaines','FLSH-Dép. inform communication',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909115,1,1394909171,'2014-03-15 14:30:02','2014-03-15 14:30:02'),
	(20,'COM-2303','Communication dans les organisations','Initiation aux principales théories de la communication des organisations ainsi qu\'aux enjeux et pratiques de la communication interne (journal d\'entreprise, intranet, mode formel et informel de communication interne, etc.). Caractéristiques des principaux contextes organisationnels et description des principales pratiques de gestion de la communication dans les organisations.',3,3,0,6,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','',1,'Lettres et sciences humaines','FLSH-Dép. inform communication',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909116,1,1394909172,'2014-03-15 14:30:03','2014-03-15 14:30:03'),
	(21,'COM-2400','Introduction à la publicité sociale','Marketing, marketing social, publicité sociale et phénomènes connexes. Les concepts fondamentaux et la démarche du marketing et de la publicité appliqués à la promotion des idées et des causes sociales: analyse du produit, analyse de la clientèle, définition des objectifs de marketing et de communication, évaluation des campagnes.',3,2,2,5,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','',1,'Lettres et sciences humaines','FLSH-Dép. inform communication',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909116,1,1394909172,'2014-03-15 14:30:03','2014-03-15 14:30:03'),
	(22,'COM-4001','Économie politique de la communication','Introduction à l\'économie politique dans le domaine de la communication. Enjeux actuels du système médiatique selon une perspective théorique et historique plus large.',3,3,0,6,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','COM 1002 OU  COM 1004 OU  JOU 1004',1,'Lettres et sciences humaines','FLSH-Dép. inform communication',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909116,1,1394909172,'2014-03-15 14:30:03','2014-03-15 14:30:03'),
	(23,'COM-4150','Communication interculturelle internationale','Initier à la communication entre personnes de cultures différentes. Développer la conscience, la sensibilité, les façons de penser et les habiletés nécessaires. Deux approches de formation interculturelles utilisées: générale (prépare aux interactions dans n\'importe quelle culture) et spécifique (informe sur une culture spécifique). Comparer les pratiques de communication dans une douzaine de cultures. Aborder les notions de stéréotype, préjugé, choc culturel, ethnocentrisme, ethnorelativisme, etc. Méthode pédagogique interactive: exposés, discussions, exercices, simulations, études de cas, présentations orales et vidéo, témoignages.',3,3,0,6,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','',1,'Lettres et sciences humaines','FLSH-Dép. inform communication',0,0,0,0,0,0,0,0,0,0,0,0,1,1394909116,1,1394909173,'2014-03-15 14:30:04','2014-03-15 14:30:04'),
	(24,'MNG-3113','Gérer et décider en situation de crise','Savoir faire face aux crises est un enjeu critique pour tous les gestionnaires. Mobiliser les réflexes appropriés, faire face à la presse, mettre en place des dispositifs de réponses efficaces de prévention et de pilotage font aujourd\'hui partie des habiletés que l\'on peut attendre de tout gestionnaire. Ce cours en fournit les principales bases.',3,3,0,6,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','',1,'Sciences de l\'administration','Management',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909117,0,1394909174,'2014-03-15 14:30:05','2014-03-15 14:30:05'),
	(25,'ESP-1000','Estructuras del español I','Révision des règles d\'accentuation. Révision systématique de la grammaire espagnole: étude de la phrase simple (syntagme nominal, syntagme verbal et morphologie du verbe). Analyse, description et application.',3,2,1,6,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','ESG 2020 à 3799 OU Examen Classement en espagnol avec résultat de 5 à 8',1,'Lettres et sciences humaines','FLSH-Dép. lang. ling. trad.',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909117,1,1394909176,'2014-03-15 14:30:05','2014-03-15 14:30:05'),
	(26,'ESP-1001','Estructuras del español II','Révision systématique de la grammaire espagnole. Étude du syntagme prépositionnel et de la phrase complexe. Analyse, description et application.',3,2,1,6,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','ESP 1000',1,'Lettres et sciences humaines','FLSH-Dép. lang. ling. trad.',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909117,0,1394909176,'2014-03-15 14:30:05','2014-03-15 14:30:05'),
	(27,'ESG-3100','Producción de documentos prácticos','Consolidation des compétences d\'expression écrite et orale en espagnol. Éléments de contenu: rédaction de lettres de divers types et niveaux, d\'un curriculum vitæ, de notes de service, de réponse à des appels d\'offre, organisation de réunions de travail, etc. Préparation et présentation d\'entrevues.',3,3,0,6,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','ESG 2020 à 3799 OU Examen Classement en espagnol avec résultat de 5 à 8',1,'Lettres et sciences humaines','FLSH-École de langues',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909117,1,1394909177,'2014-03-15 14:30:06','2014-03-15 14:30:06'),
	(28,'ESP-2000','Culture espagnole','Présentation des sommets de la culture espagnole péninsulaire. Ce cours a pour but de familiariser l\'étudiant avec les données historiques, politiques, économiques, sociales et culturelles. On apportera une attention particulière aux rapports de l\'art et de la littérature avec la société.',3,3,0,6,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','',1,'Lettres et sciences humaines','FLSH-Dép. littératures',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909117,1,1394909177,'2014-03-15 14:30:06','2014-03-15 14:30:06'),
	(29,'ESP-2002','El español en el mundo','La situation de l\'espagnol dans le monde: localisation, diffusion, statut et variation. Norme et usage. Initiation à la documentation tant bibliographique qu\'électronique. Exploration des ressources accessibles sur l\'autoroute électronique portant sur les études hispaniques; évaluation de la qualité de l\'information accessible; constitution d\'un recueil d\'information pertinente au domaine.',3,0,0,0,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','ESG 2020 à 3799 OU Examen Classement en espagnol avec résultat de 5 à 8',1,'Lettres et sciences humaines','FLSH-Dép. lang. ling. trad.',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909117,1,1394909177,'2014-03-15 14:30:06','2014-03-15 14:30:06'),
	(30,'ESP-2008','El español en el tiempo y la sociedad','Le changement linguistique sur l\'axe temporel : grammaire historique de l\'espagnol. Le changement sur l\'axe géographique : principaux phénomènes de variation dialectale. La variation sur l\'axe social : les dialectes dans la société; la sociolinguistique hispanique.',3,3,0,6,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','ESP 2002 ET  LNG 1901',1,'Lettres et sciences humaines','FLSH-Dép. lang. ling. trad.',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909118,0,1394909177,'2014-03-15 14:30:07','2014-03-15 14:30:07'),
	(31,'ESP-1002','Conversación','Perfectionnement de la compréhension et de l\'expression orale par l\'analyse systématique de longs métrages, de diverses origines, sur vidéo. Transcriptions de séquences; discussions sur le contenu culturel, thématique et linguistique des films; activités favorisant l\'acquisition et le réemploi des structures et du vocabulaire.',3,3,3,3,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','ESG 2020 à 3799 OU Examen Classement en espagnol avec résultat de 5 à 8',1,'Lettres et sciences humaines','FLSH-Dép. lang. ling. trad.',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909118,0,1394909178,'2014-03-15 14:30:07','2014-03-15 14:30:07'),
	(32,'ESP-1003','Redacción I','Remarque : Ce cours se donne en espagnol. Perfectionnement de la compréhension et de l\'expression écrite par des exercices de lecture, d\'analyse de textes, et de rédaction. L\'accent sera mis sur la synthèse de documents et la rédaction de rapports.',3,3,0,6,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','ESG 3100 OU  ESG 2020 OU Examen Classement en espagnol avec résultat de 5 à 8',1,'Lettres et sciences humaines','FLSH-Dép. lang. ling. trad.',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909118,0,1394909178,'2014-03-15 14:30:07','2014-03-15 14:30:07'),
	(33,'ANT-1200','Anthropologie du Mexique','Introduction à l\'ethnologie et aux dimensions sociales et culturelles de l\'économie politique du Mexique contemporain. Familiarisation générale avec un certain nombre de concepts anthropologiques et avec les thèmes suivants: les populations indigènes et leurs racines précolombiennes, les rapports entre les genres et entre les générations, le changement social lié au développement et au contexte de la mondialisation.',3,3,0,6,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','',1,'Sciences sociales','Anthropologie',0,0,0,0,0,0,0,0,0,0,0,0,1,1394909118,0,1394909178,'2014-03-15 14:30:09','2014-03-15 14:30:09'),
	(34,'ANT-1203','Anthropologie de l\'Amérique du Sud','Le cours porte essentiellement sur l\'Amérique andine et particulièrement sur la Colombie, l\'Équateur, la Bolivie et le Pérou. Parmi les thèmes qui sont abordés, on retrouve : la Conquête hier et la colonisation aujourd\'hui; les rapports de genre, la parenté, la famille, la maisonnée; l\'indianité et les mouvements sociaux; la mondialisation, le développement et les droits humains; l\'alimentation et la problématique de la santé; le rural, l\'urbain et les migrations; les guérillas, les cultures illicites, le narcotrafic et la répression.',3,3,0,6,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','',1,'Sciences sociales','Anthropologie',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909118,1,1394909178,'2014-03-15 14:30:09','2014-03-15 14:30:09'),
	(35,'ESP-2001','Las letras hispánicas : historia y sociedad','Ce cours vise à illustrer le développement historique des traditions culturelles des pays hispanophones, riches en formes, approches, influences et perspectives, tout en fournissant une connaissance de base utile à celui qui étudie les littératures en langue espagnole. Introduction méthodologique aux commentaires de textes et études des bases théoriques de l\'analyse de textes en espagnol.',3,3,0,6,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','ESG 2020 à 3799 OU Examen Classement en espagnol avec résultat de 5 à 8',1,'Lettres et sciences humaines','FLSH-Dép. littératures',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909119,1,1394909179,'2014-03-15 14:30:10','2014-03-15 14:30:10'),
	(36,'ESP-2003','Cultura de América Latina','Présentation des principaux phénomènes de la culture latino-américaine contemporaine. Ce cours a pour but de familiariser l\'étudiant avec les données historiques, politiques, économiques, sociales et culturelles. On apportera une attention toute particulière aux rapports de l\'art et de la littérature avec la société.',3,3,0,6,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','ESG 2020 à 3799 OU Examen Classement en espagnol avec résultat de 5 à 8',1,'Lettres et sciences humaines','FLSH-Dép. littératures',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909119,0,1394909179,'2014-03-15 14:30:11','2014-03-15 14:30:11'),
	(37,'ESP-2005','Literatura hispanoamericana actual','Étude des courants fondamentaux de la littérature hispano-américaine du XXe siècle jusqu\'à aujourd\'hui. Analyse de quelques uvres représentatives.',3,3,0,6,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','ESP 2001',1,'Lettres et sciences humaines','FLSH-Dép. littératures',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909119,0,1394909179,'2014-03-15 14:30:11','2014-03-15 14:30:11'),
	(38,'ESP-2006','Literatura española actual','Étude des principaux courants, avant-gardes et auteurs représentatifs de la littérature espagnole du XXe siècle jusqu\'à aujourd\'hui.',3,3,0,6,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','ESP 2001',1,'Lettres et sciences humaines','FLSH-Dép. littératures',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909119,0,1394909179,'2014-03-15 14:30:12','2014-03-15 14:30:12'),
	(39,'ESP-2007','Literatura española del medioevo al realismo','Périodes et courants fondamentaux de la littérature espagnole, de ses origines jusqu\'au XIXe siècle. Études d\'oeuvres importantes de chaque époque.',3,3,0,6,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','ESP 2001',1,'Lettres et sciences humaines','FLSH-Dép. littératures',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909119,1,1394909180,'2014-03-15 14:30:12','2014-03-15 14:30:12'),
	(40,'ESP-2200','Literatura y cultura hispanoamericanas: orígenes-S. XIX','Étude du contexte social, économique, idéologique et culturel de la littérature hispano-américaine, des origines jusqu\'à la fin du XIXe siècle.',3,3,0,6,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','ESP 2001',1,'Lettres et sciences humaines','FLSH-Dép. littératures',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909119,0,1394909180,'2014-03-15 14:30:12','2014-03-15 14:30:12'),
	(41,'ESP-2204','Cine y literaturas hispánicas','Cours offert en espagnol. Présentation des versions cinématographiques de textes littéraires hispaniques. Ce cours a pour objectif de familiariser l\'étudiant avec les données historiques, idéologiques, sociales et culturelles du cinéma dans son rapport à la littérature.',3,3,0,6,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','ESP 2001',1,'Lettres et sciences humaines','FLSH-Dép. littératures',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909119,0,1394909180,'2014-03-15 14:30:13','2014-03-15 14:30:13'),
	(42,'GIE-4038','Estrategias para el desarrollo de mercados en América Latina','Orientado sobre los negocios de nivel internacional, el curso de Estrategias de desarrollo de mercados en los países de América Latina permite completar la formación ofrecida, cubriendo nuevos mercados y ofrece al mismo tiempo al estudiante la oportunidad de seguir un curso especializado de su concentración en lengua española.',3,3,0,6,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','MNG 1001 OU  MNG 1103',1,'Sciences de l\'administration','Management',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909120,1,1394909180,'2014-03-15 14:30:13','2014-03-15 14:30:13'),
	(43,'GGR-2502','Géographie de l\'Amérique latine','Ce cours vise à faire un tour d\'horizon des enjeux socioéconomiques, politiques, environnementaux et culturels que doit affronter la société latino-américaine si elle veut offrir à sa population des chances plus équitables de connaître une vie décente. Thèmes abordés: pauvreté, inégalités, migrations, démocratie, nouvelle gauche latino-américaine et mouvements autochtones.',3,3,0,6,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','',1,'Foresterie, géographie géomat.','Géographie',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909120,1,1394909181,'2014-03-15 14:30:13','2014-03-15 14:30:13'),
	(44,'HAR-2302','L\'art précolombien','L\'art et l\'architecture des grandes civilisations précolombiennes du Mexique, de l\'Amérique centrale et des pays andins. Les manifestations artistiques et culturelles des peuples amérindiens d\'Amérique du Nord.',3,3,0,6,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','',1,'Lettres et sciences humaines','FLSH-Dép. sc. historiques',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909120,0,1394909181,'2014-03-15 14:30:14','2014-03-15 14:30:14'),
	(45,'HST-1305','Histoire générale de l\'Amérique latine','Délimitation des changements et continuités qui ont donné lieu au passage de l\'époque précolombienne à l\'occupation ibérique; évolution de la structure coloniale espagnole; la domination économique de l\'Amérique latine par l\'Angleterre au XIXe siècle et par les USA au XXe siècle.',3,3,0,6,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','',1,'Lettres et sciences humaines','FLSH-Dép. sc. historiques',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909120,0,1394909181,'2014-03-15 14:30:14','2014-03-15 14:30:14'),
	(46,'POL-1003','Régimes politiques et sociétés dans le monde','En intégrant les dimensions historique, économique et sociologique, ce cours initie l\'étudiant aux différents types de régimes et de systèmes politiques, à leur fonctionnement et aux problèmes auxquels ils sont confrontés dans leurs relations avec leur environnement.',3,3,0,6,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','',1,'Sciences sociales','Science politique',0,0,0,0,0,0,0,0,0,0,0,0,1,1394909121,1,1394909182,'2014-03-15 14:30:14','2014-03-15 14:30:14'),
	(47,'POL-1005','Introduction aux relations internationales','Étude du système international; formation historique et fondements idéologiques; types de systèmes et transformations; système contemporain; processus de conflits; processus de coopération; forces transnationales; grands thèmes des débats internationaux actuels; diplomatie et politique étrangère.',3,3,0,6,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','',1,'Sciences sociales','Science politique',0,0,0,0,0,0,0,0,0,0,0,0,1,1394909121,1,1394909182,'2014-03-15 14:30:15','2014-03-15 14:30:15'),
	(48,'POL-2326','Coopération dans les Amériques','Cette formation en ligne vise à initier l\'étudiant aux grands projets d\'intégration dans les Amériques, aux grandes institutions de la coopération interaméricaine, ainsi qu\'aux modèles d\'intégration économique, à la coopération continentale en matière de sécurité et à la situation de la démocratie dans les Amériques. Il s\'agit d\'une formation multidisciplinaire (politique, économique, histoire, sociologie, droit) où l\'on aborde les différentes dimensions de la coopération dans les Amériques.',3,3,0,6,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','',1,'Sciences sociales','Science politique',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909121,1,1394909182,'2014-03-15 14:30:16','2014-03-15 14:30:16'),
	(49,'SOC-2103','Afrique, Amérique latine et mondialisation','Introduction à l\'évolution historique des structures sociales et des institutions politiques de l\'Afrique sub-saharienne et de l\'Amérique latine dans l\'économie mondiale. Étude comparative de la genèse et des effets de la mondialisation sur les politiques des États. Le changement social (inégalités, pauvreté, marché du travail, mouvements sociaux) dans le monde rural et urbain.',3,0,0,0,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','',1,'Sciences sociales','Sociologie',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909121,0,1394909182,'2014-03-15 14:30:16','2014-03-15 14:30:16'),
	(50,'TRD-2150','Traduction espagnole (textes pratiques)','Objectifs: étude des difficultés de traduction entre l\'espagnol et le français; connaissance des langues; analyse du sens à transmettre; repérage des difficultés grammaticales, lexicales et syntaxiques du texte de départ; utilisation des dictionnaires unilingues et bilingues; distinction entre problèmes de langue et problèmes de traduction; contraintes et choix; connaissances linguistiques et extralinguistiques; reformulation dans une langue idiomatique. Contenu: traduction de textes pratiques de l\'espagnol au français et éventuellement du français à l\'espagnol.',3,2,1,6,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','ESG 2020 OU Examen Classement en espagnol avec résultat de 5 à 5',1,'Lettres et sciences humaines','FLSH-Dép. lang. ling. trad.',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909122,0,1394909183,'2014-03-15 14:30:16','2014-03-15 14:30:16'),
	(51,'TRD-2151','Traduction espagnole (textes littéraires et sociologiques)','Objectifs: étude des difficultés de traduction entre l\'espagnol et le français; connaissance des langues; analyse du sens à transmettre; repérage des difficultés grammaticales, lexicales et syntaxiques du texte de départ; utilisation des dictionnaires unilingues et bilingues; distinction entre problèmes de langue et problèmes de traduction; contraintes et choix; connaissances linguistiques et extralinguistiques; reformulation dans une langue idiomatique. Contenu: traduction de textes littéraires et sociologiques de l\'espagnol au français et, éventuellement, du français à l\'espagnol.',3,2,1,6,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','ESG 2020 OU Examen Classement en espagnol avec résultat de 5 à 5',1,'Lettres et sciences humaines','FLSH-Dép. lang. ling. trad.',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909122,0,1394909183,'2014-03-15 14:30:16','2014-03-15 14:30:16'),
	(52,'JAP-2010','Japonais intermédiaire I','Cours destiné aux personnes possédant de bonnes notions de japonais. Apprentissage et renforcement de mécanismes grammaticaux et de vocabulaire de la production orale et écrite. Pratique des syllabaires Hiragana et Katakana. Étude d\'idéogrammes (Kanjis) simples. Présentation de quelques aspects de culture et de civilisation japonaises. Phonétique corrective. Laboratoire individuel et dirigé.',3,3,0,6,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','JAP 1020 OU  JAP 1021 OU Examen Classement en japonais avec résultat de 3 à 3',1,'Lettres et sciences humaines','FLSH-École de langues',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909122,1,1394909184,'2014-03-15 14:30:17','2014-03-15 14:30:17'),
	(53,'JAP-2020','Japonais intermédiaire II','Cours destiné aux personnes possédant de très bonnes notions de japonais. Apprentissage et approfondissement de mécanismes grammaticaux et de vocabulaire de la production orale et écrite. Pratique des syllabaires Hiragana et Katakana. Étude d\'idéogrammes (Kanjis). Présentation de quelques aspects de culture et de civilisation japonaises. Phonétique corrective. Laboratoire individuel et dirigé.',3,3,0,6,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','JAP 2010 OU Examen Classement en japonais avec résultat de 4 à 4',1,'Lettres et sciences humaines','FLSH-École de langues',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909122,0,1394909184,'2014-03-15 14:30:18','2014-03-15 14:30:18'),
	(54,'JAP-1010','Japonais élémentaire I','Cours destiné aux personnes qui commencent l\'apprentissage du japonais. Initiation au japonais oral et écrit. Apprentissage de structures fondamentales dans des situations de communication usuelles. Apprentissage des syllabaires Hiragana et Katakana. Initiation à quelques idéogrammes (kanjis) simples. Présentation de quelques aspects de culture et de civilisation japonaises. Phonétique corrective. Laboratoire individuel et dirigé. Note : L\'étudiant qui s\'inscrit à ce cours pour débutants déclare n\'avoir acquis aucune formation antérieure dans la langue cible. S\'il n\'est pas débutant, une entrevue de classement avec le conseiller pédagogique est nécessaire pour connaître le niveau qui correspond à ses connaissances.',3,3,0,6,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','',1,'Lettres et sciences humaines','FLSH-École de langues',0,0,0,0,0,0,0,0,0,0,0,0,1,1394909123,1,1394909184,'2014-03-15 14:30:19','2014-03-15 14:30:19'),
	(55,'JAP-1020','Japonais élémentaire II','Cours destiné aux personnes qui ont des notions de japonais. Apprentissage de la grammaire et du vocabulaire de base dans des situations de communication simples. Apprentissage des syllabaires Hiragana et Katakana. Initiation à quelques idéogrammes (Kanjis) simples. Présentation de quelques aspects de culture et de civilisation japonaises. Phonétique corrective. Laboratoire individuel et dirigé.',3,3,0,6,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','JAP 1010 OU Examen Classement en japonais avec résultat de 2 à 2',1,'Lettres et sciences humaines','FLSH-École de langues',0,0,0,0,0,0,0,0,0,0,0,0,1,1394909123,0,1394909184,'2014-03-15 14:30:19','2014-03-15 14:30:19'),
	(56,'JAP-1021','Japonais intensif élémentaire','Cours intensif destiné aux personnes qui commencent l\'apprentissage du japonais. Apprentissage de la grammaire et du vocabulaire de base, des syllabaires Hiragan et Katakana et de quelques Kanjis. Phonétique corrective. Présentation de quelques aspects de culture et de civilisation japonaises.',6,15,0,3,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','',1,'Lettres et sciences humaines','FLSH-École de langues',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909123,0,1394909185,'2014-03-15 14:30:20','2014-03-15 14:30:20'),
	(57,'JAP-3010','Japonais avancé I','Enrichissement des connaissances lexicales et grammaticales. Pratique des syllabaires Hiragana et Katakana. Étude d\'idéogrammes (Kanjis). Phonétique corrective. Présentation de quelques aspects de culture et de civilisation japonaises.',3,3,0,6,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','JAP 2020 OU Examen Classement en japonais avec résultat de 5 à 5',1,'Lettres et sciences humaines','FLSH-École de langues',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909124,1,1394909185,'2014-03-15 14:30:20','2014-03-15 14:30:20'),
	(58,'JAP-3020','Japonais avancé II','Perfectionnement des connaissances lexicales et grammaticales. Pratique des syllabaires Hiragana et Katakana. Étude d\'idéogrammes (Kanjis). Phonétique corrective. Présentation de quelques aspects de culture et de civilisation japonaises. Laboratoires individuels et dirigés.',3,3,0,6,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','JAP 3010 OU Examen Classement en japonais avec résultat de 6 à 6',1,'Lettres et sciences humaines','FLSH-École de langues',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909124,0,1394909185,'2014-03-15 14:30:20','2014-03-15 14:30:20'),
	(59,'ANT-1205','Sociétés et cultures d\'Asie du Sud-Est d\'hier à aujourd\'hui','Ce cours initie l\'étudiant à une vaste et dynamique aire culturelle de 560 millions d\'habitants explorée par des anthropologues marquants (Firth, Leach, Mead, Geertz, Condominas, Dournes). On étudie les diverses sociétés, leur histoire, leurs cultures et leur adaptation contemporaine dans les deux grandes divisions caractéristiques de cette région: la continentale, où prévalent le bouddhisme et l\'éclatement linguistique (Birmanie, Thaïlande, Laos, Cambodge et Vietnam), et la maritime, austronésienne et à dominante musulmane (Indonésie, Philippines, Malaisie, Singapour, Brunei et Timor).',3,3,0,6,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','',1,'Sciences sociales','Anthropologie',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909124,0,1394909185,'2014-03-15 14:30:20','2014-03-15 14:30:20'),
	(60,'GGR-2504','Géographie de l\'Asie du Sud-Est','Caractères et problèmes de l\'Asie du Sud-Est. Fondements historiques et géographiques de l\'organisation des humains et du territoire avant l\'arrivée des Européens. Distinction entre le continent et l\'archipel. Étapes, les caractéristiques et les conséquences de la colonisation. Typologie et problèmes de l\'agriculture. Géographies nationales. Questions ethniques. Enjeux et défis contemporains.',3,3,0,6,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','',1,'Foresterie, géographie géomat.','Géographie',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909124,1,1394909186,'2014-03-15 14:30:21','2014-03-15 14:30:21'),
	(61,'GGR-2515','Géographie du Pacifique Sud','Ce cours est consacré principalement à l\'étude de l\'île&#8209;continent d\'Australie et des îles et états du Pacifique Sud insulaire : géologie et géomorphologie, biogéographie des environnements naturels, peuplement préhistorique et historique, expériences coloniales, mutations sociales, économiques, culturelles et politiques. Une attention particulière est accordée aux relations entre les états insulaires et les grandes (Chine, Japon, États&#8209;Unis, France, Grande&#8209;Bretagne) et moyennes (Australie, Nouvelle&#8209;Zélande) puissances ayant des intérêts directs dans la région. Le « mythe romantique du Pacifique » est abordé dans le cadre de ce cours avec l\'étude des liens entre les îles et les métropoles occidentales et asiatiques.',3,3,0,6,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','',1,'Foresterie, géographie géomat.','Géographie',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909124,0,1394909186,'2014-03-15 14:30:22','2014-03-15 14:30:22'),
	(62,'HST-1355','Introduction à l\'histoire de l\'Asie de l\'Est','Ce cours sert d\'introduction à l\'histoire de l\'Asie orientale, en ciblant plus précisément celles de la Chine, du Japon, de la Corée et du Viet Nam, à partir de la fondation de la dernière dynastie impériale chinoise, en 1644, jusqu\'à la réunification du Viet Nam en 1976. Il a pour objectif de permettre à l\'étudiant de mieux connaître l\'évolution de ces civilisations asiatiques ainsi que leurs relations intérieures et leurs relations avec le monde occidental.',3,3,0,6,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','',1,'Lettres et sciences humaines','FLSH-Dép. sc. historiques',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909124,1,1394909186,'2014-03-15 14:30:22','2014-03-15 14:30:22'),
	(63,'HST-2252','La Deuxième Guerre mondiale','Ce cours examine l\'expérience des peuples dans l\'Europe d\'Hitler pendant la Deuxième guerre mondiale. Les mécaniques de l\'occupation, la collaboration, la résistance et la lutte quotidienne des peuples persécutés seront abordés ainsi que d\'autres aspects plus particuliers. Certains pays (Pologne, Union soviétique, France, Italie, Yougoslavie, Grèce et Pays-Bas) serviront d\'exemples privilégiés mais non exclusifs. L\'approche consiste à analyser plusieurs cas en profondeur tout en les mettant dans un contexte comparatif.',3,3,0,6,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','',1,'Lettres et sciences humaines','FLSH-Dép. sc. historiques',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909124,0,1394909186,'2014-03-15 14:30:22','2014-03-15 14:30:22'),
	(64,'POL-2305','Relations internationales en Asie','Étude comparée de l\'évolution vers l\'indépendance des pays colonisés. Politique étrangère de la Chine, du Japon et de l\'Inde. Les rapports de l\'Asie avec les grandes puissances, en particulier les États-Unis, la Russie et l\'Union européenne. Les institutions régionales dominantes. Les coopérations et les différends intrarégionaux.',3,3,0,6,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','POL 1003 OU  POL 1005',1,'Sciences sociales','Science politique',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909125,0,1394909186,'2014-03-15 14:30:23','2014-03-15 14:30:23'),
	(65,'POL-2316','Chine et Japon : systèmes politiques comparés','Introduction à l\'analyse comparative des systèmes de gouvernement de la Chine et du Japon. Regards sur la permanence de l\'héritage culturel commun, les profondes divergences sociopolitiques et les trajectoires conflictuelles de ces deux sociétés. Analyse des identités et des crises de modernité non achevées. Étude des transformations du 21e siècle.',3,3,0,6,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','POL 1003',1,'Sciences sociales','Science politique',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909125,1,1394909187,'2014-03-15 14:30:23','2014-03-15 14:30:23'),
	(66,'POL-2319','État et société en Asie orientale','Les sociétés traditionnelles et la création des États. L\'impact de l\'Occident sur l\'Asie de l\'Est, du Sud et du Sud-Est. Colonisation et indépendance. Traditions, nations et modernité. Étude de la pluralité des structures sociales et de la diversité des régimes politiques. La puissance économique de l\'Asie et les scénarios pour le futur.',3,3,0,6,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','',1,'Sciences sociales','Science politique',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909125,0,1394909187,'2014-03-15 14:30:24','2014-03-15 14:30:24'),
	(67,'ESP-1004','Redacción II','Remarque : Ce cours se donne en espagnol. Perfectionnement de la compréhension et de l\'expression écrite par des exercices de lecture, d\'analyse de textes, et de rédaction. L\'accent sera mis sur la rédaction de textes académiques.',3,3,0,6,'Ne peut pas être inscrit à l\'un des cycles suivants:     <br />      Éducation continue<br />','ESP 1003',1,'Lettres et sciences humaines','FLSH-Dép. lang. ling. trad.',0,0,0,0,0,0,0,0,0,0,0,0,0,1394909125,1,1394909187,'2014-03-15 14:30:24','2014-03-15 14:30:24');

/*!40000 ALTER TABLE `courses` ENABLE KEYS */;
UNLOCK TABLES;


# Affichage de la table modules
# ------------------------------------------------------------

DROP TABLE IF EXISTS `modules`;

CREATE TABLE `modules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) DEFAULT NULL,
  `full_name` varchar(64) DEFAULT NULL,
  `alias` varchar(16) NOT NULL DEFAULT '',
  `url` varchar(200) NOT NULL DEFAULT '',
  `target` varchar(10) NOT NULL,
  `external` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `icon` varchar(32) NOT NULL DEFAULT '',
  `order` smallint(2) unsigned NOT NULL DEFAULT '0',
  `default` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `loading` varchar(32) NOT NULL DEFAULT '',
  `data` text NOT NULL,
  `active` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`alias`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `modules` WRITE;
/*!40000 ALTER TABLE `modules` DISABLE KEYS */;

INSERT INTO `modules` (`id`, `name`, `full_name`, `alias`, `url`, `target`, `external`, `icon`, `order`, `default`, `loading`, `data`, `active`)
VALUES
	(1,'Dossier scolaire','Dossier scolaire','studies','/studies','',0,'report.png',1,1,'studies,report','a:2:{i:0;s:20:\"data|studies,summary\";i:1;s:19:\"data|studies,report\";}',1),
	(2,'Intranet Pixel','Intranet Pixel','pixel','/services/pixel/','_blank',1,'server.png',2,0,'','',1),
	(3,'Portail des cours','Portail des cours','portail-cours','/services/portailcours/','_blank',1,'portailcours.png',3,1,'','',1),
	(4,'Exchange','Exchange','exchange','/services/exchange/','',1,'mail.png',4,1,'','',1),
	(5,'Horaire','Horaire','schedule','/schedule','',0,'schedule.png',5,1,'schedule','a:1:{i:0;s:23:\"data|schedule,semesters\";}',1),
	(6,'Frais de scolarité','Frais de scolarité','tuitions','/tuitions','',0,'fees.png',6,1,'fees','a:1:{i:0;s:17:\"data|fees,summary\";}',1),
	(7,'Inscription','Inscription','registration','/registration','',0,'registration.png',7,0,'','',1),
	(8,'Administration','Administration','admin','/admin/dashboard','',0,'preferences.png',8,0,'','',1),
	(9,'Capsule','Capsule','capsule','/services/capsule/','',1,'capsule.png',10,1,'','',1),
	(10,'WebCT','WebCT','webct','/services/webct/','',1,'intranet.png',11,0,'','',0),
	(11,'Web Dépôt','ARC - Web Dépôt','web-depot','http://www.webdepot.arc.ulaval.ca/','',1,'arc-web-depot.png',12,0,'','',1),
	(12,'Bureaux virtuels','FSA - Bur. virt.','bureaux-virtuels','http://bv.fsa.ulaval.ca/default.aspx','',1,'fsa-bv.png',13,0,'','',1),
	(13,'Elluminate','Elluminate','elluminate','/services/elluminate/','',1,'elluminate.png',14,0,'','',1),
	(14,'MED - Intranet','MED - Intranet','med-intranet','/services/s_connect/med-intranet','_blank',0,'server.png',15,0,'','',0),
	(15,'FSE - Intranet','FSE - Intranet','fse-intranet','/services/s_connect/fse-intranet','_blank',0,'server.png',16,0,'','',0),
	(16,'Bibliothèque','Bibliothèque','bibliotheque','http://www.bibl.ulaval.ca/mieux','_blank',0,'biblio.png',9,0,'','',1),
	(17,'Alfresco','Alfresco','alfresco','/services/s_connect/alfresco','',0,'alfresco.png',17,0,'','',0);

/*!40000 ALTER TABLE `modules` ENABLE KEYS */;
UNLOCK TABLES;


# Affichage de la table params
# ------------------------------------------------------------

DROP TABLE IF EXISTS `params`;

CREATE TABLE `params` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idul` varchar(11) NOT NULL DEFAULT '',
  `name` varchar(32) NOT NULL DEFAULT '',
  `value` text NOT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idul` (`idul`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Affichage de la table registration_logs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `registration_logs`;

CREATE TABLE `registration_logs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idul` varchar(10) NOT NULL DEFAULT '',
  `semester` mediumint(6) unsigned NOT NULL,
  `requested_courses` varchar(100) DEFAULT NULL,
  `is_student_allowed` tinyint(1) DEFAULT NULL COMMENT 'Possibilité de s''inscrire sur Capsule pour l''étudiant',
  `is_service_available` tinyint(1) DEFAULT NULL COMMENT 'Page d''inscription de Capsule accessible',
  `is_registration_period` tinyint(1) DEFAULT NULL COMMENT 'Période d''inscription en cours',
  `has_form` tinyint(1) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `request_time` int(11) NOT NULL DEFAULT '0' COMMENT 'Temps total de la requête en secondes',
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idul` (`idul`),
  KEY `semester` (`semester`),
  CONSTRAINT `registration_logs_ibfk_1` FOREIGN KEY (`idul`) REFERENCES `users` (`idul`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table registration_logs_data
# ------------------------------------------------------------

DROP TABLE IF EXISTS `registration_logs_data`;

CREATE TABLE `registration_logs_data` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `registration_log_id` int(11) unsigned NOT NULL,
  `idul` varchar(10) NOT NULL DEFAULT '',
  `data` longtext,
  `number` int(10) unsigned NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `log_id` (`registration_log_id`),
  KEY `idul` (`idul`),
  CONSTRAINT `registration_logs_data_ibfk_1` FOREIGN KEY (`registration_log_id`) REFERENCES `registration_logs` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `registration_logs_data_ibfk_2` FOREIGN KEY (`idul`) REFERENCES `users` (`idul`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table stu_programs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `stu_programs`;

CREATE TABLE `stu_programs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idul` varchar(10) NOT NULL DEFAULT '',
  `name` varchar(64) NOT NULL DEFAULT '',
  `full_name` varchar(64) DEFAULT NULL,
  `major` varchar(64) NOT NULL DEFAULT '',
  `minor` varchar(64) NOT NULL DEFAULT '',
  `concentrations` text,
  `diploma` varchar(64) DEFAULT '',
  `cycle` smallint(1) unsigned NOT NULL DEFAULT '1',
  `faculty` varchar(40) NOT NULL,
  `adm_semester` mediumint(6) unsigned NOT NULL,
  `adm_type` varchar(32) NOT NULL DEFAULT '',
  `attendance` varchar(32) NOT NULL DEFAULT '',
  `session_repertoire` mediumint(6) unsigned DEFAULT NULL,
  `session_evaluation` mediumint(6) unsigned DEFAULT NULL,
  `date_diplome` int(8) unsigned DEFAULT NULL,
  `date_attestation` int(8) unsigned DEFAULT NULL,
  `requirements` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `credits_program` smallint(5) unsigned NOT NULL DEFAULT '0',
  `credits_used` smallint(5) unsigned NOT NULL DEFAULT '0',
  `credits_admitted` smallint(5) unsigned NOT NULL DEFAULT '0',
  `courses_program` smallint(4) unsigned NOT NULL DEFAULT '0',
  `courses_used` smallint(4) unsigned NOT NULL DEFAULT '0',
  `courses_admitted` smallint(4) unsigned NOT NULL DEFAULT '0',
  `gpa_overall` float(5,2) unsigned NOT NULL,
  `gpa_program` float(5,2) unsigned NOT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idul` (`idul`),
  KEY `short_name` (`full_name`),
  CONSTRAINT `stu_programs_idul` FOREIGN KEY (`idul`) REFERENCES `users` (`idul`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table stu_programs_courses
# ------------------------------------------------------------

DROP TABLE IF EXISTS `stu_programs_courses`;

CREATE TABLE `stu_programs_courses` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idul` varchar(10) NOT NULL DEFAULT '',
  `program_id` int(1) unsigned DEFAULT NULL,
  `section_id` int(1) unsigned DEFAULT NULL,
  `semester` mediumint(6) unsigned DEFAULT NULL,
  `code` varchar(10) NOT NULL DEFAULT '',
  `title` text NOT NULL,
  `credits` tinyint(3) unsigned NOT NULL,
  `note` varchar(5) NOT NULL DEFAULT '',
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idul` (`idul`),
  KEY `program_id` (`program_id`),
  KEY `section_id` (`section_id`),
  CONSTRAINT `stu_programs_courses_idul` FOREIGN KEY (`idul`) REFERENCES `users` (`idul`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `stu_programs_courses_section_id` FOREIGN KEY (`section_id`) REFERENCES `stu_programs_sections` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table stu_programs_sections
# ------------------------------------------------------------

DROP TABLE IF EXISTS `stu_programs_sections`;

CREATE TABLE `stu_programs_sections` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idul` varchar(10) NOT NULL DEFAULT '',
  `program_id` int(11) unsigned DEFAULT NULL,
  `title` text NOT NULL,
  `credits` smallint(4) unsigned NOT NULL,
  `number` tinyint(1) unsigned NOT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idul` (`idul`),
  KEY `program_id` (`program_id`),
  KEY `number` (`number`),
  CONSTRAINT `stu_programs_sections_program_id` FOREIGN KEY (`program_id`) REFERENCES `stu_programs` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `stu_prog_sections_idul` FOREIGN KEY (`idul`) REFERENCES `users` (`idul`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table stu_reports
# ------------------------------------------------------------

DROP TABLE IF EXISTS `stu_reports`;

CREATE TABLE `stu_reports` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idul` varchar(10) NOT NULL DEFAULT '',
  `programs` text,
  `notes` text,
  `credits_registered` smallint(4) unsigned NOT NULL,
  `credits_done` smallint(4) unsigned NOT NULL,
  `credits_gpa` smallint(4) unsigned NOT NULL,
  `points` decimal(5,2) unsigned NOT NULL,
  `ulaval_gpa` decimal(5,2) unsigned NOT NULL,
  `credits_admitted` smallint(4) unsigned NOT NULL,
  `credits_admitted_done` smallint(4) unsigned NOT NULL,
  `credits_admitted_gpa` smallint(4) unsigned NOT NULL,
  `credits_admitted_points` decimal(5,2) unsigned NOT NULL,
  `gpa_admitted` decimal(5,2) unsigned NOT NULL,
  `gpa_cycle` decimal(5,2) unsigned NOT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idul` (`idul`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table stu_reports_admitted_sections
# ------------------------------------------------------------

DROP TABLE IF EXISTS `stu_reports_admitted_sections`;

CREATE TABLE `stu_reports_admitted_sections` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idul` varchar(10) NOT NULL DEFAULT '',
  `report_id` int(11) unsigned DEFAULT NULL,
  `period` varchar(20) NOT NULL DEFAULT '',
  `title` text NOT NULL,
  `credits_admitted` smallint(4) unsigned NOT NULL DEFAULT '0',
  `credits_gpa` smallint(4) unsigned NOT NULL DEFAULT '0',
  `points` float(5,2) unsigned NOT NULL,
  `gpa` float(5,2) unsigned NOT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idul` (`idul`),
  KEY `semester` (`period`),
  KEY `report_id` (`report_id`),
  CONSTRAINT `stu_reports_admitted_sections_ibfk_1` FOREIGN KEY (`idul`) REFERENCES `users` (`idul`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `stu_reports_admitted_sections_report_id` FOREIGN KEY (`report_id`) REFERENCES `stu_reports` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table stu_reports_courses
# ------------------------------------------------------------

DROP TABLE IF EXISTS `stu_reports_courses`;

CREATE TABLE `stu_reports_courses` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idul` varchar(10) NOT NULL DEFAULT '',
  `semester_id` int(11) unsigned DEFAULT NULL,
  `section_id` int(11) unsigned DEFAULT NULL,
  `code` varchar(10) NOT NULL DEFAULT '',
  `cycle` smallint(1) unsigned NOT NULL,
  `title` text NOT NULL,
  `credits` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `note` varchar(3) DEFAULT '',
  `points` float(5,2) unsigned DEFAULT NULL,
  `reprise` varchar(10) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idul` (`idul`),
  KEY `code` (`code`),
  KEY `semester_id` (`semester_id`),
  KEY `stu_reports_courses_section_id` (`section_id`),
  CONSTRAINT `stu_reports_courses_idul` FOREIGN KEY (`idul`) REFERENCES `users` (`idul`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `stu_reports_courses_section_id` FOREIGN KEY (`section_id`) REFERENCES `stu_reports_admitted_sections` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `stu_reports_courses_semester_id` FOREIGN KEY (`semester_id`) REFERENCES `stu_reports_semesters` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table stu_reports_semesters
# ------------------------------------------------------------

DROP TABLE IF EXISTS `stu_reports_semesters`;

CREATE TABLE `stu_reports_semesters` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idul` varchar(10) NOT NULL DEFAULT '',
  `semester` mediumint(6) unsigned NOT NULL,
  `report_id` int(11) unsigned DEFAULT NULL,
  `credits_registered` smallint(4) unsigned NOT NULL DEFAULT '0',
  `credits_done` smallint(4) unsigned NOT NULL DEFAULT '0',
  `credits_gpa` smallint(4) unsigned NOT NULL DEFAULT '0',
  `points` decimal(5,2) unsigned NOT NULL,
  `gpa` decimal(5,2) unsigned NOT NULL,
  `cumulative_gpa` decimal(5,2) unsigned NOT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idul` (`idul`),
  KEY `semester` (`semester`),
  KEY `report_id` (`report_id`),
  CONSTRAINT `stu_reports_semesters_idul` FOREIGN KEY (`idul`) REFERENCES `users` (`idul`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `stu_report_semesters_report_id` FOREIGN KEY (`report_id`) REFERENCES `stu_reports` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table stu_schedule_classes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `stu_schedule_classes`;

CREATE TABLE `stu_schedule_classes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idul` varchar(10) NOT NULL DEFAULT '',
  `course_id` int(11) unsigned DEFAULT NULL,
  `nrc` mediumint(5) unsigned NOT NULL,
  `type` varchar(32) DEFAULT '',
  `day` varchar(1) DEFAULT '',
  `hour_start` decimal(5,1) unsigned DEFAULT NULL,
  `hour_end` decimal(5,1) unsigned DEFAULT NULL,
  `location` varchar(60) DEFAULT '',
  `teaching` varchar(32) DEFAULT NULL,
  `date_start` int(8) unsigned DEFAULT NULL,
  `date_end` int(8) unsigned DEFAULT NULL,
  `teacher` varchar(40) DEFAULT '',
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idul` (`idul`),
  KEY `hour_start` (`hour_start`),
  KEY `type` (`type`),
  KEY `day_start` (`date_start`),
  KEY `day_end` (`date_end`),
  KEY `nrc` (`nrc`),
  KEY `course_id` (`course_id`),
  CONSTRAINT `stu_classes_idul` FOREIGN KEY (`idul`) REFERENCES `users` (`idul`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `stu_schedule_classes_course_id` FOREIGN KEY (`course_id`) REFERENCES `stu_schedule_courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table stu_schedule_courses
# ------------------------------------------------------------

DROP TABLE IF EXISTS `stu_schedule_courses`;

CREATE TABLE `stu_schedule_courses` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idul` varchar(10) NOT NULL,
  `semester` int(11) DEFAULT NULL,
  `semester_id` int(11) unsigned DEFAULT NULL,
  `nrc` mediumint(5) unsigned NOT NULL,
  `title` text NOT NULL,
  `teacher` text,
  `credits` tinyint(3) unsigned NOT NULL,
  `cycle` smallint(1) unsigned NOT NULL,
  `campus` varchar(32) NOT NULL DEFAULT '',
  `code` varchar(10) NOT NULL DEFAULT '',
  `section` varchar(2) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idul` (`idul`),
  KEY `nrc` (`nrc`),
  KEY `semester_id` (`semester_id`),
  CONSTRAINT `stu_schedule_courses_idul` FOREIGN KEY (`idul`) REFERENCES `users` (`idul`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `stu_schedule_courses_semester_id` FOREIGN KEY (`semester_id`) REFERENCES `stu_schedule_semesters` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table stu_schedule_semesters
# ------------------------------------------------------------

DROP TABLE IF EXISTS `stu_schedule_semesters`;

CREATE TABLE `stu_schedule_semesters` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idul` varchar(10) NOT NULL DEFAULT '',
  `semester` mediumint(6) unsigned NOT NULL,
  `shared` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sharing_url` varchar(32) NOT NULL DEFAULT '',
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idul` (`idul`),
  KEY `semester` (`semester`),
  KEY `sharing_url` (`sharing_url`),
  CONSTRAINT `stu_schedule_semesters_idul` FOREIGN KEY (`idul`) REFERENCES `users` (`idul`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table stu_selected_courses
# ------------------------------------------------------------

DROP TABLE IF EXISTS `stu_selected_courses`;

CREATE TABLE `stu_selected_courses` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idul` varchar(10) NOT NULL DEFAULT '',
  `course_id` int(11) unsigned NOT NULL,
  `code` varchar(10) NOT NULL DEFAULT '',
  `nrc` mediumint(5) unsigned NOT NULL,
  `semester` mediumint(6) unsigned NOT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idul` (`idul`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table stu_tuitions_accounts
# ------------------------------------------------------------

DROP TABLE IF EXISTS `stu_tuitions_accounts`;

CREATE TABLE `stu_tuitions_accounts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idul` varchar(10) NOT NULL DEFAULT '',
  `account_number` varchar(11) NOT NULL DEFAULT '',
  `aelies_number` varchar(20) DEFAULT NULL,
  `balance` float(7,2) NOT NULL DEFAULT '0.00',
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idul` (`idul`),
  CONSTRAINT `stu_tuitions_account_idul` FOREIGN KEY (`idul`) REFERENCES `users` (`idul`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table stu_tuitions_semesters
# ------------------------------------------------------------

DROP TABLE IF EXISTS `stu_tuitions_semesters`;

CREATE TABLE `stu_tuitions_semesters` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idul` varchar(10) NOT NULL DEFAULT '',
  `account_id` int(11) DEFAULT NULL,
  `semester` mediumint(6) unsigned NOT NULL,
  `fees` text NOT NULL,
  `total` float(7,2) unsigned NOT NULL DEFAULT '0.00',
  `payments` float(7,2) unsigned NOT NULL DEFAULT '0.00',
  `balance` float(7,2) unsigned NOT NULL DEFAULT '0.00',
  `pdf_statement_url` varchar(200) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idul` (`idul`),
  KEY `semester` (`semester`),
  KEY `account_id` (`account_id`),
  CONSTRAINT `stu_tuitions_semesters_idul` FOREIGN KEY (`idul`) REFERENCES `users` (`idul`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `idul` varchar(10) NOT NULL DEFAULT '',
  `fbuid` varchar(32) NOT NULL DEFAULT '',
  `name` varchar(32) NOT NULL,
  `da` int(11) DEFAULT NULL,
  `code_permanent` varchar(16) DEFAULT NULL,
  `birthday` varchar(15) DEFAULT NULL,
  `status` varchar(30) DEFAULT NULL,
  `registered` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `first_sem` mediumint(6) unsigned NOT NULL DEFAULT '0',
  `last_sem` mediumint(6) unsigned NOT NULL DEFAULT '0',
  `empty_data` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `program` varchar(64) NOT NULL,
  `faculty` varchar(64) NOT NULL,
  `last_visit` int(11) unsigned NOT NULL,
  `admin` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`idul`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table users_modules_map
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users_modules_map`;

CREATE TABLE `users_modules_map` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idul` varchar(10) NOT NULL DEFAULT '',
  `module_id` int(11) unsigned DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idul` (`idul`),
  KEY `module_id` (`module_id`),
  KEY `order` (`order`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- Python SQL Dump
-- Extraction de la base de donnée
-- Depuis le serveur encours
-- 
 
 
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
 
 
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
 
-- 
-- Base de données : `controle_technique`
-- 
 
-- --------------------------------------------------------
 
-- 
-- Contenu de la table : `ct_visite_extra`
-- 
 
LOCK TABLES `ct_visite_extra` WRITE;
/*!40000 ALTER TABLE `ct_visite_extra` DISABLE KEYS */;
INSERT IGNORE INTO `ct_visite_extra` (`id`, `vste_libelle`) VALUES(1, "Carnet d'entretien"),(2, 'Carte blanche'),(3, 'CIM 32 Bis'),(4, 'Carte jaune'),(5, 'Carte jaune barrée rouge'),(6, 'Carte rouge'),(7, 'Carte auto-école'),(8, 'Plaque chassis'),(9, 'CIM 31'),(10, 'CIM 31 Bis'),(11, 'CIM 32');
/*!40000 ALTER TABLE `ct_visite_extra` ENABLE KEYS */;
UNLOCK TABLES;

-- --------------------------------------------------------
 
COMMIT;
 
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
 
-- Dump Completed on 2024-01-29 05:22:27.219672

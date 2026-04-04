-- MySQL dump 10.13  Distrib 9.6.0, for Win64 (x86_64)
--
-- Host: 172.28.6.112    Database: bdd_site_web_a2_wsl
-- ------------------------------------------------------
-- Server version	8.0.45-0ubuntu0.24.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `adresse`
--

DROP TABLE IF EXISTS `adresse`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `adresse` (
  `id_adresse` int NOT NULL AUTO_INCREMENT,
  `adresse` varchar(255) NOT NULL,
  `id_ville_fk` int NOT NULL,
  PRIMARY KEY (`id_adresse`),
  KEY `Adresse_villes_id_ville_fk` (`id_ville_fk`),
  CONSTRAINT `Adresse_villes_id_ville_fk` FOREIGN KEY (`id_ville_fk`) REFERENCES `villes` (`id_ville`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `adresse`
--

LOCK TABLES `adresse` WRITE;
/*!40000 ALTER TABLE `adresse` DISABLE KEYS */;
INSERT INTO `adresse` VALUES (1,'6 Rue du Général Audran Tour CANOPY',1),(2,'6 Rue du Général Audran Tour CANOPY',2),(3,'6 Rue du Général Audran Tour CANOPY',3),(4,'6 Rue du Général Audran Tour CANOPY',4),(5,'6 Rue du Général Audran Tour CANOPY',5),(6,'6 Rue du Général Audran Tour CANOPY',2),(7,'6 Rue du Général Audran Tour CANOPY',6),(8,'6 Rue du Général Audran Tour CANOPY',7),(9,'6 Rue du Général Audran Tour CANOPY',2),(10,'6 Rue du Général Audran Tour CANOPY',2),(11,'6 Rue du Général Audran Tour CANOPY',2),(12,'6 Rue du Général Audran Tour CANOPY',2),(13,'6 Rue du Général Audran Tour CANOPY',2),(14,'6 Rue du Général Audran Tour CANOPY',2),(15,'6 Rue du Général Audran Tour CANOPY',2),(16,'6 Rue du Général Audran Tour CANOPY',2),(17,'6 Rue du Général Audran Tour CANOPY',2),(18,'6 Rue du Général Audran Tour CANOPY',2),(19,'6 Rue du Général Audran Tour CANOPY',2),(20,'6 Rue du Général Audran Tour CANOPY',2),(21,'6 Rue du Général Audran Tour CANOPY',2),(22,'6 Rue du Général Audran Tour CANOPY',6),(23,'33 Av. du Commandant Lisiack',1),(24,'22 Avenue de Wagram, 75008 Paris, France',8),(25,'Parc du Bel Air, 12 avenue Joseph Paxton',9),(26,'4 Rue du Temple',10),(27,'Zone Industrielle de l’Arsenal',11),(28,'1 Rond-point Benjamin Franklin',12),(29,'20 Rue des Deux Gares',13),(30,'EDF – Direction Régionale Auvergne-Rhône-Alpes 5 Place Jules Ferry',14);
/*!40000 ALTER TABLE `adresse` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `candidature`
--

DROP TABLE IF EXISTS `candidature`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `candidature` (
  `id_candidature` int NOT NULL AUTO_INCREMENT,
  `cv` varchar(255) NOT NULL,
  `lettre_motivation` text NOT NULL,
  `date_candidature` datetime NOT NULL,
  `id_utilisateur_fk` int NOT NULL,
  `id_offre_fk` int NOT NULL,
  PRIMARY KEY (`id_candidature`),
  KEY `candidature_offre_id_offre_fk` (`id_offre_fk`),
  KEY `candidature_utilisateur_id_utilisateur_fk` (`id_utilisateur_fk`),
  CONSTRAINT `candidature_offre_id_offre_fk` FOREIGN KEY (`id_offre_fk`) REFERENCES `offre` (`id_offre`),
  CONSTRAINT `candidature_utilisateur_id_utilisateur_fk` FOREIGN KEY (`id_utilisateur_fk`) REFERENCES `utilisateur` (`id_utilisateur`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `candidature`
--

LOCK TABLES `candidature` WRITE;
/*!40000 ALTER TABLE `candidature` DISABLE KEYS */;
INSERT INTO `candidature` VALUES (30,'42c9ce352780cea4561944f508625c5c761d5b2d.png','Madame, Monsieur,\r\n\r\nActuellement étudiant en deuxième année de cycle préparatoire intégré informatique au CESI, je souhaite mettre en pratique mes compétences en développement logiciel au sein d’un environnement industriel exigeant et innovant. Intégrer Airbus, acteur majeur de l’industrie aéronautique mondiale, représente pour moi une opportunité unique de contribuer à des projets technologiques d’envergure tout en développant mes compétences en informatique industrielle.\r\n\r\nAu cours de ma formation, j’ai acquis de solides bases en programmation, notamment en Python et en C/C++, ainsi qu’en conception d’algorithmes et en bases de données SQL. Les projets académiques que j’ai réalisés m’ont permis de développer des applications structurées, de manipuler des bases de données et d’adopter une méthodologie rigoureuse incluant phases de test et documentation technique. Ces expériences m’ont également appris à travailler en équipe, à analyser un besoin et à proposer des solutions adaptées.\r\n\r\nParticulièrement intéressé par l’optimisation des processus et l’automatisation, je suis motivé à l’idée de participer au développement d’outils internes visant à améliorer la performance des équipes de production. Contribuer à l’amélioration de bases de données industrielles, réaliser des tests et échanger avec les équipes terrain sont des missions qui correspondent pleinement à mon projet professionnel, orienté vers le développement logiciel appliqué à l’industrie.\r\n\r\nRigoureux, autonome et force de proposition, je m’investis pleinement dans les missions qui me sont confiées. Rejoindre le site de Rochefort serait pour moi l’occasion d’évoluer dans un environnement technologique de pointe et de participer activement à l’amélioration continue des processus industriels.\r\n\r\nJe me tiens à votre disposition pour un entretien afin de vous exposer plus en détail ma motivation et l’intérêt que je porte à cette opportunité.\r\n\r\nJe vous prie d’agréer, Madame, Monsieur, l’expression de mes salutations distinguées.','2026-04-02 21:49:38',2,23),(31,'13ce4fd5ca0a95b11e722374f0bda18afac28e7b.png','gfsgvkubkvinkdretesttehrezgtezgte','2026-04-03 12:08:25',2,24);
/*!40000 ALTER TABLE `candidature` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `competence_offre`
--

DROP TABLE IF EXISTS `competence_offre`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `competence_offre` (
  `id_offre_fk` int NOT NULL,
  `id_competence_fk` int NOT NULL,
  UNIQUE KEY `competence_offre_pk` (`id_competence_fk`,`id_offre_fk`),
  KEY `competence_offre_offre_id_offre_fk` (`id_offre_fk`),
  CONSTRAINT `competence_offre_competences_id_competence_fk` FOREIGN KEY (`id_competence_fk`) REFERENCES `competences` (`id_competence`),
  CONSTRAINT `competence_offre_offre_id_offre_fk` FOREIGN KEY (`id_offre_fk`) REFERENCES `offre` (`id_offre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `competence_offre`
--

LOCK TABLES `competence_offre` WRITE;
/*!40000 ALTER TABLE `competence_offre` DISABLE KEYS */;
INSERT INTO `competence_offre` VALUES (22,1),(23,20),(23,21),(23,52),(23,53),(24,58),(24,59),(24,60),(24,61),(24,62);
/*!40000 ALTER TABLE `competence_offre` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `competences`
--

DROP TABLE IF EXISTS `competences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `competences` (
  `id_competence` int NOT NULL AUTO_INCREMENT,
  `competence` varchar(255) NOT NULL,
  PRIMARY KEY (`id_competence`),
  UNIQUE KEY `Competences_unique` (`competence`)
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `competences`
--

LOCK TABLES `competences` WRITE;
/*!40000 ALTER TABLE `competences` DISABLE KEYS */;
INSERT INTO `competences` VALUES (62,' API REST'),(52,' C/C++'),(21,' Développement logiciel'),(61,' Git'),(60,' Power BI'),(53,' Python'),(59,' SQL'),(1,'Contact Client'),(20,'Informatique industrielle'),(58,'Python');
/*!40000 ALTER TABLE `competences` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact`
--

DROP TABLE IF EXISTS `contact`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact` (
  `id_contact` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id_contact`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact`
--

LOCK TABLES `contact` WRITE;
/*!40000 ALTER TABLE `contact` DISABLE KEYS */;
INSERT INTO `contact` VALUES (1,'contact@alstom.com',NULL),(2,'contact@sncf.fr',NULL),(3,'contact@airbus.com',NULL),(4,'contact@renault.com',NULL),(5,'contact@peugeot.com',NULL),(6,'contact@citroen.com',NULL),(7,'contact@total.com',NULL),(8,'contact@loreal.com',NULL),(9,'contact@danone.com',NULL),(10,'contact@sanofi.com',NULL),(11,'contact@bnpparibas.com',NULL),(12,'contact@socgen.com',NULL),(13,'contact@credit-agricole.com',NULL),(14,'contact@axa.com',NULL),(15,'contact@orange.com',NULL),(16,'contact@sfr.com',NULL),(17,'contact@bouygues.com',NULL),(18,'contact@vinci.com',NULL),(19,'contact@eiffage.com',NULL),(20,'contact@jeffdebruges.com',NULL),(21,'contact@lvmh.com',NULL),(22,'contact@acensi.fr','0175611261'),(23,'contact@alstom.com','0546513000'),(24,'contact@edf.fr','0140422222'),(25,'contact@jeffdebruges.com','0164666899'),(26,'recrutement@jeffdebruges.com','0546412633'),(27,'contact.offre@airbus.com','0546880000'),(28,'nathan.bret@outlook.com',NULL),(29,'contact@dell.com','0499754000'),(30,'accueil-lenovofr@lenovo.com','0244217994'),(34,'recrutement-stage@edf.fr','0140422222'),(36,'albert.desgranges@cesi.fr',NULL),(37,'flore.delage@juniors.fr',NULL),(38,'julien.duval@airbus.fr',NULL),(39,'jule.henry@gmail.com',NULL),(40,'julie.pesquet@cesi.fr',NULL);
/*!40000 ALTER TABLE `contact` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contrat`
--

DROP TABLE IF EXISTS `contrat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contrat` (
  `id_contrat` int NOT NULL AUTO_INCREMENT,
  `nom_contrat` varchar(50) NOT NULL,
  PRIMARY KEY (`id_contrat`),
  UNIQUE KEY `contrat_unique` (`nom_contrat`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contrat`
--

LOCK TABLES `contrat` WRITE;
/*!40000 ALTER TABLE `contrat` DISABLE KEYS */;
INSERT INTO `contrat` VALUES (4,'Alternance/Apprentissage'),(2,'CDD'),(1,'CDI'),(7,'Contrat de professionnalisation'),(8,'Intérim'),(5,'Job d\'été'),(6,'Job étudiant'),(3,'Stage');
/*!40000 ALTER TABLE `contrat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departement`
--

DROP TABLE IF EXISTS `departement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `departement` (
  `id_departement` int NOT NULL AUTO_INCREMENT,
  `departement` varchar(50) NOT NULL,
  `id_pays_fk` int NOT NULL,
  PRIMARY KEY (`id_departement`),
  KEY `departement_pays_id_pays_fk` (`id_pays_fk`),
  CONSTRAINT `departement_pays_id_pays_fk` FOREIGN KEY (`id_pays_fk`) REFERENCES `pays` (`id_pays`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departement`
--

LOCK TABLES `departement` WRITE;
/*!40000 ALTER TABLE `departement` DISABLE KEYS */;
INSERT INTO `departement` VALUES (1,'Charente-Maritime',1),(2,'Île-de-France',1),(3,'Haute-Garonne',1),(4,'Hauts-de-Seine',1),(5,'Doubs',1),(6,'île de france',1),(7,'Seine-et-Marne',1),(8,'Hérault',1),(9,'Rhône (69)',1);
/*!40000 ALTER TABLE `departement` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `entreprise`
--

DROP TABLE IF EXISTS `entreprise`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `entreprise` (
  `id_entreprise` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `descriptif` text,
  `nb_employe` int DEFAULT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '0',
  `code_entreprise` varchar(100) NOT NULL,
  `id_adresse_fk` int NOT NULL,
  `id_contact_fk` int NOT NULL,
  PRIMARY KEY (`id_entreprise`),
  KEY `Entreprise_adresse_id_adresse_fk` (`id_adresse_fk`),
  KEY `Entreprise_email_id_email_fk` (`id_contact_fk`),
  CONSTRAINT `Entreprise_adresse_id_adresse_fk` FOREIGN KEY (`id_adresse_fk`) REFERENCES `adresse` (`id_adresse`),
  CONSTRAINT `Entreprise_email_id_email_fk` FOREIGN KEY (`id_contact_fk`) REFERENCES `contact` (`id_contact`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `entreprise`
--

LOCK TABLES `entreprise` WRITE;
/*!40000 ALTER TABLE `entreprise` DISABLE KEYS */;
INSERT INTO `entreprise` VALUES (1,'Alstom','https://upload.wikimedia.org/wikipedia/commons/1/1c/Alstom_logo.svg','Alstom est un leader mondial dans le domaine de la mobilité durable et intelligente. Présent dans plus de 60 pays et fort de plus de 80 000 collaborateurs, le groupe conçoit, développe et fabrique des solutions de transport innovantes destinées à répondre aux défis de la mobilité urbaine et interurbaine.\r\n\r\nSpécialiste du transport ferroviaire, Alstom propose une large gamme de produits et de services : trains à grande vitesse, métros, tramways, locomotives, systèmes de signalisation, infrastructures ferroviaires ainsi que des solutions de maintenance et de digitalisation des réseaux. L’entreprise s’engage activement dans la transition écologique en développant des technologies plus propres, comme les trains à hydrogène et les solutions de mobilité à faible empreinte carbone.\r\n\r\nGrâce à son expertise technologique, son esprit d’innovation et son engagement en faveur d’un transport durable, Alstom contribue chaque jour à améliorer les déplacements de millions de passagers à travers le monde.\r\n\r\nRejoindre Alstom, c’est intégrer un groupe international en pleine transformation, qui valorise la diversité, l’esprit d’équipe et le développement des compétences. L’entreprise offre à ses collaborateurs un environnement stimulant, des opportunités d’évolution et la possibilité de participer à des projets d’envergure qui façonnent la mobilité de demain.',80000,1,'Alstom-520d8e42f96c2433f2f70fad',23,23),(2,'SNCF','https://upload.wikimedia.org/wikipedia/fr/a/a1/Logo_SNCF_%282011%29.svg','La SNCF (Société Nationale des Chemins de fer Français) est le principal opérateur ferroviaire en France. Fondée en 1938',284000,1,'SNCF-520d8e42f96c2433f2f70fad',2,2),(3,'Airbus','https://upload.wikimedia.org/wikipedia/commons/5/5d/Airbus_Logo_2017.svg','Airbus est un leader mondial de l’aéronautique, de l’espace et des services associés. L’entreprise conçoit, fabrique et commercialise des avions civils et militaires, des hélicoptères et des satellites, et se distingue par ses innovations technologiques.',134000,1,'Airbus-520d8e42f96c2433f2f70fad',3,3),(4,'Renault','https://upload.wikimedia.org/wikipedia/commons/a/a5/Renault_2021.svg','Renault est un constructeur automobile français fondé en 1899. L’entreprise propose une large gamme de véhicules particuliers et utilitaires',106000,1,'Renault-520d8e42f96c2433f2f70fad',4,4),(5,'Peugeot','https://upload.wikimedia.org/wikipedia/fr/9/9d/Peugeot_2021_Logo.svg','Peugeot, fondée en 1810, est une marque automobile française emblématique du groupe Stellantis. Elle propose des véhicules particuliers, utilitaires et sportifs, reconnus pour leur design, leur fiabilité et leur performance. Peugeot s’investit également dans la transition énergétique avec une offre croissante de modèles hybrides et électriques.',40000,1,'Peugeot-520d8e42f96c2433f2f70fad',5,5),(6,'Citroën','https://upload.wikimedia.org/wikipedia/commons/d/dd/Citroen_2022.svg','Citroën, créée en 1919, est une marque automobile française du groupe Stellantis. Elle se distingue par son esprit d’innovation, son design audacieux et son confort. Citroën propose une gamme variée de véhicules adaptés aux besoins des particuliers et des professionnels, tout en développant des solutions de mobilité durable.\n',19000,1,'Citroen-520d8e42f96c2433f2f70fad',6,6),(7,'Total','https://upload.wikimedia.org/wikipedia/fr/f/f7/Logo_TotalEnergies.svg','Total, devenu TotalEnergies, est un groupe énergétique mondial d’origine française. Présent dans plus de 130 pays, il opère dans le pétrole, le gaz naturel, l’électricité et les énergies renouvelables. TotalEnergies s’engage dans la transition énergétique en développant des solutions pour une énergie plus propre et accessible.',101000,1,'Total-520d8e42f96c2433f2f70fad',7,7),(8,'L’Oréal','https://upload.wikimedia.org/wikipedia/commons/9/9d/L%27Or%C3%A9al_logo.svg','L’Oréal est le leader mondial de l’industrie cosmétique. Fondé en 1909, le groupe propose une large gamme de produits de beauté, de soins de la peau, de maquillage et de parfums. Présent dans plus de 150 pays, L’Oréal se distingue par son innovation, sa recherche scientifique et son engagement en faveur de la beauté responsable.',87000,1,'Loreal-520d8e42f96c2433f2f70fad',8,8),(9,'Danone','https://upload.wikimedia.org/wikipedia/en/a/a3/Danone_dairy_logo.svg','Danone est un groupe agroalimentaire français spécialisé dans les produits laitiers, les eaux, la nutrition infantile et médicale. Fondé en 1919, Danone est reconnu pour ses marques phares et son engagement en faveur de la santé, du développement durable et de l’alimentation saine à travers le monde.',90000,1,'Danone-520d8e42f96c2433f2f70fad',9,9),(11,'BNP Paribas','https://upload.wikimedia.org/wikipedia/commons/6/6a/BNP_Paribas.svg','BNP Paribas est l’un des principaux groupes bancaires internationaux, offrant des services de banque de détail, de financement, d’investissement et d’assurance. Il se distingue par son innovation digitale et son engagement responsable.',183000,1,'BNP-520d8e42f96c2433f2f70fad',11,11),(12,'Société Générale','https://upload.wikimedia.org/wikipedia/commons/c/cd/Logo-SG-Soci%C3%A9t%C3%A9-G%C3%A9n%C3%A9rale.svg','Société Générale est un groupe bancaire français majeur, présent dans plus de 60 pays. Il propose des services bancaires, financiers et d’assurance à une clientèle variée, et s’engage dans la transformation digitale et la finance durable.',56000,1,'SG-520d8e42f96c2433f2f70fad',12,12),(13,'Crédit Agricole','https://upload.wikimedia.org/wikipedia/commons/8/8f/Cr%C3%A9dit_Agricole_2020_logo.svg','Crédit Agricole est le plus grand réseau de banques coopératives et mutualistes au monde. Il propose des services bancaires, d’assurance et de gestion d’actifs, et s’engage dans le développement local et durable.',145000,1,'CA-520d8e42f96c2433f2f70fad',13,13),(14,'AXA','https://upload.wikimedia.org/wikipedia/commons/9/94/AXA_Logo.svg','AXA est un leader mondial de l’assurance et de la gestion d’actifs. Présent dans plus de 50 pays, le groupe propose des solutions d’assurance, d’épargne et de prévoyance, et s’engage pour la protection de ses clients et la transition climatique.',145000,1,'AXA-520d8e42f96c2433f2f70fad',14,14),(15,'Orange','https://upload.wikimedia.org/wikipedia/commons/c/c8/Orange_logo.svg','Orange est un opérateur de télécommunications international, offrant des services de téléphonie, d’internet et de télévision. Il est reconnu pour son innovation dans les réseaux et son engagement pour l’inclusion numérique.',123500,1,'Orange-520d8e42f96c2433f2f70fad',15,15),(16,'SFR','https://upload.wikimedia.org/wikipedia/commons/9/97/SFR-2022-logo.svg','SFR est un acteur majeur des télécommunications en France, proposant des offres de téléphonie mobile, d’internet et de télévision. Il se distingue par ses investissements dans les réseaux très haut débit et la 5G.',8500,1,'SFR-520d8e42f96c2433f2f70fad',16,16),(17,'Bouygues','https://upload.wikimedia.org/wikipedia/commons/f/f8/Bouygues_Telecom_201x_logo.svg','Bouygues est un groupe industriel diversifié, actif dans la construction, les télécommunications et les médias. Il est reconnu pour ses réalisations dans le BTP, ses services télécoms et sa chaîne de télévision TF1.',196000,1,'Bouygue-520d8e42f96c2433f2f70fad',17,17),(18,'Vinci','https://upload.wikimedia.org/wikipedia/fr/4/4c/Logo_Vinci.svg','Vinci est un leader mondial des métiers des concessions et de la construction. Le groupe conçoit, finance, construit et gère des infrastructures et des équipements qui contribuent à l’aménagement des territoires.',280000,1,'Vinci-520d8e42f96c2433f2f70fad',18,18),(19,'Eiffage','https://upload.wikimedia.org/wikipedia/fr/c/ce/Eiffage_%C3%89nergie_Syst%C3%A8mes.svg','Eiffage est un groupe français de construction et de concessions, spécialisé dans le BTP, les infrastructures et l’énergie. Il intervient sur de grands projets en France et à l’international.',77000,1,'Eiffage-520d8e42f96c2433f2f70fad',19,19),(20,'Jeff De Bruges','https://www.jeff-de-bruges.com/Assets/256496/Theme/Project/Theme/img/logo-jdb.svg','Jeff de Bruges est une enseigne française spécialisée dans la fabrication et la distribution de chocolats et de confiseries. Fondée en 1986, la marque s’inspire du savoir-faire belge tout en valorisant la créativité française. Présente à travers un large réseau de boutiques en France et à l’international, Jeff de Bruges propose une gamme variée de chocolats, dragées et gourmandises, alliant qualité des ingrédients et originalité des créations. L’enseigne est reconnue pour son approche accessible du chocolat haut de gamme et son engagement envers la satisfaction client.',241,1,'JDB-520d8e42f96c2433f2f70fad',25,25),(21,'LVMH','https://upload.wikimedia.org/wikipedia/de/3/32/LVMH_2023_logo.svg','LVMH (Moët Hennessy Louis Vuitton) est le leader mondial du luxe, regroupant plus de 70 maisons prestigieuses dans la mode, la joaillerie, les vins et spiritueux, la parfumerie et la cosmétique.',213000,1,'LVMH-520d8e42f96c2433f2f70fad',21,21),(22,'Acensi','https://acensi.ca/bundles/pageoverride/img/logo-acensi-bleu.svg','Acensi est une société de conseil en transformation digitale et ingénierie informatique, fondée en 2003 et implantée à Courbevoie, dans les Hauts-de-Seine. Depuis sa création, l’entreprise s’est imposée comme un acteur majeur auprès des grands comptes, en accompagnant ses clients dans la conception, le pilotage et la réalisation de projets innovants. Acensi intervient sur des problématiques variées : cybersécurité, cloud, développement applicatif, data, architecture des systèmes d’information et gestion de projets.\r\n\r\nL’expertise technique d’Acensi repose sur une équipe de plus de 1200 collaborateurs passionnés, qui partagent une culture de l’excellence et un engagement fort envers la qualité de service. L’entreprise valorise l’esprit d’équipe, l’innovation et le développement professionnel de ses consultants, en proposant des formations régulières et des opportunités d’évolution interne.\r\n\r\nAcensi se distingue également par son environnement de travail inclusif et stimulant, favorisant la diversité et l’épanouissement de chacun. L’entreprise s’engage dans des démarches responsables, tant sur le plan social qu’environnemental, et soutient des initiatives visant à promouvoir l’égalité des chances et la transition numérique. Grâce à sa vision stratégique et à son savoir-faire, Acensi accompagne durablement ses clients dans leur transformation digitale, en leur apportant des solutions sur mesure et innovantes.\r\n',150,1,'Acensi-520d8e42f96c2433f2f70fad',22,22),(23,'EDF','https://companieslogo.com/img/orig/EDF.PA_BIG-428e00f5.svg','EDF (Électricité de France) est l’un des principaux acteurs mondiaux de l’énergie et le leader historique de la production et de la fourniture d’électricité en France. Fondé en 1946, le groupe est aujourd’hui majoritairement détenu par l’État français et joue un rôle central dans la stratégie énergétique du pays. Grâce à un parc de production diversifié composé notamment de centrales nucléaires, hydrauliques, solaires et éoliennes, EDF produit une grande partie de l’électricité bas carbone consommée en France.\r\n\r\nPrésent dans de nombreux pays en Europe, en Amérique et en Asie, le groupe développe des projets énergétiques à grande échelle et accompagne la transition énergétique à travers le développement des énergies renouvelables, l’innovation technologique et l’amélioration de l’efficacité énergétique. EDF investit également dans des solutions d’avenir comme le stockage d’énergie, les réseaux intelligents et la mobilité électrique.\r\n\r\nRejoindre EDF, c’est intégrer un groupe industriel international qui place l’innovation, la sécurité et la responsabilité environnementale au cœur de ses activités. L’entreprise propose de nombreuses opportunités professionnelles dans des domaines variés tels que l’ingénierie, la production d’énergie, la maintenance industrielle, l’informatique, le numérique, la recherche et développement, ainsi que les fonctions support.\r\n\r\nLe groupe accorde également une grande importance au développement des compétences de ses collaborateurs à travers des programmes de formation, des parcours de carrière évolutifs et des opportunités de mobilité interne en France et à l’international. En intégrant EDF, les candidats participent à des projets d’envergure visant à construire un avenir énergétique durable et à relever les grands défis de la transition énergétique.',165000,1,'EDF-520d8e42f96c2433f2f70fad',24,24),(24,'Dell','https://upload.wikimedia.org/wikipedia/commons/4/48/Dell_Logo.svg','Dell est une entreprise multinationale américaine spécialisée dans les technologies de l’information. Fondée en 1984 par Michael Dell, elle conçoit et commercialise des ordinateurs, serveurs, solutions de stockage, logiciels et services informatiques pour les particuliers et les entreprises. Présente dans plus de 180 pays, Dell est aujourd’hui l’un des principaux acteurs mondiaux du secteur IT.',165000,1,'Dell-520d8e42f96c2433f2f70fad',28,29),(25,'Lenovo','https://upload.wikimedia.org/wikipedia/commons/b/bd/Branding_lenovo-logo_lenovologoposred_low_res.png','Lenovo est un leader mondial de la technologie, spécialisé dans la conception, la fabrication et la commercialisation d’ordinateurs, serveurs, solutions de stockage, smartphones, tablettes et autres appareils électroniques. Présente dans plus de 180 marchés, l’entreprise vise à fournir une technologie plus intelligente au service de tous.',55000,1,'Lenovo-547e5d79423897e0feaa5b44',29,30);
/*!40000 ALTER TABLE `entreprise` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `groupe`
--

DROP TABLE IF EXISTS `groupe`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `groupe` (
  `id_groupe` int NOT NULL AUTO_INCREMENT,
  `nom_groupe` varchar(100) NOT NULL,
  PRIMARY KEY (`id_groupe`),
  UNIQUE KEY `Groupe_unique` (`nom_groupe`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `groupe`
--

LOCK TABLES `groupe` WRITE;
/*!40000 ALTER TABLE `groupe` DISABLE KEYS */;
INSERT INTO `groupe` VALUES (4,'CESI-BTP'),(3,'CESI-Info');
/*!40000 ALTER TABLE `groupe` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `groupe_utilisateur`
--

DROP TABLE IF EXISTS `groupe_utilisateur`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `groupe_utilisateur` (
  `id_utilisateur_fk` int NOT NULL,
  `id_groupe_fk` int NOT NULL,
  KEY `groupe_utilisateur_groupe_id_groupe_fk` (`id_groupe_fk`),
  KEY `groupe_utilisateur_utilisateur_id_utilisateur_fk` (`id_utilisateur_fk`),
  CONSTRAINT `groupe_utilisateur_groupe_id_groupe_fk` FOREIGN KEY (`id_groupe_fk`) REFERENCES `groupe` (`id_groupe`),
  CONSTRAINT `groupe_utilisateur_utilisateur_id_utilisateur_fk` FOREIGN KEY (`id_utilisateur_fk`) REFERENCES `utilisateur` (`id_utilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `groupe_utilisateur`
--

LOCK TABLES `groupe_utilisateur` WRITE;
/*!40000 ALTER TABLE `groupe_utilisateur` DISABLE KEYS */;
INSERT INTO `groupe_utilisateur` VALUES (8,3),(2,3),(11,3),(8,4);
/*!40000 ALTER TABLE `groupe_utilisateur` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `note_entreprise`
--

DROP TABLE IF EXISTS `note_entreprise`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `note_entreprise` (
  `id_utilisateur_fk` int NOT NULL,
  `id_entreprise_fk` int NOT NULL,
  `note` int NOT NULL,
  KEY `note_entreprise_entreprise_id_entreprise_fk` (`id_entreprise_fk`),
  KEY `note_entreprise_utilisateur_id_utilisateur_fk` (`id_utilisateur_fk`),
  CONSTRAINT `note_entreprise_entreprise_id_entreprise_fk` FOREIGN KEY (`id_entreprise_fk`) REFERENCES `entreprise` (`id_entreprise`),
  CONSTRAINT `note_entreprise_utilisateur_id_utilisateur_fk` FOREIGN KEY (`id_utilisateur_fk`) REFERENCES `utilisateur` (`id_utilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `note_entreprise`
--

LOCK TABLES `note_entreprise` WRITE;
/*!40000 ALTER TABLE `note_entreprise` DISABLE KEYS */;
INSERT INTO `note_entreprise` VALUES (8,1,5),(8,3,5),(8,22,4),(8,24,2),(8,13,4),(8,15,3),(8,20,5),(8,7,4),(8,25,4);
/*!40000 ALTER TABLE `note_entreprise` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `offre`
--

DROP TABLE IF EXISTS `offre`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `offre` (
  `id_offre` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `date_creation` date NOT NULL,
  `duree` int DEFAULT NULL,
  `descriptif` text NOT NULL,
  `domaine` varchar(100) NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '0',
  `Pause` tinyint(1) NOT NULL DEFAULT '0',
  `id_contrat_fk` int NOT NULL,
  `id_unite_duree_fk` int DEFAULT NULL,
  `id_adresse_fk` int DEFAULT NULL,
  `id_entreprise_fk` int NOT NULL,
  `id_contact_recrutement_fk` int NOT NULL,
  PRIMARY KEY (`id_offre`),
  KEY `offre_adresse_id_adresse_fk` (`id_adresse_fk`),
  KEY `offre_contrat_id_contrat_fk` (`id_contrat_fk`),
  KEY `offre_entreprise_id_entreprise_fk` (`id_entreprise_fk`),
  KEY `offre_unite_temps_id_unite_fk` (`id_unite_duree_fk`),
  KEY `offre_contact_id_contact_fk` (`id_contact_recrutement_fk`),
  CONSTRAINT `offre_adresse_id_adresse_fk` FOREIGN KEY (`id_adresse_fk`) REFERENCES `adresse` (`id_adresse`),
  CONSTRAINT `offre_contact_id_contact_fk` FOREIGN KEY (`id_contact_recrutement_fk`) REFERENCES `contact` (`id_contact`),
  CONSTRAINT `offre_contrat_id_contrat_fk` FOREIGN KEY (`id_contrat_fk`) REFERENCES `contrat` (`id_contrat`),
  CONSTRAINT `offre_entreprise_id_entreprise_fk` FOREIGN KEY (`id_entreprise_fk`) REFERENCES `entreprise` (`id_entreprise`),
  CONSTRAINT `offre_unite_temps_id_unite_fk` FOREIGN KEY (`id_unite_duree_fk`) REFERENCES `unite_temps` (`id_unite`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `offre`
--

LOCK TABLES `offre` WRITE;
/*!40000 ALTER TABLE `offre` DISABLE KEYS */;
INSERT INTO `offre` VALUES (22,'Conseiller clientèle ','2026-03-17',NULL,'Depuis sa création, Jeff de Bruges partage sa passion du chocolat à travers des recettes gourmandes, accessibles et raffinées. Présente partout en France, notre enseigne met à l’honneur la qualité des produits, le savoir-faire chocolatier et le plaisir d’offrir.\r\n\r\nRejoindre Jeff de Bruges, c’est intégrer une équipe conviviale, dynamique et passionnée par la gourmandise !\r\n\r\nVos missions\r\n\r\nEn tant que Conseiller(ère) de vente, vous êtes l’ambassadeur(rice) de la marque en boutique. À ce titre, vous :\r\n\r\n- Accueillez, conseillez et fidélisez la clientèle\r\n- Mettez en valeur les produits (chocolats, dragées, confiseries)\r\n- Réalisez les encaissements\r\n- Assurez la bonne tenue du magasin (propreté, merchandising, réassort)\r\n- Participez à la préparation des coffrets et compositions personnalisées\r\n- Contribuez à l’atteinte des objectifs commerciaux\r\n\r\nProfil recherché\r\n- Vous êtes souriant(e), dynamique et avez le sens du service\r\n- Vous aimez le contact client et le travail en équipe\r\n- Une première expérience en vente est appréciée (idéalement en commerce de - bouche)\r\n- Vous êtes rigoureux(se) et organisé(e)\r\n- Vous êtes disponible les week-ends et périodes de fêtes (Noël, Pâques, Saint-Valentin)\r\n','Chocolaterie',1,0,1,NULL,26,20,26),(23,'Stagiaire Développeur Informatique Industrielle (H/F)','2026-03-24',3,'Acteur majeur de l’industrie aéronautique mondiale, Airbus conçoit, fabrique et maintient des avions civils et militaires innovants et performants. Le site de Rochefort est spécialisé dans la production et l’assemblage d’éléments de structure aéronautique.\r\n\r\nRejoindre Airbus, c’est intégrer un environnement technologique de pointe, au sein d’équipes passionnées, et contribuer à des projets d’envergure internationale.\r\n\r\nVos missions\r\n\r\nEn tant que stagiaire Développeur Informatique Industrielle, vous serez intégré(e) au service Méthodes / Industrialisation. À ce titre, vous :\r\n\r\n- Participez au développement d’outils internes (Python, VBA ou C#) pour optimiser les processus de production\r\n- Contribuez à l’amélioration et à l’automatisation des bases de données industrielles\r\n- Analysez les besoins des équipes terrain et proposez des solutions digitales adaptées\r\n- Réalisez des tests et validez les fonctionnalités développées\r\n- Rédigez la documentation technique associée\r\n- Participez aux réunions de suivi de projet\r\n\r\nProfil recherché\r\n\r\n- Vous êtes étudiant(e) en Bac+2 ou Bac+3 en informatique, développement logiciel ou systèmes embarqués\r\n- Vous maîtrisez au moins un langage de programmation (Python, C#, Java ou équivalent)\r\n- Vous avez des bases en bases de données (SQL)\r\n- Vous êtes rigoureux(se), autonome et force de proposition\r\n- Vous appréciez le travail en équipe et les environnements industriels\r\n- Une première expérience (projet académique ou stage) est un plus','Aéronautique',1,0,3,3,27,3,27),(24,'Stage – Développeur(se) Informatique – Outils Data','2026-04-01',6,'Au sein de la Direction des Systèmes d’Information d’EDF, vous intégrerez une équipe en charge du développement d’outils d’analyse et d’optimisation de la performance énergétique des installations.\r\n\r\nVos missions principales seront :\r\n\r\n- Développer et maintenir des scripts Python pour le traitement et l’analyse de données énergétiques\r\n- Concevoir et optimiser des requêtes SQL\r\n- Participer à la création de tableaux de bord (Power BI)\r\n- Contribuer à l’amélioration d’API internes\r\n- Participer aux rituels Agile (daily, sprint planning, revues)\r\n- Rédiger la documentation technique\r\n\r\nProfil recherché :\r\nÉtudiant(e) en Bac+2 à Bac+5 en informatique, data ou école d’ingénieur.\r\nBonne maîtrise de Python et des bases de données.\r\nEsprit d’analyse, rigueur et capacité à travailler en équipe.\r\n\r\nStage basé en environnement industriel avec enjeux liés à la transition énergétique.','Informatique / Développement logiciel / Data',1,0,3,3,30,23,34);
/*!40000 ALTER TABLE `offre` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pays`
--

DROP TABLE IF EXISTS `pays`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pays` (
  `id_pays` int NOT NULL AUTO_INCREMENT,
  `nom_pays` varchar(56) NOT NULL,
  PRIMARY KEY (`id_pays`),
  UNIQUE KEY `nom_pays` (`nom_pays`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pays`
--

LOCK TABLES `pays` WRITE;
/*!40000 ALTER TABLE `pays` DISABLE KEYS */;
INSERT INTO `pays` VALUES (1,'France');
/*!40000 ALTER TABLE `pays` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `unite_temps`
--

DROP TABLE IF EXISTS `unite_temps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `unite_temps` (
  `id_unite` int NOT NULL AUTO_INCREMENT,
  `nom_unite` varchar(50) NOT NULL,
  `nb_jours` int NOT NULL,
  PRIMARY KEY (`id_unite`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unite_temps`
--

LOCK TABLES `unite_temps` WRITE;
/*!40000 ALTER TABLE `unite_temps` DISABLE KEYS */;
INSERT INTO `unite_temps` VALUES (1,'jours',1),(2,'semaine',7),(3,'mois',30),(4,'années',365);
/*!40000 ALTER TABLE `unite_temps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `utilisateur` (
  `id_utilisateur` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `id_permission` int NOT NULL,
  `approuve` tinyint(1) NOT NULL DEFAULT '0',
  `linkedin` varchar(255) DEFAULT NULL,
  `id_contact_fk` int NOT NULL,
  `id_entrprise_fk` int DEFAULT NULL,
  PRIMARY KEY (`id_utilisateur`),
  KEY `utilisateur_contact_id_contact_fk` (`id_contact_fk`),
  KEY `utilisateur_entreprise_id_entreprise_fk` (`id_entrprise_fk`),
  CONSTRAINT `utilisateur_contact_id_contact_fk` FOREIGN KEY (`id_contact_fk`) REFERENCES `contact` (`id_contact`),
  CONSTRAINT `utilisateur_entreprise_id_entreprise_fk` FOREIGN KEY (`id_entrprise_fk`) REFERENCES `entreprise` (`id_entreprise`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `utilisateur`
--

LOCK TABLES `utilisateur` WRITE;
/*!40000 ALTER TABLE `utilisateur` DISABLE KEYS */;
INSERT INTO `utilisateur` VALUES (2,'Bret','Nathan','$2y$10$Y4gor/sKWbTGGGC8.PKmauMKQQDzHKASDv0ZtTBZn03UtsZbiJTni',1,1,'',28,NULL),(8,'Desgranges','Albert','$2y$10$ytiljcGb94YShnpI55tJ1O5hk18qCwOIg4PhcGufQ0/LyjPcieQca',3,1,NULL,36,NULL),(9,'Dellage','Flore','$2y$10$ytiljcGb94YShnpI55tJ1O5hk18qCwOIg4PhcGufQ0/LyjPcieQca',4,1,NULL,37,NULL),(10,'Duval','Julien','$2y$10$HEBdAxXWNA/KKqAR9JeqIegG7atm2H6iHnZCrUWIzrEXTkQwbxRwO',2,0,NULL,38,3),(11,'Jule','Henry','$2y$10$Sh.jXBgF/elbw1OMCHM4huqYDKVXctrkahBS9ncaJdh/E.yTUD23q',1,1,NULL,39,NULL),(12,'Pesquet','Julie','$2y$10$Hs.3zFq/MZyvbgqczNzKc./GKWBZGNsLfeTVOaaJXvFfm3m/9XTw6',3,1,NULL,40,NULL);
/*!40000 ALTER TABLE `utilisateur` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `villes`
--

DROP TABLE IF EXISTS `villes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `villes` (
  `id_ville` int NOT NULL AUTO_INCREMENT,
  `nom_ville` varchar(100) NOT NULL,
  `id_departement_fk` int NOT NULL,
  PRIMARY KEY (`id_ville`),
  KEY `Villes_pays_id_pays_fk` (`id_departement_fk`),
  CONSTRAINT `Villes_pays_id_pays_fk` FOREIGN KEY (`id_departement_fk`) REFERENCES `departement` (`id_departement`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `villes`
--

LOCK TABLES `villes` WRITE;
/*!40000 ALTER TABLE `villes` DISABLE KEYS */;
INSERT INTO `villes` VALUES (1,'Aytre',1),(2,'Paris',2),(3,'Toulouse',3),(4,'Boulogne-Billancourt',4),(5,'Sochaux',5),(6,'Courbevoie',4),(7,'Clichy',4),(8,'Paris',6),(9,'Ferrières en Brie',7),(10,'La Rochelle',1),(11,'Rochefort',1),(12,'Montpellier',8),(13,'Rueil-Malmaison',4),(14,'Lyon',9);
/*!40000 ALTER TABLE `villes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `whishlist`
--

DROP TABLE IF EXISTS `whishlist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `whishlist` (
  `id_offre_fk` int NOT NULL,
  `id_utilisateur_fk` int NOT NULL,
  KEY `Whishlist_utilisateur_id_utilisateur_fk` (`id_utilisateur_fk`),
  KEY `idx_wishlist_offre_user` (`id_offre_fk`,`id_utilisateur_fk`),
  CONSTRAINT `Whishlist_offre_id_offre_fk` FOREIGN KEY (`id_offre_fk`) REFERENCES `offre` (`id_offre`),
  CONSTRAINT `Whishlist_utilisateur_id_utilisateur_fk` FOREIGN KEY (`id_utilisateur_fk`) REFERENCES `utilisateur` (`id_utilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `whishlist`
--

LOCK TABLES `whishlist` WRITE;
/*!40000 ALTER TABLE `whishlist` DISABLE KEYS */;
INSERT INTO `whishlist` VALUES (22,2),(23,2);
/*!40000 ALTER TABLE `whishlist` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-04-04 16:21:54

-- --------------------------------------------------------
-- Hôte:                         127.0.0.1
-- Version du serveur:           5.7.33 - MySQL Community Server (GPL)
-- SE du serveur:                Win64
-- HeidiSQL Version:             11.3.0.6295
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Listage de la structure de la base pour aliexprass
CREATE DATABASE IF NOT EXISTS `aliexprass` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `aliexprass`;

-- Listage de la structure de la table aliexprass. address
CREATE TABLE IF NOT EXISTS `address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `fullname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `compagny` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `complement` longtext COLLATE utf8mb4_unicode_ci,
  `phone` int(11) NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_postal` int(11) NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D4E6F81A76ED395` (`user_id`),
  CONSTRAINT `FK_D4E6F81A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table aliexprass.address : ~2 rows (environ)
/*!40000 ALTER TABLE `address` DISABLE KEYS */;
INSERT INTO `address` (`id`, `user_id`, `fullname`, `compagny`, `address`, `complement`, `phone`, `city`, `code_postal`, `country`) VALUES
	(1, 1, 'Symfony Magnarde', NULL, '87 Avenue Maréchal Foch', 'zeubi', 695569888, 'Le Puy-en-Velay', 43000, 'FR'),
	(2, 1, 'Symfony Juliene', NULL, '97 Boulevard du Web', NULL, 695569843, 'Paris', 75000, 'FR');
/*!40000 ALTER TABLE `address` ENABLE KEYS */;

-- Listage de la structure de la table aliexprass. carrier
CREATE TABLE IF NOT EXISTS `carrier` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` double NOT NULL,
  `created_at` datetime NOT NULL,
  `update_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table aliexprass.carrier : ~2 rows (environ)
/*!40000 ALTER TABLE `carrier` DISABLE KEYS */;
INSERT INTO `carrier` (`id`, `name`, `description`, `price`, `created_at`, `update_at`) VALUES
	(1, 'Chronopost', 'Fast delivery in 24hours', 1500, '2022-02-01 18:32:14', NULL),
	(2, 'Collisimo', 'Fast delivery in 48hours', 799, '2022-02-01 18:33:01', NULL);
/*!40000 ALTER TABLE `carrier` ENABLE KEYS */;

-- Listage de la structure de la table aliexprass. cart
CREATE TABLE IF NOT EXISTS `cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fullname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `carrier_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `carrier_price` double NOT NULL,
  `delivery_address` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_paid` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `more_informations` longtext COLLATE utf8mb4_unicode_ci,
  `quantity` int(11) NOT NULL,
  `sub_total_ht` double NOT NULL,
  `taxe` double NOT NULL,
  `sub_total_ttc` double NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_AB912789A76ED395` (`user_id`),
  CONSTRAINT `FK_AB912789A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table aliexprass.cart : ~8 rows (environ)
/*!40000 ALTER TABLE `cart` DISABLE KEYS */;
INSERT INTO `cart` (`id`, `user_id`, `reference`, `fullname`, `carrier_name`, `carrier_price`, `delivery_address`, `is_paid`, `created_at`, `more_informations`, `quantity`, `sub_total_ht`, `taxe`, `sub_total_ttc`) VALUES
	(3, 1, '4331C8F0-FDFC-7B60-E7C2-D1409AE9A00B', 'Symfony Magnarde', 'Chronopost', 1500, 'Symfony Magnarde[spr]87 Avenue Maréchal Foch[spr]zeubi[spr]43000 - Le Puy-en-Velay[spr]FR[spr]', 0, '2022-02-07 19:47:24', NULL, 1, 39.99, 8, 62.99),
	(4, 1, '4D367013-C270-5F01-724F-AE2332A8CD88', 'Symfony Magnarde', 'Chronopost', 1500, 'Symfony Magnarde[spr]87 Avenue Maréchal Foch[spr]zeubi[spr]43000 - Le Puy-en-Velay[spr]FR[spr]', 0, '2022-02-07 19:47:27', NULL, 1, 39.99, 8, 62.99),
	(5, 1, 'F0F14D05-692E-7850-1BC9-409EC8FFD57D', 'Symfony Magnarde', 'Chronopost', 1500, 'Symfony Magnarde[spr]87 Avenue Maréchal Foch[spr]zeubi[spr]43000 - Le Puy-en-Velay[spr]FR[spr]', 0, '2022-02-07 19:48:29', NULL, 1, 39.99, 8, 62.99),
	(9, 1, '43B4BDD9-CA6B-2570-F873-10770884E2AE', 'Symfony Magnarde', 'Chronopost', 1500, 'Symfony Magnarde[spr]87 Avenue Maréchal Foch[spr]zeubi[spr]43000 - Le Puy-en-Velay[spr]FR[spr]', 0, '2022-02-07 20:05:31', NULL, 1, 39.99, 8, 62.99),
	(10, 1, '190B3EF9-4B84-08CD-990E-B0E1BC75413C', 'Symfony Magnarde', 'Chronopost', 1500, 'Symfony Magnarde[spr]87 Avenue Maréchal Foch[spr]zeubi[spr]43000 - Le Puy-en-Velay[spr]FR[spr]', 0, '2022-02-07 20:06:15', NULL, 1, 39.99, 8, 62.99),
	(11, 1, '02DA791B-880E-48AC-7481-A4B007AB4887', 'Symfony Juliene', 'Chronopost', 1500, 'Symfony Juliene[spr]97 Boulevard du Web[spr][spr]75000 - Paris[spr]FR[spr]', 0, '2022-02-07 20:15:22', NULL, 1, 1999.99, 400, 2414.99),
	(12, 1, '18C2B218-470D-3C31-949C-1151B013F6C3', 'Symfony Juliene', 'Chronopost', 1500, 'Symfony Juliene[spr]97 Boulevard du Web[spr][spr]75000 - Paris[spr]FR[spr]', 0, '2022-02-07 20:15:46', NULL, 1, 1999.99, 400, 2414.99),
	(13, 1, 'DFFE7A5D-3548-5977-7067-59E358A6BE2A', 'Symfony Juliene', 'Chronopost', 1500, 'Symfony Juliene[spr]97 Boulevard du Web[spr][spr]75000 - Paris[spr]FR[spr]', 0, '2022-02-07 20:19:11', NULL, 2, 2069.98, 414, 2498.98);
/*!40000 ALTER TABLE `cart` ENABLE KEYS */;

-- Listage de la structure de la table aliexprass. cart_details
CREATE TABLE IF NOT EXISTS `cart_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `carts_id` int(11) NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_price` double NOT NULL,
  `quantity` int(11) NOT NULL,
  `sub_total_ht` double NOT NULL,
  `taxe` double NOT NULL,
  `sub_total_ttc` double NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_89FCC38DBCB5C6F5` (`carts_id`),
  CONSTRAINT `FK_89FCC38DBCB5C6F5` FOREIGN KEY (`carts_id`) REFERENCES `cart` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table aliexprass.cart_details : ~9 rows (environ)
/*!40000 ALTER TABLE `cart_details` DISABLE KEYS */;
INSERT INTO `cart_details` (`id`, `carts_id`, `product_name`, `product_price`, `quantity`, `sub_total_ht`, `taxe`, `sub_total_ttc`) VALUES
	(1, 3, 'Bonnets tricoté pour femmes', 39.99, 1, 39.99, 0, 47.988),
	(2, 4, 'Bonnets tricoté pour femmes', 39.99, 1, 39.99, 0, 47.988),
	(3, 5, 'Bonnets tricoté pour femmes', 39.99, 1, 39.99, 0, 47.988),
	(4, 9, 'Bonnets tricoté pour femmes', 39.99, 1, 39.99, 0, 47.988),
	(5, 10, 'Bonnets tricoté pour femmes', 39.99, 1, 39.99, 0, 47.988),
	(6, 11, 'NVIDA RTX 3090 MSI special Kokane', 1999.99, 1, 1999.99, 0, 2399.988),
	(7, 12, 'NVIDA RTX 3090 MSI special Kokane', 1999.99, 1, 1999.99, 0, 2399.988),
	(8, 13, 'NVIDA RTX 3090 MSI special Kokane', 1999.99, 1, 1999.99, 0, 2399.988),
	(9, 13, 'Bluetooth appel Fitness', 69.99, 1, 69.99, 0, 83.988);
/*!40000 ALTER TABLE `cart_details` ENABLE KEYS */;

-- Listage de la structure de la table aliexprass. categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table aliexprass.categories : ~7 rows (environ)
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` (`id`, `name`, `description`, `image`) VALUES
	(1, 'bonnet', NULL, NULL),
	(2, 'montre', NULL, NULL),
	(3, 'bijou', NULL, NULL),
	(4, 'portable', NULL, NULL),
	(5, 'drone', NULL, NULL),
	(6, 'gadget', NULL, NULL),
	(7, 'Graphic card', '<div>Graphic card</div>', NULL);
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;

-- Listage de la structure de la table aliexprass. doctrine_migration_versions
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Listage des données de la table aliexprass.doctrine_migration_versions : ~5 rows (environ)
/*!40000 ALTER TABLE `doctrine_migration_versions` DISABLE KEYS */;
INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
	('DoctrineMigrations\\Version20220131134923', '2022-01-31 13:49:34', 292),
	('DoctrineMigrations\\Version20220131201345', '2022-01-31 20:13:52', 312),
	('DoctrineMigrations\\Version20220201181711', '2022-02-01 18:17:26', 35),
	('DoctrineMigrations\\Version20220207193659', '2022-02-07 19:37:11', 372),
	('DoctrineMigrations\\Version20220207222312', '2022-02-07 22:35:17', 30);
/*!40000 ALTER TABLE `doctrine_migration_versions` ENABLE KEYS */;

-- Listage de la structure de la table aliexprass. home_slider
CREATE TABLE IF NOT EXISTS `home_slider` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `button_message` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `button_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_displayed` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table aliexprass.home_slider : ~3 rows (environ)
/*!40000 ALTER TABLE `home_slider` DISABLE KEYS */;
INSERT INTO `home_slider` (`id`, `title`, `description`, `button_message`, `button_url`, `image`, `is_displayed`) VALUES
	(1, 'Home 1', 'New article', 'Shop now', 'https://127.0.0.1:8000/product/nvida-rtx-3090-msi-special-kokane', 'f86481eefcb78ae4aecc6f7554ac331a83b1383f.png', 0),
	(2, 'Home 2', 'New article', 'Shop now', 'https://127.0.0.1:8000/product/nvida-rtx-3090-msi-special-kokane', '36530afe60d4593c0a22db4ec755cf1012d1b8a9.png', 0),
	(3, 'Home 3', 'New article', 'Shop now', 'https://127.0.0.1:8000/product/nvida-rtx-3090-msi-special-kokane', '7ee43d0a813f2932707119c081297fa28fdec681.png', 0);
/*!40000 ALTER TABLE `home_slider` ENABLE KEYS */;

-- Listage de la structure de la table aliexprass. order
CREATE TABLE IF NOT EXISTS `order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fullname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `carrier_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `carrier_price` double NOT NULL,
  `delivery_address` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_paid` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `more_informations` longtext COLLATE utf8mb4_unicode_ci,
  `quantity` int(11) NOT NULL,
  `sub_total_ht` double NOT NULL,
  `taxe` double NOT NULL,
  `sub_total_ttc` double NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_F5299398A76ED395` (`user_id`),
  CONSTRAINT `FK_F5299398A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table aliexprass.order : ~0 rows (environ)
/*!40000 ALTER TABLE `order` DISABLE KEYS */;
/*!40000 ALTER TABLE `order` ENABLE KEYS */;

-- Listage de la structure de la table aliexprass. order_details
CREATE TABLE IF NOT EXISTS `order_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orders_id` int(11) NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_price` double NOT NULL,
  `quantity` int(11) NOT NULL,
  `sub_total_ht` double NOT NULL,
  `taxe` double NOT NULL,
  `sub_total_ttc` double NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_845CA2C1CFFE9AD6` (`orders_id`),
  CONSTRAINT `FK_845CA2C1CFFE9AD6` FOREIGN KEY (`orders_id`) REFERENCES `order` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table aliexprass.order_details : ~0 rows (environ)
/*!40000 ALTER TABLE `order_details` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_details` ENABLE KEYS */;

-- Listage de la structure de la table aliexprass. product
CREATE TABLE IF NOT EXISTS `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `more_informations` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` double NOT NULL,
  `is_best_seller` tinyint(1) DEFAULT NULL,
  `is_new_arrival` tinyint(1) DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT NULL,
  `is_special_offer` tinyint(1) DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `tags` longtext COLLATE utf8mb4_unicode_ci,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table aliexprass.product : ~26 rows (environ)
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
INSERT INTO `product` (`id`, `name`, `description`, `more_informations`, `price`, `is_best_seller`, `is_new_arrival`, `is_featured`, `is_special_offer`, `image`, `quantity`, `created_at`, `tags`, `slug`) VALUES
	(1, 'Chapeau d\'hiver pour femmes', '<div>Chapeau d\'hiver pour femmes, chapeau en velours, épais, chaud, bonnet en tricot Chenille, chapeau d\'équitation, 2 casquettes en laine</div>', '<div>a</div>', 6999, 1, 0, 0, 0, 'd0c2ff065a1af86994f91e1a5ab72412a49a9574.png', 1, '2022-01-31 19:15:18', NULL, 'chapeau-dhiver-pour-femmes'),
	(2, 'Bonnets tricoté pour femmes', '<div>Bonnets tricoté pour femmes, Bonnet de marque, épais et chaud, tête de mort en tricot, lettre, Bonnet, ensembles d\'équitation en plein air</div>', '<div>a</div>', 3999, 0, 1, 0, 0, '4265fce94b19d714e1946bc33841916b0a6a6aab.png', 1, '2022-01-31 19:16:08', NULL, 'bonnets-tricote-pour-femmes'),
	(3, 'Chapeaux en laine de velours pour femmes', '<div>Chapeaux en laine de velours pour femmes, bonnet torsadé, bonnet assorti, tête de cheval, tricoté, vente en gros, nouveau hiver</div>', '<div>a</div>', 4999, 0, 0, 1, 0, 'df7243061b50b66cd7e72a3877a422f204b8a4de.png', 1, '2022-01-31 19:16:49', NULL, 'chapeaux-en-laine-de-velours-pour-femmes'),
	(4, 'Chapeau en laine pour femmes', '<div>Chapeau en laine pour femmes, Bonnet, Patch épais et chaud, Bonnet tricoté multicolore, pour l\'hiver</div>', '<div>a</div>', 7999, 0, 0, 0, 1, 'f89d834afde969727b9ef41f5b63885616a6172d.png', 1, '2022-01-31 19:17:23', NULL, 'chapeau-en-laine-pour-femmes'),
	(5, 'Montre intelligente hommes', '<div>Montre intelligente hommes femmes 1.6 plein écran tactile Bluetooth appel Smartwatch fréquence cardiaque tensiomètre pour Android et IOS"</div>', '<div>a</div>', 9999, 0, 0, 0, 1, '5c64390ef5c9115f9328003007f402c2815cdf17.png', 1, '2022-01-31 19:18:31', NULL, 'montre-intelligente-hommes'),
	(6, 'Montre intelligente hommes', '<div>Montre intelligente hommes femmes 1.6 plein écran tactile Bluetooth appel Smartwatch fréquence cardiaque tensiomètre pour Android et IOS</div>', '<div>a</div>', 9999, 0, 0, 1, 0, 'a4005a82d9e331ceabe0d851eef1857ca2c0854d.png', 1, '2022-01-31 19:19:29', NULL, 'montre-intelligente-hommes'),
	(7, 'Bluetooth appel Fitness', '<div>Plus étanche montre intelligente Sport Bracelet intelligent fréquence cardiaque moniteur de pression artérielle Fitness Tracker pour Android et IOS</div>', '<div>a</div>', 6999, 0, 1, 0, 0, 'aec78310e03391e900a37218bd09680d36e0a0e7.png', 1, '2022-01-31 19:20:14', NULL, 'bluetooth-appel-fitness'),
	(8, 'Bracelet intelligent', '<div>Plus étanche montre intelligente Sport Bracelet intelligent fréquence cardiaque moniteur de pression artérielle Fitness Tracker pour Android et IOS</div>', '<div>a</div>', 5999, 1, 0, 0, 0, 'c69077e129a77fa9ed8197d0163354745e8c6271.png', 1, '2022-01-31 19:20:53', NULL, 'bracelet-intelligent'),
	(9, 'Mode en acier inoxydable', '<div>Nouvelle mode en acier inoxydable bande lettre étoile lune oeil paume pendentif collier pour femmes charme femelle CZ bijoux cadeau</div>', '<div>a</div>', 8999, 1, 0, 0, 0, '61e9e76881686a5b7f4cec19e3b8b608982b33da.png', 1, '2022-01-31 19:22:57', NULL, 'mode-en-acier-inoxydable'),
	(10, 'Collier en acier inoxydable', '<div>Collier pendentif Vintage en acier inoxydable pour femmes, étoile de lune, breloque or, bijou CZ</div>', '<div>a</div>', 8999, 0, 1, 0, 0, 'f3ad8f2fff1b487774f26cb4d5e3ed21195f8d22.png', 1, '2022-01-31 19:24:33', NULL, 'collier-en-acier-inoxydable'),
	(11, 'Chaîne en cuivre plaqué or', '<div>Mode en acier inoxydable chaîne en cuivre plaqué or carré coeur pendentif collier pour les femmes charme femme pleine CZ bijoux collie</div>', '<div>a</div>', 8999, 0, 0, 1, 0, 'f019c011cd93d9d4116af61f19f6676f15b44f42.png', 1, '2022-01-31 19:25:42', NULL, 'chaine-en-cuivre-plaque-or'),
	(12, 'Acier inoxydable irrégulière', '<div>Collier</div>', '<div>a</div>', 8999, 0, 0, 0, 1, 'b76fb3f5cdfeabaf5e403573c058704f39d3bbca.png', 1, '2022-01-31 19:27:32', NULL, 'acier-inoxydable-irreguliere'),
	(13, 'Apple Original iPhone XS', '<div>IPhone X aucune identification de visage. Cela signifie que le téléphone n\'a pas de fonction face ID. Vous ne pouvez déverrouiller le téléphone qu\'en définissant le mot de passe dans le téléphone. Et d\'autres fonctions du téléphone fonctionnent complètement bien, si cela vous dérange, veuillez choisir iPhone X avec Face ID.</div>', '<div>a</div>', 88999, 0, 0, 0, 1, 'bdbb80bca9a43198d3bd38aff3b240a33122cf9e.png', 1, '2022-01-31 19:28:44', NULL, 'apple-original-iphone-xs'),
	(14, 'Apple iPhone XR Original 4G', '<div>Débloqué Apple iPhone XR Original 4G iOS rétine liquide entièrement écran LCD 12MP 6.1 \\"64GB/128GB/256GB visage ID Smartphones utilisés</div>', '<div>a</div>', 98999, 0, 0, 1, 0, '67a2c2766c73c4601e048afb334663407bebb80c.png', 1, '2022-01-31 19:30:30', NULL, 'apple-iphone-xr-original-4g'),
	(15, 'Original Apple iPhone X"', '<div>Débloqué Original Apple iPhone X Hexa Core Face ID 256GB/64GB ROM 3GB RAM double caméra arrière 12MP 5.8 \\"4G LTE Smartphones</div>', '<div>a</div>', 128999, 0, 1, 0, 0, 'e506535b6523797e55529d20745f8f5d0e0b58da.png', 1, '2022-01-31 19:31:21', NULL, 'original-apple-iphone-x'),
	(16, 'Téléphone portable d\'origine débloqué', '<div>Téléphone portable d\'origine débloqué Apple iPhone X Hexa Core 256GB/64GB ROM 3GB RAM double caméra arrière 12MP 5.8 \\"4G LTE Smartphone</div>', '<div>a</div>', 148999, 1, 0, 0, 0, 'e02b0bba4d38b3fe7436421bc1a4eb331fafe83c.png', 1, '2022-01-31 19:31:52', NULL, 'telephone-portable-dorigine-debloque'),
	(17, 'TUCCI 2020 nouveau Mini Drone 4K', '<div>TUCCI 2020 nouveau Mini Drone 4K 1080P HD caméra WiFi Fpv pression d\'air Altitude tenir pliable quadrirotor RC Drone enfant jouet cadeau</div>', '<div>a</div>', 5999, 1, 0, 0, 0, 'c20cc0a2e0e5ca156d2e3490b8cc3d43128b50ac.png', 1, '2022-01-31 19:32:32', NULL, 'tucci-2020-nouveau-mini-drone-4k'),
	(18, 'Aéronef sans pilote (UAV)', '<div>Aéronef sans pilote (UAV) Quadrocopter drone rc avec caméra 4K professionnel WIFI photographie aérienne grand Angle jouet télécommandé Ultra-longue durée</div>', '<div>a</div>', 8999, 0, 1, 0, 0, '369296b9026bc173eecd3bdbd038fa9ac990da9d.png', 1, '2022-01-31 19:33:15', NULL, 'aeronef-sans-pilote-uav'),
	(19, 'Nouveau Mini Drone 4K, TUCCI 2021', '<div>TUCCI 2020 nouveau Mini Drone 4K 1080P HD caméra WiFi Fpv pression d\'air Altitude tenir pliable quadrirotor RC Drone enfant jouet cadeau</div>', '<div>a</div>', 8999, 0, 0, 1, 0, '2dcdb4bbfa54da2fd25194e5a18c71d447ac57c2.png', 1, '2022-01-31 19:33:57', NULL, 'nouveau-mini-drone-4k-tucci-2021'),
	(20, 'Nouveau Mini Drone XT6 8K 5080P HD', '<div>TUCCI 2020 nouveau Mini Drone 4K 1080P HD caméra WiFi Fpv pression d\'air Altitude tenir pliable quadrirotor RC Drone enfant jouet cadeau</div>', '<div>a</div>', 38999, 0, 0, 0, 1, '4d313517fa6fe9e276c1ed174cdb36627b492fdc.png', 1, '2022-01-31 19:34:36', NULL, 'nouveau-mini-drone-xt6-8k-5080p-hd'),
	(21, 'Nouveau Mini Drone XT6 4K 1080P HD', '<div>Nouveau Mini Drone XT6 4K 1080P HD caméra WiFi Fpv pression d\'air Altitude tenir pliable quadrirotor RC Drone enfant jouet cadeau</div>', '<div>a</div>', 8999, 1, 0, 0, 0, 'd1f099990dcbca363c770c053228a8346bc3dac6.png', 1, '2022-01-31 19:34:59', NULL, 'nouveau-mini-drone-xt6-4k-1080p-hd'),
	(22, 'Coupe-légumes multifonctionnel', '<div>coupe-légumes multifonctionnel rond mandoline trancheuse pomme de terre fromage cuisine machine radis déchiqueteuse cuisine tambour hacher artefact petits accessoires accessoires de cuisine</div>', '<div>a</div>', 999, 0, 1, 0, 0, '779a8e0724a18cd5ad07bd195c519d1a64137db7.png', 1, '2022-01-31 19:35:30', NULL, 'coupe-legumes-multifonctionnel'),
	(23, 'Eplucheur de légumes multifonction', '<div>Éplucheur de légumes multifonction</div>', '<div>Éplucheur de légumes multifonction en acier inoxydable et coupeur ampJulienne Julienne éplucheur de pommes de terre carotte râpe outil de cuisine</div>', 8999, 0, 0, 1, 0, 'a92eb29f02076e7331ef344bd3d9d66dd99b939f.png', 1, '2022-01-31 19:36:00', NULL, 'Eplucheur-de-legumes-multifonction'),
	(24, 'Crêpière Antiadhésive Ensemble De Marmite', '<div>Crêpière Antiadhésive Ensemble De Marmite à Quatre trous Poêle À Frire Poêle Crêpe D\'oeuf Steak Épaissi Omelette Maker Ustensiles De Cuisine</div>', '<div>a</div>', 6999, 0, 0, 0, 1, 'fc5830cf95bf6554e4ae6dda96b20b4e7762a4c7.png', 1, '2022-01-31 19:37:03', NULL, 'crepiere-antiadhesive-ensemble-de-marmite'),
	(25, 'Trancheuse de légumes multifonctionnelle', '<div>Trancheuse de légumes multifonctionnelle éplucheur de pommes de terre ail mouture carotte oignon râpe avec crépine accessoires de cuisine outil de légumes</div>', '<div>a</div>', 2999, 0, 0, 1, 0, 'afbfdafb842b8dcbb8b4e6f794fc3fb24f5b3f08.png', 1, '2022-01-31 19:37:38', NULL, 'trancheuse-de-legumes-multifonctionnelle'),
	(26, 'NVIDA RTX 3090 MSI special Kokane', '<div>UwU</div>', '<div>UwU</div>', 199999, 0, 1, 0, 0, 'fb0ad035282a4790d49f40722d340b2bf6c5169c.jpg', 1, '2022-02-03 22:55:29', 'loak', 'nvida-rtx-3090-msi-special-kokane');
/*!40000 ALTER TABLE `product` ENABLE KEYS */;

-- Listage de la structure de la table aliexprass. product_categories
CREATE TABLE IF NOT EXISTS `product_categories` (
  `product_id` int(11) NOT NULL,
  `categories_id` int(11) NOT NULL,
  PRIMARY KEY (`product_id`,`categories_id`),
  KEY `IDX_A99419434584665A` (`product_id`),
  KEY `IDX_A9941943A21214B7` (`categories_id`),
  CONSTRAINT `FK_A99419434584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_A9941943A21214B7` FOREIGN KEY (`categories_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table aliexprass.product_categories : ~26 rows (environ)
/*!40000 ALTER TABLE `product_categories` DISABLE KEYS */;
INSERT INTO `product_categories` (`product_id`, `categories_id`) VALUES
	(1, 1),
	(2, 1),
	(3, 1),
	(4, 1),
	(5, 2),
	(6, 2),
	(7, 2),
	(8, 2),
	(9, 3),
	(10, 3),
	(11, 3),
	(12, 3),
	(13, 4),
	(14, 4),
	(15, 4),
	(16, 4),
	(17, 5),
	(18, 5),
	(19, 5),
	(20, 5),
	(21, 5),
	(22, 6),
	(23, 6),
	(24, 6),
	(25, 6),
	(26, 7);
/*!40000 ALTER TABLE `product_categories` ENABLE KEYS */;

-- Listage de la structure de la table aliexprass. reset_password_request
CREATE TABLE IF NOT EXISTS `reset_password_request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `selector` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hashed_token` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `requested_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `expires_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_7CE748AA76ED395` (`user_id`),
  CONSTRAINT `FK_7CE748AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table aliexprass.reset_password_request : ~0 rows (environ)
/*!40000 ALTER TABLE `reset_password_request` DISABLE KEYS */;
/*!40000 ALTER TABLE `reset_password_request` ENABLE KEYS */;

-- Listage de la structure de la table aliexprass. reviews_product
CREATE TABLE IF NOT EXISTS `reviews_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `note` int(11) NOT NULL,
  `comment` longtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `IDX_E0851D6CA76ED395` (`user_id`),
  KEY `IDX_E0851D6C4584665A` (`product_id`),
  CONSTRAINT `FK_E0851D6C4584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  CONSTRAINT `FK_E0851D6CA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table aliexprass.reviews_product : ~0 rows (environ)
/*!40000 ALTER TABLE `reviews_product` DISABLE KEYS */;
/*!40000 ALTER TABLE `reviews_product` ENABLE KEYS */;

-- Listage de la structure de la table aliexprass. user
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `firstname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_verified` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table aliexprass.user : ~0 rows (environ)
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` (`id`, `email`, `roles`, `password`, `username`, `firstname`, `lastname`, `is_verified`) VALUES
	(1, 'moloic@hotmail.fr', '[]', '$2y$13$znDQNVNC9tAKWOqTNYtEs.rhndXYljyMvaOx52.upq3QqPsu1aAKu', 'RyyyanZ', 'Loïc', 'MO', 1);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;

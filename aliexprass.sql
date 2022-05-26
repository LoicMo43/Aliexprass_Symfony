-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : jeu. 26 mai 2022 à 10:07
-- Version du serveur : 10.4.21-MariaDB
-- Version de PHP : 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `aliexprass`
--

-- --------------------------------------------------------

--
-- Structure de la table `address`
--

CREATE TABLE `address` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `fullname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `compagny` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `complement` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` int(11) NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_postal` int(11) NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `address`
--

INSERT INTO `address` (`id`, `user_id`, `fullname`, `compagny`, `address`, `complement`, `phone`, `city`, `code_postal`, `country`) VALUES
(1, 1, 'Symfony Magnarde', NULL, '87 Avenue Maréchal Foch', 'zeubi', 695569888, 'Le Puy-en-Velay', 43000, 'FR'),
(2, 1, 'Symfony Juliene', NULL, '97 Boulevard du Web', NULL, 695569843, 'Paris', 75000, 'FR');

-- --------------------------------------------------------

--
-- Structure de la table `calendar`
--

CREATE TABLE `calendar` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `all_day` tinyint(1) NOT NULL,
  `background_color` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL,
  `border_color` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL,
  `text_color` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `carrier`
--

CREATE TABLE `carrier` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` double NOT NULL,
  `created_at` datetime NOT NULL,
  `update_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `carrier`
--

INSERT INTO `carrier` (`id`, `name`, `description`, `price`, `created_at`, `update_at`) VALUES
(1, 'Chronopost', 'Fast delivery in 24hours', 1500, '2022-02-01 18:32:14', NULL),
(2, 'Collisimo', 'Fast delivery in 48hours', 799, '2022-02-01 18:33:01', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fullname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `carrier_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `carrier_price` double NOT NULL,
  `delivery_address` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_paid` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `more_informations` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `sub_total_ht` double NOT NULL,
  `taxe` double NOT NULL,
  `sub_total_ttc` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cart_details`
--

CREATE TABLE `cart_details` (
  `id` int(11) NOT NULL,
  `carts_id` int(11) NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_price` double NOT NULL,
  `quantity` int(11) NOT NULL,
  `sub_total_ht` double NOT NULL,
  `taxe` double NOT NULL,
  `sub_total_ttc` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `image`) VALUES
(1, 'bonnet', NULL, NULL),
(2, 'montre', NULL, NULL),
(3, 'bijou', NULL, NULL),
(4, 'portable', NULL, NULL),
(5, 'drone', NULL, NULL),
(6, 'gadget', NULL, NULL),
(7, 'Graphic card', '<div>Graphic card</div>', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20220526080337', '2022-05-26 08:03:53', 82);

-- --------------------------------------------------------

--
-- Structure de la table `home_slider`
--

CREATE TABLE `home_slider` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `button_message` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `button_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_displayed` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `home_slider`
--

INSERT INTO `home_slider` (`id`, `title`, `description`, `button_message`, `button_url`, `image`, `is_displayed`) VALUES
(1, 'Home 1', 'New article', 'Shop now', 'https://127.0.0.1:8000/product/nvida-rtx-3090-msi-special-kokane', 'f86481eefcb78ae4aecc6f7554ac331a83b1383f.png', 0),
(2, 'Home 2', 'New article', 'Shop now', 'https://127.0.0.1:8000/product/nvida-rtx-3090-msi-special-kokane', '36530afe60d4593c0a22db4ec755cf1012d1b8a9.png', 0),
(3, 'Home 3', 'New article', 'Shop now', 'https://127.0.0.1:8000/product/nvida-rtx-3090-msi-special-kokane', '7ee43d0a813f2932707119c081297fa28fdec681.png', 0);

-- --------------------------------------------------------

--
-- Structure de la table `order`
--

CREATE TABLE `order` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fullname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `carrier_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `carrier_price` double NOT NULL,
  `delivery_address` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_paid` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `more_informations` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `sub_total_ht` double NOT NULL,
  `taxe` double NOT NULL,
  `sub_total_ttc` double NOT NULL,
  `stripe_checkout_session_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `orders_id` int(11) NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_price` double NOT NULL,
  `quantity` int(11) NOT NULL,
  `sub_total_ht` double NOT NULL,
  `taxe` double NOT NULL,
  `sub_total_ttc` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
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
  `tags` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `product`
--

INSERT INTO `product` (`id`, `name`, `description`, `more_informations`, `price`, `is_best_seller`, `is_new_arrival`, `is_featured`, `is_special_offer`, `image`, `quantity`, `created_at`, `tags`, `slug`) VALUES
(1, 'Chapeau d\'hiver pour femmes', '<div>Chapeau d\'hiver pour femmes, chapeau en velours, épais, chaud, bonnet en tricot Chenille, chapeau d\'équitation, 2 casquettes en laine</div>', '<div>a</div>', 6999, 1, 0, 0, 0, 'd0c2ff065a1af86994f91e1a5ab72412a49a9574.png', 1, '2022-01-31 19:15:18', NULL, 'chapeau-dhiver-pour-femmes'),
(2, 'Bonnets tricoté pour femmes', '<div>Bonnets tricoté pour femmes, Bonnet de marque, épais et chaud, tête de mort en tricot, lettre, Bonnet, ensembles d\'équitation en plein air</div>', '<div>a</div>', 3999, 0, 1, 0, 0, '4265fce94b19d714e1946bc33841916b0a6a6aab.png', 1, '2022-01-31 19:16:08', NULL, 'bonnets-tricote-pour-femmes'),
(3, 'Chapeaux en laine de velours pour femmes', '<div>Chapeaux en laine de velours pour femmes, bonnet torsadé, bonnet assorti, tête de cheval, tricoté, vente en gros, nouveau hiver</div>', '<div>a</div>', 4999, 0, 0, 1, 0, 'df7243061b50b66cd7e72a3877a422f204b8a4de.png', 1, '2022-01-31 19:16:49', NULL, 'chapeaux-en-laine-de-velours-pour-femmes'),
(4, 'Chapeau en laine pour femmes', '<div>Chapeau en laine pour femmes, Bonnet, Patch épais et chaud, Bonnet tricoté multicolore, pour l\'hiver</div>', '<div>a</div>', 7999, 0, 0, 0, 1, 'f89d834afde969727b9ef41f5b63885616a6172d.png', 1, '2022-01-31 19:17:23', NULL, 'chapeau-en-laine-pour-femmes'),
(5, 'Montre intelligente hommes', '<div>Montre intelligente hommes femmes 1.6 plein écran tactile Bluetooth appel Smartwatch fréquence cardiaque tensiomètre pour Android et IOS\"</div>', '<div>a</div>', 9999, 0, 0, 0, 1, '5c64390ef5c9115f9328003007f402c2815cdf17.png', 1, '2022-01-31 19:18:31', NULL, 'montre-intelligente-hommes'),
(6, 'Montre intelligente hommes', '<div>Montre intelligente hommes femmes 1.6 plein écran tactile Bluetooth appel Smartwatch fréquence cardiaque tensiomètre pour Android et IOS</div>', '<div>a</div>', 9999, 0, 0, 1, 0, 'a4005a82d9e331ceabe0d851eef1857ca2c0854d.png', 1, '2022-01-31 19:19:29', NULL, 'montre-intelligente-hommes'),
(7, 'Bluetooth appel Fitness', '<div>Plus étanche montre intelligente Sport Bracelet intelligent fréquence cardiaque moniteur de pression artérielle Fitness Tracker pour Android et IOS</div>', '<div>a</div>', 6999, 0, 1, 0, 0, 'aec78310e03391e900a37218bd09680d36e0a0e7.png', 1, '2022-01-31 19:20:14', NULL, 'bluetooth-appel-fitness'),
(8, 'Bracelet intelligent', '<div>Plus étanche montre intelligente Sport Bracelet intelligent fréquence cardiaque moniteur de pression artérielle Fitness Tracker pour Android et IOS</div>', '<div>a</div>', 5999, 1, 0, 0, 0, 'c69077e129a77fa9ed8197d0163354745e8c6271.png', 1, '2022-01-31 19:20:53', NULL, 'bracelet-intelligent'),
(9, 'Mode en acier inoxydable', '<div>Nouvelle mode en acier inoxydable bande lettre étoile lune oeil paume pendentif collier pour femmes charme femelle CZ bijoux cadeau</div>', '<div>a</div>', 8999, 1, 0, 0, 0, '61e9e76881686a5b7f4cec19e3b8b608982b33da.png', 1, '2022-01-31 19:22:57', NULL, 'mode-en-acier-inoxydable'),
(10, 'Collier en acier inoxydable', '<div>Collier pendentif Vintage en acier inoxydable pour femmes, étoile de lune, breloque or, bijou CZ</div>', '<div>a</div>', 8999, 0, 1, 0, 0, 'f3ad8f2fff1b487774f26cb4d5e3ed21195f8d22.png', 1, '2022-01-31 19:24:33', NULL, 'collier-en-acier-inoxydable'),
(11, 'Chaîne en cuivre plaqué or', '<div>Mode en acier inoxydable chaîne en cuivre plaqué or carré coeur pendentif collier pour les femmes charme femme pleine CZ bijoux collie</div>', '<div>a</div>', 8999, 0, 0, 1, 0, 'f019c011cd93d9d4116af61f19f6676f15b44f42.png', 1, '2022-01-31 19:25:42', NULL, 'chaine-en-cuivre-plaque-or'),
(12, 'Acier inoxydable irrégulière', '<div>Collier</div>', '<div>a</div>', 8999, 0, 0, 0, 1, 'b76fb3f5cdfeabaf5e403573c058704f39d3bbca.png', 1, '2022-01-31 19:27:32', NULL, 'acier-inoxydable-irreguliere'),
(13, 'Apple Original iPhone XS', '<div>IPhone X aucune identification de visage. Cela signifie que le téléphone n\'a pas de fonction face ID. Vous ne pouvez déverrouiller le téléphone qu\'en définissant le mot de passe dans le téléphone. Et d\'autres fonctions du téléphone fonctionnent complètement bien, si cela vous dérange, veuillez choisir iPhone X avec Face ID.</div>', '<div>a</div>', 88999, 0, 0, 0, 1, 'bdbb80bca9a43198d3bd38aff3b240a33122cf9e.png', 1, '2022-01-31 19:28:44', NULL, 'apple-original-iphone-xs'),
(14, 'Apple iPhone XR Original 4G', '<div>Débloqué Apple iPhone XR Original 4G iOS rétine liquide entièrement écran LCD 12MP 6.1 \\\"64GB/128GB/256GB visage ID Smartphones utilisés</div>', '<div>a</div>', 98999, 0, 0, 1, 0, '67a2c2766c73c4601e048afb334663407bebb80c.png', 1, '2022-01-31 19:30:30', NULL, 'apple-iphone-xr-original-4g'),
(15, 'Original Apple iPhone X\"', '<div>Débloqué Original Apple iPhone X Hexa Core Face ID 256GB/64GB ROM 3GB RAM double caméra arrière 12MP 5.8 \\\"4G LTE Smartphones</div>', '<div>a</div>', 128999, 0, 1, 0, 0, 'e506535b6523797e55529d20745f8f5d0e0b58da.png', 1, '2022-01-31 19:31:21', NULL, 'original-apple-iphone-x'),
(16, 'Téléphone portable d\'origine débloqué', '<div>Téléphone portable d\'origine débloqué Apple iPhone X Hexa Core 256GB/64GB ROM 3GB RAM double caméra arrière 12MP 5.8 \\\"4G LTE Smartphone</div>', '<div>a</div>', 148999, 1, 0, 0, 0, 'e02b0bba4d38b3fe7436421bc1a4eb331fafe83c.png', 1, '2022-01-31 19:31:52', NULL, 'telephone-portable-dorigine-debloque'),
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

-- --------------------------------------------------------

--
-- Structure de la table `product_categories`
--

CREATE TABLE `product_categories` (
  `product_id` int(11) NOT NULL,
  `categories_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `product_categories`
--

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

-- --------------------------------------------------------

--
-- Structure de la table `reset_password_request`
--

CREATE TABLE `reset_password_request` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `selector` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hashed_token` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `requested_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `expires_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `reviews_product`
--

CREATE TABLE `reviews_product` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `note` int(11) NOT NULL,
  `comment` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:json)',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `firstname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_verified` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `username`, `firstname`, `lastname`, `is_verified`) VALUES
(1, 'moloic@hotmail.fr', '[\"ROLE_ADMIN\"]', '$2y$13$znDQNVNC9tAKWOqTNYtEs.rhndXYljyMvaOx52.upq3QqPsu1aAKu', 'RyyyanZ', 'Loïc', 'MO', 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_D4E6F81A76ED395` (`user_id`);

--
-- Index pour la table `calendar`
--
ALTER TABLE `calendar`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `carrier`
--
ALTER TABLE `carrier`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_AB912789A76ED395` (`user_id`);

--
-- Index pour la table `cart_details`
--
ALTER TABLE `cart_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_89FCC38DBCB5C6F5` (`carts_id`);

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `home_slider`
--
ALTER TABLE `home_slider`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_F5299398A76ED395` (`user_id`);

--
-- Index pour la table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_845CA2C1CFFE9AD6` (`orders_id`);

--
-- Index pour la table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`product_id`,`categories_id`),
  ADD KEY `IDX_A99419434584665A` (`product_id`),
  ADD KEY `IDX_A9941943A21214B7` (`categories_id`);

--
-- Index pour la table `reset_password_request`
--
ALTER TABLE `reset_password_request`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_7CE748AA76ED395` (`user_id`);

--
-- Index pour la table `reviews_product`
--
ALTER TABLE `reviews_product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_E0851D6CA76ED395` (`user_id`),
  ADD KEY `IDX_E0851D6C4584665A` (`product_id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `address`
--
ALTER TABLE `address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `calendar`
--
ALTER TABLE `calendar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `carrier`
--
ALTER TABLE `carrier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `cart_details`
--
ALTER TABLE `cart_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `home_slider`
--
ALTER TABLE `home_slider`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `order`
--
ALTER TABLE `order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT pour la table `reset_password_request`
--
ALTER TABLE `reset_password_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `reviews_product`
--
ALTER TABLE `reviews_product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `address`
--
ALTER TABLE `address`
  ADD CONSTRAINT `FK_D4E6F81A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `FK_AB912789A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `cart_details`
--
ALTER TABLE `cart_details`
  ADD CONSTRAINT `FK_89FCC38DBCB5C6F5` FOREIGN KEY (`carts_id`) REFERENCES `cart` (`id`);

--
-- Contraintes pour la table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `FK_F5299398A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `FK_845CA2C1CFFE9AD6` FOREIGN KEY (`orders_id`) REFERENCES `order` (`id`);

--
-- Contraintes pour la table `product_categories`
--
ALTER TABLE `product_categories`
  ADD CONSTRAINT `FK_A99419434584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_A9941943A21214B7` FOREIGN KEY (`categories_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `reset_password_request`
--
ALTER TABLE `reset_password_request`
  ADD CONSTRAINT `FK_7CE748AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `reviews_product`
--
ALTER TABLE `reviews_product`
  ADD CONSTRAINT `FK_E0851D6C4584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `FK_E0851D6CA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

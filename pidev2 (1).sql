-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 19 mars 2024 à 17:14
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `pidev2`
--

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `id` int(11) NOT NULL,
  `livreur_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `statut` varchar(255) NOT NULL,
  `prixtotale` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`id`, `livreur_id`, `user_id`, `statut`, `prixtotale`) VALUES
(1, 2, 1, 'en attente', 0),
(2, 2, 1, 'en attente', 10),
(3, 2, 2, 'en attente', 1176),
(4, 2, 2, 'en attente', 1176);

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) DEFAULT NULL,
  `contenu` varchar(255) NOT NULL,
  `nb_likes` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `defi`
--

CREATE TABLE `defi` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `des` varchar(255) NOT NULL,
  `nd` varchar(255) NOT NULL,
  `nbj` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `defi`
--

INSERT INTO `defi` (`id`, `nom`, `des`, `nd`, `nbj`) VALUES
(2, 'Aziz', 'dddddzzzzzz', '3', 7),
(3, 'hellno', 'pappiggggggggggggggggggggggggggggggggggggggggggggg', '1', 6),
(4, 'Aziz', 'pappi', '3', 14);

-- --------------------------------------------------------

--
-- Structure de la table `defi_exercice`
--

CREATE TABLE `defi_exercice` (
  `defi_id` int(11) NOT NULL,
  `exercice_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20240307185205', '2024-03-07 19:52:11', 132),
('DoctrineMigrations\\Version20240307223911', '2024-03-07 23:39:42', 24),
('DoctrineMigrations\\Version20240308014845', '2024-03-08 02:48:51', 39),
('DoctrineMigrations\\Version20240308040826', '2024-03-08 05:08:37', 7),
('DoctrineMigrations\\Version20240308053032', '2024-03-08 06:31:18', 55);

-- --------------------------------------------------------

--
-- Structure de la table `exercice`
--

CREATE TABLE `exercice` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `des` longtext NOT NULL,
  `mc` varchar(255) NOT NULL,
  `nd` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL,
  `gif` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `exercice`
--

INSERT INTO `exercice` (`id`, `nom`, `des`, `mc`, `nd`, `img`, `gif`) VALUES
(1, 'Développé couché', 'Le “bench press” ou développé couché est l’un des premiers exercices que la plupart des pratiquants apprennent lorsqu’ils commencent la musculation. C’est de loin l’exercice le plus populaire effectué dans la plupart des salles de sport. Êtes-vous déjà allé un lundi dans une salle de muscu pour essayer de faire cet exercice sur banc ? Ce n’est tout simplement pas possible.\r\n\r\nLe problème, c’est que je vois rarement des personnes le faire correctement.\r\n\r\nLe développé couché est pratiqué comme un exercice pour le haut du corps, ciblant principalement les muscles pectoraux, et secondairement les triceps et épaules. Mais lorsqu’il est effectué correctement, il permet de travailler tout le corps, avec une sollicitation des jambes, un gainage de la sangle abdominale, et une stabilisation de la ceinture scapulaire.\r\n\r\nCependant, lorsqu’il est mal exécuté, il peut causer de graves déséquilibres musculaires qui entraînent des douleurs chroniques à l’épaule. Inutile donc de vous dire que cela va compliquer la plupart de vos activités quotidiennes.', 'Pectoraux', '3', 'Front/images/exo/developpe-couche-exercice-pectoraux.jpg', 'Front/images/exo/gif/developpe-couche.gif'),
(2, 'Chest press avec sangles de suspension', 'Le chest press avec sangles de suspension fait non seulement travailler les pectoraux, mais il sollicite aussi les triceps et la partie antérieure des épaules. La réalisation de cet exercice en suspension sollicite votre corps dans des conditions d’instabilité importante. Cela vous oblige à engager constamment vos abdominaux pour effectuer le mouvement. Une sangle abdominale plus forte améliore la posture, aide à soulager les douleurs lombaires et réduit les risques de blessure. C’est un aspect très important pour le fitness fonctionnel. Si vous n’avez pas accès à une salle de sport, c’est un exercice parfait pour travailler vos pectoraux à la maison.', 'Pectoraux', '1', 'Front/images/exo/chest-press-avec-sangles-de-suspension-musculation-pectoraux.jpg', 'Front/images/exo/gif/chest-press-avec-sangles-suspension.gif'),
(17, 'Aziz', 'validation', 'Pectoraux', '1', 'Front/images/exo/65d8ba130c16a.jpg', 'Front/images/exo/gif/65d8ba130d067.gif'),
(18, 'dddd', 'ffffffffffffffffff', 'Pectoraux', '2', 'Front/images/exo/65e61189e2cc6.jpg', 'Front/images/exo/gif/65e61189e4acf.gif'),
(19, 'dddd', 'ffffffffffffffffff', 'Pectoraux', '2', 'Front/images/exo/65e611d9baeb0.jpg', 'Front/images/exo/gif/65e611d9bb1cf.gif'),
(20, 'zdzd', 'dddddddddddddddd', 'Pectoraux', '2', 'Front/images/exo/65e6124960186.jpg', 'Front/images/exo/gif/65e6124960438.gif');

-- --------------------------------------------------------

--
-- Structure de la table `livreur`
--

CREATE TABLE `livreur` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `disponibilite` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `note` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `livreur`
--

INSERT INTO `livreur` (`id`, `nom`, `prenom`, `disponibilite`, `image`, `note`) VALUES
(2, 'saif', 'bouhamed', 'indisponible', 'Front/images/65ea838f0b345.png', 4),
(3, 'YAHYA', 'Benaicha', 'indisponible', 'Front/images/65ea837c9a4df.png', 4),
(4, 'Bachta', 'Akrem', 'indisponible', 'Front/images/65ea9ad189603.png', 4);

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `panier`
--

CREATE TABLE `panier` (
  `id` int(11) NOT NULL,
  `prix_tot` int(11) NOT NULL,
  `quantite` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `panier`
--

INSERT INTO `panier` (`id`, `prix_tot`, `quantite`, `user_id`) VALUES
(1, 40, 2, 1),
(2, 0, 3, 2),
(3, 0, 0, 3),
(4, 0, 0, 4),
(6, 0, 0, 6),
(7, 0, 0, 7),
(8, 0, 0, 8),
(9, 0, 0, 9);

-- --------------------------------------------------------

--
-- Structure de la table `panier_produits`
--

CREATE TABLE `panier_produits` (
  `panier_id` int(11) NOT NULL,
  `produits_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `pdf_file`
--

CREATE TABLE `pdf_file` (
  `id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_data` longblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `pdf_file`
--

INSERT INTO `pdf_file` (`id`, `file_name`, `file_data`) VALUES
(1, 'commandes.pdf', 0x255044462d312e330a332030206f626a0a3c3c2f54797065202f506167650a2f506172656e742031203020520a2f4d65646961426f78205b302030203834312e3839203539352e32385d0a2f5265736f75726365732032203020520a2f436f6e74656e74732034203020523e3e0a656e646f626a0a342030206f626a0a3c3c2f46696c746572202f466c6174654465636f6465202f4c656e677468203139363e3e0a73747265616d0a789c7dd0bb6ec2401005d09eafb825140c3b33de875b049182e858ea088545221241f283fc7e6c83c150d0ad467bcf3c04ab9121ebf1379a47cc3e18ecc818c40396b12da97a52079b79ca1de21ee3f5b1ac12f6a9c4f7f974dafd36af09e2cfed7f47c88390406a6135900d08964c86e9b556246cd07660e21c560c05ee1a7c2e7a8f5949f3b7696ee61519c637d5aeaaab3b9107cab427d81b32e1d510c3246e68ac8f9722d5458fa86bd2fc7e0bcfa4d9d0d896a9f87aecd2dd469e6ef30faa5856650a656e6473747265616d0a656e646f626a0a312030206f626a0a3c3c2f54797065202f50616765730a2f4b696473205b3320302052205d0a2f436f756e7420310a2f4d65646961426f78205b302030203539352e3238203834312e38395d0a3e3e0a656e646f626a0a352030206f626a0a3c3c2f46696c746572202f466c6174654465636f6465202f4c656e677468203336343e3e0a73747265616d0a789c5d52cb6e833010bcf3153ea687084c1a82258444499038f4a1d27e008125452a061972e0efbbbb76d2aa4858e3b16776566b3f2f8fa5ee17e1bf99b1a960115daf5b03f378350d88335c7aedc950b47db3b81dafcd504f9e8fe26a9d17184add8d5e92f8ef78362f66159bac1dcff0e0f9afa605d3eb8bd87ce615eeabeb347dc3007a118197a6a2850e7d9eebe9a51e40f82cdb962d9ef7cbba45cdef8d8f750211f25eda2ccdd8c23cd50d985a5fc04b8220154951a41ee8f6df596415e7eeefd543814b805fea257184383ee0120621114a2256211332266247c4a325722248a2ac44ee90c85c7d32454c316e05a5ba0568be6a83e5029665e413bb2219612a1248ac8bd8d53a11dedb6411e198ee843963c5fc8e5bc8581b317eb2bc229c33bf67cf13e3c391f23b4fe295f53c725fec292def3c2561e7493995f3a4b695f3a49caab03876dd73b7340e7a30f73937576370c4fcaa78b634d55ec3fde14de3442afa7f007f0db6a90a656e6473747265616d0a656e646f626a0a362030206f626a0a3c3c2f54797065202f466f6e740a2f42617365466f6e74202f48656c7665746963612d426f6c640a2f53756274797065202f54797065310a2f456e636f64696e67202f57696e416e7369456e636f64696e670a2f546f556e69636f64652035203020520a3e3e0a656e646f626a0a372030206f626a0a3c3c2f54797065202f466f6e740a2f42617365466f6e74202f48656c7665746963610a2f53756274797065202f54797065310a2f456e636f64696e67202f57696e416e7369456e636f64696e670a2f546f556e69636f64652035203020520a3e3e0a656e646f626a0a322030206f626a0a3c3c0a2f50726f63536574205b2f504446202f54657874202f496d61676542202f496d61676543202f496d616765495d0a2f466f6e74203c3c0a2f46312036203020520a2f46322037203020520a3e3e0a2f584f626a656374203c3c0a3e3e0a3e3e0a656e646f626a0a382030206f626a0a3c3c0a2f50726f647563657220284650444620312e3832290a2f4372656174696f6e446174652028443a3230323430333038303332303130290a3e3e0a656e646f626a0a392030206f626a0a3c3c0a2f54797065202f436174616c6f670a2f50616765732031203020520a3e3e0a656e646f626a0a787265660a302031300a303030303030303030302036353533352066200a30303030303030333833203030303030206e200a30303030303031313335203030303030206e200a30303030303030303039203030303030206e200a30303030303030313137203030303030206e200a30303030303030343730203030303030206e200a30303030303030393034203030303030206e200a30303030303031303232203030303030206e200a30303030303031323439203030303030206e200a30303030303031333235203030303030206e200a747261696c65720a3c3c0a2f53697a652031300a2f526f6f742039203020520a2f496e666f2038203020520a3e3e0a7374617274787265660a313337340a2525454f460a),
(2, 'commandes.pdf', 0x255044462d312e330a332030206f626a0a3c3c2f54797065202f506167650a2f506172656e742031203020520a2f4d65646961426f78205b302030203834312e3839203539352e32385d0a2f5265736f75726365732032203020520a2f436f6e74656e74732034203020523e3e0a656e646f626a0a342030206f626a0a3c3c2f46696c746572202f466c6174654465636f6465202f4c656e677468203234353e3e0a73747265616d0a789c7d90c14ec3300c86ef7b0a1fe18089ed244d8e438000edb670d809555b9086b422b519bc3e5dbbb2a89a728bacfc9ffd7f0c6f0b85a682dfc54380fb6702b2a814844f780aa791488562c1e80abd85b0839bd5be4b1176b183edf7e15037fdeb16c2d7f9ff80e00b821d8a01230e8d03675069b81b676d84359c36109207c30a1d0d0b5e1f271e91a0f8629afa7b99f3f83ad5e998fe11dea19609419542e5e60c56846c73c66affd3c6633b41c4f6692ab7a80845e78cf72eb61f972e831bbee68614b22eb9d19e518ff269aea6103eabc9d2b1813aa5d8a438d733628a7a32ce66f9b259cee5946a8c72aef5f803bf9e8e500a656e6473747265616d0a656e646f626a0a312030206f626a0a3c3c2f54797065202f50616765730a2f4b696473205b3320302052205d0a2f436f756e7420310a2f4d65646961426f78205b302030203539352e3238203834312e38395d0a3e3e0a656e646f626a0a352030206f626a0a3c3c2f46696c746572202f466c6174654465636f6465202f4c656e677468203336343e3e0a73747265616d0a789c5d52cb6e833010bcf3153ea687084c1a82258444499038f4a1d27e008125452a061972e0efbbbb76d2aa4858e3b16776566b3f2f8fa5ee17e1bf99b1a960115daf5b03f378350d88335c7aedc950b47db3b81dafcd504f9e8fe26a9d17184add8d5e92f8ef78362f66159bac1dcff0e0f9afa605d3eb8bd87ce615eeabeb347dc3007a118197a6a2850e7d9eebe9a51e40f82cdb962d9ef7cbba45cdef8d8f750211f25eda2ccdd8c23cd50d985a5fc04b8220154951a41ee8f6df596415e7eeefd543814b805fea257184383ee0120621114a2256211332266247c4a325722248a2ac44ee90c85c7d32454c316e05a5ba0568be6a83e5029665e413bb2219612a1248ac8bd8d53a11dedb6411e198ee843963c5fc8e5bc8581b317eb2bc229c33bf67cf13e3c391f23b4fe295f53c725fec292def3c2561e7493995f3a4b695f3a49caab03876dd73b7340e7a30f73937576370c4fcaa78b634d55ec3fde14de3442afa7f007f0db6a90a656e6473747265616d0a656e646f626a0a362030206f626a0a3c3c2f54797065202f466f6e740a2f42617365466f6e74202f48656c7665746963612d426f6c640a2f53756274797065202f54797065310a2f456e636f64696e67202f57696e416e7369456e636f64696e670a2f546f556e69636f64652035203020520a3e3e0a656e646f626a0a372030206f626a0a3c3c2f54797065202f466f6e740a2f42617365466f6e74202f48656c7665746963610a2f53756274797065202f54797065310a2f456e636f64696e67202f57696e416e7369456e636f64696e670a2f546f556e69636f64652035203020520a3e3e0a656e646f626a0a322030206f626a0a3c3c0a2f50726f63536574205b2f504446202f54657874202f496d61676542202f496d61676543202f496d616765495d0a2f466f6e74203c3c0a2f46312036203020520a2f46322037203020520a3e3e0a2f584f626a656374203c3c0a3e3e0a3e3e0a656e646f626a0a382030206f626a0a3c3c0a2f50726f647563657220284650444620312e3832290a2f4372656174696f6e446174652028443a3230323430333038303432303036290a3e3e0a656e646f626a0a392030206f626a0a3c3c0a2f54797065202f436174616c6f670a2f50616765732031203020520a3e3e0a656e646f626a0a787265660a302031300a303030303030303030302036353533352066200a30303030303030343332203030303030206e200a30303030303031313834203030303030206e200a30303030303030303039203030303030206e200a30303030303030313137203030303030206e200a30303030303030353139203030303030206e200a30303030303030393533203030303030206e200a30303030303031303731203030303030206e200a30303030303031323938203030303030206e200a30303030303031333734203030303030206e200a747261696c65720a3c3c0a2f53697a652031300a2f526f6f742039203020520a2f496e666f2038203020520a3e3e0a7374617274787265660a313432330a2525454f460a);

-- --------------------------------------------------------

--
-- Structure de la table `post`
--

CREATE TABLE `post` (
  `id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` varchar(255) NOT NULL,
  `date` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `image` varchar(255) NOT NULL,
  `views` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `post`
--

INSERT INTO `post` (`id`, `author_id`, `title`, `content`, `date`, `image`, `views`) VALUES
(1, 2, 'HI', 'HELLOOOOOOOOOOOOOOOOOOOOOOO', '2024-03-08 01:37:49', 'Front/images/post/65ea5ddd72f4d.png', 1);

-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

CREATE TABLE `produits` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `quantite_stock` int(11) NOT NULL,
  `prix` double NOT NULL,
  `categories` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `produits`
--

INSERT INTO `produits` (`id`, `nom`, `description`, `quantite_stock`, `prix`, `categories`, `image`) VALUES
(7, 'dumbbells', 'Very helpful and comfortable to train', 50, 100, 'articles', 'Front/images/65eae0c950ebf.jpg'),
(8, 'Tapis', 'Trés haute qualité', 80, 75, 'articles', 'Front/images/65eae0ec6efbb.jpg'),
(9, 'Proteine', 'Helps you create muscles', 75, 52, 'articles', 'Front/images/65eae14e15d07.jpg'),
(10, 'Complement sportif', 'augmenter les performances  physique', 40, 85, 'articles', 'Front/images/65eae1c260512.jpg'),
(11, 'Basketball', 'Official NBA basketball with enhanced grip', 30, 29.99, 'articles', 'Front/images/65eae2111f022.png');

-- --------------------------------------------------------

--
-- Structure de la table `reservation`
--

CREATE TABLE `reservation` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `session_id` int(11) DEFAULT NULL,
  `date` datetime NOT NULL,
  `etat` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `session`
--

CREATE TABLE `session` (
  `id` int(11) NOT NULL,
  `coach_id` int(11) DEFAULT NULL,
  `date` datetime NOT NULL,
  `type` varchar(255) NOT NULL,
  `cap` int(11) NOT NULL,
  `vid` varchar(255) NOT NULL,
  `des` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `pwd` varchar(255) NOT NULL,
  `date_n` date NOT NULL,
  `role` varchar(255) NOT NULL,
  `adress` varchar(255) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `num_tel` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `nom`, `prenom`, `email`, `pwd`, `date_n`, `role`, `adress`, `photo`, `status`, `num_tel`) VALUES
(1, 'admin', 'admin', 'admin@admin.com', '$2y$13$PHld.8RrIaAYhqXRS8ughO9d1Q2v1.bEYV.eyCKaTiB82USoR0Xzi', '2020-01-01', 'admin', 'dkfhgudhhkfkfghjklfghj', 'Front/images/65ea0daac2171.png', 'active', '02145963'),
(2, 'BenAbda', 'AZIZ', 'mohamedazizbenabda@gmail.com', '$2y$13$24mvxjUA1.g/wx3CYEraFO.DthnXlu.s0RrHpOHJ0a/0Zx3mTiNNi', '2019-01-01', 'client', 'ariana, la petite ariana', 'Front/images/65ea36d236345.png', 'active', '55614560'),
(3, 'maram', 'bouaziz', 'maram.bouaziz@esprit.tn', '$2y$13$iq72M6lv0yEwwRV7/DyugOz.ATUFnjcrBfW0bWz33sm4vgICC1mqa', '2019-01-01', 'client', 'mourouj3,tunis,dfghjkl', 'Front/images/65ea48f128b95.png', 'active', '55614560'),
(4, 'SAMAR', 'WESLATI', 'samar.weslati@esprit.tn', '$2y$13$GnSm/xc6xORv7gIezKj6SOHmahAaf8qCk.UAkKBxAOH6F8OQV.ARa', '2019-01-01', 'client', 'ariana, la petite ariana', 'Front/images/65ea4b8671c27.png', 'inactive', '547526365'),
(6, 'rayen', 'bouzekri', 'rayen.bouzekri@esprit.tn', '$2y$13$Fh..YoVg6R6hm/vPk9fQDeF7xWI5rtqfFG/aN.a7Qo2qIuWxqKG4a', '2019-01-01', 'client', 'ariana, la petite ariana', 'Front/images/65ea4fed695df.png', 'inactive', '9520258653'),
(7, 'Bouaziz', 'sami', 'sami.bouaziz@gmail.com', '$2y$13$8qy0bleDOg3A2Ya4Fb/bjemYDnymrv5pWHLVdebbcNpLkFlGKaIQu', '2019-01-01', 'client', 'ariana, la petite ariana', 'Front/images/65ea62a2663d0.jpg', 'active', '53220612'),
(8, 'SAMIA', 'BENMECHLIA', 'benmechliasamia@gmail.com', '$2y$13$MWjcO1txuWKg5b8Nmbd.3e8EiVhfo.dITX9SlKxRSDnfDnP4P79/G', '2019-01-01', 'client', 'mourouj3,tunis,dfghjkl', 'Front/images/65ea9993dfa7b.png', 'inactive', '96574852'),
(9, 'SAMIA', 'BENMECHLIA', 'benmechlia@gmail.com', '$2y$13$.QCyysCp21pNEIy/AtGL4OmgX8S8gcWHhHpbcpu14/dtn/jHcM9hG', '2019-01-01', 'coach', 'dkfhgudhhkfkfghjklfghj', 'Front/images/65eaa648a5cda.png', 'active', '96574852');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_6EEAA67DF8646701` (`livreur_id`),
  ADD KEY `IDX_6EEAA67DA76ED395` (`user_id`);

--
-- Index pour la table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_9474526CA76ED395` (`user_id`),
  ADD KEY `IDX_9474526C4B89032C` (`post_id`);

--
-- Index pour la table `defi`
--
ALTER TABLE `defi`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `defi_exercice`
--
ALTER TABLE `defi_exercice`
  ADD PRIMARY KEY (`defi_id`,`exercice_id`),
  ADD KEY `IDX_C88D081073F00F27` (`defi_id`),
  ADD KEY `IDX_C88D081089D40298` (`exercice_id`);

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `exercice`
--
ALTER TABLE `exercice`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `livreur`
--
ALTER TABLE `livreur`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Index pour la table `panier`
--
ALTER TABLE `panier`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_24CC0DF2A76ED395` (`user_id`);

--
-- Index pour la table `panier_produits`
--
ALTER TABLE `panier_produits`
  ADD PRIMARY KEY (`panier_id`,`produits_id`),
  ADD KEY `IDX_2468D6FEF77D927C` (`panier_id`),
  ADD KEY `IDX_2468D6FECD11A2CF` (`produits_id`);

--
-- Index pour la table `pdf_file`
--
ALTER TABLE `pdf_file`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_5A8A6C8DF675F31B` (`author_id`);

--
-- Index pour la table `produits`
--
ALTER TABLE `produits`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_42C84955A76ED395` (`user_id`),
  ADD KEY `IDX_42C84955613FECDF` (`session_id`);

--
-- Index pour la table `session`
--
ALTER TABLE `session`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_D044D5D43C105691` (`coach_id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `defi`
--
ALTER TABLE `defi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `exercice`
--
ALTER TABLE `exercice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `livreur`
--
ALTER TABLE `livreur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `panier`
--
ALTER TABLE `panier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `pdf_file`
--
ALTER TABLE `pdf_file`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `post`
--
ALTER TABLE `post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `produits`
--
ALTER TABLE `produits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `session`
--
ALTER TABLE `session`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `FK_6EEAA67DA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_6EEAA67DF8646701` FOREIGN KEY (`livreur_id`) REFERENCES `livreur` (`id`);

--
-- Contraintes pour la table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `FK_9474526C4B89032C` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`),
  ADD CONSTRAINT `FK_9474526CA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `defi_exercice`
--
ALTER TABLE `defi_exercice`
  ADD CONSTRAINT `FK_C88D081073F00F27` FOREIGN KEY (`defi_id`) REFERENCES `defi` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_C88D081089D40298` FOREIGN KEY (`exercice_id`) REFERENCES `exercice` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `panier`
--
ALTER TABLE `panier`
  ADD CONSTRAINT `FK_24CC0DF2A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `panier_produits`
--
ALTER TABLE `panier_produits`
  ADD CONSTRAINT `FK_2468D6FECD11A2CF` FOREIGN KEY (`produits_id`) REFERENCES `produits` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_2468D6FEF77D927C` FOREIGN KEY (`panier_id`) REFERENCES `panier` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `FK_5A8A6C8DF675F31B` FOREIGN KEY (`author_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `FK_42C84955613FECDF` FOREIGN KEY (`session_id`) REFERENCES `session` (`id`),
  ADD CONSTRAINT `FK_42C84955A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `session`
--
ALTER TABLE `session`
  ADD CONSTRAINT `FK_D044D5D43C105691` FOREIGN KEY (`coach_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

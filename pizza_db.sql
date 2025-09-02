-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 29 août 2025 à 08:20
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `pizza_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `allergenes`
--

CREATE TABLE `allergenes` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `code` varchar(3) NOT NULL,
  `couleur` varchar(7) DEFAULT '#ffebee',
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `allergenes`
--

INSERT INTO `allergenes` (`id`, `nom`, `code`, `couleur`, `description`) VALUES
(1, 'Gluten', 'GLU', '#f44336', 'Présent dans le blé, le seigle, l\'orge, etc.'),
(2, 'Crustacés', 'CRU', '#ff9800', 'Crevettes, crabes, homards, etc.'),
(3, 'Œufs', 'OEU', '#ffc107', 'Présents dans les mayonnaises, pâtes, etc.'),
(4, 'Poisson', 'POI', '#009688', 'Saumon, thon, cabillaud, etc.'),
(5, 'Arachides', 'ARA', '#795548', 'Cacahuètes et produits dérivés.'),
(6, 'Soja', 'SOJ', '#4caf50', 'Tofu, sauce soja, huile de soja, etc.'),
(7, 'Lait', 'LAI', '#2196f3', 'Fromage, crème, beurre, yaourt, etc.'),
(8, 'Fruits à coque', 'FRU', '#9c27b0', 'Noix, amandes, noisettes, etc.'),
(9, 'Céleri', 'CEL', '#607d8b', 'Céleri-rave, céleri branche, etc.'),
(10, 'Moutarde', 'MOU', '#ffeb3b', 'Moutarde en grain, sauce moutarde, etc.'),
(11, 'Graines de sésame', 'SES', '#00bcd4', 'Huile de sésame, tahini, etc.'),
(12, 'Lupin', 'LUP', '#8bc34a', 'Farine de lupin, graines de lupin.'),
(13, 'Mollusques', 'MOL', '#009688', 'Moules, huîtres, calamars, etc.'),
(14, 'Dioxyde de soufre', 'SO2', '#ff5722', 'Conservateur dans les fruits secs, vin, etc.'),
(15, 'Tartrazine', 'TAR', '#e91e63', 'Colorant alimentaire (E102).');

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `adresse` text DEFAULT NULL,
  `numero_carte` varchar(50) DEFAULT NULL,
  `points_clients` int(11) DEFAULT 0,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_modification` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `clients`
--

INSERT INTO `clients` (`id`, `nom`, `telephone`, `adresse`, `numero_carte`, `points_clients`, `date_creation`, `date_modification`) VALUES
(4, 'Jean', '02 02 02 02 02', '2 rue de la ville', '1', 0, '2025-08-29 06:20:19', '2025-08-29 06:20:19');

-- --------------------------------------------------------

--
-- Structure de la table `client_allergenes`
--

CREATE TABLE `client_allergenes` (
  `client_id` int(11) NOT NULL,
  `allergene_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `client_allergenes`
--

INSERT INTO `client_allergenes` (`client_id`, `allergene_id`) VALUES
(4, 8),
(4, 9);

-- --------------------------------------------------------

--
-- Structure de la table `pizzas`
--

CREATE TABLE `pizzas` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `prix` decimal(6,2) NOT NULL,
  `image` varchar(255) DEFAULT 'default.jpg',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `pizzas`
--

INSERT INTO `pizzas` (`id`, `nom`, `description`, `prix`, `image`, `created_at`, `updated_at`) VALUES
(1, 'Margherita', 'Sauce tomate, champignons.', 8.90, 'http://localhost/Cyber_SLAM/images/margherita.jpg', '2025-08-27 16:30:08', '2025-08-29 05:42:13'),
(2, 'Pepperoni', 'Sauce tomate, mozzarella, pepperoni épicé.', 10.50, 'http://localhost/Cyber_SLAM/images/pepperoni.jpg', '2025-08-27 16:30:08', '2025-08-28 18:25:39'),
(3, 'Hawaïenne', 'Sauce tomate, mozzarella, jambon, ananas.', 9.90, 'http://localhost/Cyber_SLAM/images/hawaiienne.jpg', '2025-08-27 16:30:08', '2025-08-28 15:59:41'),
(4, 'Quatre Fromages', 'Crème fraîche, mozzarella, gorgonzola, chèvre, emmental.', 11.90, 'http://localhost/Cyber_SLAM/images/quatre-fromages.jpg', '2025-08-27 16:30:08', '2025-08-28 15:59:44'),
(5, 'Végétarienne', 'Sauce tomate, mozzarella, poivrons, champignons, olives, oignons.', 10.20, 'http://localhost/Cyber_SLAM/images/vegetarienne.jpg', '2025-08-27 16:30:08', '2025-08-28 15:59:47'),
(6, 'Calzone', 'Sauce tomate, mozzarella, jambon, champignons, plié en demi-lune.', 12.50, 'https://raw.githubusercontent.com/BTS-SIO-Saint-Louis-PEINTE/Cyber_Pizza/refs/heads/main/images/calzone.jpg', '2025-08-27 16:30:08', '2025-08-29 05:42:20');

-- --------------------------------------------------------

--
-- Structure de la table `pizza_allergene`
--

CREATE TABLE `pizza_allergene` (
  `pizza_id` int(11) NOT NULL,
  `allergene_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `pizza_allergene`
--

INSERT INTO `pizza_allergene` (`pizza_id`, `allergene_id`) VALUES
(1, 7),
(2, 7),
(3, 7),
(4, 1),
(4, 7),
(5, 1),
(5, 7),
(5, 9),
(6, 1),
(6, 3),
(6, 7);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'admin', 'admin123', '2025-08-27 12:50:33');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `allergenes`
--
ALTER TABLE `allergenes`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_carte` (`numero_carte`);

--
-- Index pour la table `client_allergenes`
--
ALTER TABLE `client_allergenes`
  ADD PRIMARY KEY (`client_id`,`allergene_id`),
  ADD KEY `allergene_id` (`allergene_id`);

--
-- Index pour la table `pizzas`
--
ALTER TABLE `pizzas`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `pizza_allergene`
--
ALTER TABLE `pizza_allergene`
  ADD PRIMARY KEY (`pizza_id`,`allergene_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `allergenes`
--
ALTER TABLE `allergenes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `pizzas`
--
ALTER TABLE `pizzas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `client_allergenes`
--
ALTER TABLE `client_allergenes`
  ADD CONSTRAINT `client_allergenes_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `client_allergenes_ibfk_2` FOREIGN KEY (`allergene_id`) REFERENCES `allergenes` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

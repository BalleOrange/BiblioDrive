-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 12 jan. 2024 à 12:48
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `biblio`
--

-- --------------------------------------------------------

--
-- Structure de la table `auteur`
--

CREATE TABLE `auteur` (
  `noauteur` int(11) NOT NULL,
  `nom` varchar(40) NOT NULL,
  `prenom` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `auteur`
--

INSERT INTO `auteur` (`noauteur`, `nom`, `prenom`) VALUES
(1, 'Barjavel', 'René'),
(3, 'Camus', 'Albert');

-- --------------------------------------------------------

--
-- Structure de la table `emprunter`
--

CREATE TABLE `emprunter` (
  `mel` varchar(40) NOT NULL,
  `nolivre` int(11) NOT NULL,
  `dateemprunt` date NOT NULL,
  `dateretour` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `livre`
--

CREATE TABLE `livre` (
  `nolivre` int(11) NOT NULL,
  `noauteur` int(11) NOT NULL,
  `titre` varchar(128) NOT NULL,
  `isbn13` char(13) NOT NULL,
  `anneeparution` int(11) NOT NULL,
  `resume` text NOT NULL,
  `dateajout` date DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `livre`
--

INSERT INTO `livre` (`nolivre`, `noauteur`, `titre`, `isbn13`, `anneeparution`, `resume`, `dateajout`, `image`) VALUES
(1, 1, 'Ravage', '1234567891234', 1943, 'dsfdsfsfdsfdsfdsfdsf', '2023-12-24', 'ravage.jpg'),
(2, 1, 'La Nuite Des Temps', '1234567891235', 1968, 'Ce roman raconte l\'histoire de deux scientifiques, qui découvrent, enfouis sous les glaces de l\'Antarctique, deux êtres humains en état de congélation depuis plus de 900 000 ans. Barjavel nous offre une immersion totale dans cette découverte extraordinaire qui ébranle les fondements mêmes de la civilisation moderne.', '2023-11-24', 'la_nuit_des_temps.jpg'),
(7, 1, 'Le grand secret', '7412589634587', 1973, '1955, Jeanne et Roland, amants, vivent une histoire d\'amour idyllique, mais un jour Roland disparaît mystérieusement... Le mystère du grand secret gravite autour de ce couple, aussi Jeanne décide de tout quitter, par conséquent elle sacrifie sa vie de famille pour retrouver l\'amour de sa vie Roland.', '2023-12-08', 'grand-secret.jpg'),
(13, 3, 'Caligula - Le malentendu', '9782070360642', 1972, 'CALIGULA : C\'est une vérité toute simple et toute claire, un peu bête, mais difficile à découvrir et lourde à porter.\r\nHÉLICON : Et qu\'est-ce donc que cette vérité, Caïus ?\r\nCALIGULA : Les hommes meurent et ils ne sont pas heureux.\r\nHÉLICON: Allons, Caïus, c\'est une vérité dont on s\'arrange très bien. Regarde autour de toi. Ce n\'est pas cela qui les empêche de déjeuner.\r\nCALIGULA : Alors, c\'est que tout, autour de moi, est mensonge, et moi, je veux qu\'on vive dans la vérité !\r\nSource : Folio, Gallimard', '2024-01-12', 'Caligula.png'),
(14, 3, 'Discours de Suède', '9782070401215', 1997, 'On aura peut-être été un peu surpris de voir dans ces discours l\'accent porté par Camus sur la défense de l\'art et la liberté de l\'artiste en même temps que sur la solidarité qui s\'impose à lui. Cela faisait certes partie de ce qui lui dictaient les circonstances et le milieu où il devait les prononcer, mais il est certain que Camus se sentait accablé par une situation où, selon ses propres paroles, le silence même prend un sens redoutable. A partir du moment où l\'abstention elle-même est considérée comme un choix, puni ou loué comme tel, l\'artiste, qu\'il le veuille ou non, est embarqué. Embarqué me paraît ici plus juste qu\'engagé. Et malgré une certaine éloquence -qu\'on lui reprochait également- il se sentait profondément concerné et douloureusement atteint par un conflit qui le touchait jusque dans sa chair et dans ses affections les plus enracinées.', '2024-01-12', 'Discours-de-Suede.png'),
(15, 3, 'La Peste', '9782070360420', 1972, '« Je relis La Peste, lentement – pour la troisième fois. C’est un très grand livre, et qui grandira. Je me réjouis du succès qu’il obtient – mais le vrai succès sera dans la durée, et par l’enseignement par la beauté », écrit Louis Guilloux en juillet 1947 à son ami Albert Camus à propos du roman sorti en librairie le 10 juin. Retour sur la genèse de La Peste.\r\nLouis Guilloux, rencontré chez Gallimard au cours de l’été 1945, et Jean Grenier, ancien professeur de philosophie d’Albert Camus à Alger, furent les témoins de l’écriture du roman, commencé au cours de l’été 1942 et achevé en décembre 1946. Le 22 septembre 1942, Albert Camus écrit à Jean Grenier qu’il travaille « à une sorte de roman sur la peste », et poursuit quelques jours après : « Ce que j’écris sur la peste n’est pas documentaire, bien entendu, mais je me suis fait une documentation assez sérieuse, historique et médicale, parce qu’on y trouve des “prétextes”. » De fait, le roman est en gestation depuis plusieurs années. Camus – dont les premières notes sur le sujet ont été prises fin 1938 –, s’est abondamment documenté sur les grandes pestes de l’histoire dans le courant du mois d’octobre 1940. Son projet se précise dans ses Carnets en avril 1941, où figurent la mention « Peste ou aventure (roman) », suivi d’un développement portant le titre La Peste libératrice.', '2024-01-12', 'La-Peste.png'),
(16, 3, 'L\'étranger', '9782070360024', 1972, 'Albert Camus\r\nL\'Étranger\r\n\r\nCondamné à mort, Meursault. Sur une plage algérienne, il a tué un Arabe. À cause du soleil, dira-t-il, parce qu\'il faisait chaud. On n\'en tirera rien d\'autre. Rien ne le fera plus réagir : ni l\'annonce de sa condamnation, ni la mort de sa mère, ni les paroles du prêtre avant la fin.\r\n\r\nComme si, sur cette plage, il avait soudain eu la révélation de l\'universelle équivalence du tout et du rien.', '2024-01-12', 'LEtranger.png');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `mel` varchar(40) NOT NULL,
  `motdepasse` varchar(100) NOT NULL,
  `nom` varchar(40) NOT NULL,
  `prenom` varchar(40) NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `ville` varchar(40) NOT NULL,
  `codepostal` int(11) NOT NULL,
  `profil` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`mel`, `motdepasse`, `nom`, `prenom`, `adresse`, `ville`, `codepostal`, `profil`) VALUES
('admin@mail.com', '$argon2i$v=19$m=65536,t=4,p=1$SmVLOEVjWjdTN1RIY1lrSw$X5/d0748pQPiUiGN7m2sYcEUvUGsvMCUfzqh2s5dy9E', 'ADMIN', 'Admin', '2, rue admin', 'Saint-Brieuc', 22000, 'Administrateur'),
('membre@mail.com', '$argon2i$v=19$m=65536,t=4,p=1$S1ZVcVcxU0Rkc1dvNGpLbA$nF+99MYtF1YlZfdUe0LaEaqV0ahlpMtdRen9aDkeKho', 'MEMBRE', 'Membre', '1, rue d', 'SAINT BRIEUC', 22000, 'Membre'),
('test@mail.com', '$argon2i$v=19$m=65536,t=4,p=1$SXRDYi4vbjF3dENnTk9wNg$KDDtKvVmFOjZuwp8oTLmlDdumqbBTDFJwpoKO3LOJ6s', 'TEST', 'Test', '1, test', 'VILLE', 22000, 'Membre');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `auteur`
--
ALTER TABLE `auteur`
  ADD PRIMARY KEY (`noauteur`);

--
-- Index pour la table `emprunter`
--
ALTER TABLE `emprunter`
  ADD PRIMARY KEY (`mel`,`nolivre`,`dateemprunt`),
  ADD KEY `fk_emprunter_livre` (`nolivre`);

--
-- Index pour la table `livre`
--
ALTER TABLE `livre`
  ADD PRIMARY KEY (`nolivre`),
  ADD KEY `fk_livre_auteur` (`noauteur`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`mel`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `auteur`
--
ALTER TABLE `auteur`
  MODIFY `noauteur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `livre`
--
ALTER TABLE `livre`
  MODIFY `nolivre` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `emprunter`
--
ALTER TABLE `emprunter`
  ADD CONSTRAINT `fk_emprunter_livre` FOREIGN KEY (`nolivre`) REFERENCES `livre` (`nolivre`),
  ADD CONSTRAINT `fk_emprunter_utilisateur` FOREIGN KEY (`mel`) REFERENCES `utilisateur` (`mel`);

--
-- Contraintes pour la table `livre`
--
ALTER TABLE `livre`
  ADD CONSTRAINT `fk_livre_auteur` FOREIGN KEY (`noauteur`) REFERENCES `auteur` (`noauteur`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

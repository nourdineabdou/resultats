-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le :  ven. 09 fév. 2024 à 02:19
-- Version du serveur :  5.7.24
-- Version de PHP :  7.2.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `db_unisoft`
--

-- --------------------------------------------------------

--
-- Structure de la table `annees`
--

CREATE TABLE `annees` (
  `id` int(11) NOT NULL,
  `annee` varchar(10) CHARACTER SET utf8 NOT NULL,
  `libelle` varchar(100) DEFAULT NULL,
  `code` varchar(6) DEFAULT NULL,
  `numero` int(11) DEFAULT NULL,
  `numeroMst` int(11) DEFAULT NULL,
  `etat` int(11) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `anonymats`
--

CREATE TABLE `anonymats` (
  `id` int(11) NOT NULL,
  `anonymat` varchar(10) NOT NULL,
  `profil_id` int(11) NOT NULL,
  `etudiant_id` int(11) NOT NULL,
  `nodos` varchar(10) NOT NULL,
  `etape_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `anonymatsconcours`
--

CREATE TABLE `anonymatsconcours` (
  `id` int(11) NOT NULL,
  `anonymat` varchar(10) NOT NULL,
  `candidat_id` int(11) NOT NULL,
  `pacquet` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `bachalier`
--

CREATE TABLE `bachalier` (
  `id` int(11) NOT NULL,
  `imported_id` int(11) DEFAULT NULL,
  `nobac` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nni` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nompl` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `datn` date DEFAULT NULL,
  `lieu` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nat` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `annee` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `bacheliers`
--

CREATE TABLE `bacheliers` (
  `id` int(11) NOT NULL,
  `nobac` varchar(20) DEFAULT NULL,
  `nodoss` varchar(20) DEFAULT NULL,
  `nompl` varchar(40) DEFAULT NULL,
  `nompa` varchar(40) DEFAULT NULL,
  `datn` varchar(100) DEFAULT NULL,
  `lieu` varchar(200) DEFAULT NULL,
  `lieuna` varchar(200) DEFAULT NULL,
  `sexe` varchar(1) DEFAULT NULL,
  `nat` varchar(4) DEFAULT 'S',
  `serie` varchar(3) DEFAULT NULL,
  `noetec` varchar(6) DEFAULT NULL,
  `ville` varchar(30) DEFAULT NULL,
  `inde` varchar(10) DEFAULT NULL,
  `moyg1` double DEFAULT NULL,
  `moyg2` double DEFAULT NULL,
  `session` varchar(3) DEFAULT NULL,
  `noreg` varchar(2) DEFAULT NULL,
  `noprfl` varchar(5) DEFAULT NULL,
  `nni` varchar(20) DEFAULT NULL,
  `annee` varchar(10) DEFAULT NULL,
  `etat` int(11) NOT NULL DEFAULT '1',
  `tel` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `bureaus`
--

CREATE TABLE `bureaus` (
  `id` int(11) NOT NULL,
  `libelle` varchar(100) DEFAULT NULL,
  `libelle_ar` varchar(100) DEFAULT NULL,
  `centre_id` int(11) DEFAULT NULL,
  `commune_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `candidats`
--

CREATE TABLE `candidats` (
  `id` int(11) NOT NULL,
  `tel` varchar(20) DEFAULT NULL,
  `nompl` varchar(200) DEFAULT NULL,
  `datn` date DEFAULT NULL,
  `lieu` varchar(100) DEFAULT NULL,
  `ref_genre_id` int(11) DEFAULT NULL,
  `ref_nationnalite_id` int(11) DEFAULT '1',
  `serie` varchar(3) DEFAULT NULL,
  `noetec` varchar(6) DEFAULT NULL,
  `ville` varchar(30) DEFAULT NULL,
  `inde` varchar(10) DEFAULT NULL,
  `nni` varchar(100) DEFAULT NULL,
  `annee_id` int(10) DEFAULT NULL,
  `salle_id` int(11) DEFAULT NULL,
  `etat` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `centres`
--

CREATE TABLE `centres` (
  `id` int(11) NOT NULL,
  `libelle` varchar(100) DEFAULT NULL,
  `libelle_ar` varchar(100) DEFAULT NULL,
  `comunne_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `communes`
--

CREATE TABLE `communes` (
  `id` int(11) NOT NULL,
  `libelle` varchar(45) NOT NULL DEFAULT '',
  `libelle_ar` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `adresse_GPS` text,
  `contour_gps` text,
  `nbr_habitans` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `classe_population` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `moughataa_id` int(10) NOT NULL,
  `code` varchar(20) NOT NULL,
  `nom_Maire` varchar(50) NOT NULL,
  `nom_SG` varchar(50) NOT NULL,
  `surface` int(11) NOT NULL DEFAULT '0',
  `nbr_villages_localites` int(11) DEFAULT NULL,
  `decret_de_creation` varchar(80) DEFAULT NULL,
  `nbr_conseillers_municipaux` int(11) DEFAULT NULL,
  `nbr_employes_municipaux_permanents` int(11) DEFAULT NULL,
  `nbr_employes_municipaux_temporaires` int(11) DEFAULT NULL,
  `secretaire_generale` tinyint(4) DEFAULT NULL,
  `pnidelle` tinyint(1) DEFAULT NULL,
  `organisations_internationale` tinyint(1) DEFAULT NULL,
  `recettes_impots` tinyint(1) DEFAULT NULL,
  `eclairage_public` tinyint(1) DEFAULT NULL,
  `path_carte` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `concours`
--

CREATE TABLE `concours` (
  `id` int(11) NOT NULL,
  `libelle` text,
  `nbre_admis` int(11) DEFAULT NULL,
  `nbre_attent` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `departements`
--

CREATE TABLE `departements` (
  `id` int(11) NOT NULL,
  `faculte_id` int(11) NOT NULL,
  `code` varchar(20) CHARACTER SET utf8 NOT NULL,
  `libelle` varchar(100) CHARACTER SET utf8 NOT NULL,
  `libelle_ar` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `etat_saisi` int(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `elections`
--

CREATE TABLE `elections` (
  `id` int(11) NOT NULL,
  `centre` varchar(100) DEFAULT NULL,
  `ref_type_election` int(11) DEFAULT NULL,
  `centre_id` int(11) DEFAULT NULL,
  `bureau` varchar(100) DEFAULT NULL,
  `bureau_id` int(11) DEFAULT NULL,
  `parti` text,
  `inscrits` int(11) DEFAULT NULL,
  `votants` int(11) DEFAULT NULL,
  `cartenuls` int(11) DEFAULT NULL,
  `suffragesexprimés` int(11) DEFAULT NULL,
  `nbvoixobtenues` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `commune` varchar(100) DEFAULT NULL,
  `commune_id` int(11) DEFAULT NULL,
  `moughataa` varchar(100) DEFAULT NULL,
  `moughataa_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `entete_etablissements`
--

CREATE TABLE `entete_etablissements` (
  `id` int(11) NOT NULL,
  `faculte_id` int(11) NOT NULL,
  `titre1` varchar(100) CHARACTER SET utf8mb4 NOT NULL,
  `titre1_ar` varchar(100) CHARACTER SET utf8mb4 NOT NULL,
  `titre2` varchar(100) CHARACTER SET utf8mb4 NOT NULL,
  `titre2_ar` varchar(100) CHARACTER SET utf8mb4 NOT NULL,
  `titre3` varchar(100) CHARACTER SET utf8mb4 NOT NULL,
  `titre3_ar` varchar(100) CHARACTER SET utf8mb4 NOT NULL,
  `logo` varchar(100) CHARACTER SET utf8mb4 NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `etapes`
--

CREATE TABLE `etapes` (
  `id` int(11) NOT NULL,
  `faculte_id` int(11) NOT NULL,
  `ref_diplome_id` int(11) NOT NULL,
  `ref_mode_saisie_id` int(11) NOT NULL,
  `ref_type_controle_id` int(11) DEFAULT '1',
  `code` varchar(20) NOT NULL,
  `libelle` varchar(100) CHARACTER SET utf8 NOT NULL,
  `libelle_ar` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `coaf` decimal(10,0) DEFAULT NULL,
  `note` int(11) NOT NULL DEFAULT '0',
  `ordre` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `etudiants`
--

CREATE TABLE `etudiants` (
  `id` int(11) NOT NULL,
  `NODOS` varchar(110) CHARACTER SET utf8 NOT NULL,
  `NOMF` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `NOMA` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `AD1` varchar(30) DEFAULT NULL,
  `AD2` varchar(30) DEFAULT NULL,
  `AD3` varchar(6) DEFAULT NULL,
  `SITF` varchar(1) DEFAULT NULL,
  `DATN` longtext,
  `LIEUNA` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `LIEUNF` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `ref_nationnalite_id` int(11) NOT NULL,
  `DIPL` varchar(20) DEFAULT NULL,
  `DDIPL` varchar(4) DEFAULT NULL,
  `BVP` varchar(20) DEFAULT NULL,
  `DVP` varchar(20) DEFAULT NULL,
  `BAC` varchar(3) DEFAULT NULL,
  `TEL` varchar(10) DEFAULT NULL,
  `NOBAC` varchar(20) DEFAULT NULL,
  `DBAC` varchar(4) DEFAULT NULL,
  `MOYB` double DEFAULT NULL,
  `DATEU` varchar(4) DEFAULT NULL,
  `ACTIF` tinyint(1) DEFAULT NULL,
  `DERRONO` varchar(8) DEFAULT NULL,
  `NOGPE` varchar(2) DEFAULT NULL,
  `profil_id` int(11) NOT NULL,
  `LGUE` varchar(1) DEFAULT NULL,
  `SEXE` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `SITH` varchar(1) DEFAULT NULL,
  `SITB` varchar(1) DEFAULT NULL,
  `NODECB` varchar(10) DEFAULT NULL,
  `REGETUD` varchar(1) DEFAULT NULL,
  `NOANO` varchar(6) DEFAULT NULL,
  `RNOANO` varchar(6) DEFAULT NULL,
  `INDR` varchar(18) DEFAULT NULL,
  `RINDR` varchar(18) DEFAULT NULL,
  `SINDR` varchar(18) DEFAULT NULL,
  `DATIX` datetime DEFAULT NULL,
  `NOHSALLE` varchar(80) CHARACTER SET utf8 DEFAULT NULL,
  `NOPLACE` double DEFAULT NULL,
  `MCC` double DEFAULT NULL,
  `ME1` double DEFAULT NULL,
  `MAD1` double DEFAULT NULL,
  `MO1` double DEFAULT NULL,
  `MF1` double DEFAULT NULL,
  `ME2` double DEFAULT NULL,
  `MAD2` double DEFAULT NULL,
  `MO2` double DEFAULT NULL,
  `MF2` double DEFAULT NULL,
  `MMEM` double DEFAULT NULL,
  `MANN` double DEFAULT NULL,
  `DECF` varchar(1) DEFAULT '0',
  `DECF_1` varchar(1) DEFAULT NULL,
  `DATCRE` datetime DEFAULT NULL,
  `DATMAJ` datetime DEFAULT NULL,
  `INDINSB` tinyint(1) DEFAULT NULL,
  `NORECB` varchar(5) DEFAULT NULL,
  `INDPB` varchar(12) DEFAULT NULL,
  `faculte_id` int(1) DEFAULT NULL,
  `nodep` varchar(2) DEFAULT NULL,
  `Annee` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `indanpv` tinyint(1) DEFAULT NULL,
  `Ment` varchar(30) DEFAULT NULL,
  `mentA` varchar(30) DEFAULT NULL,
  `Session` varchar(30) DEFAULT NULL,
  `SessionA` varchar(30) DEFAULT NULL,
  `Transferer` tinyint(1) DEFAULT NULL,
  `Option` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `S1` int(11) DEFAULT '0',
  `S2` int(11) DEFAULT '0',
  `CRD` int(11) DEFAULT NULL,
  `SALLE` varchar(6) DEFAULT NULL,
  `Hr` varchar(2) DEFAULT NULL,
  `Abs` tinyint(1) DEFAULT '0',
  `LG` varchar(1) DEFAULT NULL,
  `PAYS` longtext CHARACTER SET utf8,
  `NNI` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `photo` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `groupe` varchar(1) CHARACTER SET utf8 DEFAULT 'ا',
  `email` varchar(100) DEFAULT NULL,
  `adress` varchar(40) DEFAULT NULL,
  `whatsapp` varchar(40) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `etud_annee_credits`
--

CREATE TABLE `etud_annee_credits` (
  `id` int(11) NOT NULL,
  `etudiant_id` int(11) DEFAULT NULL,
  `libelle` int(11) DEFAULT NULL,
  `annee_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `etud_mats`
--

CREATE TABLE `etud_mats` (
  `id` int(11) NOT NULL,
  `etudiant_id` int(11) NOT NULL,
  `ref_groupe_id` int(11) DEFAULT NULL,
  `NODOS` varchar(7) DEFAULT NULL,
  `profil_id` int(11) NOT NULL,
  `Code` varchar(6) DEFAULT NULL COMMENT 'code de module',
  `NOMAT` varchar(100) DEFAULT NULL,
  `ref_semestre_id` int(11) NOT NULL,
  `AB` tinyint(1) DEFAULT '0',
  `CRD` double DEFAULT NULL,
  `credit` double DEFAULT '0',
  `Nfe` double DEFAULT '0',
  `matiere_id` int(11) NOT NULL,
  `annee_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `etud_semestres`
--

CREATE TABLE `etud_semestres` (
  `id` int(11) NOT NULL,
  `etudiant_id` int(11) NOT NULL,
  `NODOS` varchar(7) DEFAULT NULL,
  `NOMF` varchar(70) DEFAULT NULL,
  `NOMA` varchar(70) DEFAULT NULL,
  `profil_id` int(11) NOT NULL,
  `ref_semestre_id` int(11) NOT NULL,
  `NOANO` varchar(6) DEFAULT NULL,
  `RNOANO` varchar(6) DEFAULT NULL,
  `INDR` varchar(18) DEFAULT NULL,
  `RINDR` varchar(18) DEFAULT NULL,
  `NOHSALLE` varchar(8) DEFAULT NULL,
  `NOPLACE` double DEFAULT NULL,
  `SALLE` varchar(6) DEFAULT NULL,
  `Hr` varchar(2) DEFAULT NULL,
  `Abs` tinyint(1) DEFAULT '0',
  `Lg` varchar(1) DEFAULT NULL,
  `annee_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `exemples`
--

CREATE TABLE `exemples` (
  `id` int(11) NOT NULL,
  `libelle` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `facultes`
--

CREATE TABLE `facultes` (
  `id` int(11) NOT NULL,
  `code` varchar(40) CHARACTER SET utf8 NOT NULL,
  `libelle` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `libelle_ar` varchar(100) CHARACTER SET utf8 NOT NULL,
  `libelle_court` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `libelle_court_ar` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `nom_resp` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `nom_resp_ar` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `etat` int(11) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `ged`
--

CREATE TABLE `ged` (
  `id` int(11) NOT NULL,
  `libelle` varchar(120) DEFAULT NULL,
  `emplacement` text NOT NULL,
  `objet_id` int(11) NOT NULL,
  `type` int(11) NOT NULL DEFAULT '1' COMMENT '1:employe;',
  `extension` varchar(50) NOT NULL,
  `ref_types_document_id` int(11) NOT NULL,
  `commentaire` text,
  `taille` int(11) DEFAULT NULL,
  `type_ged` int(11) DEFAULT NULL COMMENT '1:image profile,2:autre',
  `ordre` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `matieres`
--

CREATE TABLE `matieres` (
  `id` int(11) NOT NULL,
  `profil_id` int(11) NOT NULL,
  `modulle_id` int(11) NOT NULL,
  `ref_semestre_id` int(11) NOT NULL,
  `ref_langue_id` int(11) NOT NULL,
  `libelle` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `libelle_ar` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `libelle_court` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `libelle_court_ar` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `code` varchar(20) CHARACTER SET utf8 NOT NULL,
  `coaf` float NOT NULL DEFAULT '0',
  `credit` float NOT NULL DEFAULT '0',
  `date` date DEFAULT NULL,
  `existe` int(11) DEFAULT '1',
  `tp` int(11) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `matieres_concours`
--

CREATE TABLE `matieres_concours` (
  `id` int(11) NOT NULL,
  `libelle` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `libelle_ar` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `libelle_court` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `libelle_court_ar` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `code` varchar(20) NOT NULL,
  `note` float NOT NULL,
  `coaf` double(10,0) NOT NULL DEFAULT '0',
  `credit` double(10,0) NOT NULL DEFAULT '0',
  `date` date DEFAULT NULL,
  `existe` int(11) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `matieres_profils_etapes`
--

CREATE TABLE `matieres_profils_etapes` (
  `id` int(11) NOT NULL,
  `profil_id` int(11) NOT NULL,
  `etape_id` int(11) NOT NULL,
  `matiere_id` int(11) NOT NULL,
  `ref_semestre_id` int(11) NOT NULL,
  `coef` decimal(10,0) DEFAULT NULL,
  `optionnelle` int(11) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `matiere_salle_etudiants`
--

CREATE TABLE `matiere_salle_etudiants` (
  `id` int(11) NOT NULL,
  `salle_id` int(11) DEFAULT NULL,
  `matiere_id` int(11) NOT NULL,
  `annee_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `etudiant_id` int(11) DEFAULT NULL,
  `etud_mat_id` int(11) DEFAULT NULL,
  `profil_id` int(11) DEFAULT NULL,
  `groupe_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `modules`
--

CREATE TABLE `modules` (
  `id` int(11) NOT NULL,
  `libelle` varchar(255) CHARACTER SET utf8 NOT NULL,
  `libelle_ar` varchar(100) CHARACTER SET utf8 NOT NULL,
  `is_externe` tinyint(2) NOT NULL DEFAULT '0',
  `lien` varchar(255) NOT NULL,
  `icone` varchar(50) DEFAULT NULL,
  `bg_color` varchar(50) DEFAULT NULL,
  `text_color` varchar(50) NOT NULL,
  `sys_groupes_traitement_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `modulles`
--

CREATE TABLE `modulles` (
  `id` int(11) NOT NULL,
  `profil_id` int(11) NOT NULL,
  `faculte_id` int(11) NOT NULL,
  `ref_semestre_id` int(11) NOT NULL DEFAULT '1',
  `code` varchar(20) NOT NULL,
  `libelle` varchar(100) CHARACTER SET utf8 NOT NULL,
  `libelle_ar` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `nbre` int(11) DEFAULT NULL,
  `coaf` float DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `moussabakas`
--

CREATE TABLE `moussabakas` (
  `date` varchar(512) CHARACTER SET utf8 DEFAULT NULL,
  `nom` varchar(512) CHARACTER SET utf8 DEFAULT NULL,
  `nometd` varchar(500) CHARACTER SET utf8 DEFAULT NULL,
  `nni` varchar(110) CHARACTER SET utf8 DEFAULT NULL,
  `id` varchar(110) CHARACTER SET utf8 DEFAULT NULL,
  `etat` int(11) DEFAULT NULL,
  `etudiants` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `moyennes_semestres`
--

CREATE TABLE `moyennes_semestres` (
  `id` int(11) NOT NULL,
  `etudiant_id` int(11) NOT NULL,
  `profil_id` int(11) NOT NULL,
  `ref_groupe_id` int(11) NOT NULL,
  `ref_semestre_id` int(11) NOT NULL,
  `note` float NOT NULL,
  `annee_id` int(11) NOT NULL,
  `decision` int(11) DEFAULT NULL COMMENT '1:valide,0:no valide',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `moyennes_sortants`
--

CREATE TABLE `moyennes_sortants` (
  `id` int(11) NOT NULL,
  `etudiant_id` int(11) DEFAULT NULL,
  `profil_id` int(11) NOT NULL,
  `ref_groupe_id` int(11) DEFAULT NULL,
  `niveau` int(11) DEFAULT NULL,
  `note` float DEFAULT NULL,
  `annee_id` int(11) DEFAULT NULL,
  `decision` int(11) DEFAULT NULL COMMENT '1:valide,0:no valide',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `note_concours`
--

CREATE TABLE `note_concours` (
  `id` int(11) NOT NULL,
  `salle_id` int(11) DEFAULT NULL,
  `pacquet` int(11) NOT NULL,
  `matieres_concour_id` int(11) NOT NULL,
  `note1` float DEFAULT NULL,
  `note2` float DEFAULT NULL,
  `note3` float DEFAULT NULL,
  `note` float DEFAULT NULL,
  `etat_note3` int(11) DEFAULT NULL,
  `annee_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `candidat_id` int(11) NOT NULL,
  `anonymat_id` int(11) NOT NULL,
  `pacquet3` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `note_concours_finales`
--

CREATE TABLE `note_concours_finales` (
  `id` int(11) NOT NULL,
  `salle_id` int(11) DEFAULT NULL,
  `pacquet` int(11) NOT NULL,
  `note` float DEFAULT NULL,
  `annee_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `candidat_id` int(11) NOT NULL,
  `anonymat_id` int(11) NOT NULL,
  `elimine` int(11) DEFAULT NULL,
  `etat` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `note_devoirs`
--

CREATE TABLE `note_devoirs` (
  `id` int(11) NOT NULL,
  `etudiant_id` int(11) NOT NULL,
  `anonymat_id` int(11) DEFAULT NULL,
  `profil_id` int(11) NOT NULL,
  `ref_groupe_id` int(11) NOT NULL,
  `ref_semestre_id` int(11) NOT NULL,
  `etape_id` int(11) NOT NULL,
  `matiere_id` int(11) NOT NULL,
  `note` float NOT NULL,
  `annee_id` int(11) NOT NULL,
  `etat` varchar(2) DEFAULT NULL COMMENT 'a valide inque auqune modif ',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `note_examens`
--

CREATE TABLE `note_examens` (
  `id` int(11) NOT NULL,
  `etudiant_id` int(11) NOT NULL,
  `anonymat_id` int(11) DEFAULT NULL,
  `profil_id` int(11) NOT NULL,
  `ref_groupe_id` int(11) NOT NULL,
  `ref_semestre_id` int(11) NOT NULL,
  `etape_id` int(11) NOT NULL,
  `matiere_id` int(11) NOT NULL,
  `note` float NOT NULL,
  `annee_id` int(11) NOT NULL,
  `etat` varchar(2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `note_examen_finales`
--

CREATE TABLE `note_examen_finales` (
  `id` int(11) NOT NULL,
  `etudiant_id` int(11) NOT NULL,
  `anonymat_id` int(11) DEFAULT NULL,
  `profil_id` int(11) NOT NULL,
  `ref_groupe_id` int(11) NOT NULL,
  `ref_semestre_id` int(11) NOT NULL,
  `etape_id` int(11) DEFAULT NULL COMMENT '0 ancien annee',
  `matiere_id` int(11) NOT NULL,
  `note_dev` float DEFAULT NULL,
  `note_exam` float DEFAULT NULL,
  `note_rt` float DEFAULT NULL,
  `note` float NOT NULL,
  `annee_id` int(11) NOT NULL,
  `modulle_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `note_examen_rts`
--

CREATE TABLE `note_examen_rts` (
  `id` int(11) NOT NULL,
  `profil_id` int(11) NOT NULL,
  `ref_groupe_id` int(11) NOT NULL,
  `ref_semestre_id` int(11) NOT NULL,
  `etape_id` int(11) NOT NULL,
  `matiere_id` int(11) NOT NULL,
  `note` float NOT NULL,
  `etat` varchar(2) DEFAULT NULL,
  `annee_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `etudiant_id` int(11) NOT NULL,
  `anonymat_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `note_modifier`
--

CREATE TABLE `note_modifier` (
  `id` int(11) NOT NULL,
  `etudiant_id` int(11) NOT NULL,
  `profil_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ref_groupe_id` int(11) NOT NULL,
  `ref_semestre_id` int(11) NOT NULL,
  `etape_id` int(11) NOT NULL,
  `matiere_id` int(11) NOT NULL,
  `oldnote` float NOT NULL,
  `newnote` float NOT NULL,
  `annee_id` int(11) NOT NULL,
  `etat` varchar(100) DEFAULT NULL,
  `machine` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `orientations`
--

CREATE TABLE `orientations` (
  `id` int(11) NOT NULL,
  `matier_id` int(11) DEFAULT NULL,
  `profil_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `plages`
--

CREATE TABLE `plages` (
  `id` int(11) NOT NULL,
  `profil_id` int(11) NOT NULL,
  `ref_semestre_id` int(11) NOT NULL,
  `etape_id` int(11) NOT NULL,
  `nb` int(11) DEFAULT NULL,
  `debut` int(11) DEFAULT '0',
  `fin` int(11) DEFAULT '0',
  `valide` int(11) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `profils`
--

CREATE TABLE `profils` (
  `id` int(11) NOT NULL,
  `code` varchar(20) CHARACTER SET utf8 NOT NULL,
  `libelle` varchar(100) CHARACTER SET utf8 NOT NULL,
  `libelle_ar` varchar(100) CHARACTER SET utf8 NOT NULL,
  `departement_id` int(11) NOT NULL,
  `ref_niveau_etude_id` int(11) NOT NULL,
  `profil_progression` int(11) DEFAULT '1',
  `ref_semestre_id` int(11) NOT NULL,
  `etape_id` int(11) NOT NULL,
  `faculte_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `profil_groupe_annees`
--

CREATE TABLE `profil_groupe_annees` (
  `id` int(11) NOT NULL,
  `profil_id` int(11) NOT NULL,
  `groupe_id` int(11) NOT NULL,
  `annee_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `profil_orientations`
--

CREATE TABLE `profil_orientations` (
  `id` int(11) NOT NULL,
  `profil_id` int(11) DEFAULT NULL,
  `proil_cible` int(11) DEFAULT NULL,
  `nbrdivision` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `profil_semestres`
--

CREATE TABLE `profil_semestres` (
  `id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `libelle` varchar(100) CHARACTER SET utf8 NOT NULL,
  `libelle_ar` varchar(100) CHARACTER SET utf8 NOT NULL,
  `departement_id` int(11) NOT NULL,
  `ref_niveau_etude_id` int(11) NOT NULL,
  `profil_progression` int(11) DEFAULT '1',
  `ref_semestre_id` int(11) NOT NULL,
  `etape_id` int(11) NOT NULL,
  `faculte_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `ref_diplomes`
--

CREATE TABLE `ref_diplomes` (
  `id` int(11) NOT NULL,
  `libelle` varchar(100) NOT NULL,
  `libelle_ar` varchar(100) CHARACTER SET utf32 NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `ref_genres`
--

CREATE TABLE `ref_genres` (
  `id` int(11) NOT NULL,
  `libelle` varchar(80) NOT NULL,
  `libelle_ar` varchar(80) NOT NULL,
  `ordre` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `ref_groupes`
--

CREATE TABLE `ref_groupes` (
  `id` int(11) NOT NULL,
  `libelle` varchar(100) CHARACTER SET utf8 NOT NULL,
  `libelle_ar` varchar(100) CHARACTER SET utf8 NOT NULL,
  `ordre` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `ref_langues`
--

CREATE TABLE `ref_langues` (
  `id` int(11) NOT NULL,
  `libelle` varchar(100) CHARACTER SET utf8 NOT NULL,
  `libelle_ar` varchar(100) CHARACTER SET utf8 NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `ordre` int(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `ref_mode_saisies`
--

CREATE TABLE `ref_mode_saisies` (
  `id` int(11) NOT NULL,
  `libelle` varchar(100) NOT NULL,
  `libelle_ar` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `ref_nationnalites`
--

CREATE TABLE `ref_nationnalites` (
  `id` int(11) NOT NULL,
  `libelle` varchar(110) CHARACTER SET utf8 NOT NULL,
  `libelle_ar` varchar(110) CHARACTER SET utf8 NOT NULL,
  `ordre` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `ref_niveau_etudes`
--

CREATE TABLE `ref_niveau_etudes` (
  `id` int(11) NOT NULL,
  `libelle` varchar(120) NOT NULL,
  `libelle_ar` varchar(120) NOT NULL,
  `ordre` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `ref_semestres`
--

CREATE TABLE `ref_semestres` (
  `id` int(11) NOT NULL,
  `libelle` varchar(100) CHARACTER SET utf8 NOT NULL,
  `libelle_ar` varchar(100) CHARACTER SET utf8 NOT NULL,
  `etat` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `ref_sessions`
--

CREATE TABLE `ref_sessions` (
  `id` int(11) NOT NULL,
  `libelle` varchar(100) NOT NULL,
  `libelle_ar` varchar(100) CHARACTER SET utf8 NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `ref_situation_familliales`
--

CREATE TABLE `ref_situation_familliales` (
  `id` int(11) NOT NULL,
  `libelle` varchar(255) NOT NULL,
  `libelle_ar` varchar(255) DEFAULT NULL,
  `ordre` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `ref_types_contrats`
--

CREATE TABLE `ref_types_contrats` (
  `id` int(11) NOT NULL,
  `libelle` varchar(180) NOT NULL,
  `libelle_ar` varchar(200) DEFAULT NULL,
  `ordre` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `ref_types_documents`
--

CREATE TABLE `ref_types_documents` (
  `id` int(11) NOT NULL,
  `libelle` varchar(180) NOT NULL,
  `libelle_ar` varchar(200) DEFAULT NULL,
  `ordre` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `ref_type_controles`
--

CREATE TABLE `ref_type_controles` (
  `id` int(11) NOT NULL,
  `libelle` varchar(100) CHARACTER SET utf8 NOT NULL,
  `libelle_ar` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `ordre` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `ref_type_elections`
--

CREATE TABLE `ref_type_elections` (
  `id` int(11) NOT NULL,
  `libelle` varchar(200) DEFAULT NULL,
  `libelle_ar` varchar(200) DEFAULT NULL,
  `ordre` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `ref_type_semestres`
--

CREATE TABLE `ref_type_semestres` (
  `id` int(11) NOT NULL,
  `libelle` varchar(100) DEFAULT NULL,
  `libelle_ar` varchar(100) DEFAULT NULL,
  `type` int(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `releves_notes`
--

CREATE TABLE `releves_notes` (
  `id` int(11) NOT NULL,
  `etudiant_id` int(11) NOT NULL,
  `anonymat_id` int(11) DEFAULT NULL,
  `profil_id` int(11) NOT NULL,
  `ref_groupe_id` int(11) NOT NULL,
  `ref_semestre_id` int(11) NOT NULL,
  `etape_id` int(11) DEFAULT NULL,
  `matiere_id` int(11) NOT NULL,
  `note_dev` float NOT NULL,
  `note_exam` float NOT NULL,
  `note_rt` float DEFAULT NULL,
  `modulle_id` int(11) NOT NULL,
  `note` float NOT NULL,
  `noteModule` float NOT NULL,
  `annee_id` int(11) NOT NULL,
  `decision` int(11) DEFAULT NULL COMMENT '1:valide,0:no valide',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `resultat_globals`
--

CREATE TABLE `resultat_globals` (
  `id` int(11) NOT NULL,
  `profil_id` int(11) NOT NULL,
  `ref_groupe_id` int(11) NOT NULL,
  `ref_semestre_id` int(11) NOT NULL,
  `etape_id` int(11) NOT NULL,
  `matiere_id` int(11) NOT NULL,
  `note_cc` decimal(10,0) NOT NULL,
  `note_final` decimal(10,0) NOT NULL,
  `note_rt` decimal(10,0) NOT NULL,
  `note_total` decimal(10,0) NOT NULL,
  `annee_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `salles`
--

CREATE TABLE `salles` (
  `id` int(11) NOT NULL,
  `libelle` varchar(100) CHARACTER SET utf8 NOT NULL,
  `libelle_ar` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `capacite` int(11) NOT NULL,
  `ordre` int(11) NOT NULL DEFAULT '1',
  `etat` int(11) NOT NULL DEFAULT '1' COMMENT 'accepte :1, plain:2',
  `etat1` int(11) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `sys_droits`
--

CREATE TABLE `sys_droits` (
  `id` int(11) NOT NULL,
  `libelle` varchar(120) NOT NULL,
  `type_acces` int(11) NOT NULL COMMENT 'Tous =0, Consultation=1,Enregestrement=2,Validation=3,Edition=4,Suppression=5	',
  `sys_groupes_traitement_id` int(11) NOT NULL,
  `ordre` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `supprimer` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `sys_groupes_traitements`
--

CREATE TABLE `sys_groupes_traitements` (
  `id` int(11) NOT NULL,
  `libelle` varchar(120) NOT NULL,
  `ordre` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `supprimer` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `sys_profiles`
--

CREATE TABLE `sys_profiles` (
  `id` int(11) NOT NULL,
  `libelle` varchar(120) NOT NULL,
  `ordre` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `sys_profiles_sys_droits`
--

CREATE TABLE `sys_profiles_sys_droits` (
  `id` int(11) NOT NULL,
  `sys_profile_id` int(11) NOT NULL,
  `sys_droit_id` int(11) NOT NULL,
  `ordre` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `sys_profiles_users`
--

CREATE TABLE `sys_profiles_users` (
  `id` int(11) NOT NULL,
  `sys_profile_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `commune_id` int(11) DEFAULT NULL,
  `ordre` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `sys_types_users`
--

CREATE TABLE `sys_types_users` (
  `id` int(11) NOT NULL,
  `libelle` varchar(120) DEFAULT NULL,
  `libelle_ar` varchar(120) DEFAULT NULL,
  `ordre` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `table 70`
--

CREATE TABLE `table 70` (
  `<?xml version=1.0 encoding=UTF-8 standalone=yes?>` varchar(1185) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `temp_matieres`
--

CREATE TABLE `temp_matieres` (
  `id` int(11) NOT NULL,
  `id_matiere` int(11) NOT NULL,
  `libelle` varchar(100) CHARACTER SET utf8 NOT NULL,
  `code` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `credit` float NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `temp_matieres1s`
--

CREATE TABLE `temp_matieres1s` (
  `id` int(11) NOT NULL,
  `id_matiere` int(11) NOT NULL,
  `libelle` varchar(100) CHARACTER SET utf8 NOT NULL,
  `code` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `credit` float NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `tewjihs`
--

CREATE TABLE `tewjihs` (
  `id` int(11) NOT NULL,
  `etudiant` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `profil_id` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `groupe` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `tmp_attesation_colls`
--

CREATE TABLE `tmp_attesation_colls` (
  `id` int(11) NOT NULL,
  `etudiant_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_ad` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `tmp_orientations`
--

CREATE TABLE `tmp_orientations` (
  `id` int(11) NOT NULL,
  `profil_id` int(11) DEFAULT NULL,
  `moyenne` decimal(10,0) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `etudiant_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `universites`
--

CREATE TABLE `universites` (
  `id` int(11) NOT NULL,
  `libelle` varchar(100) NOT NULL,
  `libelle_ar` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `nom_resp` varchar(100) DEFAULT NULL,
  `nom_resp_ar` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `code` varchar(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `username` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sys_types_user_id` int(11) DEFAULT NULL COMMENT '1:de;2:agent anapej;3:Employeur;4:centre_formation',
  `etat` int(11) NOT NULL DEFAULT '1',
  `phone` varchar(180) COLLATE utf8_unicode_ci DEFAULT NULL,
  `code` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `confirm` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `verif_calcule_note`
--

CREATE TABLE `verif_calcule_note` (
  `id` int(11) NOT NULL,
  `etudant_id` int(11) NOT NULL,
  `ref_semestre_id` int(11) NOT NULL,
  `annee_id` int(11) NOT NULL,
  `profil_id` int(11) NOT NULL,
  `groupe_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `verif_calcule_notes`
--

CREATE TABLE `verif_calcule_notes` (
  `id` int(11) NOT NULL,
  `ref_semestre_id` int(11) NOT NULL,
  `annee_id` int(11) NOT NULL,
  `profil_id` int(11) NOT NULL,
  `groupe_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `verif_calcule_note_indivs`
--

CREATE TABLE `verif_calcule_note_indivs` (
  `id` int(11) NOT NULL,
  `etudant_id` int(11) DEFAULT NULL,
  `ref_semestre_id` int(11) DEFAULT NULL,
  `annee_id` int(11) DEFAULT NULL,
  `profil_id` int(11) DEFAULT NULL,
  `groupe_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `annees`
--
ALTER TABLE `annees`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `anonymats`
--
ALTER TABLE `anonymats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `etudiant_id` (`etudiant_id`),
  ADD KEY `anonymats_ibfk_1` (`profil_id`),
  ADD KEY `etape_id` (`etape_id`);

--
-- Index pour la table `anonymatsconcours`
--
ALTER TABLE `anonymatsconcours`
  ADD PRIMARY KEY (`id`),
  ADD KEY `candidat_id` (`candidat_id`);

--
-- Index pour la table `bachalier`
--
ALTER TABLE `bachalier`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `bacheliers`
--
ALTER TABLE `bacheliers`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `bureaus`
--
ALTER TABLE `bureaus`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `candidats`
--
ALTER TABLE `candidats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ref_genre_id` (`ref_genre_id`,`ref_nationnalite_id`,`annee_id`),
  ADD KEY `salle_id` (`salle_id`),
  ADD KEY `annee_id` (`annee_id`),
  ADD KEY `ref_nationnalite_id` (`ref_nationnalite_id`);

--
-- Index pour la table `centres`
--
ALTER TABLE `centres`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `concours`
--
ALTER TABLE `concours`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `departements`
--
ALTER TABLE `departements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `faculte_id` (`faculte_id`);

--
-- Index pour la table `elections`
--
ALTER TABLE `elections`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `entete_etablissements`
--
ALTER TABLE `entete_etablissements`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `etapes`
--
ALTER TABLE `etapes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ref_diplome_id` (`ref_diplome_id`,`ref_mode_saisie_id`),
  ADD KEY `ref_mode_saisie_id` (`ref_mode_saisie_id`),
  ADD KEY `faculte_id` (`faculte_id`),
  ADD KEY `ref_type_controle_id` (`ref_type_controle_id`);

--
-- Index pour la table `etudiants`
--
ALTER TABLE `etudiants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ref_nationnalite_id` (`ref_nationnalite_id`),
  ADD KEY `profil_id` (`profil_id`),
  ADD KEY `faculte_id` (`faculte_id`),
  ADD KEY `faculte_id_2` (`faculte_id`);

--
-- Index pour la table `etud_annee_credits`
--
ALTER TABLE `etud_annee_credits`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `etud_mats`
--
ALTER TABLE `etud_mats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `etudiant_id` (`etudiant_id`),
  ADD KEY `profil_id` (`profil_id`,`ref_semestre_id`),
  ADD KEY `ref_semestre_id` (`ref_semestre_id`),
  ADD KEY `matiere_id` (`matiere_id`),
  ADD KEY `ref_groupe_id` (`ref_groupe_id`),
  ADD KEY `annee_id` (`annee_id`);

--
-- Index pour la table `etud_semestres`
--
ALTER TABLE `etud_semestres`
  ADD PRIMARY KEY (`id`),
  ADD KEY `etudiant_id` (`etudiant_id`,`profil_id`),
  ADD KEY `profil_id` (`profil_id`),
  ADD KEY `ref_semestre_id` (`ref_semestre_id`),
  ADD KEY `annee_id` (`annee_id`);

--
-- Index pour la table `exemples`
--
ALTER TABLE `exemples`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `facultes`
--
ALTER TABLE `facultes`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `ged`
--
ALTER TABLE `ged`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ref_types_document_id` (`ref_types_document_id`);

--
-- Index pour la table `matieres`
--
ALTER TABLE `matieres`
  ADD PRIMARY KEY (`id`),
  ADD KEY `profil_id` (`profil_id`,`modulle_id`,`ref_semestre_id`,`ref_langue_id`),
  ADD KEY `ref_semestre_id` (`ref_semestre_id`),
  ADD KEY `modulle_id` (`modulle_id`),
  ADD KEY `ref_langue_id` (`ref_langue_id`);

--
-- Index pour la table `matieres_concours`
--
ALTER TABLE `matieres_concours`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `matieres_profils_etapes`
--
ALTER TABLE `matieres_profils_etapes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `profil_id` (`profil_id`,`etape_id`,`matiere_id`,`ref_semestre_id`),
  ADD KEY `etape_id` (`etape_id`),
  ADD KEY `matiere_id` (`matiere_id`),
  ADD KEY `ref_semestre_id` (`ref_semestre_id`);

--
-- Index pour la table `matiere_salle_etudiants`
--
ALTER TABLE `matiere_salle_etudiants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `annee_id` (`annee_id`),
  ADD KEY `salle_id` (`salle_id`),
  ADD KEY `etud_mat_id` (`etud_mat_id`) USING BTREE,
  ADD KEY `matiere_id` (`matiere_id`) USING BTREE,
  ADD KEY `etudiant_id` (`etudiant_id`) USING BTREE,
  ADD KEY `profil_id` (`profil_id`,`groupe_id`),
  ADD KEY `groupe_id` (`groupe_id`);

--
-- Index pour la table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `modulles`
--
ALTER TABLE `modulles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `profil_id` (`profil_id`),
  ADD KEY `faculte_id` (`faculte_id`),
  ADD KEY `ref_semestre_id` (`ref_semestre_id`);

--
-- Index pour la table `moussabakas`
--
ALTER TABLE `moussabakas`
  ADD KEY `id` (`id`);

--
-- Index pour la table `moyennes_semestres`
--
ALTER TABLE `moyennes_semestres`
  ADD PRIMARY KEY (`id`),
  ADD KEY `annee_id` (`annee_id`),
  ADD KEY `profil_id` (`profil_id`),
  ADD KEY `ref_groupe_id` (`ref_groupe_id`),
  ADD KEY `etudiant_id` (`etudiant_id`),
  ADD KEY `ref_semestre_id` (`ref_semestre_id`);

--
-- Index pour la table `moyennes_sortants`
--
ALTER TABLE `moyennes_sortants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `profil_id` (`profil_id`,`annee_id`),
  ADD KEY `annee_id` (`annee_id`),
  ADD KEY `etudiant_id` (`etudiant_id`);

--
-- Index pour la table `note_concours`
--
ALTER TABLE `note_concours`
  ADD PRIMARY KEY (`id`),
  ADD KEY `annee_id` (`annee_id`),
  ADD KEY `anonymat_id` (`anonymat_id`),
  ADD KEY `candidat_id` (`candidat_id`),
  ADD KEY `matieres_concour_id` (`matieres_concour_id`),
  ADD KEY `salle_id` (`salle_id`);

--
-- Index pour la table `note_concours_finales`
--
ALTER TABLE `note_concours_finales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `annee_id` (`annee_id`),
  ADD KEY `anonymat_id` (`anonymat_id`),
  ADD KEY `candidat_id` (`candidat_id`),
  ADD KEY `salle_id` (`salle_id`);

--
-- Index pour la table `note_devoirs`
--
ALTER TABLE `note_devoirs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `profil_id` (`profil_id`),
  ADD KEY `ref_semestre_id` (`ref_semestre_id`),
  ADD KEY `etape_id` (`etape_id`),
  ADD KEY `matiere_id` (`matiere_id`),
  ADD KEY `annee_id` (`annee_id`),
  ADD KEY `ref_groupe_id` (`ref_groupe_id`),
  ADD KEY `etudiant_id` (`etudiant_id`,`anonymat_id`),
  ADD KEY `anonymat_id` (`anonymat_id`);

--
-- Index pour la table `note_examens`
--
ALTER TABLE `note_examens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `profil_id` (`profil_id`),
  ADD KEY `ref_semestre_id` (`ref_semestre_id`),
  ADD KEY `etape_id` (`etape_id`),
  ADD KEY `matiere_id` (`matiere_id`),
  ADD KEY `annee_id` (`annee_id`),
  ADD KEY `ref_groupe_id` (`ref_groupe_id`),
  ADD KEY `etudiant_id` (`etudiant_id`,`anonymat_id`),
  ADD KEY `anonymat_id` (`anonymat_id`);

--
-- Index pour la table `note_examen_finales`
--
ALTER TABLE `note_examen_finales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `profil_id` (`profil_id`),
  ADD KEY `ref_semestre_id` (`ref_semestre_id`),
  ADD KEY `etape_id` (`etape_id`),
  ADD KEY `matiere_id` (`matiere_id`),
  ADD KEY `annee_id` (`annee_id`),
  ADD KEY `ref_groupe_id` (`ref_groupe_id`),
  ADD KEY `etudiant_id` (`etudiant_id`,`anonymat_id`),
  ADD KEY `anonymat_id` (`anonymat_id`),
  ADD KEY `modulle_id` (`modulle_id`);

--
-- Index pour la table `note_examen_rts`
--
ALTER TABLE `note_examen_rts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `profil_id` (`profil_id`),
  ADD KEY `ref_semestre_id` (`ref_semestre_id`),
  ADD KEY `etape_id` (`etape_id`),
  ADD KEY `matiere_id` (`matiere_id`),
  ADD KEY `annee_id` (`annee_id`),
  ADD KEY `ref_groupe_id` (`ref_groupe_id`),
  ADD KEY `etudiant_id` (`etudiant_id`,`anonymat_id`),
  ADD KEY `anonymat_id` (`anonymat_id`);

--
-- Index pour la table `note_modifier`
--
ALTER TABLE `note_modifier`
  ADD PRIMARY KEY (`id`),
  ADD KEY `profil_id` (`profil_id`),
  ADD KEY `ref_semestre_id` (`ref_semestre_id`),
  ADD KEY `etape_id` (`etape_id`),
  ADD KEY `matiere_id` (`matiere_id`),
  ADD KEY `annee_id` (`annee_id`),
  ADD KEY `ref_groupe_id` (`ref_groupe_id`),
  ADD KEY `etudiant_id` (`etudiant_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `orientations`
--
ALTER TABLE `orientations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `plages`
--
ALTER TABLE `plages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `semestre_id` (`ref_semestre_id`,`etape_id`),
  ADD KEY `profil_id` (`profil_id`),
  ADD KEY `etape_id` (`etape_id`);

--
-- Index pour la table `profils`
--
ALTER TABLE `profils`
  ADD PRIMARY KEY (`id`),
  ADD KEY `departement_id` (`departement_id`,`ref_niveau_etude_id`,`ref_semestre_id`,`etape_id`),
  ADD KEY `etape_id` (`etape_id`),
  ADD KEY `ref_niveau_etude_id` (`ref_niveau_etude_id`),
  ADD KEY `semestre_id` (`ref_semestre_id`),
  ADD KEY `faculte_id` (`faculte_id`);

--
-- Index pour la table `profil_groupe_annees`
--
ALTER TABLE `profil_groupe_annees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `profil_id` (`profil_id`,`groupe_id`,`annee_id`),
  ADD KEY `annee_id` (`annee_id`),
  ADD KEY `groupe_id` (`groupe_id`);

--
-- Index pour la table `profil_orientations`
--
ALTER TABLE `profil_orientations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `profil_semestres`
--
ALTER TABLE `profil_semestres`
  ADD PRIMARY KEY (`id`),
  ADD KEY `departement_id` (`departement_id`,`ref_niveau_etude_id`,`ref_semestre_id`,`etape_id`),
  ADD KEY `etape_id` (`etape_id`),
  ADD KEY `ref_niveau_etude_id` (`ref_niveau_etude_id`),
  ADD KEY `semestre_id` (`ref_semestre_id`),
  ADD KEY `faculte_id` (`faculte_id`);

--
-- Index pour la table `ref_diplomes`
--
ALTER TABLE `ref_diplomes`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `ref_genres`
--
ALTER TABLE `ref_genres`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `ref_groupes`
--
ALTER TABLE `ref_groupes`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `ref_langues`
--
ALTER TABLE `ref_langues`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `ref_mode_saisies`
--
ALTER TABLE `ref_mode_saisies`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `ref_nationnalites`
--
ALTER TABLE `ref_nationnalites`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `ref_niveau_etudes`
--
ALTER TABLE `ref_niveau_etudes`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `ref_semestres`
--
ALTER TABLE `ref_semestres`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `ref_sessions`
--
ALTER TABLE `ref_sessions`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `ref_situation_familliales`
--
ALTER TABLE `ref_situation_familliales`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `ref_types_contrats`
--
ALTER TABLE `ref_types_contrats`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `ref_types_documents`
--
ALTER TABLE `ref_types_documents`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `ref_type_controles`
--
ALTER TABLE `ref_type_controles`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `ref_type_elections`
--
ALTER TABLE `ref_type_elections`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `ref_type_semestres`
--
ALTER TABLE `ref_type_semestres`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `releves_notes`
--
ALTER TABLE `releves_notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `profil_id` (`profil_id`),
  ADD KEY `ref_semestre_id` (`ref_semestre_id`),
  ADD KEY `etape_id` (`etape_id`),
  ADD KEY `matiere_id` (`matiere_id`),
  ADD KEY `annee_id` (`annee_id`),
  ADD KEY `ref_groupe_id` (`ref_groupe_id`),
  ADD KEY `etudiant_id` (`etudiant_id`,`anonymat_id`),
  ADD KEY `anonymat_id` (`anonymat_id`),
  ADD KEY `modulle_id` (`modulle_id`);

--
-- Index pour la table `resultat_globals`
--
ALTER TABLE `resultat_globals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `profil_id` (`profil_id`),
  ADD KEY `ref_semestre_id` (`ref_semestre_id`),
  ADD KEY `etape_id` (`etape_id`),
  ADD KEY `matiere_id` (`matiere_id`),
  ADD KEY `annee_id` (`annee_id`),
  ADD KEY `ref_groupe_id` (`ref_groupe_id`);

--
-- Index pour la table `salles`
--
ALTER TABLE `salles`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `sys_droits`
--
ALTER TABLE `sys_droits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sys_groupes_traitements_id` (`sys_groupes_traitement_id`);

--
-- Index pour la table `sys_groupes_traitements`
--
ALTER TABLE `sys_groupes_traitements`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `sys_profiles`
--
ALTER TABLE `sys_profiles`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `sys_profiles_sys_droits`
--
ALTER TABLE `sys_profiles_sys_droits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sys_profiles_id` (`sys_profile_id`),
  ADD KEY `sys_droits_id` (`sys_droit_id`);

--
-- Index pour la table `sys_profiles_users`
--
ALTER TABLE `sys_profiles_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sys_profiles_id` (`sys_profile_id`),
  ADD KEY `users_id` (`user_id`),
  ADD KEY `b_strictures_id` (`commune_id`);

--
-- Index pour la table `sys_types_users`
--
ALTER TABLE `sys_types_users`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `temp_matieres`
--
ALTER TABLE `temp_matieres`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_matiere` (`id_matiere`);

--
-- Index pour la table `temp_matieres1s`
--
ALTER TABLE `temp_matieres1s`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tewjihs`
--
ALTER TABLE `tewjihs`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tmp_attesation_colls`
--
ALTER TABLE `tmp_attesation_colls`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tmp_orientations`
--
ALTER TABLE `tmp_orientations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `universites`
--
ALTER TABLE `universites`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `ref_types_user_id` (`sys_types_user_id`);

--
-- Index pour la table `verif_calcule_notes`
--
ALTER TABLE `verif_calcule_notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `annee_id` (`annee_id`),
  ADD KEY `groupe_id` (`groupe_id`),
  ADD KEY `profil_id` (`profil_id`),
  ADD KEY `ref_semestre_id` (`ref_semestre_id`);

--
-- Index pour la table `verif_calcule_note_indivs`
--
ALTER TABLE `verif_calcule_note_indivs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `annees`
--
ALTER TABLE `annees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `anonymats`
--
ALTER TABLE `anonymats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `anonymatsconcours`
--
ALTER TABLE `anonymatsconcours`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `bachalier`
--
ALTER TABLE `bachalier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `bacheliers`
--
ALTER TABLE `bacheliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `bureaus`
--
ALTER TABLE `bureaus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `candidats`
--
ALTER TABLE `candidats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `centres`
--
ALTER TABLE `centres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `concours`
--
ALTER TABLE `concours`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `departements`
--
ALTER TABLE `departements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `entete_etablissements`
--
ALTER TABLE `entete_etablissements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `etapes`
--
ALTER TABLE `etapes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `etudiants`
--
ALTER TABLE `etudiants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `etud_annee_credits`
--
ALTER TABLE `etud_annee_credits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `etud_mats`
--
ALTER TABLE `etud_mats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `etud_semestres`
--
ALTER TABLE `etud_semestres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `exemples`
--
ALTER TABLE `exemples`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `facultes`
--
ALTER TABLE `facultes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `ged`
--
ALTER TABLE `ged`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `matieres`
--
ALTER TABLE `matieres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `matieres_concours`
--
ALTER TABLE `matieres_concours`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `matieres_profils_etapes`
--
ALTER TABLE `matieres_profils_etapes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `matiere_salle_etudiants`
--
ALTER TABLE `matiere_salle_etudiants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `modules`
--
ALTER TABLE `modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `modulles`
--
ALTER TABLE `modulles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `moyennes_semestres`
--
ALTER TABLE `moyennes_semestres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `moyennes_sortants`
--
ALTER TABLE `moyennes_sortants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `note_concours`
--
ALTER TABLE `note_concours`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `note_concours_finales`
--
ALTER TABLE `note_concours_finales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `note_devoirs`
--
ALTER TABLE `note_devoirs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `note_examens`
--
ALTER TABLE `note_examens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `note_examen_finales`
--
ALTER TABLE `note_examen_finales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `note_examen_rts`
--
ALTER TABLE `note_examen_rts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `note_modifier`
--
ALTER TABLE `note_modifier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `orientations`
--
ALTER TABLE `orientations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `plages`
--
ALTER TABLE `plages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `profils`
--
ALTER TABLE `profils`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `profil_groupe_annees`
--
ALTER TABLE `profil_groupe_annees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `profil_orientations`
--
ALTER TABLE `profil_orientations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `profil_semestres`
--
ALTER TABLE `profil_semestres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `ref_diplomes`
--
ALTER TABLE `ref_diplomes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `ref_genres`
--
ALTER TABLE `ref_genres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `ref_groupes`
--
ALTER TABLE `ref_groupes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `ref_langues`
--
ALTER TABLE `ref_langues`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `ref_mode_saisies`
--
ALTER TABLE `ref_mode_saisies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `ref_nationnalites`
--
ALTER TABLE `ref_nationnalites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `ref_niveau_etudes`
--
ALTER TABLE `ref_niveau_etudes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `ref_semestres`
--
ALTER TABLE `ref_semestres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `ref_sessions`
--
ALTER TABLE `ref_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `ref_situation_familliales`
--
ALTER TABLE `ref_situation_familliales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `ref_types_contrats`
--
ALTER TABLE `ref_types_contrats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `ref_types_documents`
--
ALTER TABLE `ref_types_documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `ref_type_controles`
--
ALTER TABLE `ref_type_controles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `ref_type_elections`
--
ALTER TABLE `ref_type_elections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `ref_type_semestres`
--
ALTER TABLE `ref_type_semestres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `releves_notes`
--
ALTER TABLE `releves_notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `salles`
--
ALTER TABLE `salles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `sys_droits`
--
ALTER TABLE `sys_droits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `sys_groupes_traitements`
--
ALTER TABLE `sys_groupes_traitements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `sys_profiles`
--
ALTER TABLE `sys_profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `sys_profiles_sys_droits`
--
ALTER TABLE `sys_profiles_sys_droits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `sys_profiles_users`
--
ALTER TABLE `sys_profiles_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `sys_types_users`
--
ALTER TABLE `sys_types_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `temp_matieres`
--
ALTER TABLE `temp_matieres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `temp_matieres1s`
--
ALTER TABLE `temp_matieres1s`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `tewjihs`
--
ALTER TABLE `tewjihs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `tmp_attesation_colls`
--
ALTER TABLE `tmp_attesation_colls`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `tmp_orientations`
--
ALTER TABLE `tmp_orientations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `universites`
--
ALTER TABLE `universites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `verif_calcule_notes`
--
ALTER TABLE `verif_calcule_notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `verif_calcule_note_indivs`
--
ALTER TABLE `verif_calcule_note_indivs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `anonymatsconcours`
--
ALTER TABLE `anonymatsconcours`
  ADD CONSTRAINT `anonymatsconcours_ibfk_1` FOREIGN KEY (`candidat_id`) REFERENCES `candidats` (`id`);

--
-- Contraintes pour la table `candidats`
--
ALTER TABLE `candidats`
  ADD CONSTRAINT `candidats_ibfk_1` FOREIGN KEY (`annee_id`) REFERENCES `annees` (`id`),
  ADD CONSTRAINT `candidats_ibfk_2` FOREIGN KEY (`ref_genre_id`) REFERENCES `ref_genres` (`id`),
  ADD CONSTRAINT `candidats_ibfk_3` FOREIGN KEY (`ref_nationnalite_id`) REFERENCES `ref_nationnalites` (`id`),
  ADD CONSTRAINT `candidats_ibfk_4` FOREIGN KEY (`salle_id`) REFERENCES `salles` (`id`);

--
-- Contraintes pour la table `departements`
--
ALTER TABLE `departements`
  ADD CONSTRAINT `departements_ibfk_1` FOREIGN KEY (`faculte_id`) REFERENCES `facultes` (`id`);

--
-- Contraintes pour la table `etapes`
--
ALTER TABLE `etapes`
  ADD CONSTRAINT `etapes_ibfk_1` FOREIGN KEY (`ref_diplome_id`) REFERENCES `ref_diplomes` (`id`),
  ADD CONSTRAINT `etapes_ibfk_2` FOREIGN KEY (`ref_mode_saisie_id`) REFERENCES `ref_mode_saisies` (`id`),
  ADD CONSTRAINT `etapes_ibfk_3` FOREIGN KEY (`faculte_id`) REFERENCES `facultes` (`id`),
  ADD CONSTRAINT `etapes_ibfk_4` FOREIGN KEY (`ref_type_controle_id`) REFERENCES `ref_type_controles` (`id`);

--
-- Contraintes pour la table `etud_mats`
--
ALTER TABLE `etud_mats`
  ADD CONSTRAINT `etud_mats_ibfk_1` FOREIGN KEY (`etudiant_id`) REFERENCES `etudiants` (`id`),
  ADD CONSTRAINT `etud_mats_ibfk_2` FOREIGN KEY (`profil_id`) REFERENCES `profils` (`id`),
  ADD CONSTRAINT `etud_mats_ibfk_3` FOREIGN KEY (`ref_semestre_id`) REFERENCES `ref_semestres` (`id`),
  ADD CONSTRAINT `etud_mats_ibfk_4` FOREIGN KEY (`matiere_id`) REFERENCES `matieres` (`id`),
  ADD CONSTRAINT `etud_mats_ibfk_5` FOREIGN KEY (`ref_groupe_id`) REFERENCES `ref_groupes` (`id`),
  ADD CONSTRAINT `etud_mats_ibfk_6` FOREIGN KEY (`annee_id`) REFERENCES `annees` (`id`);

--
-- Contraintes pour la table `etud_semestres`
--
ALTER TABLE `etud_semestres`
  ADD CONSTRAINT `etud_semestres_ibfk_1` FOREIGN KEY (`etudiant_id`) REFERENCES `etudiants` (`id`),
  ADD CONSTRAINT `etud_semestres_ibfk_2` FOREIGN KEY (`profil_id`) REFERENCES `profils` (`id`),
  ADD CONSTRAINT `etud_semestres_ibfk_3` FOREIGN KEY (`ref_semestre_id`) REFERENCES `ref_semestres` (`id`);

--
-- Contraintes pour la table `ged`
--
ALTER TABLE `ged`
  ADD CONSTRAINT `ged_ibfk_1` FOREIGN KEY (`ref_types_document_id`) REFERENCES `ref_types_documents` (`id`);

--
-- Contraintes pour la table `matieres`
--
ALTER TABLE `matieres`
  ADD CONSTRAINT `matieres_ibfk_1` FOREIGN KEY (`profil_id`) REFERENCES `profils` (`id`),
  ADD CONSTRAINT `matieres_ibfk_2` FOREIGN KEY (`ref_langue_id`) REFERENCES `ref_langues` (`id`),
  ADD CONSTRAINT `matieres_ibfk_3` FOREIGN KEY (`modulle_id`) REFERENCES `modulles` (`id`),
  ADD CONSTRAINT `matieres_ibfk_4` FOREIGN KEY (`ref_semestre_id`) REFERENCES `ref_semestres` (`id`);

--
-- Contraintes pour la table `matieres_profils_etapes`
--
ALTER TABLE `matieres_profils_etapes`
  ADD CONSTRAINT `matieres_profils_etapes_ibfk_1` FOREIGN KEY (`etape_id`) REFERENCES `etapes` (`id`),
  ADD CONSTRAINT `matieres_profils_etapes_ibfk_2` FOREIGN KEY (`matiere_id`) REFERENCES `matieres` (`id`),
  ADD CONSTRAINT `matieres_profils_etapes_ibfk_3` FOREIGN KEY (`profil_id`) REFERENCES `profils` (`id`),
  ADD CONSTRAINT `matieres_profils_etapes_ibfk_4` FOREIGN KEY (`ref_semestre_id`) REFERENCES `ref_semestres` (`id`);

--
-- Contraintes pour la table `matiere_salle_etudiants`
--
ALTER TABLE `matiere_salle_etudiants`
  ADD CONSTRAINT `matiere_salle_etudiants_ibfk_1` FOREIGN KEY (`annee_id`) REFERENCES `annees` (`id`),
  ADD CONSTRAINT `matiere_salle_etudiants_ibfk_3` FOREIGN KEY (`etudiant_id`) REFERENCES `etudiants` (`id`),
  ADD CONSTRAINT `matiere_salle_etudiants_ibfk_4` FOREIGN KEY (`matiere_id`) REFERENCES `matieres` (`id`),
  ADD CONSTRAINT `matiere_salle_etudiants_ibfk_5` FOREIGN KEY (`salle_id`) REFERENCES `salles` (`id`),
  ADD CONSTRAINT `matiere_salle_etudiants_ibfk_6` FOREIGN KEY (`profil_id`) REFERENCES `profils` (`id`),
  ADD CONSTRAINT `matiere_salle_etudiants_ibfk_7` FOREIGN KEY (`groupe_id`) REFERENCES `ref_groupes` (`id`);

--
-- Contraintes pour la table `modulles`
--
ALTER TABLE `modulles`
  ADD CONSTRAINT `modulles_ibfk_1` FOREIGN KEY (`ref_semestre_id`) REFERENCES `ref_semestres` (`id`);

--
-- Contraintes pour la table `moyennes_semestres`
--
ALTER TABLE `moyennes_semestres`
  ADD CONSTRAINT `moyennes_semestres_ibfk_1` FOREIGN KEY (`annee_id`) REFERENCES `annees` (`id`),
  ADD CONSTRAINT `moyennes_semestres_ibfk_2` FOREIGN KEY (`profil_id`) REFERENCES `profils` (`id`),
  ADD CONSTRAINT `moyennes_semestres_ibfk_3` FOREIGN KEY (`ref_groupe_id`) REFERENCES `ref_groupes` (`id`),
  ADD CONSTRAINT `moyennes_semestres_ibfk_4` FOREIGN KEY (`etudiant_id`) REFERENCES `etudiants` (`id`),
  ADD CONSTRAINT `moyennes_semestres_ibfk_5` FOREIGN KEY (`ref_semestre_id`) REFERENCES `ref_semestres` (`id`);

--
-- Contraintes pour la table `moyennes_sortants`
--
ALTER TABLE `moyennes_sortants`
  ADD CONSTRAINT `moyennes_sortants_ibfk_1` FOREIGN KEY (`annee_id`) REFERENCES `annees` (`id`),
  ADD CONSTRAINT `moyennes_sortants_ibfk_2` FOREIGN KEY (`profil_id`) REFERENCES `profils` (`id`),
  ADD CONSTRAINT `moyennes_sortants_ibfk_3` FOREIGN KEY (`etudiant_id`) REFERENCES `etudiants` (`id`);

--
-- Contraintes pour la table `note_concours`
--
ALTER TABLE `note_concours`
  ADD CONSTRAINT `note_concours_ibfk_1` FOREIGN KEY (`annee_id`) REFERENCES `annees` (`id`),
  ADD CONSTRAINT `note_concours_ibfk_2` FOREIGN KEY (`anonymat_id`) REFERENCES `anonymatsconcours` (`id`),
  ADD CONSTRAINT `note_concours_ibfk_3` FOREIGN KEY (`candidat_id`) REFERENCES `candidats` (`id`),
  ADD CONSTRAINT `note_concours_ibfk_4` FOREIGN KEY (`matieres_concour_id`) REFERENCES `matieres_concours` (`id`),
  ADD CONSTRAINT `note_concours_ibfk_5` FOREIGN KEY (`salle_id`) REFERENCES `salles` (`id`);

--
-- Contraintes pour la table `note_concours_finales`
--
ALTER TABLE `note_concours_finales`
  ADD CONSTRAINT `note_concours_finales_ibfk_1` FOREIGN KEY (`annee_id`) REFERENCES `annees` (`id`),
  ADD CONSTRAINT `note_concours_finales_ibfk_2` FOREIGN KEY (`anonymat_id`) REFERENCES `anonymatsconcours` (`id`),
  ADD CONSTRAINT `note_concours_finales_ibfk_3` FOREIGN KEY (`candidat_id`) REFERENCES `candidats` (`id`),
  ADD CONSTRAINT `note_concours_finales_ibfk_5` FOREIGN KEY (`salle_id`) REFERENCES `salles` (`id`);

--
-- Contraintes pour la table `note_devoirs`
--
ALTER TABLE `note_devoirs`
  ADD CONSTRAINT `note_devoirs_ibfk_1` FOREIGN KEY (`annee_id`) REFERENCES `annees` (`id`),
  ADD CONSTRAINT `note_devoirs_ibfk_2` FOREIGN KEY (`etape_id`) REFERENCES `etapes` (`id`),
  ADD CONSTRAINT `note_devoirs_ibfk_3` FOREIGN KEY (`matiere_id`) REFERENCES `matieres` (`id`),
  ADD CONSTRAINT `note_devoirs_ibfk_4` FOREIGN KEY (`profil_id`) REFERENCES `profils` (`id`),
  ADD CONSTRAINT `note_devoirs_ibfk_5` FOREIGN KEY (`ref_semestre_id`) REFERENCES `ref_semestres` (`id`),
  ADD CONSTRAINT `note_devoirs_ibfk_6` FOREIGN KEY (`ref_groupe_id`) REFERENCES `ref_groupes` (`id`),
  ADD CONSTRAINT `note_devoirs_ibfk_7` FOREIGN KEY (`anonymat_id`) REFERENCES `anonymats` (`id`),
  ADD CONSTRAINT `note_devoirs_ibfk_8` FOREIGN KEY (`etudiant_id`) REFERENCES `etudiants` (`id`);

--
-- Contraintes pour la table `note_examens`
--
ALTER TABLE `note_examens`
  ADD CONSTRAINT `note_examens_ibfk_1` FOREIGN KEY (`etudiant_id`) REFERENCES `etudiants` (`id`),
  ADD CONSTRAINT `note_examens_ibfk_2` FOREIGN KEY (`matiere_id`) REFERENCES `matieres` (`id`);

--
-- Contraintes pour la table `note_examen_finales`
--
ALTER TABLE `note_examen_finales`
  ADD CONSTRAINT `note_examen_finales_ibfk_1` FOREIGN KEY (`etudiant_id`) REFERENCES `etudiants` (`id`),
  ADD CONSTRAINT `note_examen_finales_ibfk_2` FOREIGN KEY (`matiere_id`) REFERENCES `matieres` (`id`),
  ADD CONSTRAINT `note_examen_finales_ibfk_3` FOREIGN KEY (`profil_id`) REFERENCES `profils` (`id`),
  ADD CONSTRAINT `note_examen_finales_ibfk_4` FOREIGN KEY (`annee_id`) REFERENCES `annees` (`id`),
  ADD CONSTRAINT `note_examen_finales_ibfk_5` FOREIGN KEY (`ref_groupe_id`) REFERENCES `ref_groupes` (`id`),
  ADD CONSTRAINT `note_examen_finales_ibfk_6` FOREIGN KEY (`ref_semestre_id`) REFERENCES `ref_semestres` (`id`),
  ADD CONSTRAINT `note_examen_finales_ibfk_7` FOREIGN KEY (`modulle_id`) REFERENCES `modulles` (`id`);

--
-- Contraintes pour la table `note_examen_rts`
--
ALTER TABLE `note_examen_rts`
  ADD CONSTRAINT `note_examen_rts_ibfk_1` FOREIGN KEY (`annee_id`) REFERENCES `annees` (`id`),
  ADD CONSTRAINT `note_examen_rts_ibfk_2` FOREIGN KEY (`matiere_id`) REFERENCES `matieres` (`id`),
  ADD CONSTRAINT `note_examen_rts_ibfk_3` FOREIGN KEY (`etape_id`) REFERENCES `etapes` (`id`),
  ADD CONSTRAINT `note_examen_rts_ibfk_4` FOREIGN KEY (`profil_id`) REFERENCES `profils` (`id`),
  ADD CONSTRAINT `note_examen_rts_ibfk_5` FOREIGN KEY (`ref_semestre_id`) REFERENCES `ref_semestres` (`id`),
  ADD CONSTRAINT `note_examen_rts_ibfk_6` FOREIGN KEY (`ref_groupe_id`) REFERENCES `ref_groupes` (`id`),
  ADD CONSTRAINT `note_examen_rts_ibfk_7` FOREIGN KEY (`anonymat_id`) REFERENCES `anonymats` (`id`),
  ADD CONSTRAINT `note_examen_rts_ibfk_8` FOREIGN KEY (`etudiant_id`) REFERENCES `etudiants` (`id`);

--
-- Contraintes pour la table `note_modifier`
--
ALTER TABLE `note_modifier`
  ADD CONSTRAINT `note_modifier_ibfk_1` FOREIGN KEY (`annee_id`) REFERENCES `annees` (`id`),
  ADD CONSTRAINT `note_modifier_ibfk_2` FOREIGN KEY (`etape_id`) REFERENCES `etapes` (`id`),
  ADD CONSTRAINT `note_modifier_ibfk_3` FOREIGN KEY (`matiere_id`) REFERENCES `matieres` (`id`),
  ADD CONSTRAINT `note_modifier_ibfk_4` FOREIGN KEY (`profil_id`) REFERENCES `profils` (`id`),
  ADD CONSTRAINT `note_modifier_ibfk_5` FOREIGN KEY (`ref_semestre_id`) REFERENCES `ref_semestres` (`id`),
  ADD CONSTRAINT `note_modifier_ibfk_6` FOREIGN KEY (`ref_groupe_id`) REFERENCES `ref_groupes` (`id`),
  ADD CONSTRAINT `note_modifier_ibfk_8` FOREIGN KEY (`etudiant_id`) REFERENCES `etudiants` (`id`),
  ADD CONSTRAINT `note_modifier_ibfk_9` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `profils`
--
ALTER TABLE `profils`
  ADD CONSTRAINT `profils_ibfk_1` FOREIGN KEY (`departement_id`) REFERENCES `departements` (`id`),
  ADD CONSTRAINT `profils_ibfk_2` FOREIGN KEY (`etape_id`) REFERENCES `etapes` (`id`),
  ADD CONSTRAINT `profils_ibfk_3` FOREIGN KEY (`ref_niveau_etude_id`) REFERENCES `ref_niveau_etudes` (`id`),
  ADD CONSTRAINT `profils_ibfk_4` FOREIGN KEY (`ref_semestre_id`) REFERENCES `ref_semestres` (`id`),
  ADD CONSTRAINT `profils_ibfk_5` FOREIGN KEY (`faculte_id`) REFERENCES `facultes` (`id`);

--
-- Contraintes pour la table `profil_groupe_annees`
--
ALTER TABLE `profil_groupe_annees`
  ADD CONSTRAINT `profil_groupe_annees_ibfk_1` FOREIGN KEY (`annee_id`) REFERENCES `annees` (`id`),
  ADD CONSTRAINT `profil_groupe_annees_ibfk_2` FOREIGN KEY (`groupe_id`) REFERENCES `ref_groupes` (`id`),
  ADD CONSTRAINT `profil_groupe_annees_ibfk_3` FOREIGN KEY (`profil_id`) REFERENCES `profils` (`id`);

--
-- Contraintes pour la table `releves_notes`
--
ALTER TABLE `releves_notes`
  ADD CONSTRAINT `releves_notes_ibfk_1` FOREIGN KEY (`annee_id`) REFERENCES `annees` (`id`),
  ADD CONSTRAINT `releves_notes_ibfk_2` FOREIGN KEY (`etudiant_id`) REFERENCES `etudiants` (`id`),
  ADD CONSTRAINT `releves_notes_ibfk_3` FOREIGN KEY (`matiere_id`) REFERENCES `matieres` (`id`),
  ADD CONSTRAINT `releves_notes_ibfk_4` FOREIGN KEY (`profil_id`) REFERENCES `profils` (`id`),
  ADD CONSTRAINT `releves_notes_ibfk_5` FOREIGN KEY (`ref_semestre_id`) REFERENCES `ref_semestres` (`id`),
  ADD CONSTRAINT `releves_notes_ibfk_6` FOREIGN KEY (`modulle_id`) REFERENCES `modulles` (`id`);

--
-- Contraintes pour la table `resultat_globals`
--
ALTER TABLE `resultat_globals`
  ADD CONSTRAINT `resultat_globals_ibfk_1` FOREIGN KEY (`annee_id`) REFERENCES `annees` (`id`),
  ADD CONSTRAINT `resultat_globals_ibfk_2` FOREIGN KEY (`etape_id`) REFERENCES `etapes` (`id`),
  ADD CONSTRAINT `resultat_globals_ibfk_3` FOREIGN KEY (`matiere_id`) REFERENCES `matieres` (`id`),
  ADD CONSTRAINT `resultat_globals_ibfk_4` FOREIGN KEY (`profil_id`) REFERENCES `profils` (`id`),
  ADD CONSTRAINT `resultat_globals_ibfk_5` FOREIGN KEY (`ref_groupe_id`) REFERENCES `ref_groupes` (`id`);

--
-- Contraintes pour la table `sys_droits`
--
ALTER TABLE `sys_droits`
  ADD CONSTRAINT `sys_droits_ibfk_1` FOREIGN KEY (`sys_groupes_traitement_id`) REFERENCES `sys_groupes_traitements` (`id`);

--
-- Contraintes pour la table `sys_profiles_sys_droits`
--
ALTER TABLE `sys_profiles_sys_droits`
  ADD CONSTRAINT `sys_profiles_sys_droits_ibfk_1` FOREIGN KEY (`sys_droit_id`) REFERENCES `sys_droits` (`id`),
  ADD CONSTRAINT `sys_profiles_sys_droits_ibfk_2` FOREIGN KEY (`sys_profile_id`) REFERENCES `sys_profiles` (`id`);

--
-- Contraintes pour la table `sys_profiles_users`
--
ALTER TABLE `sys_profiles_users`
  ADD CONSTRAINT `sys_profiles_users_ibfk_1` FOREIGN KEY (`sys_profile_id`) REFERENCES `sys_profiles` (`id`),
  ADD CONSTRAINT `sys_profiles_users_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `temp_matieres`
--
ALTER TABLE `temp_matieres`
  ADD CONSTRAINT `temp_matieres_ibfk_1` FOREIGN KEY (`id_matiere`) REFERENCES `matieres` (`id`);

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`sys_types_user_id`) REFERENCES `sys_types_users` (`id`);

--
-- Contraintes pour la table `verif_calcule_notes`
--
ALTER TABLE `verif_calcule_notes`
  ADD CONSTRAINT `verif_calcule_notes_ibfk_1` FOREIGN KEY (`annee_id`) REFERENCES `annees` (`id`),
  ADD CONSTRAINT `verif_calcule_notes_ibfk_2` FOREIGN KEY (`groupe_id`) REFERENCES `ref_groupes` (`id`),
  ADD CONSTRAINT `verif_calcule_notes_ibfk_3` FOREIGN KEY (`profil_id`) REFERENCES `profils` (`id`),
  ADD CONSTRAINT `verif_calcule_notes_ibfk_4` FOREIGN KEY (`ref_semestre_id`) REFERENCES `ref_semestres` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

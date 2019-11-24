-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 24. Nov 2019 um 10:54
-- Server-Version: 10.4.6-MariaDB
-- PHP-Version: 7.1.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `blog_oop`
--
CREATE DATABASE IF NOT EXISTS `blog_oop` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `blog_oop`;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `blog`
--

DROP TABLE IF EXISTS `blog`;
CREATE TABLE `blog` (
  `blog_id` int(11) NOT NULL,
  `blog_headline` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `blog_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blog_imageAlignment` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `blog_content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `blog_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `cat_id` int(11) NOT NULL,
  `usr_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `blog`
--

INSERT INTO `blog` (`blog_id`, `blog_headline`, `blog_image`, `blog_imageAlignment`, `blog_content`, `blog_date`, `cat_id`, `usr_id`) VALUES
(84, 'Neue Kategorien und Beiträge erstellen', 'uploads/blogimages/1574433437-neue-kategorien-und-beitraege-erstellen-spartan-dashboard-erstellen.png', 'fright', 'Das erstellen von neuen Kategorien und Beiträgen (Blogposts) gehört zu den Grundfunktionen eines jeden Systems zur Verwaltung von Inhalten, darum war dies auch eines der ersten Werkzeuge von Spartan.\r\n\r\nIn der &quot;Mach was neues!&quot; - Ansicht ist es registrierten Benutzern möglich neue Beiträge oder Kategorien zu erstellen.\r\n\r\nKategorien bekommen einen Namen für die Navigation auf der Startseite, einen kurzen Titel als Überschrift innerhalb der Kategorie und einen Kategorie-Beschreibungstext. Diese Kategoriebeschreibung wird ebenfalls innerhalb der Kategorie angezeigt, nachdem per Navigation in diese gewechselt wurde.\r\n\r\nNeue Beiträge benötigen zuerst eine vorhandene Kategorie, in der sie veröffentlicht werden. Danach folgen die Felder für die beiden obligatorischen Bestandteile eines Blogposts: Der Titel und der Inhalt des Beitrags. Und weil das Internet mittlerweile bunt ist, kann dem Beitrag auch ein Bild hinzugefügt werden.\r\n\r\nFür die Position des Bildes stehen zur Zeit die beiden Positionen &quot;links vom Text&quot; und &quot;rechts vom Text&quot; zur Verfügung. Hier im Bild rechts neben diesem Text ist die &quot;Mach was neues!&quot; - Ansicht zum erstellen von neuen Inhalten abgebiltet.', '2019-11-22 14:37:17', 2, 4),
(85, 'Beiträge und Kategorien verwalten', 'uploads/blogimages/1574435740-erstellte-beitraege-verwalten-spartan-dashboard-verwalten.png', 'fright', 'Die Verwaltungsansicht von Spartan zum Ändern und Löschen von Beiträgen und Kategorien wird nach dem Login über den Punkt &quot;Verwalten&quot; in der Headnavigation angesteuert. In der Standard-Ansicht werden hier alle Kategorien und Beiträge angezeigt. Die Kategorien können genutzt werden, um die auf der Seite angezeigten Beiträge zu filtern. Zu jeder Kategorie und zu jedem Beitrag stehen Links zum Löschen und Ändern zur Verfügung.\r\n\r\nAngemeldeten Benutzern stehen diese Links zur direkten Navigation zum ändern oder löschen eines Beitrags ebenfalls auf der Startseite zur Verfügung.\r\n\r\nSo ein Beitrag wird ja irgendwann mal alt und soll vielleicht aus dem Internet verschwinden. Für das löschen von Beiträgen steht auch eine Funktion bereit.\r\n\r\nDoch warum sollte man überhaupt Beiträge später noch einmal verwalten wollen? Hier eine kleine Geschichte, bei der der neue Beitrag direkt nach dem Erstellen verändert werden sollte. Doch warum? Alles sah so super aus. Aber der kleine Fleck da im ersten Absatz ist kein Schmutz auf dem Bildschirm und kein Pixelfehler, nein. Ein fießes Komma (,) an der völlig falschen Stelle. Wie kam es nur da hin? Keine Ahnung. Aber es muss da weg. Also hilft alles nichts. Der Beitrag muss bearbeitet werden.', '2019-11-22 14:45:48', 1, 4),
(86, 'Beitrag oder Kategorie bearbeiten', 'uploads/blogimages/1574436791-beitrag-oder-kategorie-bearbeiten-spartan-dashboard-bearbeiten.png', 'fright', 'Über die &quot;Verwalten&quot;- Ansicht oder die Links neben dem Beitrag wird das Formular zur Berarbeitung eines bestehenden Beitrags oder einer vorhandenen Kategorie aufgerufen. \r\n\r\nDie übergeordnete Kategorie steht als Select bereit. Text kann einfach ausgetauscht werden. Und auch die Headline lässt sich in diesem Formular verändern. Bei Beiträgen steht zusätzlich noch die Funktionalität zum nachträglichen hinzufügen, löschen und ändern einer Bilddatei zur Verfügung. Wird das Bild geändert, so wird das alte automatisch vom Server gelöscht und der Pfad zum neuen Bild in der Datenbank gespeichert. Die Checkbox zum löschen des Bildes wird nur angezeigt, wenn dem Beitrag ein Bild zugewiesen wurde.\r\n\r\nDiese Ansicht steht nur angemeldeten Benutzern zur Verfügung. Sie kann auch direkt über den jeweiligen &quot;Bearbeiten&quot;- Link der Beiträge auf der Startseite erreicht werden.', '2019-11-22 15:23:23', 2, 4),
(87, 'Beitrag löschen', 'uploads/blogimages/1574438481-beitrag-loeschen-spartan-dashboard-loeschen.png', 'fright', 'Um einen Beitrag zu löschen stehen auch wieder auf der Startseite und der &quot;Verwalten&quot;-Ansicht  Links zur Verfügung. Dieser Link wird natürlich nur für angemeldete Benutzer generiert. Nachdem ein Beitrag zum Löschen ausgewählt wurde, werden ein paar Stichpunkte und eine Abfrage, ob denn der Beitrag wirklich gelöscht werden soll angezeigt. Wird das Löschen des Beitrags bestätigt, löscht Spartan den Beitragsdatensatz aus der Datenbank und das Beitragsbild (falls vorhanden) vom Server.\r\n\r\nFür voreilige Entschlüsse steht in dieser Ansicht auch noch ein &quot;Bearbeiten&quot; Button zur Verfügung. Im Bild rechts neben dem Text ist der rote Hover-Effekt des löschen Buttons gerade an, weil mein Mauszeiger darauf ruht.\r\n\r\nNur der Benutzer, welcher einen Beitrag angelegt hat, ist auch dazu berechtigt diesen zu löschen. Administratoren sind von dieser Regelung natürlich ausgenommen und können Beiträge von anderen Benutzern löschen.', '2019-11-22 15:54:56', 2, 4),
(89, 'Eine Kategorie löschen', 'uploads/blogimages/1574439697-eine-kategorie-loeschen-spartan-dashboard-kategorie-loeschen.png', 'fright', 'Das Löschen einer Kategorie ist nur über die &quot;Verwalten&quot;- Ansicht verlinkt. Es erscheint eine Abfrage, ob die Kategorie mitsamt den enthaltenen Beiträgen gelöscht werden soll. Nur der Benutzer, welcher eine Kategorie angelegt hat, ist auch dazu berechtigt diese zu löschen. Administratoren sind von dieser Regelung natürlich ausgenommen und können auch Kategorien von anderen Benutzern löschen.\r\n\r\nRechts neben diesem Text wird die Ansicht zum Löschen der Kategorie &quot;Funktionen&quot; angezeigt. Eine kurze Übersicht mit Kategoriename, Titel und Beschreibungstext. Die große Schaltfläche zum Bestätigen befindet sich derzeit noch oberhalb der Übersicht, vielleicht wäre dieser darunter besser platziert. Aber Hey, Usability ist eine Philosophie für sich ....', '2019-11-22 16:21:37', 2, 4),
(90, 'Die Startseite', 'uploads/blogimages/1574444355-die-startseite-spartan-startseite.png', 'fright', 'Auf der Startseite werden ein Begrüßungstext und alle Beiträge angezeigt. Aktuell werden Beiträge nach Veröffentlichungsdatum sortiert, damit immer der neueste Beitrag ganz oben erscheint. Mit der Seitennavigation unterhalb der Überschrift lassen sich Beiträge von bestimmten Kategorien anzeigen, indem man den Namen der Kategorie anklickt.\r\n\r\nAußerdem befindet sich oben rechts im Header ein Login für registrierte Benutzer. Nach dem erfolgreichen Anmelden stehen dem Benutzer dort weitere Links zur Navigation aufs Dashboard, die Verwalten Ansicht und die Startseite zur Verfügung.', '2019-11-22 17:15:36', 1, 4);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `cat_id` int(11) NOT NULL,
  `cat_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cat_title` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cat_description` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `usr_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `category`
--

INSERT INTO `category` (`cat_id`, `cat_name`, `cat_title`, `cat_description`, `usr_id`) VALUES
(1, 'Spartan CMS', 'Einfach Inhalte verwalten', '&quot;Warum denn noch ein Content Management System? Es gibt doch schon so viele.&quot; Das höre ich oft, wenn ich von meinem Portfolio-Projekt erzähle. Meine Antwort lautet: &quot;Weil ich es kann und das zeige möchte!&quot;\r\n\r\nNun steht es da, das schlanke System mit dem spartanisch gehaltenen Funktionsumfang und will vorgestellt werden. &quot;May i introduce? This is Spartan (CMS).&quot;', 4),
(2, 'Funktionen', 'Funktionen von Spartan CMS', 'Na, was kann den Spartan CMS jetzt eigentlich schon? Es deckt die wesentlichen Grundfunktionen schonmal ab. \r\n\r\n„Ein Werkzeug, sie zu verwalten, sie alle zu sortieren, in die Datenbank zu schreiben und sauber zu datieren.“', 4),
(3, 'Testbeiträge', 'Beiträge zum Test der Funktionalität', 'Woher ich weiß, dass das funktioniert? Ich habe es getestet. Mit ettlichen Testbeiträgen in dieser Kategorie. Allerdings räume ich hier auch regelmäßig wieder auf. 😉', 4);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `role`
--

DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
  `rol_id` int(11) NOT NULL,
  `rol_name` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `role`
--

INSERT INTO `role` (`rol_id`, `rol_name`) VALUES
(1, 'Administrator'),
(2, 'Benutzer');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `usr_id` int(11) NOT NULL,
  `usr_firstname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `usr_lastname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `usr_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `usr_city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `usr_password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rol_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`usr_id`, `usr_firstname`, `usr_lastname`, `usr_email`, `usr_city`, `usr_password`, `rol_id`) VALUES
(1, 'Peter', 'Petersen', 'a@b.c', 'New York', '$2y$10$tbCYcuHF/flLur6pSSpMheR5DKA2io7T9TcE/Gw3Q/2aulfoQiGD2', 2),
(2, 'Paul', 'Paulsen', 'paul@paulsen.net', 'Paris', '$2y$10$3vC0YKbOcGVXevncK82iFuUGP611c8Es1DxHVuDZ3652veoAFA2kO', 2),
(3, 'Dritter', 'Benutzer Nachname', 'drei@b.c', 'Berlin', '$2y$10$tbCYcuHF/flLur6pSSpMheR5DKA2io7T9TcE/Gw3Q/2aulfoQiGD2', 2),
(4, 'Martin', 'Goller', 'admin@b.c', 'Saarbrücken', '$2y$10$tbCYcuHF/flLur6pSSpMheR5DKA2io7T9TcE/Gw3Q/2aulfoQiGD2', 1);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `blog`
--
ALTER TABLE `blog`
  ADD PRIMARY KEY (`blog_id`),
  ADD KEY `blog_ibfk_1` (`usr_id`) USING BTREE,
  ADD KEY `blog_ibfk_2` (`cat_id`) USING BTREE;

--
-- Indizes für die Tabelle `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`cat_id`),
  ADD KEY `category_ibfk_1` (`usr_id`);

--
-- Indizes für die Tabelle `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`rol_id`);

--
-- Indizes für die Tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`usr_id`),
  ADD KEY `user_ibfk_1` (`rol_id`) USING BTREE;

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `blog`
--
ALTER TABLE `blog`
  MODIFY `blog_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT für Tabelle `category`
--
ALTER TABLE `category`
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT für Tabelle `role`
--
ALTER TABLE `role`
  MODIFY `rol_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT für Tabelle `user`
--
ALTER TABLE `user`
  MODIFY `usr_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `blog`
--
ALTER TABLE `blog`
  ADD CONSTRAINT `blog_ibfk_2` FOREIGN KEY (`cat_id`) REFERENCES `category` (`cat_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

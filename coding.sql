-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 13, 2021 at 03:21 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `coding`
--

-- --------------------------------------------------------

--
-- Table structure for table `answer`
--

CREATE TABLE `answer` (
  `id` int(11) NOT NULL,
  `answer` text NOT NULL,
  `question_id` int(11) NOT NULL,
  `correct` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `answer`
--

INSERT INTO `answer` (`id`, `answer`, `question_id`, `correct`) VALUES
(1, 'Pridruživanje', 30, 0),
(2, 'Prikazivanje', 30, 0),
(3, 'Pozivanje', 30, 1),
(4, 'Mijenjanje', 30, 0),
(5, '\"=\"', 31, 1),
(6, '=', 31, 1),
(7, 'jednako', 31, 1),
(8, '\"jednako\"', 31, 1),
(9, 'x = \"true\"', 32, 0),
(10, 'u = 6', 32, 0),
(11, 't = False', 32, 1),
(12, 'k = \"True\"', 32, 0),
(13, 'i = True', 32, 1),
(14, 'z = 7', 32, 0),
(15, 'String', 33, 1),
(16, 'Boolean', 33, 0),
(17, 'Integer', 33, 0),
(18, 'cjelobrojnu', 34, 1),
(19, 'Specificiranje tipa varijable', 35, 1),
(20, 'Ispis varijable na ekran', 35, 0),
(21, 'Izmjena vrijednosti varijable', 35, 0),
(22, 'Dodavanje varijabli', 35, 0),
(23, 'Varijabla x sadrži navodnike, dok je varijabla y bez navodnika', 36, 0),
(24, 'Varijabla x je tipa bool, dok je varijabla y tipa string', 36, 0),
(25, 'Varijabla y sadrži navodnike, dok je varijabla x bez navodnika', 36, 1),
(26, 'Varijabla x je tipa integer, dok je varijabla y tipa string', 36, 1),
(27, 'type', 37, 1),
(28, 'type()', 37, 1),
(29, 'python -version', 44, 0),
(30, 'python version', 44, 0),
(31, 'python version--', 44, 0),
(32, 'python --version', 44, 1),
(33, '.cpp', 43, 0),
(34, '.py', 43, 1),
(35, '.yi', 43, 0),
(36, '.pyt', 43, 0),
(37, 'print()', 47, 1),
(38, 'Provjeravamo instaliranu verziju Pythona', 48, 1),
(39, 'Provjeravamo koja je najnovija verzija Python jezika', 48, 0),
(40, 'Razmaci', 52, 1),
(41, 'Novi redovi', 52, 0),
(42, 'Tabulatori', 52, 1),
(43, 'bloka koda', 51, 1),
(44, 'programskog bloka', 51, 1),
(45, 'Točno', 50, 1),
(46, 'Netočno', 50, 0),
(47, 'Uvlaka je obavezna jer označava početak bloka koda', 53, 1),
(48, 'Broj uvlaka unutar bloka koda je proizvoljan', 53, 0),
(49, 'Svaki blok koda mora koristiti jednak broj uvlaka', 53, 1),
(50, 'Blok koda izvršit će se normalno bez uvlake prije početka', 53, 0),
(51, 'Komentari u Pythonu koriste se za objašnjavanje koda', 54, 0),
(52, 'Komentari u Pythonu izvršavaju se kao i ostali dijelovi koda', 54, 1),
(53, 'Komentari u Pythonu koriste se za prevenciju izvršavanja dijelova koda', 54, 0),
(54, 'Komentari u Pythonu omogučuju bolju čitljivost koda', 54, 0),
(55, 'Komentare je u Pythonu moguće dodati samo prije linije koda', 54, 1),
(56, '#', 55, 1),
(57, 'ljestve', 55, 1),
(59, '3', 56, 1),
(60, 'tri', 56, 1),
(61, '\'\'\'', 57, 0),
(62, '\"\"\"', 57, 1),
(63, '---', 57, 0),
(64, '--', 57, 0),
(65, 'v = \"tekst\"', 60, 0),
(66, 'v = \'tekst\'', 60, 0),
(67, 'v = \"tekst\'', 60, 1),
(68, 'v = True', 60, 0),
(69, 'var = 9', 61, 1),
(70, '7h = \"tekst\"', 61, 0),
(71, '@b = 2', 61, 0),
(72, '(d)=\"d\"', 61, 0),
(73, 'Točno', 62, 1),
(74, 'Netočno', 62, 0),
(75, 'Da', 63, 1),
(76, 'Ne', 63, 0),
(77, 'Fali četvrta vrijednost', 64, 1),
(78, 'Fali posljednja vrijednost', 64, 1),
(79, 'Fali zadnja vrijednost', 64, 1),
(80, 'Nema zadnje vrijednosti', 64, 1),
(81, 'Nema četvrte vrijednosti', 64, 1),
(82, 'Donjom crtom', 67, 1),
(83, 'Slovnim znakom', 67, 1),
(84, 'Znamenkom', 67, 0),
(85, 'Da', 68, 0),
(86, 'Ne', 68, 1),
(87, 'Točno', 69, 1),
(88, 'Netočno', 69, 0),
(89, 'print', 47, 1),
(90, 'interpreterski', 42, 1),
(91, 'interpretiran', 42, 1),
(92, 'Fali vrijednost varijable d', 64, 1),
(93, 'Nema vrijednosti varijable d', 64, 1),
(94, 'k', 659, 0),
(95, 'j', 659, 1),
(96, 'e', 659, 1),
(97, 'f', 659, 0),
(98, 'Točno', 660, 1),
(99, 'Netočno', 660, 0),
(100, 'je moguće', 661, 1),
(101, 'moguće je', 661, 1),
(102, '2.1+0j', 662, 1),
(103, 'Netočno', 665, 0),
(104, 'Točno', 665, 1),
(105, 'polje', 666, 1),
(106, 'Prvo slovo stringa element je pod brojem 0', 667, 1),
(107, 'Pristupanje pojedinačnim znakovima stringa isto je kao pristupanje elementima polja', 667, 1),
(108, 'Polja započinju od broja 1', 667, 0),
(109, 'Broj znaka unutar stringa specificira se uglatim zagradama nakon naziva varijable', 667, 1),
(110, 'len', 669, 1),
(111, 'len()', 669, 1);

-- --------------------------------------------------------

--
-- Table structure for table `coding_answer`
--

CREATE TABLE `coding_answer` (
  `id` int(11) NOT NULL,
  `code` text NOT NULL,
  `display` text NOT NULL,
  `question_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `coding_answer`
--

INSERT INTO `coding_answer` (`id`, `code`, `display`, `question_id`) VALUES
(1, 'k = 11\r\nm = 8\r\nprint(k)', '11', 38),
(2, 'b = \"True\"\nprint(type(b))', '<class \'str\'>', 39),
(5, 'python python.py', '', 46),
(6, 'print(\"Evo ispisa na ekran\")', 'Evo ispisa na ekran', 49),
(7, 'print(7) # komentar', '7', 58),
(8, 'print(2)', '2', 59),
(9, 'x = \"7\"\r\ny = \"Broj \"\r\nprint(y + x)', 'Broj 7', 65),
(10, 'a = b = 8\r\nprint(a+b)', '16', 66),
(11, 'a, b = 8, 8\r\nprint(a+b)', '16', 66),
(13, 'b = \"True\"\r\nc = type(b)\r\nprint(c)', '<class \'str\'>', 39),
(14, 'k = 8\r\nm = 11\r\nprint(k)', '8', 38),
(15, 's = \"string\r\nsa više\r\nlinija\"', '', 664),
(16, 's = \"string sa\r\nviše\r\nlinija\"', '', 664),
(17, 's = \"string\r\nsa\r\nviše linija\"', '', 664),
(18, 'v = \"ispis\"\nprint(v[0] + v[3])', 'ii', 668),
(19, 'v = \"ispis\"\nprint(v[0])\nprint(v[3])', 'i\ni', 668),
(20, 'app = \"aplikacija\"\r\nres = len(app)\r\nprint(res)', '10', 670),
(21, 'text = \"tekst\"\r\nnova = text[2] + text[4]\r\nprint(len(nova))', '2', 671),
(22, 'v = \"ispis\"\r\ng = v[0] + v[3]\r\nprint(g)', 'ii', 668),
(23, 'text = \"tekst\"\r\nnova = text[2] + text[4]\r\nn = len(nova)\r\nprint(n)', '2', 671);

-- --------------------------------------------------------

--
-- Table structure for table `language`
--

CREATE TABLE `language` (
  `id` int(11) NOT NULL,
  `name` varchar(25) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `compiler_mode` varchar(15) NOT NULL,
  `language_version` tinyint(1) NOT NULL,
  `editor_mode` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `language`
--

INSERT INTO `language` (`id`, `name`, `image`, `description`, `compiler_mode`, `language_version`, `editor_mode`) VALUES
(1, 'Python', '1628262625.jpg', '<p>Python je <em>interpreterski</em> programski jezik. Interpreterski programski jezici su jezici kod kojih se izvorni k&ocirc;d izvr&scaron;ava direktno uz pomoć interpretera, tj. kod ovakvih tipova programskih jezika nema potrebe za kompajliranjem prije izvr&scaron;avanja, ili prevođenjem u izvr&scaron;ni oblik. Programi pisani u programskom jeziku Python su kraći, a i za njihovo pisanje utro&scaron;ak vremena je puno manji.</p>\n<p>Python programerima dopu&scaron;ta nekoliko stilova pisanja programa: strukturno, objektno orijentirano i aspektno orijentirano programiranje. Specifičnost sintakse Pythona je kori&scaron;tenje <em>uvlaka</em> za razlikovanje blokova programskog koda. Dodavanjem uvlake započinje se ugnježđeni dio koda, dok se micanjem uvlake zavr&scaron;ava započeti blok koda. Uz to, <em>zavr&scaron;etak linije</em> koda u Pythonu nije potrebno označavati znakom \"točka zarez\".</p>\n<p>Neka od područja gdje se Python najče&scaron;će koristi su umjetna inteligencija i strojno učenje, analiza podataka te vizualizacija podataka.</p>', 'python3', 3, 'python'),
(22, 'Ruby', '1628262847.png', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>', 'ruby', 3, 'ruby'),
(23, 'PHP', '1628263122.jpg', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>', 'php', 3, 'php'),
(24, 'SQL', '1628263333.png', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>', 'sql', 3, 'sql');

-- --------------------------------------------------------

--
-- Table structure for table `lesson`
--

CREATE TABLE `lesson` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `language_id` int(11) NOT NULL,
  `precondition` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `lesson`
--

INSERT INTO `lesson` (`id`, `name`, `description`, `language_id`, `precondition`) VALUES
(32, 'Uvod', '<p><p>Da bi se Python programi uopće mogli izvr&scaron;avati, potrebno je instalirati Python na razvojnu okolinu. U komandnoj liniji je moguće provjeriti koja verzija jezika je instalirana slijedećom naredbom:</p> <p style=\"padding-left: 40px;\"><em>python --version</em></p> <p>Python je interpretiran programski jezik, &scaron;to znači da se Python datoteke (kreirane u tekstualnom editoru) sa ekstenzijom <em>.py</em> predaju interpreteru na izvr&scaron;avanje. Pokretanje Python datoteke preko komandne linije moguće je na ovaj način:</p> <p style=\"padding-left: 40px;\"><em>python naziv_datoteke.py</em></p> <p>Naredba koja će se često koristiti je <strong>print()</strong>. <strong>Print()</strong> naredba služi za ispis podataka na ekran. Moguće je ispisati brojeve, tekst ili varijable, o kojima vi&scaron;e u idućim lekcijama</p></p>', 1, 0),
(33, 'Sintaksa i komentari', '<p><p>U prvoj lekciji kori&scaron;tena je naredba&nbsp;<strong>print()</strong>. Primijetite da nakon upisivanja naredbe, nije bilo potrebno označiti kraj linije, kao &scaron;to je to slučaj kod drugih programskih jezika. U Pythonu nije potrebno stavljati znak \";\" na kraju svake linije programskog koda.</p> <p>Uvlake označavaju bjeline na početku linije koda. U Pythonu su uvlake puno bitnije od ostalih programskih jezika, gdje se većinom koriste samo radi lak&scaron;e čitljivosti koda. U Pythonu uvlake označavaju početak bloka koda.</p> <p style=\"padding-left: 40px;\"><em>if 3&lt;5:</em></p> <p style=\"padding-left: 80px;\"><em>print(\"Broj 3 je manji od broja 5\")</em></p> <p>U ovom primjeru prikazana je linija koda koja će se izvr&scaron;iti ukoliko je&nbsp;<em>if</em> uvjet točan. <em>If </em>uvjete obraditi ćemo kasnije. Obratite pozornost na uvlaku ispod&nbsp;<em>if</em> uvjeta. Uvlaka je obavezna jer označava početak bloka koda koji se treba izvr&scaron;iti. Bez uvlake Python će izbaciti pogre&scaron;ku.</p> <p>Broj uvlaka je proizvoljan, međutim svaki blok koda mora koristiti jednak broj uvlaka.</p> <p>Komentari u Pythonu korisni su za dodavanje obja&scaron;njenja koda, bolju čitljivost, ili prevenciju izvr&scaron;avanja dijelova koda tijekom testiranja. Jednolinijski komentar u Pythonu kreira se na idući&nbsp; način:</p> <p style=\"padding-left: 40px;\"><em># ovo je jednolinijski komentar</em></p> <p style=\"padding-left: 40px;\"><em>print(\"ovo je naredba\") # i ovo je komentar</em></p> <p>Dodavanjem znaka \"<strong>#</strong>\" kreira se komentar. Primjetite da je komentar moguće staviti i unutar linije programskog koda. Sav sadržaj nakon znaka \"<strong>#</strong>\" biti će ignoriran od strane interpretera.</p> <p>Neslužbeno postoje i vi&scaron;elinijski komentari. Neslužbeno, jer su Pythonovi \"vi&scaron;elinijski komentari\" zapravo vi&scaron;elinijski stringovi koje interpreter ignorira jer nisu zadani niti jednoj varijabli. Početak takvog \"vi&scaron;elinijskog komentara\", ili vi&scaron;elinijskog stringa označava se sa trostrukim navodnicima (<strong>\"\"\"</strong>).</p> <p style=\"padding-left: 40px;\">\"\"\"</p> <p style=\"padding-left: 40px;\">ovo</p> <p style=\"padding-left: 40px;\">je</p> <p style=\"padding-left: 40px;\">neslužbeni vi&scaron;elinijski komentar</p> <p style=\"padding-left: 40px;\">\"\"\"</p></p>', 1, 0),
(34, 'Varijable', '<p><p><p>Varijable se koriste za spremanje vrijednosti. Mogli bismo varijable usporediti sa kutijama u koje se spremaju vrijednosti koje se zatim koriste po potrebi. Varijable je moguće mijenjati, pridruživati, prikazivati.</p> <p>U Pythonu nema ključnih riječi koje razlikuju vrste varijabli. Da bi se varijabla u Pythonu kreirala, potrebno je zadati naziv te nazivu zadati željenu vrijednost. Između naziva i vrijednosti upisuje se znak \"jednako\", =.</p> <p><span>Primjer deklaracije varijabla:</p> <p style=\"padding-left: 40px;\"><em>x = 5</em></p> <p style=\"padding-left: 40px;\"><em>y = \"MK\"</em></p> <p style=\"padding-left: 40px;\"><em>z = True</em></p></span> <p>U ovom primjeru deklarirane su dvije varijable naziva&nbsp;<em>x&nbsp;</em>i&nbsp;<em>y</em>, kojima su zadane vrijednosti&nbsp;<em>5</em> i&nbsp;<em>MK</em>. Varijabla&nbsp;<em>x</em> tipa je&nbsp;<strong>integer<em>,&nbsp;</em></strong>dok je varijabla&nbsp;<em>y</em> tipa&nbsp;<strong>string</strong>. Integer označava brojčane, točnije cjelobrojne vrijednosti, dok string označava tekstualne vrijednosti. Varijabla&nbsp;<em>z</em> tipa je&nbsp;<strong>boolean<em>,&nbsp;</em></strong>&scaron;to označava logičke vrijednosti&nbsp;<em>True</em> ili&nbsp;<em>False.&nbsp;</em>Ali o tome detaljnije u kasnijim lekcijama.</p> <p>U Pythonu varijable ne moraju ostati istog tipa, te se njihov tip naknadno može promijeniti. Moguće je specificiranje tipa varijable (ili&nbsp;<em>casting</em>) tako da se pri deklaraciji varijable specificira željeni tip vrijednosti.</p> <p>Primjera castinga varijabla:</p> <p style=\"padding-left: 40px;\"><em>x = str(3)</em></p> <p style=\"padding-left: 40px;\"><em>y = int(3)</em></p> <p>U ovom primjeru varijabli&nbsp;<em>x&nbsp;</em>zadana je vrijednost&nbsp;<em>3</em> ali je specificiran tip&nbsp;<strong>str</strong>, &scaron;to znači da će vrijednost varijable biti znak <em>\"3\"</em>. Varijabli&nbsp;<em>y</em> zadana je ista vrijednost ali je specifiran tip&nbsp;<strong>int</strong>, pa će vrijednost te varijable biti cjelobrojni&nbsp;<em>3</em>.</p> <p>Moguće je saznati tip deklarirane varijable pomoću ugrađene Pythonove funkcije&nbsp;<strong>type()</strong>. Ako bismo koristili tu funkciju na prije deklarirane varijable&nbsp;<em>x&nbsp;</em>i&nbsp;<em>y</em>, te ispisali rezultat na ekran pomoću naredbe <strong>print()</strong>, dobili bismo iduće:</p> <p style=\"padding-left: 40px;\"><em>&lt;class \'str\'&gt;<br />&lt;class \'int\'&gt;</em></p> <p>&Scaron;to nam govori da je varijabla <em>x</em> tipa <em>string</em>, dok je varijabla&nbsp;<em>y</em> tipa&nbsp;<em>integer</em>.</p></p></p>', 1, 0),
(35, 'Varijable 2', '<p><p><p>Kod deklariranja varijable tipa&nbsp;<strong>string</strong>, nije bitno koriste li se jednostruki ili dvostruki navodnici.</p> <p>Postoje nekoliko pravila vezano za nazive varijabla u Pythonu:</p> <ol> <li>Naziv mora početi sa <strong>slovnim znakom</strong> ili <strong>donjom crtom</strong> \"_\"</li> <li>Naziv ne smije započeti <strong>znamenkom</strong></li> <li>Naziv smije sadržavati samo <strong>alfanumeričke</strong> znakove i donje crte</li> <li>Nazivi varijabla su <em>case-sensitive&nbsp;</em>(<em>x</em> i <em>X</em> dvije su različite varijable)</li> </ol> <p>Python omogućuje dodavanje vrijednosti u vi&scaron;e varijabli istovremeno. Umjesto deklariranja 3 različite varijable na ovaj način:</p> <p style=\"padding-left: 40px;\"><em>x = 4</em></p> <p style=\"padding-left: 40px;\"><em>y = 3</em></p> <p style=\"padding-left: 40px;\"><em>z = 7</em></p> <p>Moguće je deklariranje istih varijabli unutar jedne linije:</p> <p style=\"padding-left: 40px;\"><em>x, y, z = 4, 3, 7</em></p> <p>Bitno je napomenuti da je pri ovakvoj deklaraciji obavezno navesti jednak broj vrijednosti kao i varijabli, jer će Python u suprotnom izbaciti pogre&scaron;ku.</p> <p>Moguća je i deklaracija iste vrijednosti na vi&scaron;e varijabla istovremeno:</p> <p style=\"padding-left: 40px;\"><em>x = y = z = 4</em></p> <p>Naredbom print() moguć je kombinirani ispis varijabla. Primjerice:</p> <p style=\"padding-left: 40px;\"><em>x = 6</em></p> <p style=\"padding-left: 40px;\"><em>print(\"Ovo je broj \" + x)</em></p> <p style=\"padding-left: 40px;\"><em>z = \"Ovo je \"</em></p> <p style=\"padding-left: 40px;\"><em>u = \"rečenica \"</em></p> <p style=\"padding-left: 40px;\"><em>k = z + u</em></p> <p style=\"padding-left: 40px;\"><em>print(k + \" sa 3 riječi\")</em></p> <p>U prvom primjeru ispisati će se \"Ovo je broj 6\", jer je 6 sadržaj varijable x. U drugom primjer ispisati će se rečenica \"Ovo je rečenica sa 3 riječi\". Sadržaj varijabla z i u \"<strong>+</strong>\" operatorom dodan je varijabli k koja je zatim ispisana zajedno sa zadanim tekstom \" sa 3 riječi\".</p> <p style=\"padding-left: 40px;\"><em>a = 7</em></p> <p style=\"padding-left: 40px;\"><em>b = 1</em></p> <p style=\"padding-left: 40px;\"><em>c = a+b</em></p> <p style=\"padding-left: 40px;\"><em>print(c)</em></p> <p>Operatorom \"<strong>+</strong>\" na varijable brojevnog tipa izvr&scaron;avamo zbrajanje. Ispisati će se broj 8 koji je zbroj varijabla a i b.</p> <p style=\"padding-left: 40px;\"><em>x = 2</em></p> <p style=\"padding-left: 40px;\"><em>y = \"tekst\"</em></p> <p style=\"padding-left: 40px;\"><em>print(x + y)</em></p> <p>Ovaj kod izbacit će pogre&scaron;ku jer nije moguće kori&scaron;tenje operatora \"<strong>+</strong>\" između tekstualnih i brojevnih varijabla.</p></p></p>', 1, 34),
(36, 'Tipovi podataka', '<p>Već smo spominjali osnovne tipove podataka&nbsp;<strong>integer<em>,</em></strong>&nbsp;<strong>string</strong> i&nbsp;<strong>boolean</strong>. Sada ćemo upoznati jo&scaron; neke od tipova podataka. Numerički tipovi su, osim cjelobrojnih integera, <strong>float</strong> i&nbsp;<strong>complex.&nbsp;Float </strong>je broj koji sadrži jednu ili vi&scaron;e decimala. Moguće ga je prikazati i u znanstvenom zapisu sa oznakom&nbsp;<strong>e</strong>.</p>\r\n<p style=\"padding-left: 40px;\"><em>f = 0.47</em></p>\r\n<p style=\"padding-left: 40px;\"><em>e = -75.2e9</em></p>\r\n<p>Primijetimo da je varijabla&nbsp;<em>e</em> negativne vrijednosti. Naravno, i negativne cjelobrojne brojeve moguće je prikazati minusom ispred znamenaka.</p>\r\n<p><strong>Complex</strong> numerički tip predstavlja kompleksne brojeve sa imaginarnim dijelom \"<em>j</em>\". Kompleksne brojeve nije moguće pretvoriti u druge numeričke tipove. Međutim float i int moguće je pretvoriti u complex. Casting float i complex tipova radi se na isti način kao i sa int i str:</p>\r\n<p style=\"padding-left: 40px;\"><em>c = 1j</em></p>\r\n<p style=\"padding-left: 40px;\"><em>z = 2.1</em></p>\r\n<p style=\"padding-left: 40px;\"><em>d = complex(z)</em></p>\r\n<p>Ispis varijable d biti će \"2.1+0j\".&nbsp;</p>\r\n<p>Prisjetimo se \"vi&scaron;elinijskih komentara\" koji su zapravo vi&scaron;elinijski stringovi. Pa dakle, string u vi&scaron;e linija kreira se pomoću tri dvostruka ili jednostruka navodnika.</p>\r\n<p style=\"padding-left: 40px;\"><em>vise_linijski = \"\"\"evo</em></p>\r\n<p style=\"padding-left: 40px;\"><em>teksta u vi&scaron;e</em></p>\r\n<p style=\"padding-left: 40px;\"><em>linija</em></p>\r\n<p style=\"padding-left: 40px;\"><em>\"\"\"</em></p>\r\n<p>Stringovi su zapravo <strong>polja</strong> ispunjena znakovima. Pa ako želimo pristupiti pojedinačnim znakovima unutar stringa, to je moguće učiniti kao &scaron;to bismo pristupali elementima polja. Polja započinju od broja 0, &scaron;to bi značilo da je prvo slovo stringa element pod brojem 0.</p>\r\n<p style=\"padding-left: 40px;\"><em>text = \"slova\"</em></p>\r\n<p style=\"padding-left: 40px;\"><em>print(text[3])</em></p>\r\n<p>Print naredba ispisati će slovo <em>v</em>.</p>\r\n<p>Duljinu stringa možemo izračunati koristeći ugrađenu funkciju&nbsp;<strong>len()</strong>.</p>\r\n<p style=\"padding-left: 40px;\"><em>b = \"broj\"</em></p>\r\n<p style=\"padding-left: 40px;\"><em>duljina = len(b)</em></p>\r\n<p style=\"padding-left: 40px;\"><em>print(duljina)</em></p>\r\n<p>Ispisati će se broj 4 jer varijabla <em>b</em> sadrži 4 slova.&nbsp;</p>', 1, 35);

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

CREATE TABLE `question` (
  `id` int(11) NOT NULL,
  `question` text NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `question_type` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `question`
--

INSERT INTO `question` (`id`, `question`, `lesson_id`, `question_type`) VALUES
(30, 'Što nije moguće raditi sa varijablama?', 34, 1),
(31, 'Da bi se kreirala varijabla u Pythonu, koji znak je potrebno upisati između naziva varijable i vrijednosti varijable?', 34, 3),
(32, 'Odaberi varijable vrijednosti boolean', 34, 2),
(33, 'Kojeg je tipa varijabla x = \"True\"?', 34, 1),
(34, 'Integer označava brojčanu, točnije _ vrijednost?', 34, 3),
(35, 'Što je casting?', 34, 1),
(36, 'Koja je razlika između varijable x = int(4) i y = str(4)?', 34, 2),
(37, 'Kojom ugrađenom Pythonovom funkcijom možemo dobiti tip odabrane varijable?', 34, 3),
(38, 'Deklarirajte 2 cjelobrojne varijable vrijednosti 11 i 8. Nazivi varijabla neka budu k i m. Ispišite varijablu k', 34, 4),
(39, 'Ispišite tip varijable b = \"True\"', 34, 4),
(42, 'Python je ___ programski jezik', 32, 3),
(43, 'Koja je ekstenzija Python datoteka?', 32, 1),
(44, 'Odaberite pravilno napisanu naredbu', 32, 1),
(46, 'Upišite naredbu za pokretanje \"python.py\" datoteke preko komandne linije', 32, 4),
(47, 'Kojom naredbom ispisujemo podatke na ekran?', 32, 3),
(48, 'Što radimo naredbom \"python --version\"?', 32, 1),
(49, 'Ispišite tekst \"Evo ispisa na ekran\"', 32, 4),
(50, 'U Pythonu se ne označava kraj linije programskog koda', 33, 1),
(51, 'U Pythonu uvlake označavaje početak __?', 33, 3),
(52, 'Uvlake mogu biti?', 33, 2),
(53, 'Označite točne tvrdnje', 33, 2),
(54, 'Odaberite netočne tvrdnje', 33, 2),
(55, 'Jednolinijski komentar u Pythonu dodaje se kojim znakom?', 33, 3),
(56, 'Koji broj će ispisati ova linija koda \"print(3) #print(2)\"?', 33, 3),
(57, 'Početak neslužbenog višelinijskog komentara označava se znakovima?', 33, 1),
(58, 'Ispišite broj 7 te u istoj liniji koda dodajte komentar sa riječi \"komentar\";', 33, 4),
(59, 'Ispravno upišite liniju koda \"print(\'2)\";', 33, 4),
(60, 'Odaberite neispravnu varijablu', 35, 1),
(61, 'Odaberite ispravne nazive varijabla', 35, 2),
(62, 'Varijable \"tekst = \'Text\'\" i \"Tekst = \'Text\'\" su dvije različite varijable', 35, 1),
(63, 'Je li varijabla \"_g = 9\" ispravnog naziva?', 35, 1),
(64, 'Što nije ispravno u liniji koda \"a,b,c,d = 1,5,8\"?', 35, 3),
(65, 'Ispravite kod: \"x = 7 y = \'Broj \' print(y + x)\" tako da ispisuje \"Broj 7\"', 35, 4),
(66, 'Deklarirajte u jednoj liniji dvije varijable \"a\" i \"b\" sa vrijednošću 8 te ih zbrojite i ispišite rezultat', 35, 4),
(67, 'Naziv varijable smije započeti sa...', 35, 2),
(68, 'Je li varijabla \"-g = \'varijabla\'\" ispravnog naziva?', 35, 1),
(69, 'Korištenje operatora \"+\" između tekstualnih i brojevnih varijabla nije moguće', 35, 1),
(659, 'Odaberite znakove koji se mogu pojaviti u numeričkim tipovima varijabla', 36, 2),
(660, 'Kompleksne brojeve nije moguće pretvoriti u druge numeričke tipove', 36, 1),
(661, 'Float i int __ pretvoriti u complex tip', 36, 3),
(662, 'Što će biti ispis koda\r\n\"z=2.1\r\nd=complex(z)\"?', 36, 3),
(664, 'Deklarirajte višelinijski string naziva \"s\" u 3 linije teksta\n\"string\nsa više\nlinija\"', 36, 4),
(665, 'Višelinijski string moguče je kreirati sa 3 dvostruka ili 3 jednostruka navodnika', 36, 1),
(666, 'String je _ ispunjeno znakovima', 36, 3),
(667, 'Odaberite točne tvrdnje', 36, 2),
(668, 'Deklarirajte varijablu v sa riječi \"ispis\" te ispišite prvo i četvrto slovo varijable', 36, 4),
(669, 'Upišite ime funkcije za računanje duljine stringa', 36, 3),
(670, 'Deklarirajte varijablu app sa vrijednošću \"aplikacija\" te izračunajte duljinu varijable. Rezultat spremite u varijablu res i ispišite ga', 36, 4),
(671, 'Deklarirajte varijablu text sa vrijednošću \"tekst\". U varijablu naziva nova dodajte treće i peto slovo varijable text. Ispišite duljinu varijable nova.', 36, 4);

-- --------------------------------------------------------

--
-- Table structure for table `question_type`
--

CREATE TABLE `question_type` (
  `id` int(11) NOT NULL,
  `type` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `question_type`
--

INSERT INTO `question_type` (`id`, `type`) VALUES
(1, 'Jedan točan'),
(2, 'Više točnih'),
(3, 'Nadopunjavanje'),
(4, 'Kodiranje');

-- --------------------------------------------------------

--
-- Table structure for table `user_profile`
--

CREATE TABLE `user_profile` (
  `id` int(11) NOT NULL,
  `username` varchar(15) NOT NULL,
  `password` varchar(60) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `role_code` varchar(3) NOT NULL DEFAULT 'USR'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_profile`
--

INSERT INTO `user_profile` (`id`, `username`, `password`, `image`, `role_code`) VALUES
(13, 'administrator', '$2y$10$1pL/Tp4jy6ATakSWFsu1rufNqudr0VdyUZmIhvEZ8wPpoPbwEJjrS', NULL, 'AD'),
(14, 'moderator', '$2y$10$8gma2QpXxnpt.rgLXKOYk..IC3n1tRmpaPLLVGeG6WAYiRBSE6l2.', '1629965884.png', 'MOD'),
(15, 'username', '$2y$10$4frHKJhQhG..LlL0Hq9FK.21HaKtQgMsDFMQpxsHH5Cn.nezpD4da', NULL, 'MOD');

-- --------------------------------------------------------

--
-- Table structure for table `user_progress_language`
--

CREATE TABLE `user_progress_language` (
  `user_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_progress_language`
--

INSERT INTO `user_progress_language` (`user_id`, `language_id`) VALUES
(13, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_progress_lesson`
--

CREATE TABLE `user_progress_lesson` (
  `user_id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_progress_lesson`
--

INSERT INTO `user_progress_lesson` (`user_id`, `lesson_id`) VALUES
(13, 32),
(13, 33),
(13, 34),
(13, 35),
(13, 36);

-- --------------------------------------------------------

--
-- Table structure for table `user_progress_question`
--

CREATE TABLE `user_progress_question` (
  `user_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_progress_question`
--

INSERT INTO `user_progress_question` (`user_id`, `question_id`) VALUES
(13, 37),
(13, 39),
(13, 54),
(13, 64),
(13, 659),
(13, 668),
(15, 47),
(15, 48),
(15, 49);

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE `user_role` (
  `code` varchar(3) NOT NULL,
  `name` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_role`
--

INSERT INTO `user_role` (`code`, `name`) VALUES
('AD', 'Admin'),
('MOD', 'Moderator'),
('USR', 'Korisnik');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `answer`
--
ALTER TABLE `answer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `coding_answer`
--
ALTER TABLE `coding_answer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `language`
--
ALTER TABLE `language`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lesson`
--
ALTER TABLE `lesson`
  ADD PRIMARY KEY (`id`),
  ADD KEY `language_id` (`language_id`);

--
-- Indexes for table `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lesson_id` (`lesson_id`),
  ADD KEY `question_type` (`question_type`);

--
-- Indexes for table `question_type`
--
ALTER TABLE `question_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_profile`
--
ALTER TABLE `user_profile`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_code` (`role_code`);

--
-- Indexes for table `user_progress_language`
--
ALTER TABLE `user_progress_language`
  ADD UNIQUE KEY `user_lang` (`user_id`,`language_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Indexes for table `user_progress_lesson`
--
ALTER TABLE `user_progress_lesson`
  ADD UNIQUE KEY `user_less` (`user_id`,`lesson_id`),
  ADD KEY `lesson_id` (`lesson_id`);

--
-- Indexes for table `user_progress_question`
--
ALTER TABLE `user_progress_question`
  ADD UNIQUE KEY `user_quest` (`user_id`,`question_id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `answer`
--
ALTER TABLE `answer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT for table `coding_answer`
--
ALTER TABLE `coding_answer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `language`
--
ALTER TABLE `language`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `lesson`
--
ALTER TABLE `lesson`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `question`
--
ALTER TABLE `question`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=672;

--
-- AUTO_INCREMENT for table `question_type`
--
ALTER TABLE `question_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_profile`
--
ALTER TABLE `user_profile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `answer`
--
ALTER TABLE `answer`
  ADD CONSTRAINT `answer_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `question` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `coding_answer`
--
ALTER TABLE `coding_answer`
  ADD CONSTRAINT `coding_answer_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `question` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `lesson`
--
ALTER TABLE `lesson`
  ADD CONSTRAINT `lesson_ibfk_1` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `question`
--
ALTER TABLE `question`
  ADD CONSTRAINT `question_ibfk_1` FOREIGN KEY (`lesson_id`) REFERENCES `lesson` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `question_ibfk_2` FOREIGN KEY (`question_type`) REFERENCES `question_type` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `user_profile`
--
ALTER TABLE `user_profile`
  ADD CONSTRAINT `user_profile_ibfk_1` FOREIGN KEY (`role_code`) REFERENCES `user_role` (`code`) ON UPDATE CASCADE;

--
-- Constraints for table `user_progress_language`
--
ALTER TABLE `user_progress_language`
  ADD CONSTRAINT `user_progress_language_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_profile` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_progress_language_ibfk_2` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_progress_lesson`
--
ALTER TABLE `user_progress_lesson`
  ADD CONSTRAINT `user_progress_lesson_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_profile` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_progress_lesson_ibfk_2` FOREIGN KEY (`lesson_id`) REFERENCES `lesson` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_progress_question`
--
ALTER TABLE `user_progress_question`
  ADD CONSTRAINT `user_progress_question_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_profile` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_progress_question_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `question` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

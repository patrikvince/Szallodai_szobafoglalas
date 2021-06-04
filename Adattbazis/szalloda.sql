-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2021. Ápr 06. 14:58
-- Kiszolgáló verziója: 10.4.14-MariaDB
-- PHP verzió: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: szalloda
--
CREATE DATABASE IF NOT EXISTS szalloda DEFAULT CHARACTER SET utf8 COLLATE utf8_hungarian_ci;
USE szalloda;

DELIMITER $$
--
-- Eljárások
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `allapotMegszoritas` (IN `foglszam` INT)  NO SQL
BEGIN
SELECT allapot from foglalasok WHERE fog_szam = foglszam;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `erkezesMegszoritas` (IN `foglszam` INT)  NO SQL
begin
select mettol from foglalasok where fog_szam=foglszam;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `felhasznaloAPartnerben` (IN `id` INT)  NO SQL
BEGIN
SELECT p.azon FROM partner p INNER JOIN felhasznalok f on p.azon = f.id WHERE f.id = id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `fizetendo` (IN `foglszam` INT)  NO SQL
BEGIN
DECLARE sztip varchar(1);
DECLARE foglDat date;
DECLARE foglMettol date;
DECLARE foglMeddig date;
DECLARE ara int;
SELECT sz.sz_tipus, f.fogl_datum, f.mettol, f.meddig INTO sztip, foglDat, foglMettol, foglMeddig 
FROM foglalasok f INNER JOIN szoba sz ON f.szoba = sz.sz_szam
WHERE fog_szam = foglszam;

#select sztip, foglDat, foglMettol, foglMeddig, DATEDIFF(foglMeddig, foglMettol);

SELECT CASE WHEN DATEDIFF(foglMettol, foglDat) < nappal_elotte THEN utana_ar ELSE elotte_ar END INTO ara
FROM szobaarak
where sz_tipus = sztip AND mettol = 
(
SELECT MAX(mettol)
FROM szobaarak 
where sz_tipus = sztip and mettol <= foglDat
);

SELECT ara * DATEDIFF(foglMeddig, foglMettol) as fizetendo;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `hanyAgyas` (IN `fogl` INT)  NO SQL
BEGIN 
select szt.agyak_szama from foglalasok f INNER JOIN szoba sz ON f.szoba = sz.sz_szam INNER JOIN szobatipus szt ON sz.sz_tipus = szt.sz_tipus 
where fog_szam = fogl; 
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `igazolvanySzam` (IN `foglszam` INT)  NO SQL
BEGIN
SELECT p.igazolvany_szam FROM partner p INNER JOIN foglalasok f ON p.azon = f.megrendelo WHERE f.fog_szam = foglszam;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `mennyivelUtkozik` (IN `szoba1` INT, IN `mettol1` DATE, IN `meddig1` DATE)  NO SQL
BEGIN
SELECT COUNT(*) as ossz FROM foglalasok WHERE szoba = szoba1 AND (mettol BETWEEN mettol1 AND DATE_ADD(meddig1, INTERVAL -1 DAY) OR mettol1 BETWEEN mettol AND DATE_ADD(meddig, INTERVAL -1 DAY));
END$$

--
-- Függvények
--
CREATE DEFINER=`root`@`localhost` FUNCTION `allapotMegszoritasFg` (`foglszam` INT(11)) RETURNS INT(11) NO SQL
RETURN
(
SELECT allapot from foglalasok WHERE fog_szam = foglszam
)$$

CREATE DEFINER=`root`@`localhost` FUNCTION `fizetendofgv` (`sztip` VARCHAR(1), `foglDat` DATE, `foglMettol` DATE) RETURNS INT(11) NO SQL
RETURN
(
SELECT CASE WHEN DATEDIFF(foglMettol, foglDat) < nappal_elotte THEN utana_ar ELSE elotte_ar END
FROM szobaarak
where sz_tipus = sztip AND mettol = 
(
	SELECT MAX(mettol)
	FROM szobaarak 
	where sz_tipus = sztip and mettol <= foglDat
)
)$$

DELIMITER ;

-- --------------------------------------------------------

--
-- A nézet helyettes szerkezete arbevetelek
-- (Lásd alább az aktuális nézetet)
--
CREATE TABLE `arbevetelek` (
`megjegyzes` varchar(7)
,`arbevetel` decimal(41,0)
);

-- --------------------------------------------------------

--
-- A nézet helyettes szerkezete arbevetelekevente
-- (Lásd alább az aktuális nézetet)
--
CREATE TABLE `arbevetelekevente` (
`evben` int(4)
,`arbevetel` decimal(41,0)
);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához felhasznalok
--

CREATE TABLE felhasznalok (
  id int(11) NOT NULL,
  felhasznalonev varchar(20) COLLATE utf8_hungarian_ci NOT NULL,
  jelszo varchar(256) COLLATE utf8_hungarian_ci NOT NULL,
  jogosultsag varchar(1) COLLATE utf8_hungarian_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- A tábla adatainak kiíratása felhasznalok
--

INSERT INTO felhasznalok (id, felhasznalonev, jelszo, jogosultsag) VALUES
(1, 'admin', '36c4f23f8065793ea86f6fb47dd802d4', '1'),
(2, 'Patrik', '752f60dc38bac735e88b355c5b30d76d', '0');

-- --------------------------------------------------------

--
-- A nézet helyettes szerkezete fizetendok
-- (Lásd alább az aktuális nézetet)
--
CREATE TABLE `fizetendok` (
`fog_szam` int(11)
,`szoba` int(11)
,`mettol` date
,`meddig` date
,`megrendelo` int(11)
,`fogl_datum` date
,`allapot` tinyint(4)
,`sz_szam` int(11)
,`sz_tipus` varchar(1)
,`fizetendo` bigint(17)
);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához foglalasok
--

CREATE TABLE foglalasok (
  fog_szam int(11) NOT NULL,
  szoba int(11) NOT NULL,
  mettol date NOT NULL,
  meddig date NOT NULL,
  megrendelo int(11) NOT NULL,
  fogl_datum date NOT NULL,
  allapot tinyint(4) DEFAULT 1
) ;

--
-- A tábla adatainak kiíratása foglalasok
--

INSERT INTO foglalasok (fog_szam, szoba, mettol, meddig, megrendelo, fogl_datum, allapot) VALUES
(10, 202, '2019-01-01', '2019-01-07', 1, '2018-10-20', 4),
(13, 101, '2019-01-01', '2019-01-08', 2, '2018-11-01', 4),
(17, 201, '2019-01-16', '2019-01-23', 1, '2019-01-15', 4),
(32, 101, '2020-11-27', '2020-12-03', 1, '2020-11-10', 4),
(40, 102, '2020-12-09', '2020-12-15', 1, '2020-11-16', 4),
(41, 201, '2021-04-04', '2021-04-07', 1, '2021-03-16', 1),
(42, 301, '2000-01-01', '2000-01-02', 2, '2021-03-14', 5),
(45, 201, '2021-05-01', '2021-05-06', 1, '2021-02-10', 2),
(46, 101, '2021-04-04', '2021-04-06', 2, '2021-03-01', 2),
(47, 301, '2021-04-03', '2021-04-09', 2, '2021-03-25', 2),
(100, 202, '2019-01-01', '2019-01-07', 1, '2018-10-20', 4),
(101, 101, '2021-04-06', '2021-04-08', 1, '2021-04-06', 1),
(102, 302, '2021-04-07', '2021-04-11', 2, '2021-04-06', 1);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához lakik
--

CREATE TABLE lakik (
  fogl int(11) NOT NULL,
  vendeg int(11) NOT NULL,
  erkezes date NOT NULL,
  tavozas date DEFAULT NULL
) ;

--
-- A tábla adatainak kiíratása lakik
--

INSERT INTO lakik (fogl, vendeg, erkezes, tavozas) VALUES
(10, 1, '2019-01-01', '2019-01-07'),
(13, 1, '2019-01-01', '2019-01-08'),
(17, 1, '2019-01-16', '2019-01-28'),
(32, 1, '2020-11-27', '2020-12-03'),
(40, 1, '2020-12-09', '2020-12-15'),
(41, 1, '2021-04-05', '2021-04-07'),
(42, 2, '2021-04-12', '2021-04-19'),
(46, 2, '2021-04-03', NULL);

-- --------------------------------------------------------

--
-- A nézet helyettes szerkezete legtobbetfizetettmegrendelo
-- (Lásd alább az aktuális nézetet)
--
CREATE TABLE `legtobbetfizetettmegrendelo` (
`megrendelo` int(11)
,`arbevetel` decimal(41,0)
);

-- --------------------------------------------------------

--
-- A nézet helyettes szerkezete maiszobafizetesek
-- (Lásd alább az aktuális nézetet)
--
CREATE TABLE `maiszobafizetesek` (
`fog_szam` int(11)
,`megrendelo` int(11)
,`szoba` int(11)
,`mettol` date
,`meddig` date
,`fizetendo` bigint(17)
);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához partner
--

CREATE TABLE partner (
  azon int(11) NOT NULL,
  igazolvany_szam varchar(11) COLLATE utf8_hungarian_ci NOT NULL,
  nev varchar(100) COLLATE utf8_hungarian_ci NOT NULL,
  lakhely varchar(100) COLLATE utf8_hungarian_ci DEFAULT NULL,
  telefon varchar(11) COLLATE utf8_hungarian_ci NOT NULL,
  email varchar(100) COLLATE utf8_hungarian_ci DEFAULT NULL,
  jutalek int(3) DEFAULT 0
) ;

--
-- A tábla adatainak kiíratása partner
--

INSERT INTO partner (azon, igazolvany_szam, nev, lakhely, telefon, email, jutalek) VALUES
(1, '1212', 'admin', 'asd', '1212', 'admin@gmail.com', 0),
(2, '1213', 'Patrik', 'asd', '1213', 'patrikvince@gmail.com', 0);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához szoba
--

CREATE TABLE szoba (
  sz_szam int(11) NOT NULL,
  sz_tipus varchar(1) COLLATE utf8_hungarian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- A tábla adatainak kiíratása szoba
--

INSERT INTO szoba (sz_szam, sz_tipus) VALUES
(101, 'A'),
(102, 'A'),
(103, 'A'),
(201, 'B'),
(202, 'B'),
(301, 'C'),
(302, 'C');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához szobaarak
--

CREATE TABLE szobaarak (
  sz_tipus varchar(1) COLLATE utf8_hungarian_ci NOT NULL,
  mettol date NOT NULL,
  nappal_elotte tinyint(4) DEFAULT 0,
  elotte_ar int(11) NOT NULL DEFAULT 0,
  utana_ar int(11) NOT NULL DEFAULT 0
) ;

--
-- A tábla adatainak kiíratása szobaarak
--

INSERT INTO szobaarak (sz_tipus, mettol, nappal_elotte, elotte_ar, utana_ar) VALUES
('A', '2020-01-01', 30, 5000, 7500),
('A', '2021-04-03', 90, 6500, 8000),
('A', '2021-04-11', 90, 5000, 10000),
('B', '2019-01-01', 30, 3500, 5000),
('C', '2020-03-01', 30, 7500, 10000),
('C', '2021-04-04', 90, 5000, 2500);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához szobatipus
--

CREATE TABLE szobatipus (
  sz_tipus varchar(1) COLLATE utf8_hungarian_ci NOT NULL,
  agyak_szama tinyint(4) NOT NULL,
  felszereltseg tinyint(4) NOT NULL DEFAULT 1,
  komfort tinyint(4) DEFAULT 1,
  kep varchar(50) COLLATE utf8_hungarian_ci NOT NULL
) ;

--
-- A tábla adatainak kiíratása szobatipus
--

INSERT INTO szobatipus (sz_tipus, agyak_szama, felszereltseg, komfort, kep) VALUES
('A', 2, 3, 4, 'kepek/szoba1.jpg'),
('B', 1, 4, 4, 'kepek/szoba2.jpg'),
('C', 3, 2, 3, 'kepek/szoba3.jpg');

-- --------------------------------------------------------

--
-- A nézet helyettes szerkezete top3legtobbetfizetettmegrendelo
-- (Lásd alább az aktuális nézetet)
--
CREATE TABLE `top3legtobbetfizetettmegrendelo` (
`megrendelo` int(11)
,`arbevetel` decimal(41,0)
);

-- --------------------------------------------------------

--
-- Nézet szerkezete arbevetelek
--
DROP TABLE IF EXISTS `arbevetelek`;

CREATE ALGORITHM=UNDEFINED DEFINER=root@localhost SQL SECURITY DEFINER VIEW arbevetelek  AS  select case when fizetendok.mettol <= curdate() then 'Befolyt' else 'Varhato' end AS megjegyzes,sum(fizetendok.fizetendo) AS arbevetel from fizetendok group by case when fizetendok.mettol <= curdate() then 'Befolyt' else 'Varhato' end ;

-- --------------------------------------------------------

--
-- Nézet szerkezete arbevetelekevente
--
DROP TABLE IF EXISTS `arbevetelekevente`;

CREATE ALGORITHM=UNDEFINED DEFINER=root@localhost SQL SECURITY DEFINER VIEW arbevetelekevente  AS  select year(fizetendok.mettol) AS evben,sum(fizetendok.fizetendo) AS arbevetel from fizetendok where fizetendok.allapot > 1 and fizetendok.allapot < 5 group by year(fizetendok.mettol) ;

-- --------------------------------------------------------

--
-- Nézet szerkezete fizetendok
--
DROP TABLE IF EXISTS `fizetendok`;

CREATE ALGORITHM=UNDEFINED DEFINER=root@localhost SQL SECURITY DEFINER VIEW fizetendok  AS  select f.fog_szam AS fog_szam,f.szoba AS szoba,f.mettol AS mettol,f.meddig AS meddig,f.megrendelo AS megrendelo,f.fogl_datum AS fogl_datum,f.allapot AS allapot,sz.sz_szam AS sz_szam,sz.sz_tipus AS sz_tipus,fizetendofgv(sz.sz_tipus,f.fogl_datum,f.mettol) * (to_days(f.meddig) - to_days(f.mettol)) AS fizetendo from (foglalasok f join szoba sz on(f.szoba = sz.sz_szam)) ;

-- --------------------------------------------------------

--
-- Nézet szerkezete legtobbetfizetettmegrendelo
--
DROP TABLE IF EXISTS `legtobbetfizetettmegrendelo`;

CREATE ALGORITHM=UNDEFINED DEFINER=root@localhost SQL SECURITY DEFINER VIEW legtobbetfizetettmegrendelo  AS  select fizetendok.megrendelo AS megrendelo,sum(fizetendok.fizetendo) AS arbevetel from fizetendok where fizetendok.mettol <= curdate() group by fizetendok.megrendelo order by 2 desc limit 1 ;

-- --------------------------------------------------------

--
-- Nézet szerkezete maiszobafizetesek
--
DROP TABLE IF EXISTS `maiszobafizetesek`;

CREATE ALGORITHM=UNDEFINED DEFINER=root@localhost SQL SECURITY DEFINER VIEW maiszobafizetesek  AS  select fizetendok.fog_szam AS fog_szam,fizetendok.megrendelo AS megrendelo,fizetendok.szoba AS szoba,fizetendok.mettol AS mettol,fizetendok.meddig AS meddig,fizetendok.fizetendo AS fizetendo from fizetendok where fizetendok.mettol = curdate() ;

-- --------------------------------------------------------

--
-- Nézet szerkezete top3legtobbetfizetettmegrendelo
--
DROP TABLE IF EXISTS `top3legtobbetfizetettmegrendelo`;

CREATE ALGORITHM=UNDEFINED DEFINER=root@localhost SQL SECURITY DEFINER VIEW top3legtobbetfizetettmegrendelo  AS  select fizetendok.megrendelo AS megrendelo,sum(fizetendok.fizetendo) AS arbevetel from fizetendok where fizetendok.mettol <= curdate() group by fizetendok.megrendelo order by 2 desc limit 3 ;

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei felhasznalok
--
ALTER TABLE felhasznalok
  ADD PRIMARY KEY (id);

--
-- A tábla indexei foglalasok
--
ALTER TABLE foglalasok
  ADD PRIMARY KEY (fog_szam),
  ADD KEY szoba (szoba),
  ADD KEY megrendelo (megrendelo);

--
-- A tábla indexei lakik
--
ALTER TABLE lakik
  ADD PRIMARY KEY (fogl,vendeg),
  ADD KEY vendeg (vendeg);

--
-- A tábla indexei partner
--
ALTER TABLE partner
  ADD PRIMARY KEY (azon);

--
-- A tábla indexei szoba
--
ALTER TABLE szoba
  ADD PRIMARY KEY (sz_szam),
  ADD KEY kapcs1 (sz_tipus);

--
-- A tábla indexei szobaarak
--
ALTER TABLE szobaarak
  ADD PRIMARY KEY (sz_tipus,mettol);

--
-- A tábla indexei szobatipus
--
ALTER TABLE szobatipus
  ADD PRIMARY KEY (sz_tipus);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához felhasznalok
--
ALTER TABLE felhasznalok
  MODIFY id int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Megkötések a kiírt táblákhoz
--

--
-- Megkötések a táblához foglalasok
--
ALTER TABLE foglalasok
  ADD CONSTRAINT foglalasok_ibfk_1 FOREIGN KEY (szoba) REFERENCES szoba (sz_szam),
  ADD CONSTRAINT foglalasok_ibfk_2 FOREIGN KEY (megrendelo) REFERENCES partner (azon);

--
-- Megkötések a táblához lakik
--
ALTER TABLE lakik
  ADD CONSTRAINT lakik_ibfk_1 FOREIGN KEY (vendeg) REFERENCES partner (azon),
  ADD CONSTRAINT lakik_ibfk_2 FOREIGN KEY (fogl) REFERENCES foglalasok (fog_szam);

--
-- Megkötések a táblához szoba
--
ALTER TABLE szoba
  ADD CONSTRAINT kapcs1 FOREIGN KEY (sz_tipus) REFERENCES szobatipus (sz_tipus);

--
-- Megkötések a táblához szobaarak
--
ALTER TABLE szobaarak
  ADD CONSTRAINT kapcs2 FOREIGN KEY (sz_tipus) REFERENCES szobatipus (sz_tipus);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

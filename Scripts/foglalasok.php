<?php
session_start();
$nev = $_SESSION['fnev'];
$id = $_SESSION['id'];
echo "<h2>$nev foglalásai:</h2>";
require_once "Scripts/szallodaKapcsolat.php";


//echo "session: $id<br>";

$eljaras = "felhasznaloAPartnerben($id);";
$hiba = "Hiba a lekerdezeskor! (foglalasok.php/13)";
$eredmeny = $abKapcs->eljaras($eljaras, $hiba);
//var_dump($eredmeny);
$hiba = "Hiba a azonosito lekerdezese kozben!(foglalasok.php/16)";
$azon = $abKapcs->fetchEgyAdat($eredmeny, "azon");

//echo "azon: $azon<br>";

//megszamolja, hogy hany sorunk van a partner tablaban az adott azonositoval
$oszlop = "count(*) as ossz";
$tabla = "partner";
$hiba = "Hiba a lekerdezeskor! (foglalasok.php/25)";
$feltetel = "azon = $azon";
$eredmeny = $abKapcs->lekerdezes($oszlop, $tabla, $hiba, $feltetel);
$hiba = "Hiba a sor lekerdezesekor!(foglalasok.php/35)";
$sor = $abKapcs->fetchEgyAdat($eredmeny, "ossz", $hiba);
if ($sor < 1) {
    echo "Nincsenek foglalasai!";
} else {

    $oszlop = "fog_szam, szoba, mettol, meddig, fogl_datum";
    $tabla = "foglalasok";
    $hiba = "Hiba a lekerdezeskor! (foglalasok.php/36)";
    $feltetel = "megrendelo = $id AND allapot = 1";
    $eredmeny = $abKapcs->lekerdezes($oszlop, $tabla, $hiba, $feltetel);

    $hiba = "Hiba a foglalás szám lekérdezésekor!(foglalasok.php/39)";
    $foglalasaim = $abKapcs->fetchTobbAdat($eredmeny, $hiba);

}





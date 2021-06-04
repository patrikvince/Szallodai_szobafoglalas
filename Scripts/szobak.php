<?php

include_once "szallodaKapcsolat.php";

$oszlop = "count(*) as ossz";
$tabla = "szobatipus";
$hiba = "Hiba a lekerdezeskor!(szobak.php/8)";
$eredmeny = $abKapcs->lekerdezes($oszlop, $tabla, $hiba);
$db = $abKapcs->fetchEgyAdat($eredmeny, "ossz");

$oszlop = "sz_tipus";
$tabla = "szobatipus";
$hiba = "Hiba a lekerdezeskor!(szobak.php/13)";
$eredmeny = $abKapcs->lekerdezes($oszlop, $tabla, $hiba);
$szobatipusok = $abKapcs->fetchTobbAdat($eredmeny);

$sztipus = array();
array_push($sztipus, $szobatipusok);
 $szobatipus = $sztipus[0][0]['sz_tipus'];
 //var_dump($szobatipus);
$tabla = "szobatipus";
$hiba = "Hiba a szobak lekerdezeskor! (szobak.php/27)";
$index = 0;
while ($index < $db){
    $oszlopok = "DISTINCT '" . $sztipus[0][$index]['sz_tipus'] ."' as sz_tipus, fizetendofgv('" . $sztipus[0][$index]['sz_tipus'] . "' ,CURRENT_DATE,CURRENT_DATE) as ar, kep";
    $feltetel = "kep like '%szoba" . ($index + 1) . "%'";
    $eredmeny = $abKapcs->lekerdezes($oszlopok, $tabla, $hiba, $feltetel);
    //var_dump($eredmeny);
    //belerakjuk az adatokat egy asszociaív tömbbe
    $hiba = "Hiba a szobak kiirasanal!(szobak.php/31)";
    $adatok = $abKapcs->fetchTobbAdat($eredmeny, $hiba);
    $index++;
    //var_dump($adatok);
    //vegigmegy a tombbon es kiirja a tomb kulcsat es erteket
    foreach ($adatok as $adat) {
        echo 'Szobatípus: ' . $adat['sz_tipus'] . "<br>";
        echo '<img alt="szoba" src="' . $adat['kep'] . '"><br>';
        echo 'Ár: ' . $adat['ar'] . " Ft/éjszaka<br><br>";
    }
}
$abKapcs->kapcsolatBezaras();

?>
<?php

$oszlop = "distinct(sz_tipus) as sztipus";
$hiba = "Hiba a szobatípusok lekérdezésekor!(szobatipusok.php/6)";
include_once 'szallodaKapcsolat.php';
$eredmeny = $abKapcs->lekerdezes($oszlop, "szobatipus", $hiba);

$hiba = "Hiba a szobatipusok kiirasakor(szobatipusok.php/9)";
$adatok = $abKapcs->fetchTobbAdat($eredmeny, $hiba);
$abKapcs->kapcsolatBezaras();


echo "<label for='sz_tipus'>Szobatípus</label>&nbsp;";
echo "<select name='sz_tipus' id='sz_tipus' required>";
foreach ($adatok as $adat){
    echo "<option>" . $adat['sztipus'] . "</option>";
}
echo "</select>";





?>


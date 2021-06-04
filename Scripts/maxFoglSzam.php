<?php


//$sql = "SELECT MAX(fog_szam) as fszam_max FROM foglalasok";
$hiba = "Hiba a maximum foglalas szam lekerdezesekor!(maxFoglSzam.php/7)";
include_once "szallodaKapcsolat.php";
$eredmeny = $abKapcs->lekerdezes("MAX(fog_szam) as fszam_max", "foglalasok", $hiba);

$hiba = "Hiba a maximum foglalas szam ertekekor!(maxFoglSzam.php/10)";
$kiiras = $abKapcs->fetchEgyAdat($eredmeny, "fszam_max", $hiba);
$abKapcs->kapcsolatBezaras();
echo $kiiras+1;
?>

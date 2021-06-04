<?php
require_once "Scripts/szallodaKapcsolat.php";

if (isset($_POST['lemond'])) {
    $foglszam = $_POST['foglszam'];
    $szemelyiSzam = $_POST['sz_szam'];
    $foglszam = $abKapcs->ellenorzes($foglszam);
    $szemelyiSzam = $abKapcs->ellenorzes($szemelyiSzam);

    $tabla = "foglalasok";
    $hiba = "Nem sikerult lemondani a foglalast!";
    $adatok = "allapot = 5, mettol = '2000-01-01', meddig = '2000-01-02'";
    $feltetel = "fog_szam = $foglszam";
    //echo "$adatok  $feltetel";
    $abKapcs->adatFrissit($tabla, $adatok, $feltetel, $hiba);
    $abKapcs->kapcsolatBezaras();
    echo "Sikeres lemondas!";
    header("Location: lemondas.php");
}
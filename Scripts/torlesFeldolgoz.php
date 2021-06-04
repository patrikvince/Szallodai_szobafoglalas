<?php
if (isset($_POST['torol'])) {
    include "./Scripts/szallodaKapcsolat.php";
    $foglszam = $_POST['foglszam'];
    $szemelyiSzam = $_POST['sz_szam'];

    $foglszam = $abKapcs->ellenorzes($foglszam);
    $szemelyiSzam = $abKapcs->ellenorzes($szemelyiSzam);
    if (isset($foglszam) && isset($szemelyiSzam)) {

        $tabla = "foglalasok f inner join partner p on f.megrendelo = p.azon";
        $hiba = "Hiba a foglalas szam lekerdezesekor! (torles.php/35)";
        $feltetel = "igazolvany_szam = $szemelyiSzam";
        $eredmeny = $abKapcs->lekerdezes("f.fog_szam", $tabla, $hiba, $feltetel);

        $hiba = "Hiba a foglalas szam ertekadasakor!(torles.php/40)";
        $foglalasSzam = $abKapcs->fetchEgyAdat($eredmeny, "fog_szam", $hiba);
        if ($foglalasSzam == $foglszam){
            $hiba = "Hiba a torleskor! Nem megfelelo adatok!";
            $abKapcs->adatTorles("foglalasok" ,"fog_szam = $foglszam", $hiba);

            echo 'Sikeres törlés!';
        }else{
            echo "Sikertelen törlés, nem létező foglalás szám!";
        }

    } else {
        echo "Sikertelen törlés, nincsenek adatok!";
    }
    $abKapcs->kapcsolatBezaras();
}
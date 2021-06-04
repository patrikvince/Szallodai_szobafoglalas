<?php

require_once "szallodaKapcsolat.php";

if (isset($_POST['kuld'])) {
    $felhasznalonev = $_POST['fnev'];
    $jelszo = $_POST['jelszo'];
    $jelszo2 = $_POST['jelszo2'];
    //ellenorzes
    $felhasznalonev = $abKapcs->ellenorzes($felhasznalonev);
    $jelszo = $abKapcs->ellenorzes($jelszo);
    $jelszo2 = $abKapcs->ellenorzes($jelszo2);
    //titkositas
    $jelszo = md5($jelszo);
    $jelszo2 = md5($jelszo2);
    //visszaadja a maximum id-t
    $hiba = "Hiba a maximum id lekerdezeskor!(regisztral.php/17)";
    $eredmeny = $abKapcs->lekerdezes("MAX(id) as id", "felhasznalok", $hiba);
    $hiba = "Hiba a max(id) from foglalasok lekerdezese kozben!(regisztral.php/19)";
    $id = $abKapcs->fetchEgyAdat($eredmeny, "id", $hiba);
    //ha jelszavak megegyeznek akkor felviszi a kapott adatokat a felhasznalok tablaba,
    //majd elindit egy session-t a kapott felhasznalonevvel
    if (strcmp($jelszo, $jelszo2) == 0) {
        $ertek = "$id+1, '$felhasznalonev', '$jelszo', 0";
        $hiba = "Hiba a regisztraciokor! Nem megfelelo adatok!";
        $eredmeny = $abKapcs->adatBeszuras("felhasznalok", "id, felhasznalonev, jelszo, jogosultsag", $ertek, $hiba);

        if ($eredmeny) {
            session_start();
            $_SESSION['fnev'] = $felhasznalonev;
            $fnev = $_SESSION['fnev'];
            $id = $abKapcs->sessionIdBeallitas($felhasznalonev, $jelszo);
            $_SESSION['id'] = $id;
            $abKapcs->kapcsolatBezaras();
            return "Sikeres regisztráció!";
        } else {
            $abKapcs->kapcsolatBezaras();
            return "Sikertelen regisztráció!";
        }
    } else {
        return "A két jelszó nem egyezik meg!";
    }
} 
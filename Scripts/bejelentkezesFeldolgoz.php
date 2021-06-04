<?php

include_once "szallodaKapcsolat.php";

if (isset($_POST['kuld'])) {
    $kapcs = $abKapcs->kapcsolat();
    $felhasznalonev = $_POST['fnev'];
    $jelszo = $_POST['jelszo'];
    $felhasznalonev = $abKapcs->ellenorzes($felhasznalonev);
    $jelszo = $abKapcs->ellenorzes($jelszo);
    $jelszo = md5($jelszo);

    $tabla = "felhasznalok";
    $hiba = "Hiba a felhasznalok jelszavanak lekerdezesekor!(bejelentkezesFeldolgoz/15)";
    $feltetel = "felhasznalonev = '$felhasznalonev' AND jelszo = '$jelszo'";
    $eredmeny = $abKapcs->lekerdezes("jelszo", $tabla, $hiba, $feltetel);

    $hiba = "Hiba a kapott jelszo lekerdezesekor!(bejelentkezesFeldolgoz.php/18)";
    $kapottJelszo = $abKapcs->fetchEgyAdat($eredmeny, "jelszo", $hiba);
    $egyezik = $jelszo == $kapottJelszo;
    if ($egyezik) {
        $hiba = "Hiba a felhasznalok jogosultsaganak lekerdezesekeor!(bejelentkezesFeldolgoz/22)";
        $eredmeny1 = $abKapcs->lekerdezes("jogosultsag", $tabla, $hiba, $feltetel);

        $hiba = "Hiba a jogosultsag lekerdezesekor!(bejelentlezesFeldolgoz.php/25)";
        $jogosultsag = $abKapcs->fetchEgyAdat($eredmeny1, "jogosultsag", $hiba);

        if ($jogosultsag == '1') {
            session_start();
            $_SESSION['admin'] = true;
            $_SESSION['fnev'] = $felhasznalonev;
            $id = $abKapcs->sessionIdBeallitas($felhasznalonev, $jelszo);
            $_SESSION['id'] = $id;
            header("Location: admin.php");
        } elseif ($jogosultsag == '0'){
            session_start();
            $_SESSION['fnev'] = $felhasznalonev;

            $id = $abKapcs->sessionIdBeallitas($felhasznalonev, $jelszo);
            $_SESSION['id'] = $id;

            header("Location: index.php");
        } else {
            //var_dump($eredmeny);
            echo "Sikertelen belépés, hibás felhasználonév vagy jelszó!";
        }
        $abKapcs->kapcsolatBezaras();
    } else {
        echo "Sikertelen belépés, hibás jelszó!";
    }
}

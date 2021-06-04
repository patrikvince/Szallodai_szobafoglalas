<?php
include_once "Scripts/szallodaKapcsolat.php";

if (isset($_POST['maiErkezok'])){

    $hiba = "Hiba a mai érkezők lekérdezésekor!";
    $eredmeny = $abKapcs->lekerdezes("*", "maiszobafizetesek", $hiba);
    $hiba = "Hiba a sor lekérdezésekor!(adminLekerdezesek.php/9)";
    $adatok = $abKapcs->fetchTobbAdat($eredmeny, $hiba);

    if (is_string($adatok)){
        echo "Nincsenek mai szobafizetések!";
    }else{
        echo "<table><tr><th>foglalás szám</th><th>megrendelő</th><th>szoba szám</th><th>mettől</th><th>meddig</th><th>fizetendő</th></tr>";
        foreach ($adatok as $adat) {
            echo "<tr><td>". $adat['fog_szam'] . "</td>";
            echo "<td>". $adat['megrendelo'] . "</td>";
            echo "<td>" . $adat['szoba'] . "</td>";
            echo "<td>" . $adat['mettol'] . "</td>";
            echo "<td>" . $adat['meddig'] . "</td>";
            echo "<td>" . $adat['fizetendo'] . "</td></tr>";
        }
        
    }

}elseif (isset($_POST['legtobbetFizetett'])){
    $hiba = "Hiba a legtöbbet fizetett vendég lekérdezésekor!";
    $tabla = "legtobbetfizetettmegrendelo l INNER JOIN partner p ON l.megrendelo = p.azon";
    $eredmeny = $abKapcs->lekerdezes("*", $tabla, $hiba);
    $hiba = "Hiba a sor lekérdezésekor!(adminLekerdezesek.php/30)";
    $adatok = $abKapcs->fetchTobbAdat($eredmeny, $hiba);

    if (is_string($adatok)){
        echo "Nincs ilyen megrendelő!";
    }else{
        echo "<table><tr><th>azonosító</th><th>név</th><th>összesen</th></tr>";
        foreach ($adatok as $adat) {
            echo "<tr><td>". $adat['megrendelo'] . "</td>";
            echo "<td>". $adat['nev'] . "</td>";
            echo "<td>" . $adat['arbevetel'] . " Ft</td></tr>";
        }
    }

}elseif (isset($_POST['arbevetelEvente'])){
    $hiba = "Hiba az éves árbevétel lekérdezésekor!";
    $eredmeny = $abKapcs->lekerdezes("*", "arbevetelekevente", $hiba);
    $hiba = "Hiba a sor lekérdezésekor!(adminLekerdezesek.php/46)";
    $adatok = $abKapcs->fetchTobbAdat($eredmeny, $hiba);

    if (is_string($adatok)){
        echo "Nincsenek árbevételek!";
    }else{
        echo "<table><tr><th>Év</th><th>árbevétel</th></tr>";
        foreach ($adatok as $adat) {
            echo "<tr><td>". $adat['evben'] . "</td>";
            echo "<td>" . $adat['arbevetel'] . " Ft</td></tr>";
        }
    }
}

echo "</table>";


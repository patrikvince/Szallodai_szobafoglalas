<?php
require_once 'Scripts/foglalasok.php';
if(!(is_string($foglalasaim))) {
    echo "<label for='foglszam'>Foglalás szám</label>";
    echo "<select name='foglszam' id='foglszam' required><br>";
    $foglSzam = "";
    foreach ($foglalasaim as $adat) {
        echo "<option>" . $adat['fog_szam'] . "</option>";
        $foglSzam = $adat['fog_szam'];
    }
    echo "</select><br>";

    $eljaras = "igazolvanySzam(" . $foglSzam . ")";
    $hiba = "Hiba a személyi szám lekérdezésekor!(lemondasKiiras/15)";
    $eredeny = $abKapcs->eljaras($eljaras, $hiba);
    $hiba = "Hiba a személyi szám értékadásakor!";
    $iSzam = $abKapcs->fetchEgyAdat($eredeny, "igazolvany_szam", $hiba);

    $abKapcs->kapcsolatBezaras();
    echo "<label for='sz_szam'>Személyi igazolvány szám:</label>";
    echo "<input type='text' name='sz_szam' id='sz_szam' value='$iSzam' required>";
    echo "<br>";
    echo "<input type='submit' name='lemond' id='lemond' value='Lemond'>";
}else{
    echo "Nincsenek foglalásai!";
}
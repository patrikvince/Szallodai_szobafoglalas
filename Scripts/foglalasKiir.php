<?php
include_once "foglalasok.php";
$abKapcs->kapcsolatBezaras();
if (is_string($foglalasaim)){
    echo "Nincsenek foglalásai!";
}else{
    echo "<table><tr><th>Foglalás szám</th><th>Szoba szám</th><th>Mettől</th>"
    . "<th>Meddig</th><th>Foglalás dátuma</th></tr>";
    foreach ($foglalasaim as $adat) {
        echo "<tr><td>" . $adat['fog_szam'] . "</td>";
        echo "<td>" . $adat['szoba'] . "</td>";
        echo "<td>" . $adat['mettol'] . "</td>";
        echo "<td>" . $adat['meddig'] . "</td>";
        echo "<td>" . $adat['fogl_datum'] . "</td></tr>";
    }
    echo "</table>";
}

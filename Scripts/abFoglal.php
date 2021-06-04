<?php
require_once 'szallodaKapcsolat.php';
$foglalas = $abKapcs->foglal();
echo $foglalas;
$abKapcs->kapcsolatBezaras();
?>



<?php
include_once "./Kapcsolat.php";
include_once "./AbKapcsolat.php";
//példányosítjuk az osztályunkat, hogy létrehozzuk a szálloda adatbázis kapcsolatot
$abKapcs = new AbKapcsolat("localhost", "root", "", "szalloda");
//$abKapcs = new AbKapcsolat("tanulo23.szf1b.oktatas.szamalk-szalezi.hu", "c1_tanulo23szf1b",
//"_tanulo23szf1b", "c1ABtanulo23szf1b");



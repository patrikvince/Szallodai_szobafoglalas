<?php

namespace Kapcs;
abstract class Kapcsolat {
    protected $host;
    protected $felhasznalonev;
    protected $jelszo;
    protected $abNev;

    public abstract function kapcsolat();
    public abstract function kapcsolatBezaras();
    public abstract function lekerdezes($oszlop, $tabla, $hiba, $feltetel);
    public abstract function adatBeszuras($tabla, $oszlopok, $ertekek, $hiba);
    public abstract function adatTorles($tabla, $ertekek, $hiba);
    public abstract function ellenorzes($adat);
}

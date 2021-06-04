<?php

use Kapcs\Kapcsolat;

class AbKapcsolat extends Kapcsolat
{

    //beállítja az adatbázis kapcsolatahoz tartozó értékeket
    public function __construct($host, $felhasznalonev, $jelszo, $abNev)
    {
        //ab kapcsolat adatai
        $this->host = $host;
        $this->felhasznalonev = $felhasznalonev;
        $this->jelszo = $jelszo;
        $this->abNev = $abNev;
        $this->kapcsolat();
    }

    //létrehozza az adatbáziskapcsolatot
    public function kapcsolat()
    {
        $kapcs = new mysqli($this->host, $this->felhasznalonev, $this->jelszo, $this->abNev) 
                or die("Hiba az adatbázishoz való kapcsolódáskor!");

        return $kapcs;
    }

    public function lekerdezes($oszlop, $tabla, $hiba, $feltetel = 1)
    {

        $sql = "SELECT " . $oszlop . " FROM " . $tabla . " WHERE " . $feltetel;
        $hiba = "Hiba a lekerdezeskor! " . $sql;
        return $lekerdez = $this->futtat($sql, $hiba);
    }

    public function kapcsolatBezaras()
    {
        $this->kapcsolat()->close();
    }

    public function eljaras($eljaras, $hiba){
        $sql = "CALL $eljaras";
        //$hiba = "Hiba az eljaras futtatasakor! $sql";
        return $lekerdez = $this->futtat($sql, $hiba);
    }


    public function adatBeszuras($tabla, $oszlopok, $ertekek, $hiba)
    {
        $sql = "INSERT INTO " . $tabla . "( " . $oszlopok . ") "
            . "VALUES (" . $ertekek . ")";
        //$hiba = "Hiba az adatok beszurasakor! " . $sql;
        return $beszur = $this->futtat($sql, $hiba);

    }

    public function adatTorles($tabla,  $ertekek, $hiba )
    {
        $sql = "DELETE FROM " . $tabla . " WHERE " . $ertekek;
        //$hiba = "Hiba az adatok torlesekor!";
        return $beszur = $this->futtat($sql, $hiba);

    }


    public function ellenorzes($adat)
    {
        $adat = trim($adat); //leszedi a whitespace-eket
        $adat = stripslashes($adat); //leszedi a \-t dupla \ eseten egyet csinal belole
        $adat = htmlspecialchars($adat);

        return $adat;
    }

    public function futtat($sql, $hiba)
    {
        $lekerdez = $this->kapcsolat()->query($sql) or die($hiba);
        return $lekerdez;
    }

    public function foglal()
    {

        include_once "Scripts/szallodaKapcsolat.php";
        if (isset($_POST['kuld'])) {
            $sztipus = $_POST['sz_tipus'];
            $mettol = $_POST['mettol'];
            $meddig = $_POST['meddig'];
            $foglszam = $_POST['fszam'];
            $foglDatum = date("Y/m/d");
            $igazolvanySzam = $_POST['igazolvanysz'];
            $lakhely = $_POST['lakhely'];
            $tel = $_POST['telszam'];
            $email = $_POST['email'];

            //ellenorzes
            $sztipus = $this->ellenorzes($sztipus);
            $igazolvanySzam = $this->ellenorzes($igazolvanySzam);
            $lakhely = $this->ellenorzes($lakhely);
            $tel = $this->ellenorzes($tel);
            $email = $this->ellenorzes($email);


            if (isset($sztipus) && isset($mettol) && isset($meddig) && isset($foglszam) && isset($foglDatum) && isset($tel)) {
                $allapot = 1;
                $nev = $_SESSION['fnev'];

                //regisztralt vagy bejelentlezett partner azonositojat adja vissza
                $feltetel = "felhasznalonev = '$nev'";
                $hiba = "Hiba a partner azonosito visszaadasakor!";
                $eredmeny = $this->lekerdezes("id", "felhasznalok", $hiba, $feltetel);
                $azon = $this->fetchEgyAdat($eredmeny, "id");

                $feltetel = "igazolvany_szam = \"$igazolvanySzam\"";
                $hiba = "Hiba a partner tablaban valo osszes sor lekerdezesekor!";
                $eredmeny = $this->lekerdezes("count(*) as ossz", "partner", $hiba, $feltetel);
                $vanE = $this->fetchEgyAdat($eredmeny, "ossz");

                //echo $vanE;
                //ha van mar ilyen partner akkor foglal, ha nincs akkor felviszi, majd foglal
                if ($vanE == 1) {
                    $hiba = "Hiba a foglalaskor! Nem megfelelo adatok!";
                    $this->foglalBeszuras($sztipus, $mettol, $meddig, $foglszam, $foglDatum, $azon, $allapot, $hiba);
                } else {
                    //ide
                    $jutalek = 0;
                    $oszlopok = "azon, igazolvany_szam, nev, lakhely, telefon, email, jutalek";
                    $feltetel = "$azon ,\"$igazolvanySzam\", \"$nev\", \"$lakhely\", \"$tel\", \"$email\", $jutalek";
                    $hiba = "Hiba a foglalaskor! Nem megfelelo adatok!";
                    $this->adatBeszuras("partner", $oszlopok, $feltetel, $hiba);
                    $hiba = "Hiba a foglalaskor! Nem megfelelo adatok!";
                    $this->foglalBeszuras($sztipus, $mettol, $meddig, $foglszam, $foglDatum, $azon, $allapot, $hiba);

                }
            } else {
                return "Sikertelen foglalás, hibás adatok!";
            }

        }
        return "";//"Sikertelen foglalás! Nincsenek kitöltött adatok!";
    }




    function foglalBeszuras($sztipus, $mettol, $meddig, $foglszam, $foglDatum, $azon, $allapot, $hiba){
        //az adott szobatipushoz tartozo szabad szoba szamot adja vissza
        $eredmeny = $this->lekerdezes("sz_szam", "szoba", $hiba, "sz_tipus = UPPER('$sztipus')");
        $hiba = "Hiba a szabad szobaszam lekerdezese kozben!(AbKapcsolat.php/149)";
        $szobaSzamok = $this->fetchTobbAdat($eredmeny, $hiba);

        $szobaIndexelt = array();

        foreach ($szobaSzamok as $szobaSzam) {
            array_push($szobaIndexelt, $szobaSzam);
        }
        //var_dump($szobaIndexelt);


        $jo = false;
        $index = 0;
        do {
            $szoba = $szobaIndexelt[$index]["sz_szam"];
            //echo $szoba . "<br>";
            $hiba = "Hiba a fogalalskor! Nincsen ures szoba $mettol - $meddig datum kozott '$sztipus' szobatipusra/re";
            //$eredmeny = $this->lekerdezes($sor, $tabla, $hiba, $feltetel);
            $eljaras = "mennyivelUtkozik($szoba, '$mettol', '$meddig');";
            $eredmeny = $this->eljaras($eljaras, $hiba);
            $utkozes = $this->fetchEgyAdat($eredmeny, "ossz");
            //var_dump($utkozes);
            //echo $utkozes;
            //echo "<br>";

            //ha nincs utkozes akkor 0-at kapunk vissza
            if ($utkozes != 0) {
                $index++;
            }else{
                $jo = true;
            }
        } while (!$jo);
        //echo $szoba;
        if ($utkozes == 0){
            $oszlopok = "fog_szam, szoba, mettol, meddig, megrendelo, fogl_datum, allapot";
            $tabla = "foglalasok";
            $ertekek = "$foglszam , $szoba, '$mettol', '$meddig', $azon, '$foglDatum', $allapot";
            $hiba = "Hiba a foglalaskor! Nem megfelelo adatok!";
            $beszur = $this->adatBeszuras($tabla, $oszlopok, $ertekek, $hiba);
            if ($beszur){
                echo "Sikeres foglalas";
            }
            header("Location: index.php");
        }else{
            echo "Nincs szabad szoba a \"$sztipus\"-ra/re ezekre a napokra!";
        }


    }

    //ha a lekerdezesunk tobb, mint 0 sorral ter vissza, akkor visszaadjuk a kert sort
    public function fetchEgyAdat($eredmeny, $ertek, $hiba = 1)
    {
        if ($eredmeny->num_rows > 0) {
            $sor = $eredmeny->fetch_assoc();
            return $sor[$ertek];
        } else {
            return $hiba;
        }
    }

    //ha a lekerdezesunk tobb, mint 0 sorral ter vissza, akkor visszaadjuk a kapott sorokat
    public function fetchTobbAdat($eredmeny, $hiba = 1)
    {
            if ($eredmeny->num_rows > 0) {
                $adatok = array();
            while ($sor = $eredmeny->fetch_assoc()) {
                $adatok[] = $sor;
            }
            return $adatok;
        } else {
            return $hiba;
        }
    }

    //frissiti a megadott adatot az adatbazisban
    public function adatFrissit($tabla, $adatok, $feltetel, $hiba){
        $sql = "UPDATE $tabla SET $adatok WHERE $feltetel";
        //$hiba = "Sikertelen adatfrissites";
        return $beszur = $this->futtat($sql, $hiba);
    }

    public function sessionIdBeallitas($felhasznalonev, $jelszo){
        $oszlop = "id";
        $tabla = "felhasznalok";
        $hiba = "Hiba a lekerdezeskor! (id, felhasznalok)";
        $feltetel = "felhasznalonev = \"$felhasznalonev\" AND jelszo = \"$jelszo\"";
        $eredmeny = $this->lekerdezes($oszlop, $tabla, $hiba, $feltetel);
        return $id = $this->fetchEgyAdat($eredmeny, $oszlop);

    }


}

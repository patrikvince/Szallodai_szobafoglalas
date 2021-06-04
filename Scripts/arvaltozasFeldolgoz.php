<?php

if (isset($_POST['kuld'])){
    $sztipus = $_POST['sz_tipus'];
    $mettol = $_POST['mettol'];
    $nappalElotte = $_POST['nappalElotte'];
    $elotteAr = $_POST['ear'];
    $utanaAr = $_POST['uar'];



    include_once "Scripts/szallodaKapcsolat.php";


    $oszlopok = "sz_tipus, mettol, nappal_elotte, elotte_ar, utana_ar";
    $ertekek = "'$sztipus', '$mettol', $nappalElotte, $elotteAr, $utanaAr";
    $hiba = "Hiba az árváltoztatás közben! Hibás adatok!";
    $beszur = $abKapcs->adatBeszuras("szobaarak", $oszlopok, $ertekek, $hiba);
    

    if ($beszur){
        echo "Sikeres árváltoztatás!";
    }else{
        echo "Sikertelen árváltozás! Hibás ár!";
    }
}
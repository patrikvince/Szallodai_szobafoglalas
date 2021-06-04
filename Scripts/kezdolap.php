<?php
session_start();
echo "<nav>";
echo "<ul>";
if(!(isset($_SESSION['fnev']))){
    echo "<li><a href='regisztracio.php'>Regisztráció</a></li>";
    echo "<li><a href='bejelentkezes.php'>Bejelentkezés</a></li>";
    echo "</ul>";
    echo "</nav>";
    echo "<h2>Foglaláshoz kérem jelentkezzen be!</h2>";
    include_once "./Scripts/szobak.php";

}elseif (isset($_SESSION['admin'])){
    $fnev = $_SESSION['fnev'];
    echo "<li><a href='admin.php'>Admin felület</a></li>";
    echo "<li><a href='foglalasaim.php'>Fogálasaim</a></li>";
    echo "<li><a href='arvaltozas.php'>Árváltozás</a></li>";
    echo "<li><a href='torles.php'>Foglalások törlése</a></li>";
    echo "<li><a href='kijelentkezes.php'>Kijelentkezés</a></li>";
    echo "</ul>";
    echo "</nav>";
    echo "<h2>Üdvözöljük $fnev!</h2>";
    include_once "./Scripts/szobak.php";
    echo "<br>";
    include_once "./urlap.php";
}else{
    $fnev = $_SESSION['fnev'];
    echo "<li><a href='foglalasaim.php'>Foglalásaim</a></li>";
    echo "<li><a href='lemondas.php'>Foglalás lemondása</a></li>";
    echo "<li><a href='kijelentkezes.php'>Kijelentkezés</a></li>";
    echo "</ul>";
    echo "</nav>";
    echo "<h2>Üdvözöljük $fnev!</h2>";
    include_once "./Scripts/szobak.php";
    echo "<br>";
    include_once "./urlap.php";
}
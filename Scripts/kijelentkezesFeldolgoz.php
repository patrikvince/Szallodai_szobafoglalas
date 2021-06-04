<?php
session_start();
session_unset();

echo "Sikeres kijelentkezés! <br>" ;
echo "<br>";
echo "<a href='./index.php'>Vissza a kezdőlapra</a>";
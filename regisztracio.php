<!DOCTYPE html>
<html lang="hu-HU">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link href="css/stilus.css" rel="stylesheet" type="text/css"/>
    <title>Vince Patrik</title>
</head>
<body>
<main>
    <nav>
        <ul>
            <li><a href="./index.php">Kezdőlap</a></li>
            <li><a href="bejelentkezes.php">Bejelentkezés</a></li>
        </ul>
    </nav>
    <h2>Regisztráció</h2>
<form action="#" method="post">
    <label for="fnev">Felhasználonév</label>
    <input type="text" name="fnev" id="fnev" required>
    <br>
    <label for="jelszo">Adjon meg egy jelszót</label>
    <input type="password" name="jelszo" minlength="6" maxlength="14" id="jelszo" required>
    <br>
    <label for="jelszo2">Jelszó megerősítése</label>
    <input type="password" name="jelszo2" minlength="6" maxlength="14" id="jelszo2" required>
    <br>
    <input type="submit" name="kuld" id="kuld" value="Elküld">
</form>
</main>
<footer>Készítette: Vince Patrik</footer>
</body>
</html>

<?php
include_once "Scripts/regisztracioFeldolgoz.php";
?>







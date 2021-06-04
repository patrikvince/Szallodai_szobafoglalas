<!DOCTYPE html>
<html lang="hu-HU">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>Vince Patrik</title>
</head>
<body>
<main>
    <nav>
        <ul>
            <li><a href="./index.php">Kezdőlap</a></li>
            <li><a href="regisztracio.php">Regisztráció</a></li>
        </ul>
    </nav>
    <h2>Belépés</h2>
    <form action="#" method="post">
        <label for="fnev">Felhasználónév</label>
        <input type="text" name="fnev" id="fnev" required>
        <br>
        <label for="jelszo">Jelszó</label>
        <input type="password" name="jelszo" id="jelszo" minlength="6" maxlength="14" placeholder="min. 6 karakter" required>
        <br>
        <input type="submit" name="kuld" id="kuld" value="Belépés">
    </form>
    <?php
    include_once "Scripts/bejelentkezesFeldolgoz.php";
    ?>
</main>
<footer>Keszitette: Vince Patrik</footer>
</body>
</html>
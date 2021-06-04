<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Vince Patrik</title>
    </head>
    <body>
        <main>
            <nav>
                <ul>
                    <li><a href="index.php">Kezdőlap</a></li>
                    <li><a href='admin.php'>Admin felület</a></li>
                    <li><a href='foglalasaim.php'>Fogálasaim</a></li>
                    <li><a href='arvaltozas.php'>Árváltozás</a></li>
                    <li><a href='kijelentkezes.php'>Kijelentkezés</a></li>
                </ul>
            </nav>
            <h2>Foglalás törlése</h2>
            <form action="#" method="post">
                <label for="foglszam">Foglalás szám</label>
                <input type="text" name="foglszam" required>
                <br>
                <label for="sz_szam">Személyi igazolvány szám</label>
                <input type="text" name="sz_szam" id="sz_szam" required>
                <br>
                <input type="submit" name="torol" id="torol" value="Törlés">
            </form>
            <?php
            include_once "Scripts/torlesFeldolgoz.php";
            ?>

        </main>
        <footer>Keszitette: Vince Patrik</footer>
    </body>
</html>



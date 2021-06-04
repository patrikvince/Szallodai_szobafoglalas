<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
}
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <title>Vince Patrik</title>
    </head>
    <body>
        <main>
            <nav>
                <ul>
                    <li><a href="index.php">Kezdőlap</a></li>
                    <li><a href="foglalasaim.php">Foglalásaim</a></li>
                    <li><a href='torles.php'>Foglalás törlése</a></li>
                    <li><a href='arvaltozas.php'>Árváltozás</a></li>
                    <li><a href='kijelentkezes.php'>Kijelentkezés</a></li>
                </ul>
            </nav>
            <h2>Üdvözöljük az admin felületen!</h2>
            <!--<p>Fejlesztés alatt!</p>-->
            <form action="#" method="post">
                <label for='maiErkezok'>Mai érkezők:</label>
                <input type='submit' name='maiErkezok' id='maiErkezok' value='Lekérdez'>
                <label for='legtobbetFizetett'>Legtöbbet fizetett megrendelő:</label>
                <input type='submit' name='legtobbetFizetett' id='legtobbetFizetett' value='Lekérdez'>
                <label for='arbevetelEvente'>Éves bevételek:</label>
                <input type='submit' name='arbevetelEvente' id='arbevetelEvente' value='Lekérdez'>
            </form>
            <?php
            include_once "Scripts/adminLekerdezesek.php";
            ?>
        </main>
        <footer>Készítette: Vince Patrik</footer>
    </body>
</html>
<!doctype html>
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
            <li><a href="index.php">Kezdőlap</a></li>
            <li><a href="foglalasaim.php">Foglalasaim</a></li>
            <li><a href="kijelentkezes.php">Kijelentkezes</a></li>
        </ul>
    </nav>
    <h2>Foglalás lemondása</h2>

    <form action="#" method="post">
        <?php
        include_once "Scripts/lemondasKiiras.php";
        ?>
    </form>
    <?php
    include_once "Scripts/lemondasFeldolgoz.php";
    ?>
</main>
<footer>Készítette: Vince Patrik</footer>
</body>
</html>
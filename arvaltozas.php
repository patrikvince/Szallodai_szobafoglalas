<?php
session_start();
if (!isset($_SESSION['admin'])){
    header("Location: index.php");
}
?>

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
            <li><a href="index.php">Kezdőlap</a></li>
            <li><a href='admin.php'>Admin felület</a></li>
            <li><a href='foglalasaim.php'>Fogálasaim</a></li>
            <li><a href='torles.php'>Foglalások törlése</a></li>
            <li><a href='kijelentkezes.php'>Kijelentkezés</a></li>
        </ul>
    </nav>

    <form action="#" method="post">

        <?php include_once "./Scripts/szobatipusok.php"; ?>
        <br>
        <label for="mettol">Mettől</label>
        <input type="date" name="mettol" id="mettol" value="<?php echo $holnap = date( "Y-m-d", strtotime("+1 days"));?>"  min="<?php echo $minNap = date( "Y-m-d", strtotime("+3 days"));?>"  max="<?php echo $maxNap = date( "Y-m-d", strtotime("+365 days"));?>" required>
        <br>
        <label for="nappalElotte">Nappal előtte</label>
        <input type="number" name="nappalElotte" id="nappalElotte" value="90" min="1" max="90" required>
        <br>
        <label for="ear">Előtte ár</label>
        <input type="number" name="ear" id="ear" value="5000" min="1" required>
        <br>
        <label for="uar">Utána ár</label>
        <input type="number" name="uar" id="uar" value="10000" min="1" required>
        <br>
        <input type="submit" name="kuld" id="kuld" value="Változtat">
    </form>

    <?php
    include_once "Scripts/arvaltozasFeldolgoz.php";
    ?>
</main>
<footer>Készítette: Vince Patrik</footer>
</body>
</html>

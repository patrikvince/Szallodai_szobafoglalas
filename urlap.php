
<form action="#" method="post">
    <?php include_once "./Scripts/szobatipusok.php"; ?>
    <br>
    <label for="mettol">Mettől</label>
    <input type="date" name="mettol" id="mettol" max="" required>
    <br>
    <label for="meddig">Meddig</label>
    <input type="date" name="meddig" id="meddig" required>
    <br>
    <label for="fszam">Foglalas szám</label>
    <input type="text" name="fszam" id="fszam" value="<?php include_once "Scripts/maxFoglSzam.php"; ?>" readonly>
    <br>
    <label for="igazolvanysz">Igazolvány szám</label>
    <input type="text" name="igazolvanysz" id="igazolvanysz" required>
    <br>
    <label for="lakhely">Lakhely</label>
    <input type="text" name="lakhely" id="lakhely">
    <br>
    <label for="telszam">Telefonszám</label>
    <input type="text" name="telszam" id="telszam" required>
    <br>
    <label for="email">E-mail</label>
    <input type="text" name="email" id="email">
    <br>
    <input type="submit" name="kuld" id="kuld" value="Elküld">
</form>

<?php
require_once "Scripts/abFoglal.php";
?>







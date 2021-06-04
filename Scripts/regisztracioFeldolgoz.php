<?php
$eredmeny = include_once "regisztral.php";
//echo $eredmeny;
if (isset($_SESSION['fnev'])){
    header("Location: index.php");
}
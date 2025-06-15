<?php
session_start();
if (!isset($_SESSION["logged_in"])) {
    header("Location: index.php");
    exit;
}
?>

<h1>Dashboard</h1>
<ul>
  <li><a href="iscritti/aggiungi.php">Registra nuovo allievo</a></li>
  <li><a href="corsi/crea.php">Crea nuovo corso</a></li>
  <li><a href="eventi/crea.php">Crea evento</a></li>
  <!-- aggiungi altri link -->
</ul>

<a href="logout.php">Esci</a>

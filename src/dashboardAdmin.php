<?php
session_start();
if (!isset($_SESSION["logged_in"])) {
    header("Location: index.php");
    exit;
}
if ($_SESSION["ruolo"] !== "Admin") {
    // Se non è Admin, reindirizza alla dashboard appropriata o logout
    switch ($_SESSION["ruolo"]) {
        case "Cliente":
            header("Location: dashboardCliente.php");
            break;
        case "Insegnante":
            header("Location: dashboardInsegnante.php");
            break;
        default:
            // Ruolo non riconosciuto
            header("Location: logout.php");
            break;
    }
    exit();
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

<?php 
session_start();
if (!isset($_SESSION["logged_in"])) {
    header("Location: index.php");
    exit;
}

if ($_SESSION["ruolo"] !== "Admin") {
    switch ($_SESSION["ruolo"]) {
        case "Cliente":
            header("Location: dashboardCliente.php");
            break;
        case "Insegnante":
            header("Location: dashboardInsegnante.php");
            break;
        default:
            header("Location: logout.php");
            break;
    }
    exit;
}

require_once("../db.php");
$db = new Database();
?>

<form method="POST">
  <input name="nome" placeholder="Nome" required>
  <input name="cognome" placeholder="Cognome" required>
  <input name="cf" placeholder="Codice Fiscale" required>
  <input name="telefono" placeholder="Telefono">
  <input name="email" placeholder="Email">
  <input type="date" name="nascita" required>
  <select name="ruolo" required>
    <option value="">-- Seleziona ruolo --</option>
    <option value="CLIENTE">Cliente</option>
    <option value="INSEGNANTE">Insegnante</option>
  </select>
  <button type="submit">Registra</button>
</form>


<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $result = $db->aggiungiPersona(
        $_POST["cf"],
        $_POST["nome"],
        $_POST["cognome"],
        $_POST["nascita"],
        $_POST["email"],
        $_POST["telefono"],
        $_POST["ruolo"]
    );

    echo $result["success"] ? "Persona registrata." : $result["error"];
}
?>

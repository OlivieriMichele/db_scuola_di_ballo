<?php
session_start();
if (!isset($_SESSION["logged_in"])) {
    header("Location: ../index.php");
    exit;
}
if ($_SESSION["ruolo"] !== "Admin") {
    header("Location: ../logout.php");
    exit;
}

require_once("../db.php");
$db = new Database();
?>

<h2>Crea un nuovo evento</h2>
<form method="POST">
  <input name="nome" placeholder="Nome" required>
  <input name="tipo" placeholder="Tipo" required>
  <input name="id" placeholder="ID sala" type="number" required>
  <input name="data" type="date" required>
  <input name="ora" type="number" placeholder="Ora (es: 18)" required>
  <input name="descrizione" placeholder="Descrizione" required>
  <button type="submit">Crea Evento</button>
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $result = $db->creaEvento(
        $_POST["nome"],
        $_POST["tipo"],
        $_POST["id"],
        $_POST["data"],
        $_POST["ora"],
        $_POST["descrizione"]
    );
    echo $result["success"] ? "Evento creato." : $result["error"];
}
?>

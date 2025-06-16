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

<h2>Crea un nuovo corso</h2>
<form method="POST">
  <input name="nome" placeholder="Nome" required>
  <input name="tipo" placeholder="Tipo" required>
  <input name="descrizione" placeholder="Descrizione" required>
  <button type="submit">Crea Corso</button>
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $result = $db->creaCorso($_POST["nome"], $_POST["tipo"], $_POST["descrizione"]);
    echo $result["success"] ? "Corso creato." : $result["error"];
}
?>

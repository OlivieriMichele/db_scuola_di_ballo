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

<h3>Aggiungi edizione al corso (opzionale)</h3>
<form method="POST">
  <input type="hidden" name="azione" value="edizione">
  <input name="nome" placeholder="Nome corso" required>
  <input name="tipo" placeholder="Tipo corso" required>
  <input type="number" name="anno" placeholder="Anno" required>
  <input name="livello" placeholder="Livello" required>
  <input type="number" name="id" placeholder="ID aula" required>
  <input type="date" name="data" required>
  <input type="number" name="ora" placeholder="Ora" required>
  <button type="submit">Crea Edizione</button>
</form>


<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $result = $db->creaCorso($_POST["nome"], $_POST["tipo"], $_POST["descrizione"]);
    echo $result["success"] ? "Corso creato." : $result["error"];
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["azione"]) && $_POST["azione"] === "edizione") {
        $result = $db->creaEdizioneCorso(
            $_POST["nome"],
            $_POST["tipo"],
            $_POST["anno"],
            $_POST["livello"],
            $_POST["id"],
            $_POST["data"],
            $_POST["ora"]
        );

        echo $result["success"] ? "Edizione corso creata." : $result["error"];
    } else {
        // Form creazione corso
        $result = $db->creaCorso($_POST["nome"], $_POST["tipo"], $_POST["descrizione"]);
        echo $result["success"] ? "Corso creato." : $result["error"];
    }
}

?>

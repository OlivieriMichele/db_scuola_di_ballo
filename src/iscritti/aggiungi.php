<?php include("../include/db.php"); ?>

<form method="POST">
  <input name="nome" placeholder="Nome" required>
  <input name="cognome" placeholder="Cognome" required>
  <input name="cf" placeholder="Codice Fiscale" required>
  <input name="telefono" placeholder="Telefono">
  <input name="email" placeholder="Email">
  <input type="date" name="nascita" required>
  <button type="submit">Registra</button>
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $stmt = $conn->prepare("INSERT INTO iscritti (nome, cognome, codice_fiscale, telefono, email, data_nascita, data_iscrizione) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssssss", $_POST["nome"], $_POST["cognome"], $_POST["cf"], $_POST["telefono"], $_POST["email"], $_POST["nascita"]);
    $stmt->execute();
    echo "Allievo registrato.";
}
?>

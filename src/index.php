<?php
session_start();
if (isset($_SESSION["logged_in"])) {
    header("Location: dashboard.php");
    exit;
}

include("db.php");
?>

<form method="POST">
  <input type="text" name="user" placeholder="Utente" required>
  <input type="password" name="pass" placeholder="Password" required>
  <button type="submit">Login</button>
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user = $_POST["user"];
    $pass = $_POST["pass"];

    $stmt = $conn->prepare("SELECT password_hash FROM USER WHERE UserName = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($hash);
        $stmt->fetch();

        // Se le password nel DB sono in chiaro usa:
        if ($pass === $hash) {
            $_SESSION["logged_in"] = true;
            header("Location: dashboard.php");
            exit;
        }

        // Se le password sono hashate, usa invece:
        // if (password_verify($pass, $hash)) { ... }

        echo "Password errata.";
    } else {
        echo "Utente non trovato.";
    }

    $stmt->close();
}
?>

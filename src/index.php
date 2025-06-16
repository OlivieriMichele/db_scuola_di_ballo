<?php
session_start();
if (isset($_SESSION["logged_in"])) {
    header("Location: dashboard.php");
    exit;
}

include("db.php");
$db = new Database();
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

    $result = $db->login($user, $pass);
    if ($result["success"]) {
        $_SESSION["logged_in"] = ture;
        $_SESSION["username"] = $user;
        $_SESSION["ruolo"] = $result["ruolo"];

        switch ($result["ruolo"]) {
            case "Cliente":
                header("Location: dashboardCliente.php");
                break;
            case "Insegnante":
                header("Location: dashboardInsegnante.php");
                break;
            case "Admin":
                header("Location: dashboardAdmin.php");
                break;
            default:
                echo "Ruolo non riconosciuto.";
        }
        exit;
    } else {
        echo $result['error'];
    }

}
?>

<?php
session_start();
if (isset($_SESSION["logged_in"])) {
    if (isset($_SESSION["ruolo"])) {
        switch ($_SESSION["ruolo"]) {
            case "Cliente":
                header("Location: dashboardCliente.php");
                break;
            case "Insegnante":
                header("Location: dashboardInsegnante.php");
                break;
            case "Admin":
                header("Location: dashboardAdmin.php");
                break;
        }
    }
    exit();
}

include("db.php");
$db = new Database();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema Gestionale</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 40px;
            background-color: #f8f9fa;
            color: #333;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .login-container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
            max-width: 400px;
            width: 100%;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        h1 {
            color: #495057;
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 30px;
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        input {
            padding: 12px 15px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            font-size: 16px;
            transition: border-color 0.2s ease;
            background-color: #ffffff;
        }

        input:focus {
            outline: none;
            border-color: #495057;
        }

        button {
            padding: 12px 20px;
            background-color: #495057;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        button:hover {
            background-color: #343a40;
        }

        .error-message {
            color: #495057;
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 4px;
            margin-top: 15px;
            text-align: center;
            border: 1px solid #dee2e6;
        }

        /* Responsive */
        @media (max-width: 768px) {
            body {
                padding: 20px;
            }

            .login-container {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>

<div class="login-container">
    <h1>Accedi al Sistema</h1>
    
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
            $_SESSION["logged_in"] = true;
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
                    echo '<div class="error-message">Ruolo non riconosciuto.</div>';
            }
            exit;
        } else {
            echo '<div class="error-message">' . htmlspecialchars($result['error']) . '</div>';
        }
    }
    ?>
</div>

</body>
</html>
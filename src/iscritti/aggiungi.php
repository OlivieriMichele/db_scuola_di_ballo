<?php 
session_start();
if (!isset($_SESSION["logged_in"])) {
    header("Location: ../index.php");
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

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registra Nuovo Allievo</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 40px;
            background-color: #f8f9fa;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 40px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        h1 {
            color: #495057;
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 30px;
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        input, select {
            padding: 12px 15px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            font-size: 16px;
            transition: border-color 0.2s ease;
            background-color: #ffffff;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #495057;
        }

        select {
            cursor: pointer;
        }

        button {
            padding: 15px 20px;
            background-color: #495057;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s ease;
            margin-top: 10px;
        }

        button:hover {
            background-color: #343a40;
        }

        .message {
            padding: 15px;
            border-radius: 4px;
            margin-top: 20px;
            text-align: center;
            font-weight: 500;
        }

        .success {
            color: #155724;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
        }

        .error {
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.2s ease;
        }

        .back-link:hover {
            background-color: #5a6268;
            color: white;
        }

        /* Responsive */
        @media (max-width: 768px) {
            body {
                padding: 20px;
            }

            .container {
                padding: 30px 20px;
            }

            h1 {
                font-size: 1.6rem;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Registra Nuovo Allievo</h1>

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

        if ($result["success"]) {
            echo '<div class="message success">Persona registrata con successo.</div>';
        } else {
            echo '<div class="message error">' . htmlspecialchars($result["error"]) . '</div>';
        }
    }
    ?>

    <a href="../dashboardAdmin.php" class="back-link">← Torna alla Dashboard</a>
</div>

</body>
</html>
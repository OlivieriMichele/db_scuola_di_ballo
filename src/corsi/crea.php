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

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crea Nuovo Corso</title>
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

        h1, h2 {
            color: #495057;
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 30px;
            text-align: center;
        }

        h2 {
            font-size: 1.5rem;
            margin-top: 40px;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-bottom: 30px;
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

        .form-section {
            margin-bottom: 40px;
            padding-bottom: 30px;
            border-bottom: 1px solid #dee2e6;
        }

        .form-section:last-of-type {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
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

            h2 {
                font-size: 1.3rem;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Gestione Corsi</h1>

    <div class="form-section">
        <h2>Crea un nuovo corso</h2>
        <form method="POST">
            <input name="nome" placeholder="Nome" required>
            <input name="tipo" placeholder="Tipo" required>
            <input name="descrizione" placeholder="Descrizione" required>
            <button type="submit">Crea Corso</button>
        </form>
    </div>

    <div class="form-section">
        <h2>Aggiungi edizione al corso</h2>
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
    </div>

    <?php
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

            if ($result["success"]) {
                echo '<div class="message success">Edizione corso creata con successo.</div>';
            } else {
                echo '<div class="message error">' . htmlspecialchars($result["error"]) . '</div>';
            }
        } else {
            // Form creazione corso
            $result = $db->creaCorso($_POST["nome"], $_POST["tipo"], $_POST["descrizione"]);
            
            if ($result["success"]) {
                echo '<div class="message success">Corso creato con successo.</div>';
            } else {
                echo '<div class="message error">' . htmlspecialchars($result["error"]) . '</div>';
            }
        }
    }
    ?>

    <a href="../dashboardAdmin.php" class="back-link">← Torna alla Dashboard</a>
</div>

</body>
</html>
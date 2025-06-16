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
    <title>Crea Nuova Lezione</title>
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

        .form-note {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 14px;
            color: #6c757d;
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
    <h1>Crea Nuova Lezione</h1>

    <div class="form-note">
        <strong>Nota:</strong> Se una lezione con lo stesso ID, data e ora esiste già, non verrà creata una duplicata.
    </div>

    <form method="POST">
        <input type="number" name="id" placeholder="ID Lezione" required min="1">
        <input type="date" name="data" required>
        <input type="number" name="ora" placeholder="Ora (formato 24h, es: 14 per le 14:00)" required min="0" max="23">
        <button type="submit">Crea Lezione</button>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $id = (int)$_POST["id"];
        $data = $_POST["data"];
        $ora = (int)$_POST["ora"];

        $result = $db->creaLezioneSeNonEsiste($id, $data, $ora);

        if ($result["success"]) {
            echo '<div class="message success">Lezione creata con successo.</div>';
        } else {
            echo '<div class="message error">' . htmlspecialchars($result["error"]) . '</div>';
        }
    }
    ?>

    <a href="../dashboardAdmin.php" class="back-link">← Torna alla Dashboard</a>
</div>

</body>
</html>
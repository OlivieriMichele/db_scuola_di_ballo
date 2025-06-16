<?php
session_start();
if (!isset($_SESSION["logged_in"])) {
    header("Location: index.php");
    exit;
}
if ($_SESSION["ruolo"] !== "Admin") {
    // Se non è Admin, reindirizza alla dashboard appropriata o logout
    switch ($_SESSION["ruolo"]) {
        case "Cliente":
            header("Location: dashboardCliente.php");
            break;
        case "Insegnante":
            header("Location: dashboardInsegnante.php");
            break;
        default:
            // Ruolo non riconosciuto
            header("Location: logout.php");
            break;
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 40px;
            background-color: #f8f9fa;
            color: #333;
        }

        h1 {
            color: #495057;
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 30px;
            text-align: center;
        }

        ul {
            list-style: none;
            padding: 0;
            margin: 0 auto 40px auto;
            max-width: 600px;
        }

        li {
            margin-bottom: 15px;
        }

        a {
            display: block;
            padding: 15px 20px;
            background-color: #ffffff;
            color: #495057;
            text-decoration: none;
            border-radius: 8px;
            border: 1px solid #dee2e6;
            transition: all 0.2s ease;
            font-weight: 500;
        }

        a:hover {
            background-color: #e9ecef;
            border-color: #adb5bd;
            color: #212529;
        }

        .logout-link {
            display: block;
            max-width: 200px;
            margin: 40px auto 0 auto;
            text-align: center;
            background-color:rgb(175, 131, 180);
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.2s ease;
        }

        .logout-link:hover {
            background-color:rgb(200, 35, 150);
            color: white;
        }

        /* Responsive */
        @media (max-width: 768px) {
            body {
                padding: 20px;
            }

            h1 {
                font-size: 1.6rem;
            }

            ul {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>

<h1>Dashboard</h1>
<ul>
  <li><a href="iscritti/aggiungi.php">Registra nuovo allievo</a></li>
  <li><a href="corsi/crea.php">Crea nuovo corso</a></li>
  <li><a href="eventi/crea.php">Crea evento</a></li>
  <li><a href="lezioni/crea.php">Crea lezione</a></li>
  <!-- aggiungi altri link -->
</ul>

<a href="logout.php" class="logout-link">Esci</a>

</body>
</html>
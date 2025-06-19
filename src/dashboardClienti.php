<?php
session_start();
if (!isset($_SESSION["logged_in"]) || $_SESSION["ruolo"] !== "Cliente") {
    header("Location: ../logout.php");
    exit;
}

require_once("db.php");
$db = new Database();

$dataInizio = $_GET["inizio"] ?? date('Y-m-d');
$eventi = $db->getEventiPerData($dataInizio);
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Cliente</title>
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
  <style>
    body { font-family: sans-serif; margin: 20px; }
    table { border-collapse: collapse; width: 100%; margin-bottom: 30px; }
    th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
    th { background-color: #f0f0f0; }
    form { margin-bottom: 30px; }
    #calendar { max-width: 1000px; margin: auto; }

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
  </style>
</head>
<body>

<h1>Benvenuto, <?= htmlspecialchars($_SESSION["username"]) ?></h1>

<form method="GET">
  <label for="inizio">Seleziona data inizio settimana:</label>
  <input type="date" name="inizio" id="inizio" value="<?= htmlspecialchars($dataInizio) ?>">
  <button type="submit">Visualizza</button>
</form>

<h2>Calendario settimanale</h2>
<div id="calendar"></div>

<h2>Eventi</h2>
<table>
  <tr><th>Nome</th><th>Data</th><th>Ora</th><th>Descrizione</th></tr>
  <?php foreach ($eventi as $e): ?>
    <?php if ($e['tipo'] !== 'CORSO'): ?>
      <tr>
        <td><?= htmlspecialchars($e['nome']) ?></td>
        <td><?= htmlspecialchars($e['data']) ?></td>
        <td><?= htmlspecialchars($e['ora']) ?></td>
        <td><?= htmlspecialchars($e['descrizione']) ?></td>
      </tr>
    <?php endif; ?>
  <?php endforeach; ?>
</table>

<h2>Corsi</h2>
<table>
  <tr><th>Nome</th><th>Data</th><th>Ora</th><th>Livello</th></tr>
  <?php foreach ($eventi as $e): ?>
    <?php if ($e['tipo'] === 'CORSO'): ?>
      <tr>
        <td><?= htmlspecialchars($e['nome']) ?></td>
        <td><?= htmlspecialchars($e['data']) ?></td>
        <td><?= htmlspecialchars($e['ora']) ?></td>
        <td><?= htmlspecialchars($e['descrizione']) ?></td>
      </tr>
    <?php endif; ?>
  <?php endforeach; ?>
</table>

<h2>Lezioni frequentate</h2>
<table>
  <tr><th>Sala</th><th>Data</th><th>Ora</th></tr>
  <?php
  $lezioniPartecipate = $db->getLezioniClientePartecipateDaUsername($_SESSION["username"]); // o $_SESSION["cf"] se lo usi come chiave
  foreach ($lezioniPartecipate as $lez): ?>
    <tr>
      <td><?= htmlspecialchars($lez["sala"]) ?></td>
      <td><?= htmlspecialchars($lez["data"]) ?></td>
      <td><?= htmlspecialchars($lez["ora"]) ?></td>
    </tr>
  <?php endforeach; ?>
</table>

<a href="logout.php" class="logout-link">Esci</a>

<script>
document.addEventListener('DOMContentLoaded', function() {
  var calendarEl = document.getElementById('calendar');
  var calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'timeGridWeek',
    initialDate: "<?= $dataInizio ?>",
    locale: 'it',
    allDaySlot: false,
    slotMinTime: "08:00:00",
    slotMaxTime: "22:00:00",
    events: [
      <?php foreach ($eventi as $e): ?>
      {
        title: "<?= addslashes($e['nome']) ?> - <?= addslashes($e['descrizione']) ?>",
        start: "<?= $e['data'] . 'T' . str_pad($e['ora'], 2, '0', STR_PAD_LEFT) ?>:00"
      },
      <?php endforeach; ?>
    ]
  });
  calendar.render();
});
</script>

</body>
</html>

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
    <?php if ($e['tipo'] === 'EVENTO'): ?>
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

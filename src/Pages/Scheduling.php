<?php

use App\Database\Connection;

$pdo = Connection::get()->connect();

// Получаем дату из URL или используем текущую дату
$date = $_GET['date'] ?? date('Y-m-d');

// Запрос для получения поездок на выбранную дату
$sql = "
SELECT trips.*, couriers.name as courier_name, regions.name as region_name
FROM trips
JOIN couriers ON trips.courier_id = couriers.id
JOIN regions ON trips.region_id = regions.id
WHERE departure_date = :date
";
$stmt = $pdo->prepare($sql);
$stmt->execute([':date' => $date]);
$trips = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Расписание поездок</title>
</head>
<body>
<h1>Расписание поездок на <?= htmlspecialchars($date) ?></h1>

<form method="get" action="/schedule">
    <label for="date">Выберите дату:</label>
    <input type="date" name="date" id="date" value="<?= htmlspecialchars($date) ?>">
    <button type="submit">Показать</button>
</form>

<table border="1">
    <thead>
    <tr>
        <th>Курьер</th>
        <th>Регион</th>
        <th>Дата выезда</th>
        <th>Дата прибытия</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($trips as $trip): ?>
        <tr>
            <td><?= htmlspecialchars($trip['courier_name']) ?></td>
            <td><?= htmlspecialchars($trip['region_name']) ?></td>
            <td><?= htmlspecialchars($trip['departure_date']) ?></td>
            <td><?= htmlspecialchars($trip['arrival_date']) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</body>
</html>
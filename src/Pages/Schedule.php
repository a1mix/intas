<?php

use App\Database\Connection;

$pdo = Connection::get()->connect();

$date = $_GET['date'] ?? date('Y-m-d');

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Расписание поездок</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h1 class="text-center mb-4">Расписание поездок</h1>

    <form method="get" action="/schedule" class="mb-4">
        <div class="input-group">
            <input type="date" name="date" class="form-control" value="<?= htmlspecialchars($date) ?>">
            <button type="submit" class="btn btn-primary">Показать</button>
        </div>
    </form>

    <table class="table table-bordered table-striped">
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
</div>
</body>
</html>